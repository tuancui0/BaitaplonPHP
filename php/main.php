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

?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang Chủ</title>
    <!-- Thêm CSS nếu cần -->
</head>
<body>
    <div class="header">
        <?php if (isset($_SESSION['user_name'])): ?>
            <p>Chào mừng, <?php echo $_SESSION['user_name']; ?> | <a href="../php/logout.php">Đăng xuất</a></p>
        <?php else: ?>
            <p><a href="../html/login.php">Đăng nhập</a></p>
        <?php endif; ?>
    </div>
    <!-- Nội dung trang chủ -->
    <div class="container">
        <!-- Thêm nội dung slider, danh mục, v.v. từ file HTML hiện tại -->
    </div>
</body>
</html>

<?php
$conn->close();
?>