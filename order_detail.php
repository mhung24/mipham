<?php
session_start();
require_once 'config/connect.php';

// 1. KIỂM TRA ĐĂNG NHẬP
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Vui lòng đăng nhập!'); window.location.href='login.php';</script>";
    exit;
}

// 2. KIỂM TRA ID TRÊN URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: orders.php");
    exit;
}

$order_id = $_GET['id'];
$user_id = $_SESSION['user_id'];

try {
    // 3. LẤY THÔNG TIN ĐƠN HÀNG (BẢO MẬT: Phải đúng user_id mới xem được)
    $stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ? AND user_id = ?");
    $stmt->execute([$order_id, $user_id]);
    $order = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$order) {
        echo "<script>alert('Đơn hàng không tồn tại hoặc bạn không có quyền truy cập!'); window.location.href='orders.php';</script>";
        exit;
    }

    // 4. LẤY CHI TIẾT SẢN PHẨM
    // Subquery lấy ảnh đại diện từ bảng product_gallery
    $sql_items = "SELECT oi.*, 
                 (SELECT image_url FROM product_gallery WHERE product_id = oi.product_id LIMIT 1) as thumbnail
                 FROM order_items oi 
                 WHERE oi.order_id = ?";
    $stmt_items = $pdo->prepare($sql_items);
    $stmt_items->execute([$order_id]);
    $items = $stmt_items->fetchAll(PDO::FETCH_ASSOC);

} catch (Exception $e) {
    die("Lỗi hệ thống: " . $e->getMessage());
}

