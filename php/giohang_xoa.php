<?php
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

session_start(); // Bắt đầu session

// Lấy mã sản phẩm từ URL
if (isset($_GET['ma_san_pham']) && isset($_SESSION['user_id'])) {
    $ma_san_pham = $_GET['ma_san_pham'];

    // Truy vấn xóa sản phẩm
    $sql = "DELETE FROM gio_hang WHERE nguoi_dung_id = ? AND ma_san_pham = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $_SESSION['user_id'], $ma_san_pham);
    $stmt->execute();

    // Kiểm tra xem sản phẩm đã được xóa chưa
    if ($stmt->affected_rows > 0) {
        header("Location: ../html/giohang.php?refresh=" . time());
        exit();
    } else {
        echo "Không tìm thấy sản phẩm để xóa.";
    }

    $stmt->close();
} else {
    echo "Mã sản phẩm hoặc người dùng không hợp lệ.";
}

// Đóng kết nối
$conn->close();
?>