<?php
// Kết nối database
$servername = "localhost";
$username = "root"; // Thay đổi thành tên người dùng của bạn
$password = "Tuan2004@";
$dbname = "btl2"; // Thay đổi thành tên cơ sở dữ liệu của bạn

// Tạo kết nối
$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Hàm validate input
function validateInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Xử lý thêm tài khoản
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['them_tai_khoan'])) {
    $ho = validateInput($_POST['ho']);
    $ten = validateInput($_POST['ten']);
    $email = validateInput($_POST['email']);
    $mat_khau = validateInput($_POST['mat_khau']);

    // Validate email và mật khẩu
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Email không hợp lệ!'); window.location.href='quan_ly_nguoi_dung.php';</script>";
    } elseif (strlen($mat_khau) < 6) {
        echo "<script>alert('Mật khẩu phải dài ít nhất 6 ký tự!'); window.location.href='quan_ly_nguoi_dung.php';</script>";
    } else {
        // Kiểm tra email đã tồn tại chưa
        $check_sql = "SELECT email FROM tai_khoan WHERE email = ?";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param("s", $email);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();

        if ($check_result->num_rows > 0) {
            echo "<script>alert('Email đã tồn tại!'); window.location.href='quan_ly_nguoi_dung.php';</script>";
        } else {
            $mat_khau = password_hash($mat_khau, PASSWORD_DEFAULT); // Mã hóa mật khẩu
            $sql = "INSERT INTO tai_khoan (ho, ten, email, mat_khau) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssss", $ho, $ten, $email, $mat_khau);

            if ($stmt->execute()) {
                echo "<script>alert('Thêm tài khoản thành công!'); window.location.href='quan_ly_nguoi_dung.php';</script>";
            } else {
                echo "<script>alert('Lỗi khi thêm tài khoản!');</script>";
            }
            $stmt->close();
        }
        $check_stmt->close();
    }
}

// Xử lý sửa tài khoản
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['sua_tai_khoan'])) {
    $id = $_POST['id'];
    $ho = validateInput($_POST['ho']);
    $ten = validateInput($_POST['ten']);
    $email = validateInput($_POST['email']);
    $mat_khau = !empty($_POST['mat_khau']) ? password_hash(validateInput($_POST['mat_khau']), PASSWORD_DEFAULT) : null;

    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Email không hợp lệ!'); window.location.href='quan_ly_nguoi_dung.php';</script>";
    } else {
        // Kiểm tra email có trùng với tài khoản khác không (trừ tài khoản đang sửa)
        $check_sql = "SELECT email FROM tai_khoan WHERE email = ? AND id != ?";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param("si", $email, $id);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();

        if ($check_result->num_rows > 0) {
            echo "<script>alert('Email đã tồn tại!'); window.location.href='quan_ly_nguoi_dung.php';</script>";
        } else {
            if ($mat_khau) {
                $sql = "UPDATE tai_khoan SET ho = ?, ten = ?, email = ?, mat_khau = ? WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ssssi", $ho, $ten, $email, $mat_khau, $id);
            } else {
                $sql = "UPDATE tai_khoan SET ho = ?, ten = ?, email = ? WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("sssi", $ho, $ten, $email, $id);
            }

            if ($stmt->execute()) {
                echo "<script>alert('Sửa tài khoản thành công!'); window.location.href='quan_ly_nguoi_dung.php';</script>";
            } else {
                echo "<script>alert('Lỗi khi sửa tài khoản!');</script>";
            }
            $stmt->close();
        }
        $check_stmt->close();
    }
}

// Xử lý xóa tài khoản
if (isset($_GET['xoa_email'])) {
    $email = $_GET['xoa_email'];
    $sql = "DELETE FROM tai_khoan WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);

    if ($stmt->execute()) {
        echo "<script>alert('Xóa tài khoản thành công!'); window.location.href='quan_ly_nguoi_dung.php';</script>";
    } else {
        echo "<script>alert('Lỗi khi xóa tài khoản!');</script>";
    }
    $stmt->close();
}

