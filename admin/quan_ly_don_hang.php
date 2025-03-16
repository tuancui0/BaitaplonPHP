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

// Xử lý xóa đơn hàng
if (isset($_GET['xoa_id'])) {
    $id = intval($_GET['xoa_id']);
    $sql = "DELETE FROM don_hang WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "<script>alert('Xóa đơn hàng thành công!'); window.location.href='quan_ly_don_hang.php';</script>";
    } else {
        echo "<script>alert('Lỗi khi xóa đơn hàng!');</script>";
    }
    $stmt->close();
}

// Xử lý cập nhật trạng thái qua POST
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['cap_nhat_trang_thai'])) {
    $id = intval($_POST['id']);
    $trang_thai = $_POST['trang_thai'];

    $valid_statuses = ['Đang xử lý', 'Đã xác nhận', 'Nhận hàng thành công'];
    if (!in_array($trang_thai, $valid_statuses)) {
        echo "<script>alert('Trạng thái không hợp lệ!');</script>";
    } else {
        $sql = "UPDATE don_hang SET trang_thai = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $trang_thai, $id);

        if ($stmt->execute()) {
            echo "<script>alert('Cập nhật trạng thái thành công!'); window.location.href='quan_ly_don_hang.php';</script>";
        } else {
            echo "<script>alert('Lỗi khi cập nhật trạng thái!');</script>";
        }
        $stmt->close();
    }
}

// Xử lý tìm kiếm
$search_query = isset($_GET['search']) ? trim($_GET['search']) : '';
$sql = "SELECT dh.*, tk.ho, tk.ten FROM don_hang dh LEFT JOIN tai_khoan tk ON dh.nguoi_dung_id = tk.id";
if (!empty($search_query)) {
    $search_query = $conn->real_escape_string($search_query);
    $sql .= " WHERE dh.id LIKE '%$search_query%'
              OR tk.ho LIKE '%$search_query%'
              OR tk.ten LIKE '%$search_query%'
              OR dh.phuong_thuc_thanh_toan LIKE '%$search_query%'
              OR dh.trang_thai LIKE '%$search_query%'
              OR dh.dia_chi LIKE '%$search_query%'
              OR dh.ten_nguoi_nhan LIKE '%$search_query%'
              OR dh.so_dien_thoai LIKE '%$search_query%'
              OR dh.thoi_gian_dat LIKE '%$search_query%'";
}
$result = $conn->query($sql);

