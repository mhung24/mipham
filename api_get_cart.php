<?php
session_start();
require_once 'config/connect.php';

// Nếu chưa đăng nhập -> Trả về thông báo trống hoặc yêu cầu đăng nhập
if (!isset($_SESSION['user_id'])) {
    echo '<div class="text-center py-3"><p class="text-muted small">Vui lòng đăng nhập để xem giỏ hàng</p></div>';
    exit;
}

$user_id = $_SESSION['user_id'];

// 1. Lấy danh sách sản phẩm trong giỏ
$sql = "SELECT c.product_id, c.quantity, p.name, p.price, 
       (SELECT image_url FROM product_gallery WHERE product_id = p.id LIMIT 1) as thumbnail
        FROM cart c
        JOIN products p ON c.product_id = p.id
        WHERE c.user_id = ?
        ORDER BY c.created_at DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute([$user_id]);
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 2. Tính tổng tiền
$total_money = 0;
foreach ($items as $item) {
    $total_money += $item['price'] * $item['quantity'];
}

// 3. XUẤT HTML (Để JS hiển thị)
if (empty($items)) {
    // GIỎ HÀNG TRỐNG
    echo '
    <div class="text-center py-4">
        <img src="https://deo.shopeemobile.com/shopee/shopee-pcmall-live-sg/5fafbb923393b712b96488590b8f781f.png" width="60" alt="Empty">
        <p class="text-muted small mt-2">Chưa có sản phẩm</p>
    </div>';
} else {
    // CÓ SẢN PHẨM -> HIỆN DANH SÁCH
    echo '<div class="mini-cart-list" style="max-height: 300px; overflow-y: auto;">';

    foreach ($items as $item) {
        $img = !empty($item['thumbnail']) ? $item['thumbnail'] : 'https://via.placeholder.com/50';
        $link = 'product_detail.php?id=' . $item['product_id'];

        echo '
        <div class="d-flex align-items-center p-2 border-bottom">
            <a href="' . $link . '"><img src="' . $img . '" style="width: 50px; height: 50px; object-fit: contain; border: 1px solid #eee;"></a>
            <div class="ms-2 flex-grow-1" style="line-height: 1.2;">
                <a href="' . $link . '" class="text-decoration-none text-dark d-block text-truncate" style="font-size: 13px; max-width: 180px;">
                    ' . htmlspecialchars($item['name']) . '
                </a>
                <div class="d-flex justify-content-between mt-1">
                    <span class="text-danger fw-bold" style="font-size: 12px;">' . number_format($item['price'], 0, ',', '.') . 'đ</span>
                    <span class="text-muted" style="font-size: 12px;">x' . $item['quantity'] . '</span>
                </div>
            </div>
        </div>';
    }

    echo '</div>'; // End list

    // PHẦN TỔNG TIỀN & NÚT XEM GIỎ
    echo '
    <div class="p-2 bg-light border-top">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <span class="small text-muted">Tổng cộng:</span>
            <span class="fw-bold text-danger">' . number_format($total_money, 0, ',', '.') . 'đ</span>
        </div>
        <a href="cart.php" class="btn btn-danger btn-sm w-100">XEM GIỎ HÀNG</a>
    </div>';
}
?>