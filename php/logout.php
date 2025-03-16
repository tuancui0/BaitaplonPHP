<?php
session_start(); // Bắt đầu session
session_destroy(); // Xóa tất cả session
echo "<script>alert('Bạn đã đăng xuất'); window.location.href='../html/login.php';</script>"; // Chuyển hướng về trang đăng nhập
?>