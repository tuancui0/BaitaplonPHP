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

// Kiểm tra xem người dùng đã đăng nhập chưa
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Vui lòng đăng nhập để xem trạng thái đơn hàng'); window.location.href='../html/login.php';</script>";
    exit();
}

$user_id = $_SESSION['user_id'];

// Xử lý cập nhật trạng thái đơn hàng khi nhấn nút "Đã nhận hàng"
if (isset($_POST['update_status']) && isset($_POST['don_hang_id'])) {
    $don_hang_id = intval($_POST['don_hang_id']);
    $new_status = "Nhận hàng thành công";

    $sql = "UPDATE don_hang SET trang_thai = ? WHERE id = ? AND nguoi_dung_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sii", $new_status, $don_hang_id, $user_id);

    if ($stmt->execute()) {
        echo "<script>alert('Cập nhật trạng thái thành công!'); window.location.href='trangthai_donhang.php';</script>";
    } else {
        echo "<script>alert('Có lỗi xảy ra khi cập nhật trạng thái.'); window.location.href='trangthai_donhang.php';</script>";
    }
    $stmt->close();
}

// Truy vấn danh sách đơn hàng của người dùng
$sql = "SELECT id, tong_tien, phuong_thuc_thanh_toan, trang_thai, thoi_gian_dat 
        FROM don_hang 
        WHERE nguoi_dung_id = ? 
        ORDER BY thoi_gian_dat DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trạng Thái Đơn Hàng - Minh Long Book</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="../css/cua_hang.css"> <!-- Nếu bạn có file CSS chung -->
    <style>
        .order-container {
            max-width: 1000px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .order-table {
            width: 100%;
            border-collapse: collapse;
        }
        .order-table th, .order-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        .order-table th {
            background-color: #39b54a;
            color: white;
        }
        .order-table tr:hover {
            background-color: #f5f5f5;
        }
        .order-link {
            color: #007bff;
            text-decoration: none;
        }
        .order-link:hover {
            text-decoration: underline;
        }
        .no-orders {
            text-align: center;
            padding: 20px;
            color: #666;
        }
        .btn-received {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 3px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .btn-received:hover {
            background-color: #45a049;
        }
        .btn-received:disabled {
            background-color: #cccccc;
            cursor: not-allowed;
        }
    </style>
</head>
<body>
    <!-- HEADER -->
    <div class="header">
        <div id="main_heder">
            <div id="logo"> <a href="../html/main_new.php"><img id="MINHLONG_logo" src="../img/logo.png" alt="logo"></a>
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
                            <p><?php echo htmlspecialchars($_SESSION['user_name']); ?> | <a href="../php/logout.php">Đăng xuất</a></p>
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
    <div class="order-container">
        <h1>Trạng Thái Đơn Hàng</h1>
        <?php if ($result->num_rows > 0): ?>
            <table class="order-table">
                <tr>
                    <th>Mã đơn hàng</th>
                    <th>Tổng tiền</th>
                    <th>Phương thức thanh toán</th>
                    <th>Trạng thái</th>
                    <th>Thời gian đặt</th>
                    <th>Chi tiết</th>
                    <th>Hành động</th>
                </tr>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['id']); ?></td>
                        <td><?php echo number_format($row['tong_tien'], 0, ',', '.'); ?> VNĐ</td>
                        <td><?php echo htmlspecialchars($row['phuong_thuc_thanh_toan']); ?></td>
                        <td><?php echo htmlspecialchars($row['trang_thai']); ?></td>
                        <td><?php echo htmlspecialchars($row['thoi_gian_dat']); ?></td>
                        <td>
                            <a href="../html/chitiet_donhang.php?don_hang_id=<?php echo $row['id']; ?>" class="order-link">Xem chi tiết</a>
                        </td>
                        <td>
                            <?php if ($row['trang_thai'] !== "Nhận hàng thành công"): ?>
                                <form method="post" style="display:inline;">
                                    <input type="hidden" name="don_hang_id" value="<?php echo $row['id']; ?>">
                                    <input type="hidden" name="update_status" value="1">
                                    <button type="submit" class="btn-received">Đã nhận hàng</button>
                                </form>
                            <?php else: ?>
                                <button class="btn-received" disabled>Đã nhận hàng</button>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </table>
        <?php else: ?>
            <p class="no-orders">Bạn chưa có đơn hàng nào.</p>
        <?php endif; ?>
        <a href="../html/main_new.php" class="btn">Quay lại trang chủ</a>
    </div>

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

<?php
$stmt->close();
$conn->close();
?>