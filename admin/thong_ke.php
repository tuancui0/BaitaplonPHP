<?php
// Kết nối database
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

// Truy vấn tổng số đơn hàng và tổng doanh thu
$sql_stats = "SELECT COUNT(*) as total_orders, SUM(tong_tien) as total_revenue FROM don_hang";
$result_stats = $conn->query($sql_stats);
$row_stats = $result_stats->fetch_assoc();
$total_orders = $row_stats['total_orders'];
$total_revenue = $row_stats['total_revenue'] ?? 0;

// Truy vấn số lượng sản phẩm bán ra theo từng loại sản phẩm
$sql_products = "SELECT ctdh.ten_san_pham, SUM(ctdh.so_luong) as total_quantity 
                FROM chi_tiet_don_hang ctdh 
                GROUP BY ctdh.ten_san_pham";
$result_products = $conn->query($sql_products);

$sql_monthly = "SELECT MONTH(thoi_gian_dat) as month, SUM(tong_tien) as revenue 
                FROM don_hang 
                WHERE YEAR(thoi_gian_dat) = 2025 
                GROUP BY MONTH(thoi_gian_dat) 
                ORDER BY month";
$result_monthly = $conn->query($sql_monthly);

$monthly_data = [];
while ($row = $result_monthly->fetch_assoc()) {
    $monthly_data[$row['month']] = $row['revenue'] ?? 0;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thống Kê Đơn Hàng</title>
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

        .container {
            max-width: 1200px;
            margin: 30px auto;
            padding: 20px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .stats-section {
            margin-bottom: 30px;
            text-align: center;
        }

        .stats-section h3 {
            color: #FF9800;
            font-size: 20px;
            margin-bottom: 10px;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            padding: 20px;
        }

        .stat-item {
            background-color: #e0e0e0;
            padding: 15px;
            border-radius: 5px;
            transition: all 0.3s ease;
        }

        .stat-item:hover {
            background-color: #d3d3d3;
            transform: translateY(-2px);
        }

        .stat-item p {
            margin: 5px 0;
            color: #666;
            font-size: 16px;
            font-weight: 500;
        }

        .stat-item strong {
            color: #333;
            font-size: 18px;
        }

        .table-section {
            margin-top: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
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

        .chart-section {
            margin-top: 20px;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 5px;
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
    </style>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <!-- HEADER -->
    <div class="header">
        <h2>Thống Kê Đơn Hàng</h2>
    </div>

    <!-- CONTAINER -->
    <div class="container">
        <!-- NÚT QUAY LẠI -->
        <a href="../admin/index.php" class="back-btn">Quay lại Trang Quản Trị</a>

        <!-- THÔNG TIN TỔNG QUAN -->
        <div class="stats-section">
            <h3>Thông Tin Tổng Quan</h3>
            <div class="stats-grid">
                <div class="stat-item">
                    <p>Tổng số đơn hàng</p>
                    <strong><?php echo $total_orders; ?></strong>
                </div>
                <div class="stat-item">
                    <p>Tổng doanh thu</p>
                    <strong><?php echo number_format($total_revenue, 2); ?> VNĐ</strong>
                </div>
            </div>
        </div>

        <!-- BẢNG SỐ LƯỢNG SẢN PHẨM BÁN RA -->
        <div class="table-section">
            <h3>Số Lượng Sản Phẩm Bán Ra</h3>
            <table>
                <tr>
                    <th>Tên Sản Phẩm</th>
                    <th>Số Lượng</th>
                </tr>
                <?php
                if ($result_products->num_rows > 0) {
                    while ($row = $result_products->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row["ten_san_pham"]) . "</td>";
                        echo "<td>" . $row["total_quantity"] . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='2'>Không có dữ liệu sản phẩm.</td></tr>";
                }
                ?>
            </table>
        </div>

        <!-- BIỂU ĐỒ DOANH THU THEO THÁNG -->
        <div class="chart-section">
            <h3>Doanh Thu Theo Tháng (Năm 2025)</h3>
            <canvas id="monthlyRevenueChart"></canvas>
        </div>
    </div>

    <script>
        // Dữ liệu cho biểu đồ
        const monthlyData = {
            labels: ['Tháng 1', 'Tháng 2', 'Tháng 3', 'Tháng 4', 'Tháng 5', 'Tháng 6', 
                    'Tháng 7', 'Tháng 8', 'Tháng 9', 'Tháng 10', 'Tháng 11', 'Tháng 12'],
            datasets: [{
                label: 'Doanh Thu (VNĐ)',
                data: [
                    <?php echo isset($monthly_data[1]) ? $monthly_data[1] : 0; ?>,
                    <?php echo isset($monthly_data[2]) ? $monthly_data[2] : 0; ?>,
                    <?php echo isset($monthly_data[3]) ? $monthly_data[3] : 0; ?>,
                    <?php echo isset($monthly_data[4]) ? $monthly_data[4] : 0; ?>,
                    <?php echo isset($monthly_data[5]) ? $monthly_data[5] : 0; ?>,
                    <?php echo isset($monthly_data[6]) ? $monthly_data[6] : 0; ?>,
                    <?php echo isset($monthly_data[7]) ? $monthly_data[7] : 0; ?>,
                    <?php echo isset($monthly_data[8]) ? $monthly_data[8] : 0; ?>,
                    <?php echo isset($monthly_data[9]) ? $monthly_data[9] : 0; ?>,
                    <?php echo isset($monthly_data[10]) ? $monthly_data[10] : 0; ?>,
                    <?php echo isset($monthly_data[11]) ? $monthly_data[11] : 0; ?>,
                    <?php echo isset($monthly_data[12]) ? $monthly_data[12] : 0; ?>
                ],
                backgroundColor: 'rgba(76, 175, 80, 0.2)',
                borderColor: 'rgba(76, 175, 80, 1)',
                borderWidth: 1,
                fill: true
            }]
        };

        // Tạo biểu đồ
        const ctx = document.getElementById('monthlyRevenueChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: monthlyData,
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return value.toLocaleString('vi-VN') + ' VNĐ';
                            }
                        }
                    }
                },
                plugins: {
                    legend: {
                        labels: {
                            font: {
                                size: 14
                            }
                        }
                    }
                }
            }
        });
    </script>
</body>
</html>

<?php
$conn->close();
?>