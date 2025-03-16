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
    echo "<script>alert('Vui lòng đăng nhập để xem thông tin đơn hàng'); window.location.href='../html/login.php';</script>";
    exit();
}

$don_hang_id = isset($_GET['don_hang_id']) ? intval($_GET['don_hang_id']) : 0;
$user_id = $_SESSION['user_id'];

// Lấy thông tin đơn hàng
$sql = "SELECT * FROM don_hang WHERE id = ? AND nguoi_dung_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $don_hang_id, $user_id);
$stmt->execute();
$don_hang = $stmt->get_result()->fetch_assoc();
$stmt->close();

// Lấy chi tiết đơn hàng
$sql = "SELECT * FROM chi_tiet_don_hang WHERE don_hang_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $don_hang_id);
$stmt->execute();
$chi_tiet = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xác nhận đơn hàng - Minh Long Book</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>

    <div class="container">
        <h1 class="title">Xác nhận đơn hàng</h1>
        <?php if ($don_hang): ?>
            <p>Cảm ơn bạn đã đặt hàng! Dưới đây là thông tin đơn hàng của bạn:</p>
            <div class="order-info">
                <p><b>Mã đơn hàng:</b> <?php echo $don_hang['id']; ?></p>
                <p><b>Tổng tiền:</b> <?php echo number_format($don_hang['tong_tien'], 0, ',', '.'); ?>₫</p>
                <p><b>Phương thức thanh toán:</b> <?php echo $don_hang['phuong_thuc_thanh_toan']; ?></p>
                <p><b>Trạng thái:</b> <?php echo $don_hang['trang_thai']; ?></p>
                <p><b>Thời gian đặt:</b> <?php echo $don_hang['thoi_gian_dat']; ?></p>
            </div>
            <h2>Chi tiết đơn hàng</h2>
            <?php while ($item = $chi_tiet->fetch_assoc()): ?>
                <div class="order-item">
                    <p>Sách: <?php echo htmlspecialchars($item['ten_san_pham']); ?> - Số lượng: <?php echo $item['so_luong']; ?> - Giá: <?php echo number_format($item['gia'] * $item['so_luong'], 0, ',', '.'); ?>₫</p>
                </div>
            <?php endwhile; ?>
            <a href="../html/index.php" class="btn">Tiếp tục mua sắm</a>
        <?php else: ?>
            <p>Không tìm thấy thông tin đơn hàng.</p>
        <?php endif; ?>
    </div>

</body>
</html>

<?php
$stmt->close();
$conn->close();
?>