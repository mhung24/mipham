<?php
session_start();
require_once 'config/connect.php';

// Kiểm tra đăng nhập
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Lấy danh sách đơn hàng của User này
try {
    $stmt = $pdo->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY id DESC");
    $stmt->execute([$user_id]);
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $orders = [];
}

function getStatusColor($status)
{
    switch ($status) {
        case 'Đang xử lý':
            return 'bg-warning text-dark';
        case 'Đang giao':
            return 'bg-primary';
        case 'Hoàn thành':
            return 'bg-success';
        case 'Đã hủy':
            return 'bg-danger';
        default:
            return 'bg-secondary';
    }
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Đơn hàng của tôi | Cocolux Clone</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">



    <style>
        .sidebar-menu .list-group-item.active {
            background-color: #fff5f5;
            color: #d0021b;
            border-color: #fff5f5;
            font-weight: bold;
            border-left: 3px solid #d0021b;
        }

        .order-card {
            border: 1px solid #eee;
            border-radius: 8px;
            margin-bottom: 20px;
            background: #fff;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.02);
        }

        .order-header {
            background: #f9f9f9;
            padding: 15px;
            border-bottom: 1px solid #eee;
            border-radius: 8px 8px 0 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .order-body {
            padding: 20px;
        }

        .badge-status {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: normal;
        }
    </style>
</head>

<body style="background-color: #f8f9fa;">

    <?php if (file_exists('header.php'))
        include 'header.php'; ?>
    <?php if (file_exists('menu.php'))
        include 'menu.php'; ?>

    <div class="container py-4">
        <div class="row">

            <div class="col-lg-3 mb-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center py-4">
                        <i class="bi bi-person-circle text-secondary" style="font-size: 60px;"></i>
                        <h6 class="fw-bold mt-2"><?= htmlspecialchars($_SESSION['user_name'] ?? 'Khách hàng') ?></h6>
                    </div>
                    <div class="list-group list-group-flush sidebar-menu pb-3">
                        <a href="profile.php" class="list-group-item"><i class="bi bi-person-vcard me-2"></i> Thông tin
                            tài khoản</a>
                        <a href="orders.php" class="list-group-item active"><i class="bi bi-bag-check me-2"></i> Đơn
                            hàng của tôi</a>
                        <a href="logout.php" class="list-group-item text-danger"><i
                                class="bi bi-box-arrow-right me-2"></i> Đăng xuất</a>
                    </div>
                </div>
            </div>

            <div class="col-lg-9" style="min-height: 800px;">
                <h5 class="text-uppercase fw-bold mb-4">Lịch sử đơn hàng</h5>

                <?php if (empty($orders)): ?>
                    <div class="text-center py-5 bg-white rounded shadow-sm">
                        <img src="https://deo.shopeemobile.com/shopee/shopee-pcmall-live-sg/5fafbb923393b712b96488590b8f781f.png"
                            alt="No Order" width="100">
                        <p class="text-muted mt-3">Chưa có đơn hàng nào.</p>
                        <a href="index.php" class="btn btn-danger">MUA SẮM NGAY</a>
                    </div>
                <?php else: ?>

                    <?php foreach ($orders as $order): ?>
                        <div class="order-card">
                            <div class="order-header">
                                <div>
                                    <span class="fw-bold me-2">#ĐƠN HÀNG <?= $order['id'] ?></span>
                                    <span class="text-muted small">|
                                        <?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></span>
                                </div>
                                <span class="badge badge-status <?= getStatusColor($order['status']) ?>">
                                    <?= htmlspecialchars($order['status']) ?>
                                </span>
                            </div>
                            <div class="order-body">
                                <div class="row align-items-center">
                                    <div class="col-md-8">
                                        <p class="mb-1"><i class="bi bi-geo-alt me-1 text-danger"></i> <strong>Địa chỉ
                                                nhận:</strong> <?= htmlspecialchars($order['address']) ?></p>
                                        <p class="mb-0 text-muted small">Người nhận:
                                            <?= htmlspecialchars($order['full_name']) ?>
                                            (<?= htmlspecialchars($order['phone']) ?>)
                                        </p>
                                    </div>
                                    <div class="col-md-4 text-md-end mt-3 mt-md-0">
                                        <div class="small text-muted">Tổng tiền thanh toán</div>
                                        <div class="fs-5 fw-bold text-danger">
                                            <?= number_format($order['total_money'], 0, ',', '.') ?>đ
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>

                <?php endif; ?>

            </div>
        </div>
    </div>

    <?php if (file_exists('footer.php'))
        include 'footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>