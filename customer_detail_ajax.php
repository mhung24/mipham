<?php
// Tên file: customer_detail_ajax.php
require_once 'config/connect.php';

if (!isset($pdo) || $_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(400);
    echo "Lỗi truy vấn.";
    exit;
}

$customer_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$customer_id) {
    http_response_code(404);
    echo "ID khách hàng không hợp lệ.";
    exit;
}

// Hàm format giá tiền
function format_vnd($amount)
{
    return number_format((float) $amount, 0, ',', '.') . 'đ';
}

try {
    // 1. Lấy thông tin chung của khách hàng
    $sql_customer = "SELECT u.*, (SELECT SUM(total_money) FROM orders o WHERE o.user_id = u.id AND o.status = 'Hoàn thành') AS total_spent FROM users u WHERE u.id = ? AND u.role = 'user'";
    $stmt_customer = $pdo->prepare($sql_customer);
    $stmt_customer->execute([$customer_id]);
    $customer = $stmt_customer->fetch();

    if (!$customer) {
        echo "Không tìm thấy khách hàng #$customer_id.";
        exit;
    }

    // 2. Lấy lịch sử 5 đơn hàng gần nhất
    $sql_orders = "SELECT id, total_money, status, created_at FROM orders WHERE user_id = ? ORDER BY created_at DESC LIMIT 5";
    $stmt_orders = $pdo->prepare($sql_orders);
    $stmt_orders->execute([$customer_id]);
    $orders = $stmt_orders->fetchAll();

    // --- Bắt đầu xuất HTML cho Modal ---
    ?>
    <div class="row">
        <div class="col-md-6">
            <h6>Thông tin Liên hệ</h6>
            <p><strong>Họ và Tên:</strong> <?php echo htmlspecialchars($customer['full_name']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($customer['email']); ?></p>
            <p><strong>Điện thoại:</strong> <?php echo htmlspecialchars($customer['phone'] ?? 'Chưa cung cấp'); ?></p>
            <p><strong>Địa chỉ:</strong> <?php echo htmlspecialchars($customer['address'] ?? 'Chưa cung cấp'); ?></p>
            <p><strong>Ngày tham gia:</strong> <?php echo date('d/m/Y', strtotime($customer['created_at'])); ?></p>
        </div>
        <div class="col-md-6">
            <h6>Thống kê Mua hàng</h6>
            <p><strong>Tổng số đơn:</strong> <span class="badge bg-primary"><?php echo count($orders); ?></span></p>
            <p><strong>Tổng chi tiêu:</strong> <span
                    class="fw-bold text-danger"><?php echo format_vnd($customer['total_spent'] ?? 0); ?></span></p>
        </div>
    </div>

    <h6 class="mt-4">Lịch sử 5 đơn hàng gần nhất</h6>
    <table class="table table-bordered table-striped table-sm">
        <thead>
            <tr>
                <th>ID Đơn hàng</th>
                <th>Ngày đặt</th>
                <th>Tổng tiền</th>
                <th>Trạng thái</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($orders)): ?>
                <tr>
                    <td colspan="4" class="text-center">Khách hàng chưa có đơn hàng nào.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($orders as $order): ?>
                    <tr>
                        <td>#<?php echo $order['id']; ?></td>
                        <td><?php echo date('d/m/Y', strtotime($order['created_at'])); ?></td>
                        <td class="fw-bold"><?php echo format_vnd($order['total_money']); ?></td>
                        <td><?php echo $order['status']; ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

    <?php

} catch (\PDOException $e) {
    http_response_code(500);
    echo "Lỗi server khi tải dữ liệu: " . $e->getMessage();
}
?>