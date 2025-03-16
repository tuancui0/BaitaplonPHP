<?php
// Kết nối database
$servername = "localhost";
$username = "root"; // Thay đổi thành tên người dùng của bạn
$password = "Tuan2004@";
$dbname = "btl2"; // Thay đổi thành tên cơ sở dữ liệu của bạn

// Tạo kết nối
$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Hàm validate input
function validateInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Danh sách loại sách cố định
$loai_sach_options = ["Sách Kinh Doanh", "Sách Mầm Non", "Sách Kỹ Năng", "Sách Thiếu Nhi"];

// Xử lý thêm sản phẩm
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['them_san_pham'])) {
    $loai_sach = validateInput($_POST['loai_sach']);
    $ma_san_pham = validateInput($_POST['ma_san_pham']);
    $ten_san_pham = validateInput($_POST['ten_san_pham']);
    $mo_ta = validateInput($_POST['mo_ta']);
    $gia = floatval($_POST['gia']);
    $so_luong = intval($_POST['so_luong']);

    // Validate input
    if (!in_array($loai_sach, $loai_sach_options)) {
        echo "<script>alert('Loại sách không hợp lệ!'); window.location.href='quan_ly_san_pham.php';</script>";
    } elseif (empty($ma_san_pham) || strlen($ma_san_pham) > 50) {
        echo "<script>alert('Mã sản phẩm không hợp lệ (tối đa 50 ký tự)!'); window.location.href='quan_ly_san_pham.php';</script>";
    } elseif (empty($ten_san_pham) || strlen($ten_san_pham) > 100) {
        echo "<script>alert('Tên sản phẩm không hợp lệ (tối đa 100 ký tự)!'); window.location.href='quan_ly_san_pham.php';</script>";
    } elseif ($gia <= 0) {
        echo "<script>alert('Giá phải lớn hơn 0!'); window.location.href='quan_ly_san_pham.php';</script>";
    } elseif ($so_luong < 0) {
        echo "<script>alert('Số lượng không được âm!'); window.location.href='quan_ly_san_pham.php';</script>";
    } else {
        // Kiểm tra mã sản phẩm đã tồn tại chưa
        $check_sql = "SELECT ma_san_pham FROM san_pham WHERE ma_san_pham = ?";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param("s", $ma_san_pham);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();

        if ($check_result->num_rows > 0) {
            echo "<script>alert('Mã sản phẩm đã tồn tại!'); window.location.href='quan_ly_san_pham.php';</script>";
        } else {
            // Xử lý upload ảnh và chuyển thành dữ liệu nhị phân
            $anh = null;
            if (isset($_FILES['anh']) && $_FILES['anh']['error'] == 0) {
                $image_data = file_get_contents($_FILES['anh']['tmp_name']);
                $anh = $image_data ? $image_data : null;
            }

            $sql = "INSERT INTO san_pham (loai_sach, ma_san_pham, ten_san_pham, mo_ta, gia, so_luong, anh) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssddib", $loai_sach, $ma_san_pham, $ten_san_pham, $mo_ta, $gia, $so_luong, $anh);

            if ($stmt->execute()) {
                echo "<script>alert('Thêm sản phẩm thành công!'); window.location.href='quan_ly_san_pham.php';</script>";
            } else {
                echo "<script>alert('Lỗi khi thêm sản phẩm!');</script>";
            }
            $stmt->close();
        }
        $check_stmt->close();
    }
}

// Xử lý xóa sản phẩm
if (isset($_GET['xoa_ma'])) {
    $ma_san_pham = $_GET['xoa_ma'];
    $sql = "DELETE FROM san_pham WHERE ma_san_pham = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $ma_san_pham);

    if ($stmt->execute()) {
        echo "<script>alert('Xóa sản phẩm thành công!'); window.location.href='quan_ly_san_pham.php';</script>";
    } else {
        echo "<script>alert('Lỗi khi xóa sản phẩm!');</script>";
    }
    $stmt->close();
}

