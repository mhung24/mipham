<?php
session_start();
require_once 'config/connect.php';

// 1. KIỂM TRA ĐĂNG NHẬP
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
$user_id = $_SESSION['user_id'];

// 2. XỬ LÝ CẬP NHẬT GIỎ HÀNG (POST)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'] ?? '';
    $p_id = intval($_POST['product_id']);

    // --- XÓA SẢN PHẨM ---
    if ($action == 'remove') {
        $stmt = $pdo->prepare("DELETE FROM cart WHERE user_id = ? AND product_id = ?");
        $stmt->execute([$user_id, $p_id]);
    }

    // --- CẬP NHẬT SỐ LƯỢNG (+ hoặc -) ---
    if ($action == 'update') {
        $qty = intval($_POST['quantity']);
        if ($qty < 1) {
            // Nếu giảm xuống dưới 1 thì xóa luôn
            $stmt = $pdo->prepare("DELETE FROM cart WHERE user_id = ? AND product_id = ?");
            $stmt->execute([$user_id, $p_id]);
        } else {
            $stmt = $pdo->prepare("UPDATE cart SET quantity = ? WHERE user_id = ? AND product_id = ?");
            $stmt->execute([$qty, $user_id, $p_id]);
        }
    }

    // Refresh lại trang để cập nhật số liệu
    header("Location: cart.php");
    exit;
}

// 3. LẤY DỮ LIỆU GIỎ HÀNG TỪ DB
// Join bảng cart với products để lấy tên, giá, ảnh
$sql = "SELECT c.quantity, p.id, p.name, p.price, p.old_price, 
       (SELECT image_url FROM product_gallery WHERE product_id = p.id LIMIT 1) as thumbnail
        FROM cart c
        JOIN products p ON c.product_id = p.id
        WHERE c.user_id = ?
        ORDER BY c.created_at DESC";

$cart_items = [];
$total_money = 0;
$total_count = 0;

