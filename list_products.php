<?php
// 1. KẾT NỐI DATABASE
require_once 'config/connect.php';

// ====================================================
// A. LẤY THAM SỐ & CẤU HÌNH
// ====================================================
$keyword = isset($_GET['q']) ? trim($_GET['q']) : '';
$cat_id = isset($_GET['cat_id']) ? intval($_GET['cat_id']) : 0;
$brand_id = isset($_GET['brand_id']) ? intval($_GET['brand_id']) : 0;

// Lấy tham số sắp xếp (Mặc định là 'new' - Hàng mới)
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'new';

// Cấu hình phân trang
$limit = 30;
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
if ($page < 1)
    $page = 1;

// ====================================================
// B. HÀM HỖ TRỢ TẠO LINK (QUAN TRỌNG)
// ====================================================
// Hàm này giúp tạo URL giữ nguyên các bộ lọc cũ, chỉ thay đổi tham số cần thiết
function create_url($new_params = [])
{
    $params = $_GET; // Lấy tất cả tham số hiện tại trên URL (q, cat_id, brand_id...)

    // Gộp tham số mới vào
    foreach ($new_params as $key => $value) {
        $params[$key] = $value;
    }

    // Nếu thay đổi bộ lọc hoặc sắp xếp thì reset về trang 1
    if (isset($new_params['sort']) || isset($new_params['cat_id']) || isset($new_params['brand_id'])) {
        $params['page'] = 1;
    }

    return 'list_products.php?' . http_build_query($params);
}

// ====================================================
// C. LẤY DỮ LIỆU SIDEBAR
// ====================================================
$sidebar_cats = [];
try {
    $stmt_c1 = $pdo->query("SELECT id, name FROM categories WHERE parent_id = 0 ORDER BY sort_order ASC");
    $sidebar_cats = $stmt_c1->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
}

$sidebar_brands = [];
try {
    $stmt_brands = $pdo->query("SELECT id, name FROM brands ORDER BY name ASC");
    $sidebar_brands = $stmt_brands->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
}

// ====================================================
// D. XÂY DỰNG SQL (WHERE & ORDER BY)
// ====================================================
$page_title = 'Tất cả sản phẩm';
$where_sql = " WHERE 1=1 ";
$params = [];

// 1. Xử lý điều kiện lọc (WHERE)
if (!empty($keyword)) {
    $page_title = 'Tìm kiếm: "' . htmlspecialchars($keyword) . '"';
    $where_sql .= " AND p.name LIKE ? ";
    $params[] = "%$keyword%";
} elseif ($brand_id > 0) {
    try {
        $stmt_name = $pdo->prepare("SELECT name FROM brands WHERE id = ?");
        $stmt_name->execute([$brand_id]);
        $b_name = $stmt_name->fetchColumn();
        if ($b_name)
            $page_title = "Thương hiệu: " . $b_name;
    } catch (Exception $e) {
    }
    $where_sql .= " AND p.brand_id = ? ";
    $params[] = $brand_id;
} elseif ($cat_id > 0) {
    try {
        $stmt_name = $pdo->prepare("SELECT name FROM categories WHERE id = ?");
        $stmt_name->execute([$cat_id]);
        $c_name = $stmt_name->fetchColumn();
        if ($c_name)
            $page_title = $c_name;
    } catch (Exception $e) {
    }
    $where_sql .= " AND (p.category_id = ? OR p.category_id IN (SELECT id FROM categories WHERE parent_id = ?)) ";
    $params[] = $cat_id;
    $params[] = $cat_id;
}

// 2. Xử lý sắp xếp (ORDER BY) - LOGIC MỚI
switch ($sort) {
    case 'price_asc': // Giá thấp -> cao
        $order_sql = " ORDER BY p.price ASC ";
        break;
    case 'price_desc': // Giá cao -> thấp
        $order_sql = " ORDER BY p.price DESC ";
        break;
    case 'best_sell': // Bán chạy (Nếu chưa có cột sold thì tạm thời để ID)
        // $order_sql = " ORDER BY p.sold_count DESC "; 
        $order_sql = " ORDER BY p.id DESC ";
        break;
    case 'new': // Hàng mới (Mặc định)
    default:
        $order_sql = " ORDER BY p.id DESC ";
        break;
}

