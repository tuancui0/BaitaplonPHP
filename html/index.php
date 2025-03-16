<?php
session_start(); // Bắt đầu session

// Kết nối database
$servername = "localhost";
$username = "root";
$password = "Tuan2004@";
$dbname = "btl2";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Lấy dữ liệu sách nổi bật (giả sử lấy 8 sách có số lượng bán cao nhất)
$sql_featured_books = "SELECT id, loai_sach, ma_san_pham, ten_san_pham, mo_ta, gia, so_luong, thoi_gian_tao, anh FROM san_pham ORDER BY so_luong DESC LIMIT 14";
$result_featured_books = $conn->query($sql_featured_books);

// Lấy dữ liệu sách mầm non (giả sử lấy 4 sách)
$sql_mam_non = "SELECT id, loai_sach, ma_san_pham, ten_san_pham, mo_ta, gia, so_luong, thoi_gian_tao, anh FROM san_pham WHERE loai_sach = 'Sách Mầm Non' LIMIT 7";
$result_mam_non = $conn->query($sql_mam_non);

// Lấy dữ liệu sách kinh doanh (giả sử lấy 4 sách)
$sql_kinh_doanh = "SELECT id, loai_sach, ma_san_pham, ten_san_pham, mo_ta, gia, so_luong, thoi_gian_tao, anh FROM san_pham WHERE loai_sach = 'Sách Kinh Doanh' LIMIT 7";
$result_kinh_doanh = $conn->query($sql_kinh_doanh);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minh Long Book - Trang Chủ</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="../css/cua_hang.css"> 
    <link rel="stylesheet" href="../css/main_new.css"> 
    <style>
        .zalo-chat {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 1000;
        }

        .zalo-chat img {
            width: 60px;
            height: auto;
            border-radius: 50%;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2);
        }

        .menu_hedear {
            position: relative;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #f9f9f9;
            min-width: 200px;
            box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
            z-index: 1;
            left: 0;
            top: 100%;
        }

        .dropdown-content a {
            color: black;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
            font-size: 14px;
        }

        .dropdown-content a:hover {
            background-color: #f1f1f1;
        }

        .menu_hedear:hover .dropdown-content {
            display: block;
        }

        #title:hover {
            background-color: #ddd;
        }
    </style>
