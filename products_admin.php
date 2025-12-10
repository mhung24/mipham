<?php
require_once 'admin_guard.php';
$active_page = 'products';
require_once 'config/connect.php';

$page_title = "Quản Lý Sản Phẩm";
$products = [];
$limit = 20;
$page = isset($_GET['p']) ? (int) $_GET['p'] : 1;
if ($page < 1)
    $page = 1;

$start = ($page - 1) * $limit;
$total_pages = 1;
$search_term = isset($_GET['search']) ? trim($_GET['search']) : '';
$where_clause = '';

if (!empty($search_term)) {
    $where_clause = " WHERE p.name LIKE :search_term ";
}

try {
    $count_sql = "SELECT COUNT(id) FROM products p" . $where_clause;
    $count_stmt = $pdo->prepare($count_sql);
    if (!empty($search_term)) {
        $count_stmt->bindValue(':search_term', '%' . $search_term . '%', PDO::PARAM_STR);
    }
    $count_stmt->execute();
    $total_results = $count_stmt->fetchColumn();
    $total_pages = ceil($total_results / $limit);

    if ($page > $total_pages && $total_pages > 0) {
        $page = $total_pages;
        $start = ($page - 1) * $limit;
    }

    $sql = "
        SELECT 
            p.id, 
            p.name, 
            p.price, 
            p.old_price, 
            p.stock_quantity, 
            p.status,
            (
                SELECT image_url 
                FROM product_gallery pg 
                WHERE pg.product_id = p.id 
                ORDER BY pg.id ASC 
                LIMIT 1
            ) AS main_image_url
        FROM 
            products p
        " . $where_clause . "
        ORDER BY 
            p.created_at DESC
        LIMIT :start, :limit
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':start', $start, PDO::PARAM_INT);
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    if (!empty($search_term)) {
        $stmt->bindValue(':search_term', '%' . $search_term . '%', PDO::PARAM_STR);
    }
    $stmt->execute();
    $products = $stmt->fetchAll();

} catch (\PDOException $e) {
    error_log("Lỗi truy vấn Sản phẩm: " . $e->getMessage());
}

function format_vnd($amount)
{
    return number_format($amount, 0, ',', '.') . 'đ';
}

function get_status_badge($status)
{
    switch ($status) {
        case 'published':
            return '<span class="badge bg-success">Đang bán</span>';
        case 'draft':
            return '<span class="badge bg-secondary">Nháp</span>';
        case 'out_of_stock':
            return '<span class="badge bg-danger">Hết hàng</span>';
        default:
            return '<span class="badge bg-info">' . ucfirst($status) . '</span>';
    }
}
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --sidebar-width: 260px;
            --primary-color: #4e73df;
            --text-light: #f8f9fc;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f3f4f6;
        }

        #sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            background: #2c3e50;
            color: var(--text-light);
            transition: all 0.3s;
            z-index: 1000;
            overflow-y: auto;
        }

        #sidebar .logo {
            padding: 20px;
            font-size: 1.5rem;
            font-weight: bold;
            border-bottom: 1px solid #34495e;
            color: #fff;
        }

        #sidebar ul {
            list-style: none;
            padding: 0;
            margin-bottom: 0;
        }

        #sidebar ul li a {
            display: block;
            padding: 15px 20px;
            color: #b0b8c5;
            text-decoration: none;
            border-left: 4px solid transparent;
        }

        #sidebar ul li a:hover,
        #sidebar ul li a.active {
            background: #34495e;
            color: #fff;
            border-left-color: var(--primary-color);
        }

        #sidebar ul li a i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }

        #content {
            margin-left: var(--sidebar-width);
            transition: all 0.3s;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .top-navbar {
            background: #fff;
            padding: 15px 30px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 999;
        }

        .product-img {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 5px;
        }

        @media (max-width: 768px) {
            #sidebar {
                margin-left: calc(var(--sidebar-width) * -1);
            }

            #sidebar.active {
                margin-left: 0;
            }

            #content {
                margin-left: 0;
            }
        }
    </style>
</head>

