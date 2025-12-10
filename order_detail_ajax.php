<?php
// Tên file: order_detail_ajax.php
require_once 'config/connect.php';

if (!isset($pdo) || $_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(400);
    echo "Lỗi truy vấn.";
    exit;
}

$order_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$order_id) {
    http_response_code(404);
    echo "ID đơn hàng không hợp lệ.";
    exit;
}

// Hàm format giá tiền (giữ lại để tái sử dụng)
function format_vnd($amount)
{
    return number_format((float) $amount, 0, ',', '.') . 'đ';
}

try {
    // 1. Lấy thông tin chung của đơn hàng
    $sql_order = "SELECT * FROM orders WHERE id = ?";
    $stmt_order = $pdo->prepare($sql_order);
    $stmt_order->execute([$order_id]);
    $order = $stmt_order->fetch();

    // 2. Lấy danh sách sản phẩm trong đơn hàng
    $sql_items = "SELECT * FROM order_items WHERE order_id = ?";
    $stmt_items = $pdo->prepare($sql_items);
    $stmt_items->execute([$order_id]);
    $items = $stmt_items->fetchAll();

    if (!$order) {
        echo "Không tìm thấy đơn hàng #$order_id.";
        exit;
    }

    // 3. Lấy email người dùng từ bảng users (nếu có)
    $user_email = 'Khách vãng lai';
    if ($order['user_id'] > 0) {
        $stmt_user = $pdo->prepare("SELECT email FROM users WHERE id = ?");
        $stmt_user->execute([$order['user_id']]);
        $user_email = $stmt_user->fetchColumn() ?? 'N/A';
    }


    // --- Bắt đầu xuất HTML cho Modal ---
    ?>
    <div class="row">
        <div class="col-md-6">
            <h6>Thông tin Khách hàng</h6>
            <p><strong>Khách hàng:</strong> <?php echo htmlspecialchars($order['full_name']); ?></p>
            <p><strong>Điện thoại:</strong> <?php echo htmlspecialchars($order['phone']); ?></p>
            <p><strong>Địa chỉ:</strong> <?php echo htmlspecialchars($order['address']); ?></p>
            <p><strong>Email (User ID):</strong> <?php echo htmlspecialchars($user_email); ?> (ID:
                <?php echo $order['user_id']; ?>)</p>
            <p><strong>Ghi chú:</strong> <?php echo htmlspecialchars($order['note'] ?? 'Không'); ?></p>
        </div>
        <div class="col-md-6">
            <h6>Chi tiết Thanh toán</h6>
            <p><strong>Ngày đặt:</strong> <?php echo date('d/m/Y H:i', strtotime($order['created_at'])); ?></p>
            <p><strong>Trạng thái:</strong> <span
                    class="fw-bold text-<?php echo get_status_badge_class($order['status']); ?>"><?php echo $order['status']; ?></span>
            </p>
            <hr>
            <p><strong>Tổng tiền hàng:</strong> <?php echo format_vnd($order['total_money'] + $order['discount_amount']); ?>
            </p>
            <p><strong>Giảm giá (Voucher: <?php echo htmlspecialchars($order['voucher_code'] ?? 'N/A'); ?>):</strong> -
                <?php echo format_vnd($order['discount_amount']); ?></p>
            <p class="fw-bold"><strong>Tổng thanh toán:</strong> <span
                    class="text-danger fs-5"><?php echo format_vnd($order['total_money']); ?></span></p>
        </div>
    </div>

    <h6 class="mt-4">Danh sách Sản phẩm</h6>
    <table class="table table-bordered table-striped table-sm">
        <thead>
            <tr>
                <th>Sản phẩm</th>
                <th style="width: 100px;">Giá</th>
                <th style="width: 80px;">SL</th>
                <th style="width: 120px;">Thành tiền</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($items)): ?>
                <tr>
                    <td colspan="4" class="text-center">Đơn hàng không có sản phẩm nào.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($items as $item): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($item['product_name']); ?></td>
                        <td><?php echo format_vnd($item['price']); ?></td>
                        <td><?php echo $item['quantity']; ?></td>
                        <td class="fw-bold"><?php echo format_vnd($item['price'] * $item['quantity']); ?></td>
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

/**
 * Hàm hỗ trợ lấy class màu cho badge (Đã được định nghĩa trong orders_admin.php)
 */
function get_status_badge_class($status)
{
    $all_statuses = [
        'Đang xử lý' => 'warning',
        'Đang giao' => 'info',
        'Hoàn thành' => 'success',
        'Đã hủy' => 'danger'
    ];
    return $all_statuses[$status] ?? 'secondary';
}
?>