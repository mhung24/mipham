<?php
// 1. TỰ ĐỘNG TÌM ID CỦA DANH MỤC "Chăm Sóc Da"
$stmt_get_id = $pdo->prepare("SELECT id FROM categories WHERE name = 'Chăm Sóc Da' LIMIT 1");
$stmt_get_id->execute();
$row_cat = $stmt_get_id->fetch(PDO::FETCH_ASSOC);

// Nếu tìm thấy thì lấy ID, không thì để 0
$cat_id_skincare = $row_cat ? $row_cat['id'] : 0;

// 2. LẤY SẢN PHẨM (Thuộc Chăm Sóc Da hoặc con của nó)
$sql = "SELECT p.*, 
               (SELECT image_url FROM product_gallery WHERE product_id = p.id LIMIT 1) as thumbnail 
        FROM products p 
        WHERE p.category_id = $cat_id_skincare 
           OR p.category_id IN (SELECT id FROM categories WHERE parent_id = $cat_id_skincare) 
        ORDER BY p.id DESC LIMIT 10";

try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $skincare_products = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $skincare_products = [];
}

// 3. LẤY MENU CON (Để hiện các tab: Làm sạch, Dưỡng da...)
$sql_sub = "SELECT * FROM categories WHERE parent_id = $cat_id_skincare ORDER BY sort_order ASC LIMIT 5";
try {
    $stmt_sub = $pdo->prepare($sql_sub);
    $stmt_sub->execute();
    $skincare_sub_cats = $stmt_sub->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $skincare_sub_cats = [];
}
?>

<link rel="stylesheet" href="css/section_makeup.css">

<div class="container section-makeup">
    <div class="makeup-header">
        <div class="makeup-sub-links">
            <a class="makeup-tab-active" href="list_products.php?cat_id=<?= $cat_id_skincare ?>">
                <h2 class="text-uppercase mb-0">CHĂM SÓC DA</h2>
            </a>

            <div class="section-sub-menu">
                <?php foreach ($skincare_sub_cats as $sub): ?>
                    <a href="list_products.php?cat_id=<?= $sub['id'] ?>">
                        <?= mb_strtoupper($sub['name'], 'UTF-8') ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>

        <a class="section-more" href="list_products.php?cat_id=<?= $cat_id_skincare ?>">XEM THÊM</a>
    </div>

    <div class="row g-0">

        <div class="col-lg-2 col-md-3 d-none d-md-block makeup-banner-col">
            <img src="./img/banner_skincare.jpg" class="makeup-banner-img" alt="Banner Chăm Sóc Da">
        </div>

        <div class="col-lg-10 col-md-9">
            <div class="row row-cols-2 row-cols-md-3 row-cols-lg-5 g-0 makeup-grid-container">

                <?php foreach ($skincare_products as $row): ?>
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
    </div>
</div>