<body>

    <?php include 'sidebar_admin.php'; ?>

    <div id="content">

        <div class="top-navbar">
            <button class="btn btn-outline-secondary d-md-none" id="sidebarCollapse">
                <i class="fas fa-bars"></i>
            </button>
            <h5 class="m-0"><?php echo $page_title; ?></h5>
            <div class="user-info d-flex align-items-center">
                <span class="me-2">Xin chào, <strong>Admin</strong></span>
                <img src="https://via.placeholder.com/40" class="rounded-circle" alt="Admin">
            </div>
        </div>

        <div class="container-fluid p-4">

            <?php if (isset($_GET['delete'])): ?>
                <script>
                    window.onload = function () {
                        const deleteStatus = '<?php echo htmlspecialchars($_GET['delete']); ?>';
                        if (deleteStatus === 'success') {
                            if (typeof showToast === 'function') {
                                showToast('success', 'Đã xóa sản phẩm thành công!');
                            } else {
                                console.log('Đã xóa sản phẩm thành công!');
                            }
                        } else if (deleteStatus === 'error') {
                            const errorMsg = '<?php echo htmlspecialchars($_GET['msg'] ?? "Có lỗi xảy ra khi xóa sản phẩm."); ?>';
                            if (typeof showToast === 'function') {
                                showToast('error', errorMsg);
                            } else {
                                alert('Lỗi xóa: ' + errorMsg);
                            }
                        }
                    };
                </script>
            <?php endif; ?>

            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Danh sách Sản phẩm (Tổng cộng:
                        <?php echo $total_results; ?>)
                    </h6>
                    <a href="add_product_admin.php" class="btn btn-primary btn-sm"><i class="fas fa-plus me-1"></i> Thêm
                        mới</a>
                </div>
                <div class="card-body">

                    <form method="get" class="row g-3 mb-4 align-items-center">
                        <div class="col-md-4">
                            <input type="text" name="search" class="form-control" placeholder="Tìm theo tên sản phẩm..."
                                value="<?php echo htmlspecialchars($search_term); ?>">
                        </div>
                        <div class="col-md-auto">
                            <button type="submit" class="btn btn-secondary"><i class="fas fa-search me-1"></i> Tìm
                                kiếm</button>
                        </div>
                        <?php if (!empty($search_term)): ?>
                            <div class="col-md-auto">
                                <a href="products_admin.php" class="btn btn-outline-secondary">Xóa tìm kiếm</a>
                            </div>
                        <?php endif; ?>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Ảnh</th>
                                    <th>Tên sản phẩm</th>
                                    <th>Giá (Giá cũ)</th>
                                    <th>Tồn kho</th>
                                    <th>Trạng thái</th>
                                    <th style="width: 150px;">Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($products)): ?>
                                    <tr>
                                        <td colspan="6" class="text-center py-4 text-muted">Không tìm thấy sản phẩm nào.
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($products as $product): ?>
                                        <tr>
                                            <td>
                                                <img src="<?php echo htmlspecialchars($product['main_image_url'] ?: 'https://via.placeholder.com/50x50?text=NoImg'); ?>"
                                                    alt="<?php echo htmlspecialchars($product['name']); ?>" class="product-img">
                                            </td>
                                            <td><?php echo htmlspecialchars($product['name']); ?></td>
                                            <td>
                                                <strong><?php echo format_vnd($product['price']); ?></strong><br>
                                                <?php
                                                if (isset($product['old_price']) && $product['old_price'] > $product['price']): ?>
                                                    <del
                                                        class="text-muted small"><?php echo format_vnd($product['old_price']); ?></del>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if ($product['stock_quantity'] < 10 && $product['stock_quantity'] > 0): ?>
                                                    <span
                                                        class="text-warning fw-bold"><?php echo $product['stock_quantity']; ?></span>
                                                <?php elseif ($product['stock_quantity'] <= 0): ?>
                                                    <span class="text-danger fw-bold">0</span>
                                                <?php else: ?>
                                                    <?php echo $product['stock_quantity']; ?>
                                                <?php endif; ?>
                                            </td>
                                            <td><?php echo get_status_badge($product['status']); ?></td>
                                            <td>
                                                <a href="edit_product_admin.php?id=<?php echo $product['id']; ?>"
                                                    class="btn btn-sm btn-info text-white" title="Chỉnh sửa">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button type="button"
                                                    onclick="showDeleteModal(<?php echo $product['id']; ?>, '<?php echo htmlspecialchars($product['name'], ENT_QUOTES); ?>')"
                                                    class="btn btn-sm btn-danger" title="Xóa" data-bs-toggle="modal"
                                                    data-bs-target="#confirmDeleteModal">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <?php if ($total_results > 0): // Hiển thị nếu có ít nhất 1 sản phẩm ?>
                        <nav aria-label="Phân trang">
                            <ul class="pagination justify-content-center">
                                <?php
                                $current_query = http_build_query(['search' => $search_term]);
                                $url_base = 'products_admin.php?' . $current_query . '&p=';
                                ?>
                                <li class="page-item <?php echo ($page <= 1) ? 'disabled' : ''; ?>">
                                    <a class="page-link" href="<?php echo $url_base . ($page - 1); ?>"
                                        aria-label="Previous">
                                        <span aria-hidden="true">&laquo;</span>
                                    </a>
                                </li>

                                <?php
                                $start_page = max(1, $page - 2);
                                $end_page = min($total_pages, $page + 2);

                                if ($end_page - $start_page < 4 && $page <= 3) {
                                    $end_page = min($total_pages, $start_page + 4);
                                }
                                if ($end_page - $start_page < 4 && $page > $total_pages - 3) {
                                    $start_page = max(1, $end_page - 4);
                                }
                                ?>

                                <?php for ($i = $start_page; $i <= $end_page; $i++): ?>
                                    <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
                                        <a class="page-link" href="<?php echo $url_base . $i; ?>"><?php echo $i; ?></a>
                                    </li>
                                <?php endfor; ?>

                                <li class="page-item <?php echo ($page >= $total_pages) ? 'disabled' : ''; ?>">
                                    <a class="page-link" href="<?php echo $url_base . ($page + 1); ?>" aria-label="Next">
                                        <span aria-hidden="true">&raquo;</span>
                                    </a>
                                </li>
                            </ul>
                        </nav>
                    <?php endif; ?>

                </div>
            </div>

        </div>
    </div>

    <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="confirmDeleteModalLabel"><i
                            class="fas fa-exclamation-triangle me-2"></i> Xác nhận xóa</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Bạn có chắc chắn muốn xóa sản phẩm: <strong><span id="productNameDelete"></span></strong>?
                    <br>
                    <small class="text-danger">Lưu ý: Hành động này không thể hoàn tác.</small>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <a href="#" id="confirmDeleteButton" class="btn btn-danger">Xóa Vĩnh Viễn</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('sidebarCollapse').addEventListener('click', function () {
            document.getElementById('sidebar').classList.toggle('active');
        });

        function showDeleteModal(productId, productName) {
            document.getElementById('productNameDelete').textContent = productName;

            const deleteUrl = 'delete_product_admin.php?id=' + productId;
            document.getElementById('confirmDeleteButton').setAttribute('href', deleteUrl);
        }
    </script>
</body>

</html>