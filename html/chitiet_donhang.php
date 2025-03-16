<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "Tuan2004@";
$dbname = "btl2";

// Tạo kết nối
$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Kiểm tra đăng nhập
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Vui lòng đăng nhập để xem chi tiết đơn hàng'); window.location.href='../html/login.php';</script>";
    exit();
}

$user_id = $_SESSION['user_id'];
$don_hang_id = isset($_GET['don_hang_id']) ? intval($_GET['don_hang_id']) : 0;

// Lấy thông tin đơn hàng
$sql_don_hang = "SELECT * FROM don_hang WHERE id = ? AND nguoi_dung_id = ?";
$stmt_don_hang = $conn->prepare($sql_don_hang);
$stmt_don_hang->bind_param("ii", $don_hang_id, $user_id);
$stmt_don_hang->execute();
$don_hang = $stmt_don_hang->get_result()->fetch_assoc();
$stmt_don_hang->close();

if (!$don_hang) {
    echo "<script>alert('Không tìm thấy đơn hàng này.'); window.location.href='../html/xem_trang_thai_don_hang.php';</script>";
    exit();
}

// Xử lý cập nhật địa chỉ nếu form được gửi
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_address'])) {
    $ten = htmlspecialchars($_POST['ten']);
    $so_dien_thoai = htmlspecialchars($_POST['so_dien_thoai']);
    $dia_chi_moi = htmlspecialchars($_POST['dia_chi']);

    $sql_update = "UPDATE don_hang SET ten_nguoi_nhan = ?, so_dien_thoai = ?, dia_chi = ? WHERE id = ? AND nguoi_dung_id = ?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param("ssiii", $ten, $so_dien_thoai, $dia_chi_moi, $don_hang_id, $user_id);

    if ($stmt_update->execute()) {
        echo "<script>alert('Cập nhật thông tin giao hàng thành công!'); window.location.href='chitiet_donhang.php?don_hang_id=$don_hang_id';</script>";
    } else {
        echo "<script>alert('Cập nhật thất bại. Vui lòng thử lại.'); window.location.href='chitiet_donhang.php?don_hang_id=$don_hang_id';</script>";
    }
    $stmt_update->close();
}

