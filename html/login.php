<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="../css/login.css"> <!-- Liên kết với tệp CSS -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập</title>
    <!-- Thêm Google Sign-In API -->
    <script src="https://accounts.google.com/gsi/client" async defer></script>
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
                    <div id="icon1"><i class="fa-solid fa-phone"></i></div>
                    <div><p>Tra cứu đơn hàng</p></div>
                </div>
                <div id="giohang"> 
                    <div id="icon2"><i class="fa-solid fa-cart-shopping"></i></div>
                    <div><p>Giỏ hàng</p></div>
                </div>
                <div id="taikhoan">
                    <div id="icon3"><i class="fa-regular fa-user"></i></div>
                    <div><p>Tài khoản</p></div>
                </div>
            </div>
        </div>
    </div>

    <div id="main">
        <div class="menu_hedear">
            <div id="title">
                <div id="icon4"><i class="fa fa-bars"></i></div>
                <div><span class="menu_header">DANH MỤC SÁCH</span></div>
            </div>   
            <div id="menu">
                <div><span>Sản phẩm đã xem</span></div>
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

    <!-- LOGIN BOX -->
    <div class="login_box">
        <div class="box_login">
            <div id="title_boxlogin">
                <h3>ĐĂNG NHẬP BẰNG</h3>
            </div>
            <div>
                <button id="fa_btn" type="button">Facebook</button>
            </div>
            <div>
                <!-- Button Google Sign-In -->
                <div id="g_id_onload"
                     data-client_id="759951305942-bv5efqf3oif2td4his2iq400slua8t0g.apps.googleusercontent.com"
                     data-callback="handleCredentialResponse">
                </div>
                <div class="g_id_signin" data-type="standard" data-size="large" data-theme="outline" data-text="sign_in_with" data-shape="rectangular" data-logo_alignment="left"></div>
            </div>
        </div>
        <div class="container">
            <div class="login">
                <h3>ĐĂNG NHẬP</h3>
                <form action="../php/login.php" method="post">
                    <div class="taikhoan">
                        <div class="input_icon">
                            <span><i class="fa-regular fa-envelope"></i></span>
                            <input type="email" placeholder="Nhập email của bạn" name="email_login" required>
                        </div>                     
                    </div>
                    <div class="password">
                        <div class="input_icon">
                            <span><i class="fa-solid fa-lock"></i></span>
                            <input type="password" placeholder="Nhập mật khẩu của bạn" name="password_login" required>
                        </div>
                    </div>
                    <button id="login_btn" type="submit" name="login_btn">Đăng nhập</button>
                    <u><h4>Quên mật khẩu</h4></u>
                </form>
            </div>

            <div class="box_signup">
                <h3>ĐĂNG KÍ THÀNH VIÊN MỚI</h3>
                <div class="signup_box">
                    <form class="sign_form" action="../php/login.php" method="post">
                        <div class="input_icon">
                            <span><i class="fa-solid fa-user"></i></span>
                            <input type="text" placeholder="Họ" name="ho" required>
                        </div>
                        <div class="input_icon">
                            <span><i class="fa-solid fa-user"></i></span>
                            <input type="text" placeholder="Tên" name="ten" required>
                        </div>
                        <div class="input_icon">
                            <span><i class="fa-regular fa-envelope"></i></span>
                            <input type="email" placeholder="Email" name="email_signup" required>
                        </div>
                        <div class="input_icon">
                            <span><i class="fa-solid fa-lock"></i></span>
                            <input type="password" placeholder="Mật khẩu" name="password_signup" required>
                        </div>
                        <div class="input_icon">
                            <span><i class="fa-solid fa-lock"></i></span>
                            <input type="password" placeholder="Nhập lại mật khẩu" name="password_signup_again" required>
                        </div>
                        <button id="signup_btn" type="submit" name="signup_btn">Đăng kí</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- FOOTER PROMOTION -->
    <div class="footer_promotion">
        <div class="footer_promotion_main">
            <div class="item_footer_promotion">
                <div><img class="img_footer_promotion" src="../img/vanchuyen.png" alt=""></div>
                <div><h4>MIỄN PHÍ VẬN CHUYỂN</h4><p>cho đơn hàng trên 300.000 VNĐ</p></div>
            </div>
            <div class="item_footer_promotion">
                <div><img class="img_footer_promotion" src="../img/shipcode_footer.png" alt=""></div>
                <div><h4>SHIP COD TOÀN QUỐC</h4><p>thanh toán khi nhận sách</p></div>
            </div>
            <div class="item_footer_promotion">
                <div><img class="img_footer_promotion" src="../img/doi tra.png" alt=""></div>
                <div><h4>MIỄN PHÍ ĐỔI TRẢ</h4><p>trong vòng 10 ngày</p></div>
            </div>
            <div class="item_footer_promotion">
                <div><img class="img_footer_promotion" src="../img/sdt_fotter.png" alt=""></div>
                <div><h4>HOTLINE</h4><p>0889833688-0373720545</p></div>
            </div>
        </div>
    </div>

    <!-- FOOTER MAIN -->
    <div class="footer_main">
        <div class="footer_main_cotainer">
            <div class="footer_main_cotainer_col1">
                <div><img class="footer_logo" src="../img/footer.logo.png" alt=""></div>
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
                <div class="footer_main_cotainer_title"><h4>TIN TỨC</h4></div>
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
                <div class="footer_main_cotainer_title"><h4>HỖ TRỢ KHÁCH HÀNG</h4></div>
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
                <div class="footer_main_cotainer_title"><h4>THÔNG TIN</h4></div>
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

    <!-- JavaScript xử lý Google Sign-In -->
    <script>
        function handleCredentialResponse(response) {
            const idToken = response.credential;
            const xhr = new XMLHttpRequest();
            xhr.open('POST', '../php/login.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onload = function() {
                if (xhr.status === 200) {
                    const result = JSON.parse(xhr.responseText);
                    if (result.success) {
                        alert('Đăng nhập bằng Google thành công: ' + result.name);
                        window.location.href = '../html/index.php';
                    } else {
                        alert('Đăng nhập bằng Google thất bại: ' + result.message);
                    }
                } else {
                    alert('Lỗi kết nối đến server');
                }
            };
            xhr.send('idtoken=' + idToken);
        }
    </script>
</body>
</html>