<?php
$stmt_banner = $pdo->prepare("SELECT name, image FROM banners WHERE id = 1 LIMIT 1");
$stmt_banner->execute();
$banner_data = $stmt_banner->fetch(PDO::FETCH_ASSOC);

if ($banner_data) {
    $target_name = $banner_data['name'];
    $current_banner = $banner_data['image'];

    if (strpos($current_banner, 'uploads/') === false && strpos($current_banner, 'img/') === false) {
        $current_banner = 'uploads/banners/' . $current_banner;
    }
} else {
    // Fallback
    $target_name = 'Chăm Sóc Cơ Thể';
    $current_banner = './img/banner_bodycare.jpg';
}

$stmt_get_id = $pdo->prepare("SELECT id FROM categories WHERE name = :name LIMIT 1");
$stmt_get_id->execute([':name' => $target_name]);
$cat_id_body = $stmt_get_id->fetchColumn();

if (!$cat_id_body) {
    $cat_id_body = 0;
}

$sql = "SELECT p.*, 
               (SELECT image_url FROM product_gallery WHERE product_id = p.id LIMIT 1) as thumbnail 
        FROM products p 
        WHERE p.category_id = $cat_id_body 
           OR p.category_id IN (SELECT id FROM categories WHERE parent_id = $cat_id_body) 
        ORDER BY p.id DESC LIMIT 10";

try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $body_products = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $body_products = [];
}

$sql_sub = "SELECT * FROM categories WHERE parent_id = $cat_id_body ORDER BY sort_order ASC LIMIT 5";
try {
    $stmt_sub = $pdo->prepare($sql_sub);
    $stmt_sub->execute();
    $body_sub_cats = $stmt_sub->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $body_sub_cats = [];
}
?>

<link rel="stylesheet" href="css/section_makeup.css">

<div class="container section-makeup" style="margin-top: 30px;">

    <div class="makeup-header d-flex justify-content-between align-items-center"
        style="border-bottom: 2px solid #ddd; background: #f9f9f9; padding: 0;">

        <div class="d-flex align-items-center" style="height: 100%;">

            <a class="makeup-tab-active" href="list_products.php?cat_id=<?= $cat_id_body ?>"
                style="background: #222; color: #fff; padding: 15px 30px; display: block; text-decoration: none; position: relative; margin-right: 20px;">
                <h2 class="text-uppercase mb-0" style="color: #fff; font-size: 16px; margin: 0;">
                    <?= htmlspecialchars($target_name) ?>
                </h2>
                <div style="position: absolute; bottom: -8px; right: 0; width: 0; height: 0; 
                            border-left: 10px solid #222; border-bottom: 10px solid transparent;"></div>
            </a>

            <div class="section-sub-menu d-none d-md-block">
                <?php foreach ($body_sub_cats as $sub): ?>
                    <a href="list_products.php?cat_id=<?= $sub['id'] ?>"
                        style="color: #666; margin-right: 20px; text-transform: uppercase; font-size: 13px;">
                        <?= mb_strtoupper($sub['name'], 'UTF-8') ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="pe-3">
            <a class="section-more" href="list_products.php?cat_id=<?= $cat_id_body ?>"
                style="color: #d63384; font-weight: bold;">
                XEM THÊM
            </a>
        </div>
    </div>

    <div class="row g-0">

        <div class="col-lg-2 col-md-3 d-none d-md-block makeup-banner-col">
            <img src="<?= htmlspecialchars($current_banner) ?>" class="makeup-banner-img"
                alt="Banner <?= htmlspecialchars($target_name) ?>"
                style="width: 100%; height: 100%; object-fit: cover;">
        </div>

        <div class="col-lg-10 col-md-9">
            <div class="row row-cols-2 row-cols-md-3 row-cols-lg-5 g-0 makeup-grid-container">
                <?php foreach ($body_products as $row): ?>
                    <?php
                    $discount = ($row['old_price'] > $row['price']) ? round((($row['old_price'] - $row['price']) / $row['old_price']) * 100) : 0;
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

    </div>
</div>