</head>
<body>
    <div class="header">
        <div id="main_heder">
            <div id="logo"> <a href="../html/index.php"><img id="MINHLONG_logo" src="../img/logo.png" alt="logo"></a>
            </div>
            <div id="search">
                <form action="/search" method="get">
                    <input type="search" placeholder="Tìm kiếm..." name="query" required>
                    <button type="submit">Tìm kiếm</button>
                </form>
            </div>
            <div class="icon">
                <div id="donhang">
                    <div id="icon1">
                        <i class="fa-solid fa-phone"></i>
                    </div>
                    <div><a href="../html/trangthai_donhang.php">
                            <p>Tra cứu đơn hàng</p>
                        </a></div>
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
        <div class="menu_hedear">
            <div id="title">
                <div id="icon4">
                    <i class="fa fa-bars"></i>
                </div>
                <div>
                    <span class="menu_header">DANH MỤC SÁCH</span>
                </div>
            </div>
            <div class="dropdown-content">
                <a href="../html/sach_mam_non.php">Sách Mầm Non</a>
                <a href="../html/sach_thieu_nhi.php">Sách Thiếu Nhi</a>
                <a href="../html/sach_ky_nang.php">Sách Kỹ Năng</a>
                <a href="../html/sach_kinh_doanh.php">Sách Kinh Doanh</a>
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
                    <span class="menu_header">0889833688/ 0373720545</span>
                </div>
            </div>
        </div>
    </div>

    <!-- BANNER -->
    <section class="banner">
        <img src="../img/anh_trang_main/slide4.png" alt="Banner">
        <div class="banner-content">
            <h1>Khám Phá Thế Giới Sách</h1>
            <p>Hàng ngàn cuốn sách đang chờ bạn khám phá!</p>
            <a href="#featured-books">Xem ngay</a>
        </div>
    </section>

    <!-- DANH MỤC NỔI BẬT -->
    <section class="categories">
        <h2>Danh Mục Nổi Bật</h2>
        <div class="category-grid">
            <div class="category-item">
                <a href="../html/sach_mam_non.php">
                    <img src="../img/anh_trang_main/slide1.png" alt="Sách Mầm Non">
                    <h3>Sách Mầm Non</h3>
                </a>
            </div>
            <div class="category-item">
                <a href="../html/sach_thieu_nhi.php">
                    <img src="../img/anh_trang_main/slide4.png" alt="Sách Thiếu Nhi">
                    <h3>Sách Thiếu Nhi</h3>
                </a>
            </div>
            <div class="category-item">
                <a href="../html/sach_ky_nang.php">
                    <img src="../img/anh_trang_main/slide2.jpg" alt="Sách Kỹ Năng">
                    <h3>Sách Kỹ Năng</h3>
                </a>
            </div>
            <div class="category-item">
                <a href="../html/sach_kinh_doanh.php">
                    <img src="../img/anh_trang_main/slide3.jpg" alt="Sách Kinh Doanh">
                    <h3>Sách Kinh Doanh</h3>
                </a>
            </div>
        </div>
    </section>

    <!-- SÁCH NỔI BẬT -->
    <section class="book-section" id="featured-books">
        <h2>Sách Nổi Bật</h2>
        <div class="book-grid">
            <?php
            if ($result_featured_books->num_rows > 0) {
                while ($row = $result_featured_books->fetch_assoc()) {
                    // Chuyển đổi ảnh từ binary sang base64
                    $imageData = base64_encode($row['anh']);
                    $src = 'data:image/jpeg;base64,' . $imageData;
                    echo '<a href="../html/chitiet_sp.php?id=' . $row['id'] . '" class="book-item">';
                    echo '<img src="' . $src . '" alt="' . htmlspecialchars($row['ten_san_pham']) . '">';
                    echo '<div class="book-info">';
                    echo '<h3>' . htmlspecialchars($row['ten_san_pham']) . '</h3>';
                    echo '<p class="price">' . number_format($row['gia'], 0, ',', '.') . 'đ</p>';
                    echo '</div>';
                    echo '</a>';
                }
            } else {
                echo '<p>Không có sách nổi bật.</p>';
            }
            ?>
        </div>
        <div class="view-more">
            <a href="../html/sach_mam_non.php">Xem thêm</a>
        </div>
    </section>

    <!-- SÁCH MẦM NON -->
    <section class="book-section">
        <h2>Sách Mầm Non</h2>
        <div class="book-grid">
            <?php
            if ($result_mam_non->num_rows > 0) {
                while ($row = $result_mam_non->fetch_assoc()) {
                    // Chuyển đổi ảnh từ binary sang base64
                    $imageData = base64_encode($row['anh']);
                    $src = 'data:image/jpeg;base64,' . $imageData;
                    echo '<a href="../html/chitiet_sp.php?id=' . $row['id'] . '" class="book-item">';
                    echo '<img src="' . $src . '" alt="' . htmlspecialchars($row['ten_san_pham']) . '">';
                    echo '<div class="book-info">';
                    echo '<h3>' . htmlspecialchars($row['ten_san_pham']) . '</h3>';
                    echo '<p class="price">' . number_format($row['gia'], 0, ',', '.') . 'đ</p>';
                    echo '</div>';
                    echo '</a>';
                }
            } else {
                echo '<p>Không có sách mầm non.</p>';
            }
            ?>
        </div>
        <div class="view-more">
            <a href="../html/sach_mam_non.php">Xem thêm</a>
        </div>
    </section>

    <!-- SÁCH KINH DOANH -->
    <section class="book-section">
        <h2>Sách Kinh Doanh</h2>
        <div class="book-grid">
            <?php
            if ($result_kinh_doanh->num_rows > 0) {
                while ($row = $result_kinh_doanh->fetch_assoc()) {
                    // Chuyển đổi ảnh từ binary sang base64
                    $imageData = base64_encode($row['anh']);
                    $src = 'data:image/jpeg;base64,' . $imageData;
                    echo '<a href="../html/chitiet_sp.php?id=' . $row['id'] . '" class="book-item">';
                    echo '<img src="' . $src . '" alt="' . htmlspecialchars($row['ten_san_pham']) . '">';
                    echo '<div class="book-info">';
                    echo '<h3>' . htmlspecialchars($row['ten_san_pham']) . '</h3>';
                    echo '<p class="price">' . number_format($row['gia'], 0, ',', '.') . 'đ</p>';
                    echo '</div>';
                    echo '</a>';
                }
            } else {
                echo '<p>Không có sách kinh doanh.</p>';
            }
            ?>
        </div>
        <div class="view-more">
            <a href="../html/sach_kinh_doanh.php">Xem thêm</a>
        </div>
        <a href="https://zalo.me/0387463250" target="_blank" class="zalo-chat">
            <img src="https://stc-zaloprofile.zdn.vn/pc/v1/images/zalo_sharelogo.png" alt="Chat Zalo">
        </a>
    </section>

    <!-- FOOTER PROMOTION -->
    <div class="footer_promotion">
        <div class="footer_promotion_main">
            <div class="item_footer_promotion">
                <div>
                    <img class="img_footer_promotion" src="../img/vanchuyen.png" alt="">
                </div>
                <div>
                    <h4>MIỄN PHÍ VẬN CHUYỂN</h4>
                    <p>cho đơn hàng trên 300.000 VNĐ</p>
                </div>
            </div>
            <div class="item_footer_promotion">
                <div>
                    <img class="img_footer_promotion" src="../img/shipcode_footer.png" alt="">
                </div>
                <div>
                    <h4>SHIP COD TOÀN QUỐC</h4>
                    <p>thanh toán khi nhận sách</p>
                </div>
            </div>
            <div class="item_footer_promotion">
                <div>
                    <img class="img_footer_promotion" src="../img/doi tra.png" alt="">
                </div>
                <div>
                    <h4>MIỄN PHÍ ĐỔI TRẢ</h4>
                    <p>trong vòng 10 ngày</p>
                </div>
            </div>
            <div class="item_footer_promotion">
                <div>
                    <img class="img_footer_promotion" src="../img/sdt_fotter.png" alt="">
                </div>
                <div>
                    <h4>HOTLINE</h4>
                    <p>0889833688-0373720545</p>
                </div>
            </div>
        </div>
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
                    <p><i class="fa

-solid fa-location-dot"></i> Chi nhánh Miền Nam: 33 Đỗ Thừa Tự, Tân Quý, Tân Phú, Thành phố Hồ Chí Minh, Việt Nam</p>
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
    <script>
    const menu = document.querySelector('.menu_hedear');
    const dropdown = document.querySelector('.dropdown-content');

    menu.addEventListener('mouseenter', () => {
        dropdown.style.display = 'block';
    });

    menu.addEventListener('mouseleave', () => {
        dropdown.style.display = 'none';
    });
</script>
</body>

</html>

<?php
$conn->close();
?>