// Lấy chi tiết đơn hàng
$sql_chi_tiet = "SELECT * FROM chi_tiet_don_hang WHERE don_hang_id = ?";
$stmt_chi_tiet = $conn->prepare($sql_chi_tiet);
$stmt_chi_tiet->bind_param("i", $don_hang_id);
$stmt_chi_tiet->execute();
$chi_tiet = $stmt_chi_tiet->get_result();
$stmt_chi_tiet->close();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/cua_hang.css"> <!-- Liên kết với file CSS riêng -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <title>Chi tiết đơn hàng - Minh Long Book</title>
    <style>
    .container {
        max-width: 1000px;
        margin: 20px auto;
        padding: 20px;
        background-color: #fff;
        border-radius: 5px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }
    .title {
        text-align: center;
        color: #39b54a;
    }
    .order-info strong {
        color: #000;
        font-size: 1.5em; /* Điều chỉnh kích cỡ của thẻ strong */
    }
    .order-info p {
        font-size: 1em; /* Điều chỉnh kích cỡ của thẻ p */
    }
    .order-info, .order-items {
        margin-bottom: 24px;
    }
    .order-info p, .order-items p {
        margin: 5px 0;
    }
    .order-items table {
        width: 100%;
        border-collapse: collapse;
    }
    .order-items th, .order-items td {
        padding: 10px;
        border: 1px solid #ddd;
        text-align: left;
    }
    .order-items th {
        background-color: #39b54a;
        color: white;
    }
    .shipping-form {
        margin-top: 20px;
        padding: 15px;
        border: 1px solid #ddd;
        border-radius: 5px;
    }
    .shipping-form label {
        display: block;
        margin-bottom: 5px;
        font-weight: bold;
    }
    .shipping-form input, .shipping-form textarea {
        width: 100%;
        padding: 8px;
        margin-bottom: 10px;
        border: 1px solid #ccc;
        border-radius: 4px;
    }
    .shipping-form button {
        background-color: #39b54a;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }
    .shipping-form button:hover {
        background-color: #2e8b3d;
    }
    .back-btn {
        display: inline-block;
        margin-top: 10px;
        padding: 10px 20px;
        background-color: #007bff;
        color: white;
        text-decoration: none;
        border-radius: 5px;
    }
    .back-btn:hover {
        background-color: #0056b3;
    }
</style>
</head>
<body>
    <!-- HEADER -->
<div class="header">
        <div id="main_heder">
            <div id="logo"> <a href="../html/index.php"><img id="MINHLONG_logo" src="../img/logo.png" alt="logo"></a>
            </div>
            <div id="search"> <form action="/search" method="get">
                <input type="search" placeholder="Tìm kiếm..." name="query" required>
                <button type="submit">Tìm kiếm</button>
                </form>
            </div>
            <div class="icon">
                <div id="donhang">
                    <div id="icon1">
                        <i class="fa-solid fa-phone"></i>
                    </div>
                    <div><a href="../html/trangthai_donhang.php"><p>Tra cứu đơn hàng</p></a></div>
                </div>
                <div id="giohang"> 
                    <div id="icon2">
                        <i class="fa-solid fa-cart-shopping"></i>
                    </div>
                    <div>
                        <p><a href="../html/giohang.php">Giỏ hàng</a></p>
                    </div>
                </div>
                <div id="taikhoan">
                    <div id="icon3">
                        <i class="fa-regular fa-user"></i>
                    </div>
                    <div>
                        <?php if (isset($_SESSION['user_name'])): ?>
                            <p><?php echo $_SESSION['user_name']; ?> | <a href="../php/logout.php">Đăng xuất</a></p>
                        <?php else: ?>
                            <p><a href="../html/login.php">Tài khoản</a></p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="main">
        <div class="menu_hedear ">
            <div id="title">
                <div id="icon4">
                    <i class="fa fa-bars"></i>
                </div>
                <div >
                    <span class="menu_header">DANH MỤC SÁCH</span>
                </div>
            </div>   
            <div id="menu">
                <div>
                    <span>Sản phẩm đã xem</span>
                </div>
                <div id="ship_cod">
                    <div><img class="img2" src="../img/ship cod.png" alt=""></div>
                    <span class="menu_header">Ship COD trên toàn quốc</span>
                </div>
                <div id="free_ship">
                    <div><img class="img2" src="../img/free ship.png" alt=""></div>
                    <span class="menu_header">Freeship đơn hàng trên 300k</span>
                </div>
                <div id="sdt">
                    <div><img class="img2" src="../img/sdt.png" alt=""></div>
                    <span class="menu_header">0889833688/ 0373720545 </span>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <h1 class="title">Chi tiết đơn hàng</h1>

        <div class="order-info">
            <p><strong>Mã đơn hàng:</strong> <?php echo htmlspecialchars($don_hang['id']); ?></p>
            <p><strong>Trạng thái:</strong> <?php echo htmlspecialchars($don_hang['trang_thai']); ?></p>
        </div>

        <div class="order-items">
            <h3>Chi tiết sản phẩm</h3>
            <table>
                <tr>
                    <th>Tên sản phẩm</th>
                    <th>Số lượng</th>
                    <th>Giá</th>
                    <th>Thành tiền</th>
                </tr>
                <?php while ($item = $chi_tiet->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($item['ten_san_pham']); ?></td>
                        <td><?php echo htmlspecialchars($item['so_luong']); ?></td>
                        <td><?php echo number_format($item['gia'], 0, ',', '.'); ?> VNĐ</td>
                        <td><?php echo number_format($item['gia'] * $item['so_luong'], 0, ',', '.'); ?> VNĐ</td>
                    </tr>
                <?php endwhile; ?>
            </table>
            <h3>Thông tin giao hàng</h3>
            <table>
                <tr>
                    <th>Tên người nhận</th>
                    <th>Số điện thoại</th>
                    <th>Địa chỉ</th>
                </tr>
                <tr>
                    <td><?php echo htmlspecialchars($don_hang['ten_nguoi_nhan']); ?></td>
                    <td><?php echo htmlspecialchars($don_hang['so_dien_thoai']); ?></td>
                    <td><?php echo htmlspecialchars($don_hang['dia_chi']); ?></td>
                </tr>
            </table>
        </div>

        <div class="shipping-form">
            <h3>Cập nhật thông tin giao hàng</h3>
            <form action="" method="post">
                <label for="ten">Họ và tên:</label>
                <input type="text" id="ten" name="ten" value="<?php echo htmlspecialchars($don_hang['ten_nguoi_nhan']); ?>" required pattern="[A-Za-zÀ-ỹ\s]+" title="Tên chỉ được chứa chữ cái và khoảng trắng">

                <label for="so_dien_thoai">Số điện thoại:</label>
                <input type="text" id="so_dien_thoai" name="so_dien_thoai" value="<?php echo htmlspecialchars($don_hang['so_dien_thoai']); ?>" required pattern="[0-9]{10}" title="Số điện thoại phải là 10 chữ số">

                <label for="dia_chi">Địa chỉ:</label>
                <textarea id="dia_chi" name="dia_chi" required minlength="10" title="Địa chỉ phải ít nhất 10 ký tự"><?php echo htmlspecialchars($don_hang['dia_chi']); ?></textarea>

                <button type="submit" name="update_address">Cập nhật thông tin</button>
            </form>
        </div>

        <a href="../html/trangthai_donhang.php" class="back-btn">Quay lại danh sách đơn hàng</a>
    </div>

    <?php $conn->close(); ?>
    <!-- FOOTER MAIN -->
<div class="footer_main">
        <div class="footer_main_cotainer">
            <div class="footer_main_cotainer_col1">
                <div>
                    <img class="footer_logo" src="../img/footer.logo.png" alt="">
                </div>
                <div>
                    <p>Công ty TNHH BA Thành viên Thương mại & Dịch vụ Văn hóa Minh Long</p>
                    <p>Mã số thuế: 001283868686</p>
                </div>
                <div>
                    <p><i class="fa-solid fa-location-dot"></i> Văn phòng: LK 02 - 03, Dãy B, KĐT Green Pearl, 378 Minh Khai, Hai Bà Trưng, Hà Nội.</p>
                    <p><i class="fa-solid fa-location-dot"></i> Cửa hàng: Gian hàng Minh Long Book tại Phố Sách Hà Nội, Phố 19 tháng 12, Hoàn Kiếm, Hà Nội.</p>
                    <p><i class="fa-solid fa-phone"></i> 0889833688-0373720545</p>
                    <p><i class="fa-regular fa-envelope"></i> 20223044@eaut.edu.vn</p>
                    <p><i class="fa-solid fa-location-dot"></i> Chi nhánh Miền Nam: 33 Đỗ Thừa Tự, Tân Quý, Tân Phú, Thành phố Hồ Chí Minh, Việt Nam</p>
                </div>
            </div>
            <div class="footer_main_cotainer_col">
                <div class="footer_main_cotainer_title">
                    <h4>TIN TỨC</h4>
                </div>
                <div>
                    <ul>
                        <li>Giới thiệu</li>
                        <li>Điểm sách</li>
                        <li>Tuyển dụng</li>
                        <li>Sự kiện</li>
                        <li>Tin khuyến mại</li>
                    </ul>
                </div>
            </div>
            <div class="footer_main_cotainer_col">
                <div class="footer_main_cotainer_title">
                    <h4>HỖ TRỢ KHÁCH HÀNG</h4>
                </div>
                <div>
                    <ul>
                        <li>Điều khoản sử dụng</li>
                        <li>Hướng dẫn mua hàng</li>
                        <li>Phương thức thanh toán</li>
                        <li>Phương thức giao hàng</li>
                        <li>Chính sách đổi trả</li>
                        <li>Bảo mật thông tin</li>
                    </ul>
                </div>
            </div>
            <div class="footer_main_cotainer_col">
                <div class="footer_main_cotainer_title">
                    <h4>THÔNG TIN</h4>
                </div>
                <div>
                    <ul>
                        <li><a href="../html/login.php">Đăng nhập</a></li>
                        <li>Đăng ký</li>
                        <li>Tra cứu đơn hàng</li>
                        <li>Liên hệ</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</body>
</html>