// Truy vấn tổng số đơn hàng và tổng doanh thu
$sql_stats = "SELECT COUNT(*) as total_orders, SUM(tong_tien) as total_revenue FROM don_hang";
$result_stats = $conn->query($sql_stats);
$row_stats = $result_stats->fetch_assoc();
$total_orders = $row_stats['total_orders'];
$total_revenue = $row_stats['total_revenue'] ?? 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý Đơn Hàng</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
            color: #333;
            line-height: 1.6;
        }

        .header {
            background-color: #4CAF50;
            color: white;
            padding: 20px;
            text-align: center;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .header h2 {
            margin: 0;
            font-size: 28px;
            font-weight: 600;
            letter-spacing: 1px;
        }

        .order-info {
            background-color: #ffffff;
            padding: 15px;
            margin: 10px auto;
            max-width: 1200px;
            border-radius: 5px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .order-info h3 {
            color: #FF9800;
            font-size: 20px;
            margin-bottom: 10px;
        }

        .order-info .order-list {
            display: flex;
            justify-content: center;
            gap: 20px;
            flex-wrap: wrap;
        }

        .order-item {
            background-color: #e0e0e0;
            padding: 10px;
            border-radius: 5px;
            min-width: 150px;
            transition: all 0.3s ease;
        }

        .order-item:hover {
            background-color: #d3d3d3;
            transform: translateY(-2px);
        }

        .order-item p {
            margin: 5px 0;
            color: #666;
            font-size: 16px;
            font-weight: 500;
        }

        .container {
            max-width: 1200px;
            margin: 30px auto;
            padding: 20px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .search-form {
            margin-bottom: 20px;
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .search-form input[type="text"] {
            padding: 8px;
            width: 300px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .search-form button {
            background-color: #4CAF50;
            color: white;
            padding: 8px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .search-form button:hover {
            background-color: #388E3C;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #4CAF50;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #e8f5e9;
        }

        .status-completed {
            color: #2e7d32;
            font-weight: bold;
        }

        .action-btn {
            padding: 5px 10px;
            margin-right: 5px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .action-btn.details {
            background-color: #9C27B0;
            color: white;
        }

        .action-btn.details:hover {
            background-color: #7B1FA2;
        }

        .action-btn.delete {
            background-color: #f44336;
            color: white;
        }

        .action-btn.delete:hover {
            background-color: #d32f2f;
        }

        .back-btn {
            display: inline-block;
            margin: 20px 0;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            transition: background-color 0.3s ease;
        }

        .back-btn:hover {
            background-color: #388E3C;
        }

        .status-select {
            padding: 5px;
            border-radius: 4px;
            border: 1px solid #ddd;
        }

        .detail-modal {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 90%;
            max-width: 800px;
            height: auto;
            max-height: 80vh;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            overflow-y: auto;
        }

        .detail-modal-content {
            background-color: white;
            margin: 0;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            position: relative;
        }

        .detail-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .detail-table th,
        .detail-table td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .detail-table th {
            background-color: #9C27B0;
            color: white;
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="header">
        <h2>Quản Lý Đơn Hàng</h2>
    </div>

    <div class="order-info">
        <h3>Thống Kê Đơn Hàng</h3>
        <div class="order-list">
            <div class="order-item">
                <p><strong>Tổng số đơn hàng</strong></p>
                <p><?php echo $total_orders; ?></p>
            </div>
            <div class="order-item">
                <p><strong>Tổng doanh thu</strong></p>
                <p><?php echo number_format($total_revenue, 2); ?> VNĐ</p>
            </div>
        </div>
    </div>

    <div class="container">
        <a href="../admin/index.php" class="back-btn">Quay lại Trang Quản Trị</a>

        <div class="search-form">
            <form method="GET" action="">
                <input type="text" name="search" placeholder="Tìm kiếm theo ID, họ, tên, phương thức, trạng thái, địa chỉ, tên người nhận, số điện thoại, thời gian đặt..." value="<?php echo htmlspecialchars($search_query); ?>">
                <button type="submit">Tìm kiếm</button>
            </form>
        </div>

        <h3>Danh Sách Đơn Hàng</h3>
        <table>
            <tr>
                <th>ID</th>
                <th>Họ</th>
                <th>Tên</th>
                <th>ID Người Dùng</th>
                <th>Tổng tiền</th>
                <th>Phương thức thanh toán</th>
                <th>Trạng thái</th>
                <th>Địa chỉ</th>
                <th>Tên người nhận</th>
                <th>Số điện thoại</th>
                <th>Thời gian đặt</th>
                <th>Hành Động</th>
            </tr>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row["id"] . "</td>";
                    echo "<td>" . htmlspecialchars($row["ho"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["ten"]) . "</td>";
                    echo "<td>" . $row["nguoi_dung_id"] . "</td>";
                    echo "<td>" . number_format($row["tong_tien"], 2) . " VNĐ</td>";
                    echo "<td>" . htmlspecialchars($row["phuong_thuc_thanh_toan"]) . "</td>";
                    echo "<td>";
                    echo "<form method='POST' action=''>";
                    echo "<input type='hidden' name='id' value='" . $row["id"] . "'>";
                    echo "<select name='trang_thai' class='status-select' onchange='this.form.submit()'>";
                    $statuses = ['Đang xử lý', 'Đã xác nhận', 'Nhận hàng thành công'];
                    foreach ($statuses as $status) {
                        $selected = ($row["trang_thai"] === $status) ? "selected" : "";
                        echo "<option value='$status' $selected>" . htmlspecialchars($status) . "</option>";
                    }
                    echo "</select>";
                    echo "<input type='hidden' name='cap_nhat_trang_thai' value='1'>";
                    echo "</form>";
                    echo "</td>";
                    echo "<td>" . htmlspecialchars($row["dia_chi"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["ten_nguoi_nhan"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["so_dien_thoai"]) . "</td>";
                    echo "<td>" . $row["thoi_gian_dat"] . "</td>";
                    echo "<td>";
                    echo "<button class='action-btn details' onclick=\"openDetailModal(" . $row['id'] . ")\">Xem chi tiết</button>";
                    echo "<button class='action-btn delete' onclick=\"if(confirm('Bạn có chắc muốn xóa đơn hàng này?')) window.location.href='quan_ly_don_hang.php?xoa_id=" . urlencode($row["id"]) . "'\">Xóa</button>";
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='12'>Không có đơn hàng nào.</td></tr>";
            }
            ?>
        </table>
    </div>

    <div id="detailModal" class="detail-modal">
        <div class="detail-modal-content">
            <span class="close" onclick="closeDetailModal()">×</span>
            <h3>Chi Tiết Đơn Hàng</h3>
            <table class="detail-table">
                <tr>
                    <th>Mã sản phẩm</th>
                    <th>Tên sản phẩm</th>
                    <th>Số lượng</th>
                    <th>Giá</th>
                    <th>Thành tiền</th>
                </tr>
                <tbody id="detailTableBody"></tbody>
            </table>
        </div>
    </div>

    <script>
        function openDetailModal(orderId) {
            const xhr = new XMLHttpRequest();
            xhr.open('GET', 'get_order_details.php?id=' + orderId, true);
            xhr.onload = function() {
                if (xhr.status === 200) {
                    document.getElementById('detailTableBody').innerHTML = xhr.responseText;
                    document.getElementById('detailModal').style.display = 'block';
                } else {
                    alert('Lỗi khi tải chi tiết đơn hàng!');
                }
            };
            xhr.send();
        }

        function closeDetailModal() {
            document.getElementById('detailModal').style.display = 'none';
        }

        window.onclick = function(event) {
            const detailModal = document.getElementById('detailModal');
            if (event.target == detailModal) {
                detailModal.style.display = 'none';
            }
        }
    </script>
</body>
</html>

<?php
$conn->close();
?>