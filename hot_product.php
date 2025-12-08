<?php
require_once 'config/connect.php';

if (!isset($pdo)) {
    die("Lỗi kết nối database.");
}

$hot_products = [];
try {

    $sql = "SELECT p.*, 
            (SELECT image_url FROM product_gallery g WHERE g.product_id = p.id LIMIT 1) as thumbnail 
            FROM products p 
            WHERE p.is_hot = 1 AND p.status = 'published'
            ORDER BY p.id DESC 
            LIMIT 20";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $hot_products = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Lỗi truy vấn: " . $e->getMessage();
}

function formatPrice($price)
{
    return number_format($price, 0, ',', '.') . 'đ';
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang chủ - Shop Mỹ Phẩm</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./css/hot_product.css">
</head>

<body>

    <div class="container mt-5 mb-5">

        <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-2">
            <h2 class="fs-4 fw-bold text-uppercase">
                <i class="bi bi-fire text-danger me-2"></i> Sản phẩm Hot
            </h2>
            <a href="list_products.php?type=hot" class="text-decoration-none fw-bold small text-dark">
                XEM TẤT CẢ <i class="bi bi-chevron-right"></i>
            </a>
        </div>

        <div class="product-slider-wrapper">

            <button class="nav-arrow left-arrow"><i class="bi bi-chevron-left"></i></button>
            <button class="nav-arrow right-arrow"><i class="bi bi-chevron-right"></i></button>

            <div class="product-list-row">

                <?php if (count($hot_products) > 0): ?>
                    <?php foreach ($hot_products as $product): ?>
                        <?php
                        $imgSrc = !empty($product['thumbnail']) ? $product['thumbnail'] : 'img/no-image.png';

                        $price = $product['price'];
                        $old_price = isset($product['old_price']) ? $product['old_price'] : 0;

                        $has_discount = ($old_price > $price);
                        $discount_percent = 0;
                        if ($has_discount) {
                            $discount_percent = round((($old_price - $price) / $old_price) * 100);
                        }
                        ?>

                        <div class="product-col">
                            <div class="product-card">

                                <?php if ($has_discount): ?>
                                    <span class="discount-badge">-<?= $discount_percent ?>%</span>
                                <?php endif; ?>

                                <a href="product_detail.php?id=<?= $product['id'] ?>" class="product-link">
                                    <div class="product-img-container">
                                        <img src="<?= htmlspecialchars($imgSrc) ?>"
                                            alt="<?= htmlspecialchars($product['name']) ?>">
                                    </div>

                                    <div class="card-body">
                                        <div class="price-section">
                                            <span class="new-price"><?= formatPrice($price) ?></span>
                                            <?php if ($has_discount): ?>
                                                <span class="old-price"><?= formatPrice($old_price) ?></span>
                                            <?php endif; ?>
                                        </div>

                                        <div class="product-name" title="<?= htmlspecialchars($product['name']) ?>">
                                            <?= htmlspecialchars($product['name']) ?>
                                        </div>

                                        <div class="product-description">
                                            SKU: <?= htmlspecialchars($product['sku']) ?>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-12 text-center py-5">
                        <p class="text-muted">Chưa có sản phẩm nổi bật nào.</p>
                    </div>
                <?php endif; ?>

            </div>
        </div>
    </div>

    <script src="./js/hot_product.js"></script>

</body>

</html>