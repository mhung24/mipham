<?php
// Tên file: create_hash.php
// THAY THẾ 'matkhauadmin123' BẰNG MẬT KHẨU BẠN MUỐN ĐẶT CHO ADMIN
$admin_password_clear = 'admin';

// Tạo chuỗi hash
$hashed_password = password_hash($admin_password_clear, PASSWORD_DEFAULT);

echo "Chuỗi Hash của mật khẩu: " . $hashed_password;
?>