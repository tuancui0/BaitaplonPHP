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

$book_type = 'Sách Kinh Doanh'; // Có thể thay đổi loại sách
$sql = "SELECT id, anh, ten_san_pham, gia FROM san_pham WHERE loai_sach = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $book_type);
$stmt->execute();
$result = $stmt->get_result();
?>

<div class="book-container">
    <div class="book-grid">
        <?php
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $imageData = base64_encode($row["anh"]);
                $src = 'data:image/jpeg;base64,' . $imageData;
                
                echo '<div class="book-card">';
                    echo '<a href="chitiet_sp.php?id=' . $row["id"] . '" class="book-link">';
                        echo '<div class="book-image-wrapper">';
                            echo '<img src="' . $src . '" alt="' . htmlspecialchars($row["ten_san_pham"]) . '" class="book-image">';
                        echo '</div>';
                        echo '<div class="book-info">';
                            echo '<h3 class="book-title">' . htmlspecialchars($row["ten_san_pham"]) . '</h3>';
                            echo '<p class="book-price">' . number_format($row["gia"], 0, ',', '.') . ' ₫</p>';
                        echo '</div>';
                    echo '</a>';
                echo '</div>';
            }
        } else {
            echo '<p class="no-results">Không tìm thấy sách nào trong danh mục này.</p>';
        }
        $stmt->close();
        $conn->close();
        ?>
    </div>
</div>

<style>
.book-container {
    width: 100%;
    padding: 20px;
    max-width: 1200px;
    margin: 0 auto;
}

.book-grid {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    gap: 15px;
    margin-top: 20px;
}

.book-card {
    width: 23%;
    background: #fff;
    border-radius: 5px;
    overflow: hidden;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    transition: transform 0.2s ease;
    margin-bottom: 20px;
}

.book-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.15);
}

.book-link {
    text-decoration: none;
    color: #333;
    display: block;
}

.book-image-wrapper {
    width: 100%;
    height: 180px;
    overflow: hidden;
    background: #f8f8f8;
}

.book-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.book-card:hover .book-image {
    transform: scale(1.03);
}

.book-info {
    padding: 10px;
    text-align: center;
}

.book-title {
    font-size: 14px;
    margin: 0 0 8px;
    line-height: 1.3;
    height: 36px;
    overflow: hidden;
    text-overflow: ellipsis;
    display: -webkit-box;
    -webkit-box-orient: vertical;
}

.book-price {
    font-size: 16px;
    color: #d63031;
    font-weight: 600;
    margin: 0;
}

.no-results {
    text-align: center;
    color: #666;
    padding: 20px;
    width: 100%;
}

@media (max-width: 768px) {
    .book-card {
        width: 48%;
    }
}

@media (max-width: 480px) {
    .book-card {
        width: 100%;
    }
}
</style>