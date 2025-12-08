<?php
require_once 'config/connect.php';

// 1. LẤY MENU NGANG
$menu_items_db = [];
try {
    $stmt_menu = $pdo->query("SELECT * FROM menus ORDER BY sort_order ASC");
    $menu_items_db = $stmt_menu->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
}

// 2. LẤY DANH MỤC ĐA CẤP
$stmt_cat = $pdo->query("SELECT * FROM categories ORDER BY sort_order ASC, id ASC");
$all_cats = $stmt_cat->fetchAll(PDO::FETCH_ASSOC);

$category_tree = [];

// Xây dựng cây thư mục (3 bước)
foreach ($all_cats as $key => $cat) {
    if ($cat['parent_id'] == 0) {
        $category_tree[$cat['id']] = $cat;
        $category_tree[$cat['id']]['children'] = [];
        unset($all_cats[$key]);
    }
}
foreach ($all_cats as $key => $cat) {
    $pId = $cat['parent_id'];
    if (isset($category_tree[$pId])) {
        $cat['sub_items'] = [];
        $category_tree[$pId]['children'][$cat['id']] = $cat;
        unset($all_cats[$key]);
    }
}
foreach ($all_cats as $cat) {
    foreach ($category_tree as $rootId => $root) {
        $pId = $cat['parent_id'];
        if (isset($root['children'][$pId])) {
            $category_tree[$rootId]['children'][$pId]['sub_items'][] = $cat;
        }
    }
}
?>

<link rel="stylesheet" href="css/menu.css">

<div class="nav-wrapper sticky-top" style="background: #fff; border-bottom: 1px solid #eee; z-index: 1000;">
    <div class="container h-100">
        <nav class="navbar navbar-expand-lg p-0 h-100 position-relative">

            <div class="category-dropdown-wrapper">
                <div class="category-box">
                    <i class="bi bi-list fs-4 me-2"></i> DANH MỤC SẢN PHẨM
                </div>

                <ul class="vertical-menu">
                    <?php foreach ($category_tree as $cat): ?>
                        <?php $has_child = !empty($cat['children']); ?>

                        <li class="v-menu-item">
                            <a href="list_products.php?cat_id=<?= $cat['id'] ?>" class="v-menu-link">
                                <?php if (!empty($cat['image'])): ?>
                                    <img src="<?= $cat['image'] ?>" alt="icon" style="width: 20px; margin-right: 8px;">
                                <?php endif; ?>

                                <span><?= htmlspecialchars($cat['name']) ?></span>

                                <?php if ($has_child): ?>
                                    <i class="bi bi-chevron-right small float-end"
                                        style="font-size: 12px; margin-top: 5px;"></i>
                                <?php endif; ?>
                            </a>

                            <?php if ($has_child): ?>
                                <div class="mega-menu-container">
                                    <div class="row">
                                        <?php foreach ($cat['children'] as $child): ?>
                                            <div class="col-4 mb-3">
                                                <h6 class="mega-group-title">
                                                    <a href="list_products.php?cat_id=<?= $child['id'] ?>"
                                                        class="text-dark text-decoration-none fw-bold">
                                                        <?= htmlspecialchars($child['name']) ?>
                                                    </a>
                                                </h6>

                                                <ul class="mega-list list-unstyled ps-0">
                                                    <?php if (!empty($child['sub_items'])): ?>
                                                        <?php foreach ($child['sub_items'] as $sub): ?>
                                                            <li>
                                                                <a href="list_products.php?cat_id=<?= $sub['id'] ?>"
                                                                    class="text-secondary text-decoration-none" style="font-size: 13px;">
                                                                    <?= htmlspecialchars($sub['name']) ?>
                                                                </a>
                                                            </li>
                                                        <?php endforeach; ?>
                                                    <?php endif; ?>
                                                </ul>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>

                    <li class="v-menu-item flex-grow-1 bg-white"></li>
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