// ====================================================
// E. TRUY VẤN DỮ LIỆU
// ====================================================
// 1. Đếm tổng
$total_records = 0;
try {
    $sql_count = "SELECT COUNT(p.id) FROM products p " . $where_sql;
    $stmt_count = $pdo->prepare($sql_count);
    $stmt_count->execute($params);
    $total_records = $stmt_count->fetchColumn();
} catch (Exception $e) {
    $total_records = 0;
}

// 2. Phân trang
$total_pages = ceil($total_records / $limit);
if ($page > $total_pages && $total_pages > 0)
    $page = $total_pages;
$offset = ($page - 1) * $limit;

// 3. Lấy danh sách sản phẩm
$products = [];
if ($total_records > 0) {
    $sql = "SELECT p.*, 
            (SELECT image_url FROM product_gallery WHERE product_id = p.id LIMIT 1) as thumbnail 
            FROM products p 
            $where_sql 
            $order_sql 
            LIMIT $limit OFFSET $offset";
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        $products = [];
    }
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($page_title) ?> | Cocolux Clone</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <link rel="stylesheet" href="css/menu.css">
    <link rel="stylesheet" href="css/product_list.css">
    <link rel="stylesheet" href="css/footer.css">
</head>

<body style="background-color: #fff;">

    <?php if (file_exists('header.php'))
        include 'header.php'; ?>
    <?php if (file_exists('menu.php'))
        include 'menu.php'; ?>

    <div class="container py-3">

        <div class="breadcrumb-wrap">
            <a href="index.php" class="text-secondary text-decoration-none"><i class="fa fa-home"></i> Trang chủ</a>
            <span class="mx-1">></span>
            <strong class="text-danger"><?= htmlspecialchars($page_title) ?></strong>
        </div>

        <div class="row mt-3">

            <div class="col-lg-3 d-none d-lg-block filter-sidebar">
                <h5 class="text-uppercase"><i class="fa fa-filter"></i> Bộ lọc tìm kiếm</h5>

                <div class="filter-group">
                    <div class="filter-title">DANH MỤC</div>
                    <ul class="filter-list">
                        <li>
                            <a href="<?= create_url(['cat_id' => 0, 'brand_id' => 0]) ?>"
                                class="<?= ($cat_id == 0 && $brand_id == 0) ? 'fw-bold text-danger' : '' ?>">
                                Tất cả danh mục
                            </a>
                        </li>
                        <?php foreach ($sidebar_cats as $c1): ?>
                            <li>
                                <a href="<?= create_url(['cat_id' => $c1['id'], 'brand_id' => 0]) ?>"
                                    class="<?= ($cat_id == $c1['id']) ? 'fw-bold text-danger' : '' ?>">
                                    <?= htmlspecialchars($c1['name']) ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <div class="filter-group">
                    <div class="filter-title">THƯƠNG HIỆU</div>
                    <ul class="filter-list" style="max-height: 250px; overflow-y: auto;">
                        <?php if (!empty($sidebar_brands)): ?>
                            <?php foreach ($sidebar_brands as $brand): ?>
                                <li>
                                    <a href="<?= create_url(['brand_id' => $brand['id'], 'cat_id' => 0]) ?>"
                                        class="<?= ($brand_id == $brand['id']) ? 'fw-bold text-danger' : '' ?>">
                                        <?= htmlspecialchars($brand['name']) ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <li><span class="text-muted small">Chưa có dữ liệu hãng</span></li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>

            <div class="col-lg-9" style="min-height: 600px;">

                <div class="result-header">
                    <span class="result-count">(<?= $total_records ?> KẾT QUẢ)</span>
                </div>

                <div class="sort-bar-wrapper bg-light p-2 mb-3 rounded border">
                    <div class="d-flex align-items-center flex-wrap gap-2">
                        <span class="fw-bold fs-6 ms-2" style="font-size: 13px;">Sắp xếp theo:</span>

                        <a href="<?= create_url(['sort' => 'new']) ?>"
                            class="btn btn-white border btn-sm text-dark <?= ($sort == 'new') ? 'active' : '' ?>">
                            Nổi bật
                        </a>

                        <a href="<?= create_url(['sort' => 'best_sell']) ?>"
                            class="btn btn-white border btn-sm text-dark <?= ($sort == 'best_sell') ? 'active' : '' ?>">
                            Bán chạy
                        </a>

                        <a href="<?= create_url(['sort' => 'new']) ?>"
                            class="btn btn-white border btn-sm text-dark <?= ($sort == 'new') ? 'active' : '' ?>">
                            Hàng mới
                        </a>

                        <a href="<?= create_url(['sort' => 'price_asc']) ?>"
                            class="btn btn-white border btn-sm text-dark <?= ($sort == 'price_asc') ? 'active' : '' ?>">
                            Giá thấp - cao
                        </a>

                        <a href="<?= create_url(['sort' => 'price_desc']) ?>"
                            class="btn btn-white border btn-sm text-dark <?= ($sort == 'price_desc') ? 'active' : '' ?>">
                            Giá cao - thấp
                        </a>
                    </div>
                </div>

                <div class="row row-cols-2 row-cols-md-3 row-cols-lg-5 g-2">
                    <?php if (empty($products)): ?>
                        <div class="col-12 text-center py-5">
                            <p class="text-muted mb-3">Không tìm thấy sản phẩm nào phù hợp.</p>
                            <a href="index.php" class="btn btn-danger">Về trang chủ</a>
                        </div>
                    <?php else: ?>
                        <?php foreach ($products as $row): ?>
                            <?php
                            $discount = 0;
                            if ($row['old_price'] > $row['price']) {
                                $discount = round((($row['old_price'] - $row['price']) / $row['old_price']) * 100);
                            }
                            $imgSrc = !empty($row['thumbnail']) ? $row['thumbnail'] : 'https://via.placeholder.com/300x300?text=No+Image';
                            $qty = isset($row['quantity']) ? $row['quantity'] : 1;
                            $is_out_of_stock = ($qty <= 0);
                            ?>
                            <div class="col">
                                <div class="coco-card h-100 position-relative">
                                    <div class="badge-freeship">FREESHIP</div>
                                    <?php if ($discount > 0): ?>
                                        <div class="badge-percent">-<?= $discount ?>%</div>
                                    <?php endif; ?>

                                    <a href="product_detail.php?id=<?= $row['id'] ?>" class="coco-img-wrapper d-block">
                                        <img src="<?= htmlspecialchars($imgSrc) ?>" alt="<?= htmlspecialchars($row['name']) ?>">
                                        <?php if ($is_out_of_stock): ?>
                                            <div class="out-of-stock-overlay"><span class="stock-label">Tạm hết hàng</span></div>
                                        <?php endif; ?>
                                    </a>

                                    <div class="card-body">
                                        <div class="mb-1">
                                            <span
                                                class="coco-price-new"><?= number_format($row['price'], 0, ',', '.') ?>đ</span>
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
                    <?php endif; ?>
                </div>

                <?php if ($total_pages > 0): ?>
                    <div class="d-flex justify-content-center mt-5">
                        <nav aria-label="Page navigation">
                            <ul class="pagination pagination-sm">
                                <li class="page-item <?= ($page <= 1) ? 'disabled' : '' ?>">
                                    <a class="page-link text-dark" href="<?= create_url(['page' => $page - 1]) ?>">
                                        <i class="fa fa-angle-left"></i>
                                    </a>
                                </li>
                                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                    <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                                        <a class="page-link <?= ($i == $page) ? 'bg-danger border-danger' : 'text-dark' ?>"
                                            href="<?= create_url(['page' => $i]) ?>">
                                            <?= $i ?>
                                        </a>
                                    </li>
                                <?php endfor; ?>
                                <li class="page-item <?= ($page >= $total_pages) ? 'disabled' : '' ?>">
                                    <a class="page-link text-dark" href="<?= create_url(['page' => $page + 1]) ?>">
                                        <i class="fa fa-angle-right"></i>
                                    </a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                <?php endif; ?>

            </div>
        </div>
    </div>

    <?php if (file_exists('footer.php'))
        include 'footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>