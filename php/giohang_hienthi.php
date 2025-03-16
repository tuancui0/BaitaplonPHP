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
// Kiểm tra xem người dùng đã đăng nhập chưa
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Vui lòng đăng nhập để xem giỏ hàng'); window.location.href='../html/login.php';</script>";
    exit();
}

// Truy vấn dữ liệu theo nguoi_dung_id
$user_id = $_SESSION['user_id'];
$sql = "SELECT ten_san_pham, ma_san_pham, gia, so_luong FROM gio_hang WHERE nguoi_dung_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Tính tổng giá tiền
$tong_gia = 0;

// Hiển thị dữ liệu
if ($result->num_rows > 0) {
    echo "<div style='overflow-x:auto;'>";
    echo "<table style='width: 100%; border-collapse: collapse; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);'>";
    echo "<tr style='background-color: #39b54a; color: white;'>";
    echo "<th style='padding: 15px; text-align: left;'>Tên sản phẩm</th>";
    echo "<th style='padding: 15px; text-align: left;'>Mã sản phẩm</th>";
    echo "<th style='padding: 15px; text-align: left;'>Giá</th>";
    echo "<th style='padding: 15px; text-align: left;'>Số lượng</th>";
    echo "<th style='padding: 15px; text-align: left;'>Thành tiền</th>";
    echo "<th style='padding: 15px; text-align: left;'>Hành động</th>";
    echo "</tr>";
    
    while ($row = $result->fetch_assoc()) {
        $thanh_tien = $row['gia'] * $row['so_luong'];
        $tong_gia += $thanh_tien;

        echo "<tr style='border-bottom: 1px solid #ddd;'>";
        echo "<td style='padding: 12px;'>" . htmlspecialchars($row['ten_san_pham']) . "</td>";
        echo "<td style='padding: 12px;'>" . htmlspecialchars($row['ma_san_pham']) . "</td>";
        echo "<td style='padding: 12px;'>" . htmlspecialchars(number_format($row['gia'], 0, ',', '.')) . " VNĐ</td>";
        echo "<td style='padding: 12px;'>" . htmlspecialchars($row['so_luong']) . "</td>";
        echo "<td style='padding: 12px;'>" . htmlspecialchars(number_format($thanh_tien, 0, ',', '.')) . " VNĐ</td>";
        echo "<td style='padding: 12px;'><a href='../php/giohang_xoa.php?ma_san_pham=" . urlencode($row['ma_san_pham']) . "' style='color: red; text-decoration: none;'>Xóa</a></td>";
        echo "</tr>";
    }
    
    // Hiển thị tổng giá tiền
    echo "<tr style='font-weight: bold; background-color: #f2f2f2;'>";
    echo "<td colspan='4' style='text-align: right; padding: 12px;'>Tổng giá:</td>";
    echo "<td style='padding: 12px;'>" . htmlspecialchars(number_format($tong_gia, 0, ',', '.')) . " VNĐ</td>";
    echo "<td></td>";
    echo "</tr>";
    
    echo "</table>";
    echo "</div>";
} else {
    echo '<div class="empty-cart" style="text-align: center; margin: 20px;">';
    echo '<img src="https://theme.hstatic.net/1000237375/1000756917/14/empty_cart.png?v=1672" alt="Giỏ hàng trống" style="max-width: 150px; margin-bottom: 15px;">';
    echo '<p>Không có sản phẩm nào trong giỏ hàng của bạn</p>';
    echo '</div>'; 
}

// Đóng kết nối
$conn->close();
?>