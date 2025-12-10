<?php
// BẮT BUỘC: Không được có khoảng trắng trước thẻ <?php
ob_start(); // Bắt đầu bộ đệm đầu ra (để chặn các lỗi warning in ra làm hỏng JSON)
session_start();
require_once 'config/connect.php';

// Xóa mọi nội dung thừa thãi trước đó
ob_clean(); 

header('Content-Type: application/json');

// Tắt hiển thị lỗi PHP ra màn hình (Lỗi sẽ được catch bên dưới)
error_reporting(0);
ini_set('display_errors', 0);

try {
    // 1. Kiểm tra đăng nhập
    if (!isset($_SESSION['user_id'])) {
        throw new Exception('Vui lòng đăng nhập để sử dụng mã!');
    }

    $code = isset($_POST['code']) ? trim($_POST['code']) : '';
    $total_order = isset($_POST['total_order']) ? floatval($_POST['total_order']) : 0;

    if (empty($code)) {
        throw new Exception('Vui lòng nhập mã giảm giá!');
    }

    // 2. Tìm mã trong DB
    $stmt = $pdo->prepare("SELECT * FROM vouchers WHERE code = :code AND is_active = 1 LIMIT 1");
    $stmt->execute([':code' => $code]);
    $voucher = $stmt->fetch(PDO::FETCH_ASSOC);

    $today = date('Y-m-d');

    // 3. Validate các điều kiện
    if (!$voucher) {
        throw new Exception('Mã giảm giá không tồn tại!');
    }
    if ($today < $voucher['start_date']) {
        throw new Exception('Mã này chưa đến đợt áp dụng!');
    }
    if ($today > $voucher['end_date']) {
        throw new Exception('Mã này đã hết hạn!');
    }
    if ($voucher['used_count'] >= $voucher['quantity']) {
        throw new Exception('Mã này đã hết lượt sử dụng!');
    }
    if ($total_order < $voucher['min_order_amount']) {
        throw new Exception('Đơn hàng phải từ ' . number_format($voucher['min_order_amount']) . 'đ mới được dùng mã này!');
    }

    // 4. Thành công
    echo json_encode([
        'status' => 'success',
        'message' => 'Áp dụng mã thành công: Giảm ' . number_format($voucher['discount_amount']) . 'đ',
        'discount_amount' => $voucher['discount_amount'],
        'code' => $voucher['code']
    ]);

} catch (Exception $e) {
    // Trả về lỗi dạng JSON
    echo json_encode([
        'status' => 'error', 
        'message' => $e->getMessage()
    ]);
}
exit;
?>