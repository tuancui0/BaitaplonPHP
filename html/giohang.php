<?php
session_start(); // Bắt đầu session
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="../css/cua_hang.css"> <!-- Liên kết với tệp CSS -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giỏ Hàng</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            color: #333;
        }

        .cart-container {
            max-width: 1200px;
            margin: 20px auto;
            background: #ffffff;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .cart-container h1 {
            text-align: center;
            color: #39b54a;
        }

        .empty-cart {
            text-align: center;
            margin: 50px 0;
        }

        .empty-cart img {
            width: 40%;
            margin-bottom: 20px;
        }

        .empty-cart p {
            font-size: 18px;
            margin-bottom: 20px;
            color: #666;
        }

        .btn-primary {
            display: inline-block;
            padding: 10px 20px;
            background-color: #39b54a;
            color: #fff;
            border-radius: 4px;
            text-decoration: none;
            margin: 10px 0;
            transition: background-color 0.3s;
        }

        .btn-primary:hover {
            background-color: #2e7d32;
        }
    </style>
</head>
<body>
    <!-- HEADER -->
    <div class="header">
        <div id="main_heder">
            <div id="logo">
                <a href="../html/index.php"><img id="MINHLONG_logo" src="../img/logo.png" alt="logo"></a>
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
                    <div><a href="../html/trangthai_donhang.php"><p>Tra cứu đơn hàng</p></a></div>
                </div>
                <div id="giohang"> 
                    <div id="icon2">
                        <i class="fa-solid fa-cart-shopping"></i>
                    </div>
                    <div>
                        <p>Giỏ hàng</p>
                    </div>
                </div>
                <div id="taikhoan">
                    <div id="icon3">
                        <i class="fa-regular fa-user"></i>
                    </div>
                    <div>
                        <?php if (isset($_SESSION['user_name'])): ?>
                            <p>Chào, <?php echo $_SESSION['user_name']; ?> | <a href="../php/logout.php">Đăng xuất</a></p>
                        <?php else: ?>
                            <a href="../html/login.php"><p>Tài khoản</p></a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Giỏ hàng -->
    <div class="cart-container">
        <h1>Giỏ Hàng</h1>
        <?php include('../php/giohang_hienthi.php'); ?> <!-- Nhúng file hiển thị giỏ hàng -->
        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="../html/checkout.php" class="btn-primary">Thanh Toán</a>
        <?php else: ?>
            <p>Vui lòng <a href="../html/login.php">đăng nhập</a> để thanh toán.</p>
        <?php endif; ?>
        <a href="../html/index.php" class="btn-primary">Tiếp tục mua hàng</a>
    </div>

    <!-- Footer -->
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