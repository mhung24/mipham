<?php
// Tên file: order_process.php
require_once 'config/connect.php';

if (!isset($pdo)) {
    header("Location: orders_admin.php?update=error&msg=Database connection failed");
    exit;
}

$order_id = filter_input(INPUT_POST, 'order_id', FILTER_VALIDATE_INT);
$new_status = $_POST['new_status'] ?? '';
$action_type = $_POST['action_type'] ?? '';

if ($action_type === 'update_status' && $order_id > 0 && !empty($new_status)) {
    try {
        $sql = "UPDATE orders SET status = ? WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$new_status, $order_id]);

        // CHUYỂN HƯỚNG VỀ TRANG DANH SÁCH VÀ BÁO THÀNH CÔNG
        header("Location: orders_admin.php?update=success&status_name=" . urlencode($new_status));
        exit;

    } catch (\PDOException $e) {
        header("Location: orders_admin.php?update=error&msg=" . urlencode($e->getMessage()));
        exit;
    }
} else {
    // Nếu thiếu dữ liệu POST
    header("Location: orders_admin.php?update=error&msg=Dữ liệu gửi không hợp lệ.");
    exit;
}
?>