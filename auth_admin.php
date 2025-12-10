<?php
session_start();
require_once 'config/connect.php';

if (!isset($pdo)) {

    header("Location: login_admin.php?error=db_failed");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: login_admin.php");
    exit;
}

$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
$password = $_POST['password'] ?? '';

if (!$email || empty($password)) {
    header("Location: login_admin.php?error=invalid_credentials");
    exit;
}

try {
    // 1. Tìm kiếm người dùng bằng email
    $sql = "SELECT id, full_name, password, role FROM users WHERE email = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if (!$user) {
        header("Location: login_admin.php?error=invalid_credentials");
        exit;
    }

    if (password_verify($password, $user['password'])) {

        if ($user['role'] === 'admin') {

            $_SESSION['admin_id'] = $user['id'];
            $_SESSION['admin_name'] = $user['full_name'];
            $_SESSION['admin_role'] = $user['role'];

            header("Location: admin.php");
            exit;
        } else {
            header("Location: login_admin.php?error=no_admin_role");
            exit;
        }

    } else {
        header("Location: login_admin.php?error=invalid_credentials");
        exit;
    }

} catch (\PDOException $e) {
    error_log("Lỗi PDO khi đăng nhập: " . $e->getMessage());
    header("Location: login_admin.php?error=login_failed");
    exit;
}
?>