try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$user_id]);
    $cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Tính tổng tiền
    foreach ($cart_items as $item) {
        $total_money += $item['price'] * $item['quantity'];
        $total_count += $item['quantity'];
    }

    // Cập nhật session count để hiện lên Header
    $_SESSION['cart_count'] = $total_count;

} catch (Exception $e) {
    $cart_items = [];
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Giỏ hàng của bạn | Cocolux Clone</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <link rel="stylesheet" href="css/menu.css">
    <link rel="stylesheet" href="css/footer.css">

    <style>
        .cart-header {
            background: #f9f9f9;
            padding: 15px;
            border-radius: 5px;
            font-weight: bold;
            font-size: 14px;
        }

        .cart-item {
            border-bottom: 1px solid #eee;
            padding: 20px 0;
        }

        .cart-img {
            width: 80px;
            height: 80px;
            object-fit: contain;
            border: 1px solid #eee;
            border-radius: 4px;
        }

        .qty-btn {
            width: 30px;
            height: 30px;
            border: 1px solid #ddd;
            background: #fff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: 0.2s;
        }

        .qty-btn:hover {
            background: #eee;
        }

        .qty-input {
            width: 40px;
            height: 30px;
            border: 1px solid #ddd;
            border-left: none;
            border-right: none;
            text-align: center;
            outline: none;
            font-size: 14px;
        }

        .btn-checkout {
            background-color: #d0021b;
            color: #fff;
            font-weight: bold;
            width: 100%;
            padding: 12px;
            text-transform: uppercase;
            border: none;
        }

        .btn-checkout:hover {
            background-color: #a80014;
            color: #fff;
        }
    </style>
</head>

<body style="background-color: #fff;">

    <?php if (file_exists('header.php'))
        include 'header.php'; ?>
    <?php if (file_exists('menu.php'))
        include 'menu.php'; ?>

    <div class="container py-4" style="min-height: 600px;">

        <h4 class="fw-bold mb-4 text-uppercase">Giỏ hàng của bạn <span class="text-muted fs-6">(<?= $total_count ?> sản
                phẩm)</span></h4>

        <?php if (empty($cart_items)): ?>
            <div class="text-center py-5">
                <img src="https://deo.shopeemobile.com/shopee/shopee-pcmall-live-sg/5fafbb923393b712b96488590b8f781f.png"
                    width="100" alt="Empty Cart">
                <p class="mt-3 text-muted">Giỏ hàng của bạn còn trống.</p>
                <a href="index.php" class="btn btn-danger">MUA SẮM NGAY</a>
            </div>
        <?php else: ?>

            <div class="row">
                <div class="col-lg-8">
                    <div class="cart-header d-none d-md-flex row align-items-center">
                        <div class="col-md-5">Sản phẩm</div>
                        <div class="col-md-2 text-center">Đơn giá</div>
                        <div class="col-md-3 text-center">Số lượng</div>
                        <div class="col-md-2 text-center">Thành tiền</div>
                    </div>

                    <?php foreach ($cart_items as $item): ?>
                        <?php
                        $imgSrc = !empty($item['thumbnail']) ? $item['thumbnail'] : 'https://via.placeholder.com/80';
                        $line_total = $item['price'] * $item['quantity'];
                        ?>
                        <div class="cart-item row align-items-center">

                            <div class="col-md-5 d-flex align-items-center">
                                <form action="cart.php" method="POST" class="me-3">
                                    <input type="hidden" name="action" value="remove">
                                    <input type="hidden" name="product_id" value="<?= $item['id'] ?>">
                                    <button type="submit" class="btn btn-sm text-secondary border-0 p-0"
                                        onclick="return confirm('Bạn muốn xóa sản phẩm này?')">
                                        <i class="bi bi-trash3 fs-5"></i>
                                    </button>
                                </form>

                                <a href="product_detail.php?id=<?= $item['id'] ?>">
                                    <img src="<?= htmlspecialchars($imgSrc) ?>" class="cart-img">
                                </a>
                                <div class="ms-3">
                                    <a href="product_detail.php?id=<?= $item['id'] ?>"
                                        class="text-dark text-decoration-none fw-bold" style="font-size: 14px;">
                                        <?= htmlspecialchars($item['name']) ?>
                                    </a>
                                </div>
                            </div>

                            <div class="col-md-2 text-center d-none d-md-block">
                                <span class="fw-bold"><?= number_format($item['price'], 0, ',', '.') ?>đ</span>
                                <?php if ($item['old_price'] > $item['price']): ?>
                                    <br><del class="text-muted small"><?= number_format($item['old_price'], 0, ',', '.') ?>đ</del>
                                <?php endif; ?>
                            </div>

                            <div class="col-md-3 text-center mt-3 mt-md-0">
                                <form action="cart.php" method="POST" class="d-flex justify-content-center align-items-center">
                                    <input type="hidden" name="action" value="update">
                                    <input type="hidden" name="product_id" value="<?= $item['id'] ?>">

                                    <button type="submit" name="quantity" value="<?= $item['quantity'] - 1 ?>"
                                        class="qty-btn">-</button>

                                    <input type="text" class="qty-input" value="<?= $item['quantity'] ?>" readonly>

                                    <button type="submit" name="quantity" value="<?= $item['quantity'] + 1 ?>"
                                        class="qty-btn">+</button>
                                </form>
                            </div>

                            <div class="col-md-2 text-center fw-bold text-danger d-none d-md-block">
                                <?= number_format($line_total, 0, ',', '.') ?>đ
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="col-lg-4 mt-4 mt-lg-0">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <h5 class="fw-bold mb-3">Tổng đơn hàng</h5>

                            <div class="d-flex justify-content-between mb-2">
                                <span>Tạm tính:</span>
                                <span><?= number_format($total_money, 0, ',', '.') ?>đ</span>
                            </div>

                            <hr>

                            <div class="d-flex justify-content-between mb-4">
                                <span class="fw-bold fs-5">Tổng cộng:</span>
                                <span
                                    class="fw-bold fs-4 text-danger"><?= number_format($total_money, 0, ',', '.') ?>đ</span>
                            </div>

                            <a href="checkout.php" class="btn btn-checkout"
                                style="background-color: #d0021b; color: #fff;">TIẾN HÀNH
                                THANH TOÁN</a>

                            <div class="mt-3 text-center">
                                <a href="index.php" class="text-decoration-none text-danger fw-bold"
                                    style="font-size: 14px;">
                                    <i class="bi bi-arrow-left"></i> Tiếp tục mua sắm
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

    </div>

    <?php if (file_exists('footer.php'))
        include 'footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>