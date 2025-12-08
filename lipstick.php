<?php
// 1. TỰ ĐỘNG TÌM ID CỦA DANH MỤC "Son Môi"
$stmt_get_id = $pdo->prepare("SELECT id FROM categories WHERE name = 'Son Môi' LIMIT 1");
$stmt_get_id->execute();
$row_cat = $stmt_get_id->fetch(PDO::FETCH_ASSOC);

// Nếu tìm thấy thì lấy ID đó, không thì để 0
$cat_id_lips = $row_cat ? $row_cat['id'] : 0;

// 2. SQL LẤY SẢN PHẨM (Dựa theo ID vừa tìm được)
$sql = "SELECT p.*, 
               (SELECT image_url FROM product_gallery WHERE product_id = p.id LIMIT 1) as thumbnail 
        FROM products p 
        WHERE p.category_id = $cat_id_lips 
           OR p.category_id IN (SELECT id FROM categories WHERE parent_id = $cat_id_lips) 
        ORDER BY p.id DESC LIMIT 10";

try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $lips_products = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $lips_products = [];
}

// 3. SQL LẤY MENU CON
$sql_sub = "SELECT * FROM categories WHERE parent_id = $cat_id_lips ORDER BY sort_order ASC LIMIT 5";
try {
    $stmt_sub = $pdo->prepare($sql_sub);
    $stmt_sub->execute();
    $lips_sub_cats = $stmt_sub->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $lips_sub_cats = [];
}
?>

<link rel="stylesheet" href="css/section_makeup.css">

<div class="container section-makeup" style="margin-top: 30px;">

    <div class="makeup-header d-flex justify-content-between align-items-center"
        style="border-bottom: 2px solid #ddd; background: #f9f9f9; padding: 0;">

        <a class="section-more" href="list_products.php?cat_id=<?= $cat_id_lips ?>"
            style="float: none; margin-left: 20px; order: 1; color: #d63384; font-weight: bold;">
            XEM THÊM
        </a>

        <div class="d-flex align-items-center" style="order: 2; height: 100%;">

            <div class="section-sub-menu me-3" style="margin-right: 20px;">
                <?php foreach ($lips_sub_cats as $sub): ?>
                    <a href="list_products.php?cat_id=<?= $sub['id'] ?>"
                        style="color: #666; margin-left: 15px; text-transform: uppercase; font-size: 13px;">
                        <?= mb_strtoupper($sub['name'], 'UTF-8') ?>
                    </a>
                <?php endforeach; ?>
            </div>

            <a class="makeup-tab-active-lipstick" href="list_products.php?cat_id=<?= $cat_id_lips ?>"
                style="background: #222; color: #fff; padding: 15px 30px; display: block; text-decoration: none; position: relative;">
                <h2 class="text-uppercase mb-0" style="color: #fff; font-size: 18px; margin: 0;">SON MÔI</h2>
            </a>
        </div>
    </div>

    <div class="row g-0">

        <div class="col-lg-10 col-md-9">
            <div class="row row-cols-2 row-cols-md-3 row-cols-lg-5 g-0 makeup-grid-container">
                <?php foreach ($lips_products as $row): ?>
                    <?php
                    $discount = 0;
                    if ($row['old_price'] > $row['price']) {
                        $discount = round((($row['old_price'] - $row['price']) / $row['old_price']) * 100);
                    }
                    $imgSrc = !empty($row['thumbnail']) ? $row['thumbnail'] : 'https://via.placeholder.com/300x300?text=No+Image';
                    ?>
                    <div class="col makeup-grid-item">
                        <div class="coco-card">
                            <div class="badge-freeship">FREESHIP</div>
                            <?php if ($discount > 0): ?>
                                <div class="badge-percent">-<?= $discount ?>%</div>
                            <?php endif; ?>

                            <a href="product_detail.php?id=<?= $row['id'] ?>" class="coco-img-wrapper d-block">
                                <img src="<?= htmlspecialchars($imgSrc) ?>" alt="<?= htmlspecialchars($row['name']) ?>">
                            </a>

                            <div class="card-body p-0 mt-2">
                                <div class="coco-price-row mb-1">
                                    <span class="coco-price-new"><?= number_format($row['price'], 0, ',', '.') ?>đ</span>
                                    <?php if ($discount > 0): ?>
                                        <span
                                            class="coco-price-old"><?= number_format($row['old_price'], 0, ',', '.') ?>đ</span>
                                    <?php endif; ?>
                                </div>
                                <span class="coco-brand">COCOLUX</span>
                                <a href="product_detail.php?id=<?= $row['id'] ?>" class="text-decoration-none">
                                    <h3 class="coco-name"><?= htmlspecialchars($row['name']) ?></h3>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="col-lg-2 col-md-3 d-none d-md-block makeup-banner-col">
            <img src="./img/banner_lipstick.jpg" class="makeup-banner-img" alt="Banner Son Môi"
                style="width: 100%; height: 100%; object-fit: cover;">
        </div>

    </div>
</div>