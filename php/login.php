<?php
$servername = 'localhost';
$username = 'root';
$password = "Tuan2004@";
$database = 'btl2';
$conn = mysqli_connect($servername, $username, $password, $database);

if (!$conn) {
    die("<script>alert('Kết nối thất bại: " . mysqli_connect_error() . "');</script>");
}

session_start(); // Bắt đầu session

// Hàm xác thực token từ Google
function verifyGoogleToken($idToken) {
    $clientId = '759951305942-bv5efqf3oif2td4his2iq400slua8t0g.apps.googleusercontent.com'; // Thay bằng Client ID của bạn
    $url = 'https://oauth2.googleapis.com/tokeninfo?id_token=' . $idToken;
    
    error_log("Verifying token: " . $idToken); // Ghi log token gửi lên
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $response = curl_exec($ch);
    
    if (curl_errno($ch)) {
        error_log("cURL Error: " . curl_error($ch)); // Ghi log lỗi cURL nếu có
    }
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE); // Lấy mã trạng thái HTTP
    error_log("HTTP Status: " . $httpCode);
    curl_close($ch);

    $payload = json_decode($response, true);
    error_log("Payload: " . print_r($payload, true)); // Ghi log toàn bộ payload

    return $payload;
}

// Xử lý đăng nhập bằng Google
if (isset($_POST['idtoken'])) {
    $idToken = $_POST['idtoken'];

    // Kiểm tra token có rỗng hay không
    if (empty($idToken)) {
        echo json_encode(['success' => false, 'message' => 'Không nhận được token']);
        $conn->close();
        exit;
    }

    $payload = verifyGoogleToken($idToken);

    // Kiểm tra payload và aud
    if ($payload && isset($payload['sub']) && $payload['aud'] === '759951305942-bv5efqf3oif2td4his2iq400slua8t0g.apps.googleusercontent.com') {
        $google_id = $payload['sub'];
        $email = $payload['email'];
        $ho = $payload['family_name'] ?? '';
        $ten = $payload['given_name'] ?? '';
        $name = $payload['name'];

        // Kiểm tra xem người dùng đã tồn tại chưa
        $check_sql = "SELECT id, ho, ten FROM tai_khoan WHERE email = ?";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param("s", $email);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();

        if ($check_result->num_rows > 0) {
            // Người dùng đã tồn tại, đăng nhập
            $row = $check_result->fetch_assoc();
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['user_name'] = $row['ho'] . ' ' . $row['ten'];
        } else {
            // Người dùng mới, thêm vào database
            $sql = "INSERT INTO tai_khoan (ho, ten, email, mat_khau) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $mat_khau = null; // Không cần mật khẩu cho đăng nhập Google
            $stmt->bind_param("ssss", $ho, $ten, $email, $mat_khau);

            if ($stmt->execute()) {
                $_SESSION['user_id'] = $conn->insert_id;
                $_SESSION['user_name'] = $ho . ' ' . $ten;
            } else {
                echo json_encode(['success' => false, 'message' => 'Lỗi khi thêm người dùng']);
                $conn->close();
                exit;
            }
            $stmt->close();
        }
        $check_stmt->close();

        echo json_encode(['success' => true, 'name' => $_SESSION['user_name']]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Đăng nhập thất bại!']);
    }
    $conn->close();
    exit;
}

// Xử lý đăng ký
if (isset($_POST["signup_btn"])) {
    $ho = $_POST["ho"];
    $ten = $_POST["ten"];
    $email = $_POST["email_signup"];
    $password = $_POST["password_signup"]; // Bỏ mã hóa mật khẩu

    // Kiểm tra xem email đã tồn tại chưa
    $check_sql = "SELECT email FROM tai_khoan WHERE email = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("s", $email);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows > 0) {
        echo "<script>alert('Email đã tồn tại. Vui lòng sử dụng email khác.');
        window.location.href='../html/login.php';</script>";
    } else {
        $sql = "INSERT INTO tai_khoan (ho, ten, email, mat_khau) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $ho, $ten, $email, $password);

        if ($stmt->execute()) {
            echo "<script>alert('Chào mừng đến với ML Book'); 
            window.location.href='../html/login.php';</script>"; 
        } else {
            echo "<script>alert('Thêm không thành công');</script>";
        }
        $stmt->close();
    }
    $check_stmt->close();
}

// Xử lý đăng nhập thông thường
if (isset($_POST["login_btn"])) {
    $email = $_POST["email_login"];
    $password = $_POST["password_login"];

    $sql = "SELECT id, ho, ten, mat_khau FROM tai_khoan WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if ($password === $row['mat_khau']) {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['user_name'] = $row['ho'] . ' ' . $row['ten'];
            echo "<script>alert('Chào mừng " . $_SESSION['user_name'] . " đến với ML Book'); 
            window.location.href='../html/index.php';</script>"; 
        } else {
            echo "<script>alert('Email hoặc mật khẩu không đúng. Vui lòng thử lại.');
            window.location.href='../html/login.php';</script>";
        }
    } else {
        echo "<script>alert('Email hoặc mật khẩu không đúng. Vui lòng thử lại.');
        window.location.href='../html/login.php';</script>";
    }
    $stmt->close();
}

$conn->close();
?>