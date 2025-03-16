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

// Truy vấn số lượng
$sql_nguoi_dung = "SELECT COUNT(*) as total FROM tai_khoan";
$result_nguoi_dung = $conn->query($sql_nguoi_dung);
$row_nguoi_dung = $result_nguoi_dung->fetch_assoc();
$so_luong_nguoi_dung = $row_nguoi_dung['total'];

$sql_san_pham = "SELECT COUNT(*) as total FROM san_pham";
$result_san_pham = $conn->query($sql_san_pham);
$row_san_pham = $result_san_pham->fetch_assoc();
$so_luong_san_pham = $row_san_pham['total'];

$sql_don_hang = "SELECT COUNT(*) as total FROM don_hang";
$result_don_hang = $conn->query($sql_don_hang);
$row_don_hang = $result_san_pham->fetch_assoc();
$so_luong_don_hang = $row_san_pham['total'];
// Đóng kết nối
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang Quản Trị</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9; /* Xám nhạt */
            color: #333;
            line-height: 1.6;
        }

        .header {
            background-color: #4CAF50; /* Giữ xanh lá cho header */
            color: white;
            padding: 20px;
            text-align: center;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            position: relative;
        }

        .header h2 {
            margin: 0;
            font-size: 28px;
            font-weight: 600;
            letter-spacing: 1px;
        }

        /* Thay thế nav bằng banner trang trí */
        .decorative-banner {
            background: linear-gradient(90deg, #4CAF50, #81C784); /* Gradient xanh lá */
            height: 100px;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .decorative-banner::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.2) 0%, transparent 70%);
            animation: rotate 10s linear infinite;
        }

        @keyframes rotate {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .decorative-banner h3 {
            color: white;
            font-size: 24px;
            font-weight: 600;
            z-index: 1;
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.3);
            position: relative;
        }

        .decorative-banner .book-icon {
            position: absolute;
            font-size: 40px;
            color: #FFD700; /* Vàng nhạt */
            animation: float 3s ease-in-out infinite;
        }

        .decorative-banner .book-icon:nth-child(2) {
            left: 20%;
            animation-delay: 1s;
        }

        .decorative-banner .book-icon:nth-child(3) {
            right: 20%;
            animation-delay: 2s;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }

        .content {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            margin: 30px;
            gap: 20px;
        }

        .box {
            width: 220px;
            height: 120px;
            background-color: white;
            border: 2px solid #4CAF50; /* Giữ viền xanh lá */
            border-radius: 10px;
            margin: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            font-size: 18px;
            font-weight: 500;
            color: #333;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .box:hover {
            background-color: #FFF9C4; /* Vàng nhạt khi hover */
            border-color: #FFD700; /* Viền vàng nhạt */
            transform: scale(1.05);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
        }

        .box i {
            font-size: 24px;
            margin-bottom: 10px;
            color: #4CAF50; /* Giữ icon xanh lá */
        }

        .quick-info {
            margin: 30px auto;
            max-width: 1200px;
            background-color: #ffffff; /* Nền trắng */
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .quick-info h3 {
            color: #FF9800; /* Cam nhạt */
            font-size: 22px;
            margin-bottom: 20px;
        }

        .info-grid {
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
        }

        .info-item {
            width: 200px;
            padding: 15px;
            background-color: #e0e0e0; /* Xám nhạt */
            border-radius: 8px;
            margin: 10px;
            transition: all 0.3s ease;
        }

        .info-item:hover {
            background-color: #d3d3d3; /* Xám đậm hơn khi hover */
            transform: translateY(-2px);
        }

        .info-item h4 {
            margin: 0;
            color: #333;
            font-size: 18px;
        }

        .info-item p {
            margin: 5px 0 0;
            color: #666;
            font-size: 16px;
            font-weight: 600;
        }

        .footer {
            background-color: #4CAF50; /* Giữ xanh lá */
            color: white;
            text-align: center;
            padding: 15px 0;
            margin-top: 30px;
            box-shadow: 0 -2px 5px rgba(0, 0, 0, 0.1);
        }

        .footer p {
            margin: 5px 0;
            font-size: 14px;
        }

        .back-btn {
            display: inline-block;
            margin: 20px 0;
            padding: 10px 20px;
            background-color: #4CAF50; /* Giữ xanh lá */
            color: white;
            text-decoration: none;
            border-radius: 4px;
            transition: background-color 0.3s ease;
        }

        .back-btn:hover {
            background-color: #FF9800; /* Cam nhạt khi hover */
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <!-- HEADER -->
    <div class="header">
        <h2>Trang Quản Trị</h2>
    </div>

    <div class="decorative-banner">
        <i class="fas fa-book book-icon"></i>
        <i class="fas fa-book book-icon"></i>
        <i class="fas fa-book book-icon"></i>
    </div>

    <!-- CONTENT -->
    <div class="content">
        <div class="box" onclick="window.location.href='quan_ly_nguoi_dung.php'">
            <i class="fas fa-users"></i><br>Quản lý người dùng
        </div>
        <div class="box" onclick="window.location.href='quan_ly_san_pham.php'">
            <i class="fas fa-boxes"></i><br>Quản lý sản phẩm
        </div>
        <div class="box" onclick="window.location.href='quan_ly_don_hang.php'">
            <i class="fas fa-shopping-cart"></i><br>Quản lý đơn hàng
        </div>
        <div class="box" onclick="window.location.href='thong_ke.php'">
            <i class="fas fa-chart-bar"></i><br>Thống kê
        </div>
    </div>

    <!-- QUICK INFO -->
    <div class="quick-info">
        <h3>Thông Tin Nhanh</h3>
        <div class="info-grid">
            <div class="info-item">
                <h4>Số lượng người dùng</h4>
                <p><?php echo $so_luong_nguoi_dung; ?></p>
            </div>
            <div class="info-item">
                <h4>Số sản phẩm</h4>
                <p><?php echo $so_luong_san_pham; ?></p>
            </div>
            <div class="info-item">
                <h4>Số đơn hàng</h4>
                <p><?php echo $so_luong_don_hang; ?></p>
            </div>
        </div>
    </div>

    <!-- FOOTER -->
    <div class="footer">
        <p>Công ty TNHH Minh Long Book</p>
        <p>Hotline: 0889 833 688 | Email: support@minhlongbook.com</p>
        <p>© 2025 - Tất cả quyền được bảo lưu</p>
    </div>
</body>
</html>