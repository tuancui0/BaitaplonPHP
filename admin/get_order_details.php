<?php
// Kết nối database
$servername = "localhost";
$username = "root";
$password = "Tuan2004@";
$dbname = "btl2";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

if (isset($_GET['id'])) {
    $order_id = $_GET['id'];
    $sql = "SELECT ctdh.ma_san_pham, ctdh.ten_san_pham, ctdh.so_luong, ctdh.gia, (ctdh.so_luong * ctdh.gia) as thanh_tien 
            FROM chi_tiet_don_hang ctdh 
            WHERE ctdh.don_hang_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row["ma_san_pham"]) . "</td>";
            echo "<td>" . htmlspecialchars($row["ten_san_pham"]) . "</td>";
            echo "<td>" . $row["so_luong"] . "</td>";
            echo "<td>" . number_format($row["gia"], 2) . " VNĐ</td>";
            echo "<td>" . number_format($row["thanh_tien"], 2) . " VNĐ</td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='5'>Không có chi tiết cho đơn hàng này.</td></tr>";
    }
    $stmt->close();
}

$conn->close();
?>