// Xử lý chỉnh sửa sản phẩm
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['cap_nhat_san_pham'])) {
    $id = intval($_POST['id']);
    $loai_sach = validateInput($_POST['loai_sach']);
    $ma_san_pham = validateInput($_POST['ma_san_pham']);
    $ten_san_pham = validateInput($_POST['ten_san_pham']);
    $mo_ta = validateInput($_POST['mo_ta']);
    $gia = floatval($_POST['gia']);
    $so_luong = intval($_POST['so_luong']);

    // Validate input
    if (!in_array($loai_sach, $loai_sach_options)) {
        echo "<script>alert('Loại sách không hợp lệ!'); window.location.href='quan_ly_san_pham.php';</script>";
    } elseif (empty($ma_san_pham) || strlen($ma_san_pham) > 50) {
        echo "<script>alert('Mã sản phẩm không hợp lệ (tối đa 50 ký tự)!'); window.location.href='quan_ly_san_pham.php';</script>";
    } elseif (empty($ten_san_pham) || strlen($ten_san_pham) > 100) {
        echo "<script>alert('Tên sản phẩm không hợp lệ (tối đa 100 ký tự)!'); window.location.href='quan_ly_san_pham.php';</script>";
    } elseif ($gia <= 0) {
        echo "<script>alert('Giá phải lớn hơn 0!'); window.location.href='quan_ly_san_pham.php';</script>";
    } elseif ($so_luong < 0) {
        echo "<script>alert('Số lượng không được âm!'); window.location.href='quan_ly_san_pham.php';</script>";
    } else {
        // Kiểm tra mã sản phẩm có trùng không (trừ sản phẩm đang sửa)
        $check_sql = "SELECT ma_san_pham FROM san_pham WHERE ma_san_pham = ? AND id != ?";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param("si", $ma_san_pham, $id);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();

        if ($check_result->num_rows > 0) {
            echo "<script>alert('Mã sản phẩm đã tồn tại!'); window.location.href='quan_ly_san_pham.php';</script>";
        } else {
            // Xử lý upload ảnh mới (nếu có)
            $anh = null;
            if (isset($_FILES['anh']) && $_FILES['anh']['error'] == 0) {
                $image_data = file_get_contents($_FILES['anh']['tmp_name']);
                $anh = $image_data ? $image_data : null;
            }

            if ($anh) {
                $sql = "UPDATE san_pham SET loai_sach = ?, ma_san_pham = ?, ten_san_pham = ?, mo_ta = ?, gia = ?, so_luong = ?, anh = ? WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("sssddbi", $loai_sach, $ma_san_pham, $ten_san_pham, $mo_ta, $gia, $so_luong, $anh, $id);
            } else {
                $sql = "UPDATE san_pham SET loai_sach = ?, ma_san_pham = ?, ten_san_pham = ?, mo_ta = ?, gia = ?, so_luong = ? WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("sssddi", $loai_sach, $ma_san_pham, $ten_san_pham, $mo_ta, $gia, $so_luong, $id);
            }

            if ($stmt->execute()) {
                echo "<script>alert('Cập nhật sản phẩm thành công!'); window.location.href='quan_ly_san_pham.php';</script>";
            } else {
                echo "<script>alert('Lỗi khi cập nhật sản phẩm!');</script>";
            }
            $stmt->close();
        }
        $check_stmt->close();
    }
}

