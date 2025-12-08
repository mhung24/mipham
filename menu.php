<?php
require_once 'config/connect.php';

// 1. LẤY MENU NGANG TỪ DB
$menu_items_db = [];
try {
    $stmt_menu = $pdo->query("SELECT * FROM menus ORDER BY sort_order ASC");
    $menu_items_db = $stmt_menu->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
}

// 2. LẤY DANH MỤC ĐA CẤP (3 Level)
$stmt_cat = $pdo->query("SELECT * FROM categories ORDER BY sort_order ASC, id ASC");
$all_cats = $stmt_cat->fetchAll(PDO::FETCH_ASSOC);

$category_tree = [];

// Cấp 1: Cha
foreach ($all_cats as $cat) {
    if ($cat['parent_id'] == 0) {
        $category_tree[$cat['id']] = $cat;
        $category_tree[$cat['id']]['groups'] = [];
    }
}

// Cấp 2: Nhóm con
foreach ($all_cats as $cat) {
    $pId = $cat['parent_id'];
    if (isset($category_tree[$pId])) {
        $cat['items'] = []; // Chuẩn bị chứa cấp 3
        $category_tree[$pId]['groups'][$cat['id']] = $cat;
    }
}

// Cấp 3: Mục con
foreach ($all_cats as $cat) {
    foreach ($category_tree as $rootId => $root) {
        $pId = $cat['parent_id'];
        if (isset($root['groups'][$pId])) {
            $category_tree[$rootId]['groups'][$pId]['items'][] = $cat;
        }
    }
}
?>

<link rel="stylesheet" href="css/menu.css">

<div class="nav-wrapper sticky-top">
    <div class="container h-100">
        <nav class="navbar navbar-expand-lg p-0 h-100 position-relative">

            <div class="category-dropdown-wrapper">
                <div class="category-box">
                    <i class="bi bi-list fs-4 me-2"></i> DANH MỤC SẢN PHẨM
                </div>

                <ul class="vertical-menu">
                    <?php foreach ($category_tree as $cat): ?>
                        <?php $has_sub = !empty($cat['groups']); ?>

                        <li class="v-menu-item">
                            <a href="list_products.php?cat_id=<?= $cat['id'] ?>" class="v-menu-link">
                                <span><?= htmlspecialchars($cat['name']) ?></span>
                                <?php if ($has_sub): ?>
                                    <i class="bi bi-chevron-right small float-end"></i>
                                <?php endif; ?>
                            </a>

                            <?php if ($has_sub): ?>
                                <div class="mega-menu-container">
                                    <div class="mega-tabs">
                                        <span class="active">Nổi bật</span>
                                        <span>Bán chạy</span>
                                        <span>Hàng mới</span>
                                    </div>

                                    <div class="row">
                                        <?php foreach ($cat['groups'] as $group): ?>
                                            <div class="col-6 mb-4">
                                                <h6 class="mega-group-title">
                                                    <a href="list_products.php?cat_id=<?= $group['id'] ?>">
                                                        <?= htmlspecialchars($group['name']) ?>
                                                    </a>
                                                </h6>

                                                <ul class="mega-list list-unstyled">
                                                    <?php if (!empty($group['items'])): ?>
                                                        <?php foreach ($group['items'] as $item): ?>
                                                            <li>
                                                                <a href="list_products.php?cat_id=<?= $item['id'] ?>">
                                                                    <?= htmlspecialchars($item['name']) ?>
                                                                </a>
                                                            </li>
                                                        <?php endforeach; ?>
                                                    <?php else: ?>
                                                        <li><a href="list_products.php?cat_id=<?= $group['id'] ?>"
                                                                class="text-muted">Xem tất cả...</a></li>
                                                    <?php endif; ?>
                                                </ul>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-3 align-items-center h-100">
                    <?php foreach ($menu_items_db as $item): ?>
                        <li class="nav-item h-100 d-flex align-items-center">
                            <a class="nav-link custom-link <?= ($item['is_flash'] == 1) ? 'flash-text' : '' ?>"
                                href="<?= $item['link'] ?>">
                                <?php if (!empty($item['icon'])): ?>
                                    <i class="bi <?= $item['icon'] ?> me-1"></i>
                                <?php endif; ?>
                                <?= htmlspecialchars($item['name']) ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>

        </nav>
    </div>
</div>