// Xử lý tìm kiếm
$search_query = isset($_GET['search']) ? $_GET['search'] : '';
$sql = "SELECT * FROM tai_khoan";
if (!empty($search_query)) {
    $search_query = $conn->real_escape_string($search_query);
    $sql .= " WHERE ho LIKE '%$search_query%' OR ten LIKE '%$search_query%' OR email LIKE '%$search_query%'";
}
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý Người Dùng</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
            color: #333;
            line-height: 1.6;
        }

        .header {
            background-color: #4CAF50;
            color: white;
            padding: 20px;
            text-align: center;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .header h2 {
            margin: 0;
            font-size: 28px;
            font-weight: 600;
            letter-spacing: 1px;
        }

        .container {
            max-width: 1200px;
            margin: 30px auto;
            padding: 20px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .form-add {
            margin-bottom: 20px;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 8px;
        }

        .form-add label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
            color: #333;
        }

        .form-add input {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .form-add button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .form-add button:hover {
            background-color: #388E3C;
        }

        .search-form {
            margin-bottom: 20px;
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .search-form input[type="text"] {
            padding: 8px;
            width: 300px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .search-form button {
            background-color: #4CAF50;
            color: white;
            padding: 8px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .search-form button:hover {
            background-color: #388E3C;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #4CAF50;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #e8f5e9;
        }

        .action-btn {
            padding: 5px 10px;
            margin-right: 5px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .action-btn.delete {
            background-color: #f44336;
            color: white;
        }

        .action-btn.delete:hover {
            background-color: #d32f2f;
        }

        .action-btn.edit {
            background-color: #2196F3;
            color: white;
        }

        .action-btn.edit:hover {
            background-color: #1976D2;
        }

        .back-btn {
            display: inline-block;
            margin: 20px 0;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            transition: background-color 0.3s ease;
        }

        .back-btn:hover {
            background-color: #388E3C;
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <!-- HEADER -->
    <div class="header">
        <h2>Quản Lý Người Dùng</h2>
    </div>

    <!-- CONTAINER -->
    <div class="container">
        <!-- NÚT QUAY LẠI -->
        <a href="../admin/index.php" class="back-btn">Quay lại Trang Quản Trị</a>

        <!-- FORM THÊM TÀI KHOẢN -->
        <div class="form-add">
            <h3>Thêm Tài Khoản Mới</h3>
            <form method="POST" action="">
                <label for="ho">Họ:</label>
                <input type="text" id="ho" name="ho" required>

                <label for="ten">Tên:</label>
                <input type="text" id="ten" name="ten" required>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>

                <label for="mat_khau">Mật khẩu:</label>
                <input type="password" id="mat_khau" name="mat_khau" required>

                <button type="submit" name="them_tai_khoan">Thêm Tài Khoản</button>
            </form>
        </div>

        <!-- FORM TÌM KIẾM -->
        <div class="search-form">
            <form method="GET" action="">
                <input type="text" name="search" placeholder="Tìm kiếm theo họ, tên, hoặc email..." value="<?php echo htmlspecialchars($search_query); ?>">
                <button type="submit">Tìm kiếm</button>
            </form>
        </div>

        <!-- DANH SÁCH TÀI KHOẢN -->
        <h3>Danh Sách Tài Khoản</h3>
        <table>
            <tr>
                <th>ID</th>
                <th>Họ</th>
                <th>Tên</th>
                <th>Email</th>
                <th>Mật Khẩu</th>
                <th>Thời Gian Tạo</th>
                <th>Hành Động</th>
            </tr>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row["id"] . "</td>";
                    echo "<td>" . htmlspecialchars($row["ho"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["ten"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["email"]) . "</td>";
                    echo "<td>" . htmlspecialchars(substr($row["mat_khau"], 0, 10)) . "... (đã mã hóa)" . "</td>";
                    echo "<td>" . $row["thoi_gian_tao"] . "</td>";
                    echo "<td>";
                    echo "<button class='action-btn edit' onclick=\"document.getElementById('edit-form-{$row["id"]}').style.display='block'\">Sửa</button>";
                    echo "<button class='action-btn delete' onclick=\"if(confirm('Bạn có chắc muốn xóa tài khoản này?')) window.location.href='quan_ly_nguoi_dung.php?xoa_email=" . urlencode($row["email"]) . "'\">Xóa</button>";
                    echo "</td>";
                    echo "</tr>";

                    // Form sửa tài khoản (ẩn mặc định)
                    echo "<tr id='edit-form-{$row["id"]}' style='display:none;'>";
                    echo "<td colspan='7'>";
                    echo "<form method='POST' action=''>";
                    echo "<input type='hidden' name='id' value='{$row["id"]}'>";
                    echo "<label>Họ: <input type='text' name='ho' value='" . htmlspecialchars($row["ho"]) . "' required></label>";
                    echo "<label>Tên: <input type='text' name='ten' value='" . htmlspecialchars($row["ten"]) . "' required></label>";
                    echo "<label>Email: <input type='email' name='email' value='" . htmlspecialchars($row["email"]) . "' required></label>";
                    echo "<label>Mật khẩu (để trống nếu không đổi): <input type='password' name='mat_khau'></label>";
                    echo "<button type='submit' name='sua_tai_khoan'>Lưu</button>";
                    echo "<button type='button' onclick=\"document.getElementById('edit-form-{$row["id"]}').style.display='none'\">Hủy</button>";
                    echo "</form>";
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='7'>Không có tài khoản nào.</td></tr>";
            }
            ?>
        </table>
    </div>
</body>
</html>

<?php
$conn->close();
?>