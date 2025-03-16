<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "Tuan2004@";
$dbname = "btl2";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Vui lòng đăng nhập!'); window.location.href='../html/login.php';</script>";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $ten = htmlspecialchars($_POST['ten']);
    $so_dien_thoai = htmlspecialchars($_POST['so_dien_thoai']);
    $dia_chi = htmlspecialchars($_POST['dia_chi']);
    $subtotal = isset($_POST['subtotal']) ? floatval($_POST['subtotal']) : 0;
    $shipping_fee = isset($_POST['shipping_fee']) ? floatval($_POST['shipping_fee']) : 0;
    $total = isset($_POST['total']) ? floatval($_POST['total']) : 0;

    // Kiểm tra nếu tổng tiền không hợp lệ
    if ($total == 0) {
        echo "<script>alert('Lỗi: Tổng tiền không hợp lệ. Vui lòng kiểm tra lại giỏ hàng.'); window.location.href='../html/giohang.php';</script>";
        exit();
    }

    // Kiểm tra phương thức thanh toán
    if (!isset($_POST['payment']) || !in_array($_POST['payment'], ['COD', 'bank'])) {
        echo "<script>alert('Lỗi: Phương thức thanh toán không hợp lệ.'); window.location.href='../html/thanhtoan.php';</script>";
        exit();
    }
    $payment_method = $_POST['payment'];

    // Ghi log để kiểm tra giá trị từ form
    file_put_contents('debug.log', "Payment Method from form: $payment_method\n", FILE_APPEND);

    // Ánh xạ phương thức thanh toán
    $payment_method_db = "Không xác định"; // Giá trị mặc định
    if ($payment_method === "COD") {
        $payment_method_db = "Ship COD";
    } elseif ($payment_method === "bank") {
        $payment_method_db = "Chuyển khoản";
    }

    // Ghi log để kiểm tra giá trị sau khi ánh xạ
    file_put_contents('debug.log', "Payment Method DB: $payment_method_db\n", FILE_APPEND);

    // Lưu thông tin đơn hàng vào bảng don_hang
    $sql_order = "INSERT INTO don_hang (nguoi_dung_id, tong_tien, phuong_thuc_thanh_toan, trang_thai, thoi_gian_dat, dia_chi, ten_nguoi_nhan, so_dien_thoai) VALUES (?, ?, ?, ?, NOW(), ?, ?, ?)";
    $stmt_order = $conn->prepare($sql_order);
    $trang_thai = 'Đang xử lý';
    $stmt_order->bind_param("idsssss", $user_id, $total, $payment_method_db, $trang_thai, $dia_chi, $ten, $so_dien_thoai);
    if (!$stmt_order->execute()) {
        // Ghi log nếu có lỗi khi chèn
        file_put_contents('debug.log', "SQL Error: " . $stmt_order->error . "\n", FILE_APPEND);
        echo "<script>alert('Lỗi khi lưu đơn hàng. Vui lòng thử lại.'); window.location.href='../html/thanhtoan.php';</script>";
        exit();
    }
    $order_id = $conn->insert_id;

    // Lấy giỏ hàng để lưu chi tiết đơn hàng
    $sql_cart = "SELECT ma_san_pham, ten_san_pham, gia, so_luong FROM gio_hang WHERE nguoi_dung_id = ?";
    $stmt_cart = $conn->prepare($sql_cart);
    $stmt_cart->bind_param("i", $user_id);
    $stmt_cart->execute();
    $result_cart = $stmt_cart->get_result();

    while ($item = $result_cart->fetch_assoc()) {
        $ma_san_pham = $item['ma_san_pham'];
        $ten_san_pham = $item['ten_san_pham'];
        $gia = $item['gia'];
        $so_luong = $item['so_luong'];

        $sql_detail = "INSERT INTO chi_tiet_don_hang (don_hang_id, ma_san_pham, ten_san_pham, so_luong, gia) VALUES (?, ?, ?, ?, ?)";
        $stmt_detail = $conn->prepare($sql_detail);
        $stmt_detail->bind_param("issid", $order_id, $ma_san_pham, $ten_san_pham, $so_luong, $gia);
        $stmt_detail->execute();
    }

    // Xóa giỏ hàng
    $sql_delete = "DELETE FROM gio_hang WHERE nguoi_dung_id = ?";
    $stmt_delete = $conn->prepare($sql_delete);
    $stmt_delete->bind_param("i", $user_id);
    $stmt_delete->execute();

    if ($payment_method === "COD") {
        echo "<script>alert('Đơn hàng của bạn đã được đặt thành công! Thanh toán khi nhận hàng (Ship COD).'); window.location.href='../html/giohang.php';</script>";
    } else {
        echo "<script>alert('Đơn hàng của bạn đã được đặt thành công! Vui lòng chuyển khoản ngân hàng để hoàn tất.'); window.location.href='../html/giohang.php';</script>";
    }

    $stmt_order->close();
    $stmt_cart->close();
    $stmt_detail->close();
    $stmt_delete->close();
    $conn->close();
} else {
    echo "<script>alert('Yêu cầu không hợp lệ!'); window.location.href='../html/giohang.php';</script>";
}
?>