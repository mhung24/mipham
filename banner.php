<?php
require_once 'config/connect.php';

// 1. Lấy toàn bộ danh mục
$stmt = $pdo->prepare("SELECT * FROM categories ORDER BY parent_id ASC, sort_order ASC, id ASC");
$stmt->execute();
$cats = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 2. Phân nhóm theo parent_id để dễ lấy
// Mảng này sẽ có dạng: [ 0 => [Ds C1], 1 => [Ds con của ID 1], ... ]
$cats_by_parent = [];
foreach ($cats as $c) {
    $cats_by_parent[$c['parent_id']][] = $c;
}
?>

<link rel="stylesheet" href="./css/banner.css">
<div class="container">
    <div class="row">

        <div class="col-lg-3 col-md-4 d-none d-md-block">
            <div class="sidebar-menu card border-0 rounded-0 shadow-sm position-relative">
                <ul class="list-group list-group-flush">

                    <?php
                    // Lấy danh sách C1 (parent_id = 0)
                    if (isset($cats_by_parent[0])):
                        foreach ($cats_by_parent[0] as $c1):
                            // Kiểm tra xem C1 này có con (C2) không
                            $has_c2 = isset($cats_by_parent[$c1['id']]);
                            ?>
                            <li class="list-group-item menu-item-c1">
                                <a href="list_products.php?cat_id=<?= $c1['id'] ?>"
                                    class="d-flex justify-content-between align-items-center text-decoration-none text-dark w-100">
                                    <span>
                                        <?= htmlspecialchars($c1['name']) ?>
                                    </span>
                                    <?php if ($has_c2): ?>
                                        <i class="bi bi-chevron-right small text-muted"></i>
                                    <?php endif; ?>
                                </a>

                                <?php if ($has_c2): ?>
                                    <div class="mega-menu shadow-sm">
                                        <div class="row g-3">
                                            <?php foreach ($cats_by_parent[$c1['id']] as $c2): ?>
                                                <div class="col-6 mb-3"> <a href="list_products.php?cat_id=<?= $c2['id'] ?>"
                                                        class="fw-bold text-dark text-decoration-none d-block mb-1">
                                                        <?= htmlspecialchars($c2['name']) ?>
                                                    </a>

                                                    <?php if (isset($cats_by_parent[$c2['id']])): ?>
                                                        <ul class="list-unstyled ps-2 mb-0 border-start border-2 ms-1">
                                                            <?php foreach ($cats_by_parent[$c2['id']] as $c3): ?>
                                                                <li>
                                                                    <a href="list_products.php?cat_id=<?= $c3['id'] ?>"
                                                                        class="text-secondary text-decoration-none text-sm d-block py-1 hover-red">
                                                                        <?= htmlspecialchars($c3['name']) ?>
                                                                    </a>
                                                                </li>
                                                            <?php endforeach; ?>
                                                        </ul>
                                                    <?php endif; ?>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </li>
                            <?php
                        endforeach;
                    endif;
                    ?>

                </ul>
            </div>
        </div>

        <div class="col-lg-9 col-md-8">
            <div class="row g-3">

                <div class="col-lg-8">
                    <a href="#combo-xin" class="d-block">
                        <img src="./img/banner3.png" class="img-fluid main-banner-img" alt="Banner Combo Xịn Sale 40%">
                    </a>
                </div>

                <div class="col-lg-4">
                    <div class="d-flex flex-column justify-content-between h-100">
                        <a href="#deal-khung" class="d-block mb-3 h-50">
                            <img src="./img/banner1.png" class="img-fluid sub-banner-img h-100" alt="Banner Deal Khủng">
                        </a>

                        <a href="#freeship" class="d-block h-50">
                            <img src="./img/banner2.png" class="img-fluid sub-banner-img h-100" alt="Banner Freeship">
                        </a>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>
<style>
    .sidebar-menu {
        border: 1px solid #e0e0e0;
        height: 100%;
        overflow: hidden;
    }

    .sidebar-menu .list-group-item {
        border-left: none !important;
        border-right: none !important;
        border-radius: 0 !important;
        padding: 10px 15px;
        font-size: 14.5px;
    }

    .sidebar-menu .list-group-item:hover {
        background-color: #f5f5f5;
        cursor: pointer;
    }

    .sidebar-link {
        color: #333 !important;
    }

    .main-banner-img,
    .sub-banner-img {
        width: 100%;
        border-radius: 0 !important;
    }

    .col-lg-4>.d-flex {
        height: 100%;
    }

    .sub-banner-img {
        object-fit: cover;
    }

    .col-lg-4 .mb-3 {
        margin-bottom: 12px !important;
    }

    .col-lg-4 .d-flex a:last-child {
        margin-bottom: 0 !important;
    }
</style>