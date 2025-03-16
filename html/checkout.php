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
    echo "<script>alert('Vui lòng đăng nhập để thanh toán'); window.location.href='../html/login.php';</script>";
    exit();
}

$user_id = $_SESSION['user_id'];

// Lấy dữ liệu giỏ hàng, bao gồm ảnh từ bảng san_pham
$sql = "SELECT gh.ma_san_pham, gh.ten_san_pham, gh.gia, gh.so_luong, sp.anh 
        FROM gio_hang gh 
        LEFT JOIN san_pham sp ON gh.ma_san_pham = sp.ma_san_pham 
        WHERE gh.nguoi_dung_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$cart_items = [];
$subtotal = 0;
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $cart_items[] = $row;
        $subtotal += $row['gia'] * $row['so_luong'];
    }
}

$shipping_fee = ($subtotal >= 300000) ? 0 : 25000;
$total = $subtotal + $shipping_fee;

$stmt->close();

// Lấy địa chỉ mặc định nếu có
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thanh toán - Minh Long Book</title>
    <link rel="stylesheet" href="../css/thanhtoan.css">
</head>
<body>
    
    <div class="container">
        <h1 class="title">MINH LONG BOOK</h1>
        <nav>
            <a href="../html/giohang.php">Giỏ hàng</a> >
            <a href="#">Thông tin giao hàng</a> >
            <span>Phương thức thanh toán</span>
        </nav>

        <div class="checkout">
            <div class="left">
                <h2>Thông tin giao hàng</h2>
                <form action="../php/process_order.php" method="post" onsubmit="return validateForm()">
                    <div class="shipping-info">
                        <label for="ten">Họ và tên:</label>
                        <input type="text" id="ten" name="ten" value="<?php echo isset($default_name) ? $default_name : ''; ?>" required pattern="[A-Za-zÀ-ỹ\s]+" title="Tên chỉ được chứa chữ cái và khoảng trắng">
                        
                        <label for="so_dien_thoai">Số điện thoại:</label>
                        <input type="text" id="so_dien_thoai" name="so_dien_thoai" value="<?php echo isset($default_phone) ? $default_phone : ''; ?>" required pattern="[0-9]{10}" title="Số điện thoại phải là 10 chữ số">
                        
                        <label for="dia_chi">Địa chỉ giao hàng:</label>
                        <textarea id="dia_chi" name="dia_chi" required minlength="10" title="Địa chỉ phải ít nhất 10 ký tự"><?php ; ?></textarea>
                    </div>

                    <h2>Phương thức vận chuyển</h2>
                    <div class="shipping-method">
                        <input type="radio" id="ship-home" name="shipping" value="ship-home" checked>
                        <label for="ship-home">🚚 Giao hàng tận nơi - 
                            <b><?php echo ($shipping_fee == 0) ? "Miễn phí" : number_format($shipping_fee, 0, ',', '.') . "₫"; ?></b>
                        </label>
                    </div>

                    <h2>Phương thức thanh toán</h2>
                    <div class="payment-method">
                        <input type="radio" id="cod" name="payment" value="COD" checked>
                        <label for="cod">💰 Thanh toán khi giao hàng (COD)</label>
                        <p class="desc">Bạn sẽ thanh toán tiền mặt khi nhận hàng từ nhân viên giao hàng.</p>
                    </div>

                    <div class="payment-method">
                        <input type="radio" id="bank" name="payment" value="bank">
                        <label for="bank">🏦 Chuyển khoản qua ngân hàng</label>
                        <div class="bank-info">
                            <p><b>Công ty TNHH Minh Long</b></p>
                            <p>Số TK: <b>minhton04</b></p>
                            <p>Ngân hàng TechcomBank</p>
                        </div>
                    </div>  
                    <input type="hidden" name="subtotal" value="<?php echo $subtotal; ?>">
                    <input type="hidden" name="shipping_fee" value="<?php echo $shipping_fee; ?>">
                    <input type="hidden" name="total" value="<?php echo $total; ?>">
                    <button type="submit" class="btn">Hoàn tất đơn hàng</button>
                </form>
            </div>

            <div class="right">
                <h2>🛒 Giỏ hàng</h2>
                <?php if (!empty($cart_items)): ?>
                    <?php foreach ($cart_items as $item): ?>
                        <div class="cart-item">
                            <div class="cart-item-details">
                                <p><strong>Sách:</strong> <?php echo htmlspecialchars($item['ten_san_pham']); ?></p>
                                <p><strong>Số lượng:</strong> <?php echo $item['so_luong']; ?></p>
                                <p><strong>Giá:</strong> <b><?php echo number_format($item['gia'] * $item['so_luong'], 0, ',', '.'); ?>₫</b></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    <div class="total">
                        <p>Tạm tính: <b><?php echo number_format($subtotal, 0, ',', '.'); ?>₫</b></p>
                        <p>Phí vận chuyển: <b><?php echo ($shipping_fee == 0) ? "Miễn phí" : number_format($shipping_fee, 0, ',', '.') . "₫"; ?></b></p>
                        <p class="grand-total">Tổng cộng: <b><?php echo number_format($total, 0, ',', '.'); ?>₫</b></p>
                    </div>
                <?php else: ?>
                    <p>Giỏ hàng của bạn đang trống.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script>
    function validateForm() {
        let phone = document.getElementById("so_dien_thoai").value;
        let name = document.getElementById("ten").value;
        let address = document.getElementById("dia_chi").value;

        if (!phone.match(/^[0-9]{10}$/)) {
            alert("Số điện thoại phải là 10 chữ số!");
            return false;
        }
        if (!name.match(/^[A-Za-zÀ-ỹ\s]+$/)) {
            alert("Tên chỉ được chứa chữ cái và khoảng trắng!");
            return false;
        }
        if (address.length < 10) {
            alert("Địa chỉ phải ít nhất 10 ký tự!");
            return false;
        }
        return true;
    }
    </script>
</body>
</html>

<?php $conn->close(); ?>