// Xử lý tìm kiếm
$search_query = isset($_GET['search']) ? $_GET['search'] : '';
$sql = "SELECT * FROM san_pham";
if (!empty($search_query)) {
    $search_query = $conn->real_escape_string($search_query);
    $sql .= " WHERE loai_sach LIKE '%$search_query%' OR ma_san_pham LIKE '%$search_query%' OR ten_san_pham LIKE '%$search_query%'";
}
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý Sản Phẩm</title>
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

        .form-add {
            margin-bottom: 20px;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 8px;
        }

        .form-add label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
            color: #333;
        }

        .form-add input,
        .form-add textarea,
        .form-add select {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .form-add button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .form-add button:hover {
            background-color: #388E3C;
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

        .action-btn {
            padding: 5px 10px;
            margin-right: 5px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .action-btn.delete {
            background-color: #f44336;
            color: white;
        }

        .action-btn.delete:hover {
            background-color: #d32f2f;
        }

        .action-btn.edit {
            background-color: #2196F3;
            color: white;
        }

        .action-btn.edit:hover {
            background-color: #1976D2;
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

        .thumbnail {
            max-width: 100px;
            max-height: 100px;
            object-fit: cover;
        }

        /* Modal cho chỉnh sửa */
        .modal {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 90%;
            max-width: 600px;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1000;
        }

        .modal-content {
            background-color: white;
            margin: 0;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .modal-content label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
            color: #333;
        }

        .modal-content input,
        .modal-content textarea,
        .modal-content select {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .modal-content button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .modal-content button:hover {
            background-color: #388E3C;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
        }

        .current-image {
            max-width: 200px;
            max-height: 200px;
            object-fit: contain;
            margin-bottom: 10px;
            display: block;
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <!-- HEADER -->
    <div class="header">
        <h2>Quản Lý Sản Phẩm</h2>
    </div>

    <!-- CONTAINER -->
    <div class="container">
        <!-- NÚT QUAY LẠI -->
        <a href="../admin/index.php" class="back-btn">Quay lại Trang Quản Trị</a>

        <!-- FORM THÊM SẢN PHẨM -->
        <div class="form-add">
            <h3>Thêm Sản Phẩm Mới</h3>
            <form method="POST" action="" enctype="multipart/form-data">
                <label for="loai_sach">Loại sách:</label>
                <select id="loai_sach" name="loai_sach" required>
                    <?php foreach ($loai_sach_options as $option): ?>
                        <option value="<?php echo htmlspecialchars($option); ?>"><?php echo htmlspecialchars($option); ?></option>
                    <?php endforeach; ?>
                </select>

                <label for="ma_san_pham">Mã sản phẩm:</label>
                <input type="text" id="ma_san_pham" name="ma_san_pham" required>

                <label for="ten_san_pham">Tên sản phẩm:</label>
                <input type="text" id="ten_san_pham" name="ten_san_pham" required>

                <label for="mo_ta">Mô tả:</label>
                <textarea id="mo_ta" name="mo_ta" rows="4"></textarea>

                <label for="gia">Giá:</label>
                <input type="number" id="gia" name="gia" step="0.01" required>

                <label for="so_luong">Số lượng:</label>
                <input type="number" id="so_luong" name="so_luong" required>

                <label for="anh">Ảnh sản phẩm:</label>
                <input type="file" id="anh" name="anh" accept="image/*">

                <button type="submit" name="them_san_pham">Thêm Sản Phẩm</button>
            </form>
        </div>

        <!-- FORM TÌM KIẾM -->
        <div class="search-form">
            <form method="GET" action="">
                <input type="text" name="search" placeholder="Tìm kiếm theo loại sách, mã, hoặc tên sản phẩm..." value="<?php echo htmlspecialchars($search_query); ?>">
                <button type="submit">Tìm kiếm</button>
            </form>
        </div>

        <!-- DANH SÁCH SẢN PHẨM -->
        <h3>Danh Sách Sản Phẩm</h3>
        <table>
            <tr>
                <th>ID</th>
                <th>Loại sách</th>
                <th>Mã sản phẩm</th>
                <th>Tên sản phẩm</th>
                <th>Mô tả</th>
                <th>Giá</th>
                <th>Số lượng</th>
                <th>Ảnh</th>
                <th>Hành Động</th>
            </tr>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row["id"] . "</td>";
                    echo "<td>" . htmlspecialchars($row["loai_sach"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["ma_san_pham"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["ten_san_pham"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["mo_ta"]) . "</td>";
                    echo "<td>" . number_format($row["gia"], 2) . " VNĐ" . "</td>";
                    echo "<td>" . $row["so_luong"] . "</td>";
                    echo "<td>";
                    if ($row["anh"]) {
                        echo "<img src='data:image/jpeg;base64," . base64_encode($row["anh"]) . "' class='thumbnail' alt='Ảnh sản phẩm'>";
                    } else {
                        echo "Không có ảnh";
                    }
                    echo "</td>";
                    echo "<td>";
                    echo "<button class='action-btn edit' onclick=\"openEditModal(" . $row['id'] . ", '" . htmlspecialchars($row['loai_sach']) . "', '" . htmlspecialchars($row['ma_san_pham']) . "', '" . htmlspecialchars($row['ten_san_pham']) . "', '" . htmlspecialchars($row['mo_ta']) . "', " . $row['gia'] . ", " . $row['so_luong'] . ", '" . ($row['anh'] ? base64_encode($row['anh']) : '') . "')\">Sửa</button>";
                    echo "<button class='action-btn delete' onclick=\"if(confirm('Bạn có chắc muốn xóa sản phẩm này?')) window.location.href='quan_ly_san_pham.php?xoa_ma=" . urlencode($row["ma_san_pham"]) . "'\">Xóa</button>";
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='9'>Không có sản phẩm nào.</td></tr>";
            }
            ?>
        </table>
    </div>

    <!-- MODAL CHỈNH SỬA -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeEditModal()">×</span>
            <h3>Chỉnh Sửa Sản Phẩm</h3>
            <form method="POST" action="" enctype="multipart/form-data">
                <input type="hidden" id="edit_id" name="id">
                <label for="edit_loai_sach">Loại sách:</label>
                <select id="edit_loai_sach" name="loai_sach" required>
                    <?php foreach ($loai_sach_options as $option): ?>
                        <option value="<?php echo htmlspecialchars($option); ?>"><?php echo htmlspecialchars($option); ?></option>
                    <?php endforeach; ?>
                </select>

                <label for="edit_ma_san_pham">Mã sản phẩm:</label>
                <input type="text" id="edit_ma_san_pham" name="ma_san_pham" required>

                <label for="edit_ten_san_pham">Tên sản phẩm:</label>
                <input type="text" id="edit_ten_san_pham" name="ten_san_pham" required>

                <label for="edit_mo_ta">Mô tả:</label>
                <textarea id="edit_mo_ta" name="mo_ta" rows="4"></textarea>

                <label for="edit_gia">Giá:</label>
                <input type="number" id="edit_gia" name="gia" step="0.01" required>

                <label for="edit_so_luong">Số lượng:</label>
                <input type="number" id="edit_so_luong" name="so_luong" required>

                <label>Ảnh hiện tại:</label>
                <img id="current_image" class="current-image" src="" alt="Ảnh hiện tại">

                <label for="edit_anh">Cập nhật ảnh (không bắt buộc):</label>
                <input type="file" id="edit_anh" name="anh" accept="image/*">

                <button type="submit" name="cap_nhat_san_pham">Cập Nhật</button>
            </form>
        </div>
    </div>

    <script>
        // Mở modal chỉnh sửa
        function openEditModal(id, loai_sach, ma_san_pham, ten_san_pham, mo_ta, gia, so_luong, anh_base64) {
            document.getElementById('edit_id').value = id;
            document.getElementById('edit_loai_sach').value = loai_sach;
            document.getElementById('edit_ma_san_pham').value = ma_san_pham;
            document.getElementById('edit_ten_san_pham').value = ten_san_pham;
            document.getElementById('edit_mo_ta').value = mo_ta;
            document.getElementById('edit_gia').value = gia;
            document.getElementById('edit_so_luong').value = so_luong;

            const currentImage = document.getElementById('current_image');
            if (anh_base64) {
                currentImage.src = 'data:image/jpeg;base64,' + anh_base64;
                currentImage.style.display = 'block';
            } else {
                currentImage.src = '';
                currentImage.style.display = 'none';
            }

            document.getElementById('editModal').style.display = 'block';
        }

        // Đóng modal
        function closeEditModal() {
            document.getElementById('editModal').style.display = 'none';
        }

        // Đóng modal khi nhấp ra ngoài
        window.onclick = function(event) {
            const modal = document.getElementById('editModal');
            if (event.target == modal) {
                modal.style.display = 'none';
            }
        }
    </script>
</body>
</html>

<?php
$conn->close();
?>