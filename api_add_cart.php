<?php
// api_add_cart.php
session_start();
require_once 'config/connect.php';

header('Content-Type: application/json'); // Trả về định dạng JSON cho JS đọc

// 1. Kiểm tra đăng nhập
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'login_required', 'message' => 'Bạn cần đăng nhập để mua hàng!']);
    exit;
}

$user_id = $_SESSION['user_id'];
$p_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
$qty = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;

if ($p_id > 0 && $qty > 0) {
    try {
        // 2. Kiểm tra sản phẩm đã có trong giỏ chưa
        $stmt_check = $pdo->prepare("SELECT id, quantity FROM cart WHERE user_id = ? AND product_id = ?");
        $stmt_check->execute([$user_id, $p_id]);
        $item = $stmt_check->fetch(PDO::FETCH_ASSOC);

        if ($item) {
            // Đã có -> Cộng dồn số lượng
            $new_qty = $item['quantity'] + $qty;
            $stmt_update = $pdo->prepare("UPDATE cart SET quantity = ? WHERE id = ?");
            $stmt_update->execute([$new_qty, $item['id']]);
        } else {
            // Chưa có -> Thêm dòng mới
            $stmt_insert = $pdo->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)");
            $stmt_insert->execute([$user_id, $p_id, $qty]);
        }

        // 3. Đếm tổng số lượng trong giỏ để cập nhật icon Header
        $stmt_count = $pdo->prepare("SELECT SUM(quantity) FROM cart WHERE user_id = ?");
        $stmt_count->execute([$user_id]);
        $total_count = $stmt_count->fetchColumn() ?: 0;

        // Cập nhật session
        $_SESSION['cart_count'] = $total_count;

        echo json_encode([
            'status' => 'success',
            'total_count' => $total_count,
            'message' => 'Đã thêm sản phẩm vào giỏ hàng!'
        ]);

    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => 'Lỗi Database: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Dữ liệu không hợp lệ']);
}
?>