// Hàm màu trạng thái
function getStatusColor($status) {
    switch ($status) {
        case 'Đang xử lý': return 'bg-warning text-dark';
        case 'Đang giao': return 'bg-primary';
        case 'Hoàn thành': return 'bg-success';
        case 'Đã hủy': return 'bg-danger';
        default: return 'bg-secondary';
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Chi tiết đơn hàng #<?= $order_id ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        body { background-color: #f8f9fa; font-family: 'Segoe UI', sans-serif; }
        
        .detail-card {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.02);
            border: 1px solid #eee;
            margin-bottom: 20px;
        }
        
        .section-header {
            padding: 15px 20px;
            border-bottom: 1px solid #eee;
            font-weight: 700;
            font-size: 16px;
            color: #333;
            background: #fdfdfd;
            border-radius: 8px 8px 0 0;
        }

        /* Timeline trạng thái */
        .status-timeline {
            display: flex;
            justify-content: space-between;
            padding: 30px 40px;
            background: #fff;
            margin-bottom: 20px;
            border-radius: 8px;
            border: 1px solid #eee;
        }
        .status-step {
            text-align: center;
            position: relative;
            flex: 1;
            opacity: 0.4;
            font-weight: 600;
        }
        .status-step.active { opacity: 1; color: #d0021b; } /* Màu đỏ chủ đạo active */
        .status-step i { font-size: 28px; display: block; margin-bottom: 8px; }
        
        /* Table Products */
        .product-img {
            width: 70px; height: 70px;
            object-fit: cover;
            border-radius: 6px;
            border: 1px solid #eee;
        }
        
        .info-row { margin-bottom: 10px; font-size: 14px; }
        .info-label { color: #666; width: 140px; display: inline-block; font-weight: 500; }
    </style>
</head>
<body>

    <?php if (file_exists('header.php')) include 'header.php'; ?>
    <?php if (file_exists('menu.php')) include 'menu.php'; ?>

    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <a href="orders.php" class="text-decoration-none text-muted fw-bold">
                <i class="bi bi-chevron-left"></i> QUAY LẠI DANH SÁCH
            </a>
            <span class="text-muted">Mã đơn hàng: <strong class="text-dark">#<?= $order_id ?></strong></span>
        </div>

        <div class="status-timeline d-none d-md-flex shadow-sm">
            <div class="status-step <?= ($order['status'] == 'Đang xử lý' || $order['status'] == 'Đang giao' || $order['status'] == 'Hoàn thành') ? 'active' : '' ?>">
                <i class="bi bi-clipboard-check-fill"></i> Đặt hàng
            </div>
            <div class="status-step <?= ($order['status'] == 'Đang giao' || $order['status'] == 'Hoàn thành') ? 'active' : '' ?>">
                <i class="bi bi-truck-front-fill"></i> Đang giao
            </div>
            <div class="status-step <?= ($order['status'] == 'Hoàn thành') ? 'active' : '' ?>">
                <i class="bi bi-star-fill"></i> Hoàn thành
            </div>
            <?php if($order['status'] == 'Đã hủy'): ?>
                <div class="status-step active text-danger" style="opacity: 1;">
                    <i class="bi bi-x-circle-fill"></i> Đã hủy
                </div>
            <?php endif; ?>
        </div>

        <div class="detail-card p-3 d-md-none text-center">
            <span class="text-muted me-2">Trạng thái:</span>
            <span class="badge <?= getStatusColor($order['status']) ?> fs-6"><?= $order['status'] ?></span>
        </div>

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="detail-card h-100">
                    <div class="section-header">
                        <i class="bi bi-box-seam me-2"></i> Thông tin sản phẩm
                    </div>
                    <div class="p-3">
                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead class="table-light">
                                    <tr class="text-muted small text-uppercase">
                                        <th>Sản phẩm</th>
                                        <th class="text-center">Số lượng</th>
                                        <th class="text-end">Đơn giá</th>
                                        <th class="text-end">Thành tiền</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $subtotal = 0; ?>
                                    <?php foreach ($items as $item): ?>
                                        <?php $subtotal += $item['price'] * $item['quantity']; ?>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="<?= $item['thumbnail'] ?: 'https://via.placeholder.com/70' ?>" class="product-img me-3">
                                                    <div>
                                                        <div class="fw-bold text-dark" style="font-size: 14px;">
                                                            <?= htmlspecialchars($item['product_name']) ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center fw-bold text-muted">x<?= $item['quantity'] ?></td>
                                            <td class="text-end text-muted small"><?= number_format($item['price'], 0, ',', '.') ?>đ</td>
                                            <td class="text-end fw-bold text-dark"><?= number_format($item['price'] * $item['quantity'], 0, ',', '.') ?>đ</td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                        <div class="row justify-content-end pt-3 mt-2 border-top">
                            <div class="col-md-6 col-12">
                                <div class="d-flex justify-content-between mb-2 small">
                                    <span class="text-muted">Tổng tiền hàng:</span>
                                    <span class="fw-bold"><?= number_format($subtotal, 0, ',', '.') ?>đ</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2 small">
                                    <span class="text-muted">Phí vận chuyển:</span>
                                    <span class="text-success fw-bold">Miễn phí</span>
                                </div>
                                <?php if($order['discount_amount'] > 0): ?>
                                <div class="d-flex justify-content-between mb-2 small">
                                    <span class="text-muted">Voucher giảm giá:</span>
                                    <span class="text-success fw-bold">-<?= number_format($order['discount_amount'], 0, ',', '.') ?>đ</span>
                                </div>
                                <?php endif; ?>
                                <div class="d-flex justify-content-between mt-3 pt-3 border-top">
                                    <span class="fw-bold">TỔNG THANH TOÁN:</span>
                                    <span class="fw-bold fs-5 text-danger"><?= number_format($order['total_money'], 0, ',', '.') ?>đ</span>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="detail-card">
                    <div class="section-header">
                        <i class="bi bi-geo-alt me-2"></i> Địa chỉ nhận hàng
                    </div>
                    <div class="p-4">
                        <h6 class="fw-bold mb-1"><?= htmlspecialchars($order['full_name']) ?></h6>
                        <p class="text-muted mb-3 small"><?= htmlspecialchars($order['phone']) ?></p>
                        <p class="small mb-0 text-dark">
                            <?= nl2br(htmlspecialchars($order['address'])) ?>
                        </p>
                    </div>
                </div>

                <div class="detail-card">
                    <div class="section-header">
                        <i class="bi bi-info-circle me-2"></i> Thông tin đơn hàng
                    </div>
                    <div class="p-4">
                        <div class="info-row">
                            <span class="info-label">Mã đơn hàng:</span> 
                            <strong>#<?= $order['id'] ?></strong>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Ngày đặt:</span> 
                            <?= date('d/m/Y H:i', strtotime($order['created_at'])) ?>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Thanh toán:</span> 
                            <?php 
                                if (strpos($order['note'], 'CK') !== false || strpos($order['note'], 'BANKING') !== false) {
                                    echo '<span class="badge bg-primary-subtle text-primary border border-primary-subtle">Chuyển khoản</span>';
                                } else {
                                    echo '<span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle">COD</span>';
                                }
                            ?>
                        </div>
                        <?php if(!empty($order['note'])): ?>
                            <div class="mt-3 p-2 bg-light rounded border border-dashed small text-muted">
                                <i class="bi bi-sticky me-1"></i> <?= htmlspecialchars($order['note']) ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <?php if ($order['status'] == 'Đang xử lý'): ?>
                    <form action="cancel_order.php" method="POST" onsubmit="return confirm('Bạn có chắc muốn hủy đơn này? Hành động này không thể hoàn tác.');">
                        <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                        <button type="submit" class="btn btn-outline-danger w-100 py-2 fw-bold shadow-sm">
                            <i class="bi bi-x-lg me-2"></i> HỦY ĐƠN HÀNG
                        </button>
                    </form>
                    <div class="text-center mt-2">
                        <small class="text-muted fst-italic">* Chỉ có thể hủy khi đơn chưa được giao.</small>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php if (file_exists('footer.php')) include 'footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>