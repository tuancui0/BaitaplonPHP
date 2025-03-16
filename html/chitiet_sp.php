<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="../css/cua_hang.css"> <!-- Liên kết với tệp CSS -->
    <link rel="stylesheet" href="../css/chitet_sp.css"> <!-- Liên kết với tệp CSS -->

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <!-- HEADER -->
    <div class=" header">
        <div id="main_heder">
        <div id="logo"> <a href="../html/index.php"><img id="MINHLONG_logo" src="../img/logo.png" alt="logo"></a>
            </div>
            <div id="search"> <form action="/search" method="get">
                <input  type="search" placeholder="Tìm kiếm..." name="query" required>
                <button  type="submit">Tìm kiếm</button>
                </form>
            </div>
            <div class="icon">
                <div id="donhang">
                    <div id="icon1">
                        <i class="fa-solid fa-phone"></i>
                    </div>
                    <div><a href="../html/trangthai_donhang.php"><p>Tra cứu đơn hàng</p></a></div>
                    </div>
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
                    <div>
                        <a href="../html/login.php"><p>Tài khoản</p></a>
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
    <!-- -----------------------------------------------------mainn------------------------------------------>

    <?php include('../php/chitiet.php');; ?> <!-- Nhúng file PHP để lấy dữ liệu -->  











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
                    <img  class="img_footer_promotion"src="../img/doi tra.png" alt="">
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