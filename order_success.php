<?php
session_start();
require_once 'config/connect.php';

$order_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đặt hàng thành công</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="css/menu.css">
    <link rel="stylesheet" href="css/footer.css">
</head>
<body>
    <?php if (file_exists('header.php')) include 'header.php'; ?>
    <?php if (file_exists('menu.php')) include 'menu.php'; ?>

    <div class="container py-5 text-center">
        <div class="card border-0 shadow-sm p-5 mx-auto" style="max-width: 600px;">
            <div class="mb-4">
                <i class="bi bi-check-circle-fill text-success" style="font-size: 80px;"></i>
            </div>
            <h2 class="fw-bold text-success mb-3">ĐẶT HÀNG THÀNH CÔNG!</h2>
            <p class="text-muted mb-4">
                Cảm ơn bạn đã mua sắm tại Cocolux.<br>
                Mã đơn hàng của bạn là: <strong class="text-dark">#<?= $order_id ?></strong>
            </p>
            
            <div class="d-flex justify-content-center gap-3">
                <a href="index.php" class="btn btn-outline-secondary">Về trang chủ</a>
                <a href="orders.php" class="btn btn-danger">Xem đơn hàng</a>
            </div>
        </div>
    </div>

    <?php if (file_exists('footer.php')) include 'footer.php'; ?>
</body>
</html>