<?php
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

// Lấy ID sản phẩm từ tham số URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$sql = "SELECT ten_san_pham, gia, so_luong, mo_ta, ma_san_pham, anh FROM san_pham WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
} else {
    echo "Sản phẩm không tồn tại.";
    exit;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($row['ten_san_pham'], ENT_QUOTES, 'UTF-8'); ?></title>
</head>
<body>
    <div class="product-container">
        <div id="img_sp">
            <?php
            $imageData = base64_encode($row['anh']);
            $src = 'data:image/jpeg;base64,' . $imageData;
            echo '<img id="img" src="' . $src . '" alt="' . htmlspecialchars($row['ten_san_pham'], ENT_QUOTES, 'UTF-8') . '" />';
            ?>
        </div>
        <div class="product-info">
            <form action="../php/giohang.php" method="post" id="productForm">
                <h1><?php echo htmlspecialchars($row['ten_san_pham'], ENT_QUOTES, 'UTF-8'); ?></h1>
                <p>Mã sản phẩm: <?php echo htmlspecialchars($row['ma_san_pham'], ENT_QUOTES, 'UTF-8'); ?></p>
                <p>Sắp phát hành</p>
                <p class="price"><?php echo number_format($row['gia'], 0, ',', '.'); ?> VNĐ</p>
                <input type="hidden" name="ma_san_pham" value="<?php echo htmlspecialchars($row['ma_san_pham'], ENT_QUOTES, 'UTF-8'); ?>">
                <input type="hidden" name="ten_san_pham" value="<?php echo htmlspecialchars($row['ten_san_pham'], ENT_QUOTES, 'UTF-8'); ?>">
                <input type="hidden" name="gia" value="<?php echo $row['gia']; ?>">
                <input type="hidden" name="so_luong" value="1">
    
                <div class="description">
                    <h3>Mô tả:</h3>
                    <p><?php echo htmlspecialchars($row['mo_ta'], ENT_QUOTES, 'UTF-8'); ?></p>
                    <button class="button" type="submit" name="action" value="add_to_cart">Thêm Vào Giỏ Hàng</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>

<style>
body {
    margin: 0;
    padding: 0;
    font-family: Arial, sans-serif;
    background-color: #f0f0f0;
    color: #333;
}
.button {
    background-color: #4CAF50;
    color: white;
    border: none;
    border-radius: 5px;
    padding: 10px 20px;
    font-size: 16px;
    cursor: pointer;
    transition: background-color 0.3s, transform 0.2s;
    margin-right: 10px;
}

.button:hover {
    transform: scale(1.05);
}

.button:focus {
    outline: none;
}
.product-container {
    background-color: #fff;
    border-radius: 5px;
    padding: 20px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    display: flex;
    flex-direction: row;
    align-items: center;
}

#img_sp {
    margin-left: 100px;
    flex: 2;
    margin-right: 10px;
}

#img_sp #img {
    width: 400px;
    height: 400px;
    margin-left: 80px;
}

.product-info {
    margin-top: -100px;
    flex: 3;
}

h1 {
    font-size: 24px;
    color: #333;
}

.price {
    font-size: 20px;
    color: #e74c3c;
    font-weight: bold;
}

.description {
    margin-top: 20px;
}

.description h3 {
    margin-bottom: 10px;
}
</style>