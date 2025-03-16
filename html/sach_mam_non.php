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
    <title>Document</title>
</head>
<style>
    .container{
        margin-left: 10%;
        margin-right: 5%;
    }
    .container_title{
        text-align: center;
        margin-top: 50px;
        margin-bottom: -20px;
        border: solid 1px rgba(194, 173, 173, 0.496);
    }
    .container_title h4{
        font-weight: 100;
    }
    .container_row{
        margin-top: 50px;
        display: flex;
        flex-direction: row;
        justify-content: space-between;
    }
    .container_row_item{
        width: 25%;
        text-align: center;
    }
    .container_row_item_img{
        width: 100px;
        height: 150px;
        margin-top: 10px;
    }
    .container_row_item p{
        color: #333;
        font-weight: 15;
    }
    .container_row_item_cost p{
        color: #45a049;
        font-size: 16px;
    }

    /* -----------------------------------------------------------container_menu-------------------------------- */
    .main_main{
        display: flex;
        flex-direction: row;
        width: 80%;
        margin-left: 10%;
        margin-right: 10%;
    }
    .cotainer_menu{
        margin-top: 50px;
        width: 30%;
        height: 400px;
        padding-right: 20px;
        border-right: solid rgb(235, 227, 227) 2px;
    }
    .cotainer_menu h4{
        width: 100%;
        font-weight: 100;
    }
    .cotainer_menu li{
        list-style-type: none; /* Bỏ dấu chấm đầu dòng */
        padding-left: 0; /* Bỏ padding bên trái */
        line-height: 2;
        margin-left: 20px;
    }
    .cotainer_menu li h5{
        font-weight: 100;
    }
    .cotainer_menu li:hover h5 {
        color: #4CAF50; /* Đổi màu chữ khi hover vào mục li */
    }
    #this{
        color: #4CAF50;
    }
    a {
        text-decoration: none; /* Tắt gạch chân */
        color: inherit; /* Đặt màu giống màu chữ mặc định */
    }
    .container_row_item_title p {
        color: black; /* Màu mặc định */
        transition: color 0.3s; /* Hiệu ứng chuyển màu */
    }
    .container_row_item:hover .container_row_item_title p {
        color: #4CAF50; /* Màu khi di chuột vào */
    }
    .container_row_item {
        width: 20%;
        text-align: center;
        transition: transform 0.3s ease, box-shadow 0.3s ease; /* Thêm hiệu ứng chuyển tiếp */
    }
    .container_row_item {
        position: relative;
        transition: transform 0.3s, box-shadow 0.3s;
    }
    .hoverr {
        display: none; /* Ẩn phần tử này mặc định */
        position: absolute; /* Để có thể đặt vị trí */
        bottom: 10px; /* Cách đáy một chút */
        left: 10px; /* Cách trái một chút */
    }
    .container_row_item:hover {
        transform: scale(1.05); /* Phóng to lên 5% khi di chuột */
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2); /* Thêm bóng đổ */
    }
    .container_row_item:hover .hoverr {
        display: flex; /* Hiện phần tử hover khi hover */
    }
    .hoverr i {
        display: flex;
        flex-direction: column;
    }
</style>
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
                        <a href="../html/giohang.php"><p>Giỏ hàng</p></a>
                    </div>
                </div>
                <div id="taikhoan">
                    <div id="icon3">
                        <i class="fa-regular fa-user"></i>
                    </div>
                    <div id="account">
                        <?php if (isset($_SESSION['user_name'])): ?>
                            <p><?php echo $_SESSION['user_name']; ?> | <a href="../php/logout.php">Đăng xuất</a></p>
                        <?php else: ?>
                            <a href="../html/login.php"><p>Tài khoản</p></a>
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
                    <span class="menu_header">0889833688/ 0373720545</span>
                </div>
            </div>
        </div>
    </div>
    <!-- -----------------------------------------------------mainn------------------------------------------>
    <div class="main_main">
        <div class="cotainer_menu">
            <ul><h4>DANH MỤC SẢN PHẨM</h4>
                <li><a href="../html/index.php"><h5>TRANG CHỦ</h5></a></li>
                <li><h5 id="this">SÁCH MẦM NON</h5></a></li>
                <li><a href="../html/sach_thieu_nhi.php"><h5>SÁCH THIẾU NHI</h5></a></li>
                <li><a href="../html/sach_ky_nang.php"><h5>SÁCH KĨ NĂNG</h5></a></li>
                <li><a href="../html/sach_kinh_doanh.php"><h5>SÁCH KINH DOANH</h5></a></li>
            </ul>
        </div>
        
        <div class="container">
            <div>
                <div class="container_title">
                    <h4>SÁCH MẦM NON</h4>
                </div>

                <!-- Hiển thị danh sách sách từ cơ sở dữ liệu -->
                <div class="container_row">
                    <?php include('../php/sachmamnon.php'); ?> <!-- Nhúng file PHP để lấy dữ liệu -->
                </div>
            </div>
        </div>
    </div>

    <!------------------------------------------------- footer_promotion---------------------------------- -->
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
            <div>
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
    </div>
    <!-- --------------------footer_main----------------------------------------------------------- -->
    <div class="footer_main">
        <div class="footer_main_cotainer">
            <div class="footer_main_cotainer_col1">
                <div>
                    <img class="footer_logo" src="../img/footer.logo.png" alt="">
                </div>
                <div>
                    <p>Công ty TNHH BA Thành viên Thương mại & Dịch vụ Văn hóa Minh Long</p>
                    <P>Mã số thuể: 001283868686</P>
                </div>
                <div>
                    <p><i class="fa-solid fa-location-dot"></i>  Văn phòng: LK 02 - 03, Dãy B, KĐT Green Pearl, 378 Minh Khai, Hai Bà Trưng, Hà Nội.</p>
                    <p><i class="fa-solid fa-location-dot"></i>  Cửa hàng: Gian hàng Minh Long Book tại Phố Sách Hà Nội, Phố 19 tháng 12, Hoàn Kiếm, Hà Nội.</p>
                    <p><i class="fa-solid fa-phone"></i>  0889833688-0373720545</p>
                    <p><i class="fa-regular fa-envelope"></i>  20223044@eaut.edu.vn</p>
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