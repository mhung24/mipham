<?php
// Tên file: reviews_admin.php
$active_page = 'reviews';
require_once 'config/connect.php';

if (!isset($pdo)) {
    die("Lỗi: Không tìm thấy biến kết nối \$pdo.");
}

$page_title = "Quản Lý Đánh Giá Sản Phẩm";
$reviews = [];
$limit = 20;
$page = isset($_GET['p']) ? (int) $_GET['p'] : 1;
if ($page < 1)
    $page = 1;
$start = ($page - 1) * $limit;
$total_pages = 1;

// --- Bộ lọc ---
$filter_status = $_GET['status'] ?? 'pending';
$where_clauses = [];
$bind_params = [];

$all_statuses = [
    'pending' => ['text' => 'Chờ duyệt', 'class' => 'warning'],
    'approved' => ['text' => 'Đã duyệt', 'class' => 'success'],
    'hidden' => ['text' => 'Đã ẩn', 'class' => 'secondary']
];

// Chỉ thêm điều kiện WHERE nếu filter_status là hợp lệ
if (!empty($filter_status) && array_key_exists($filter_status, $all_statuses)) {
    $where_clauses[] = "pr.status = :status";
    $bind_params[':status'] = $filter_status;
} else {
    // Nếu status không hợp lệ hoặc rỗng, mặc định hiển thị 'pending'
    $filter_status = 'pending';
    $where_clauses[] = "pr.status = :status";
    $bind_params[':status'] = $filter_status;
}

$where_sql = " WHERE " . implode(' AND ', $where_clauses);

function render_stars($rating)
{
    return str_repeat('⭐', (int) $rating);
}

function get_status_badge($status)
{
    global $all_statuses;
    $info = $all_statuses[$status] ?? ['text' => 'Không rõ', 'class' => 'danger'];
    $text_class = ($info['class'] === 'warning' || $info['class'] === 'light') ? 'text-dark' : '';
    return '<span class="badge bg-' . $info['class'] . ' ' . $text_class . '">' . $info['text'] . '</span>';
}


try {
    $count_sql = "SELECT COUNT(id) FROM product_reviews pr" . $where_sql;
    $count_stmt = $pdo->prepare($count_sql);
    $count_stmt->execute($bind_params);
    $total_results = $count_stmt->fetchColumn();
    $total_pages = ceil($total_results / $limit);

    if ($page > $total_pages && $total_pages > 0) {
        $page = $total_pages;
        $start = ($page - 1) * $limit;
    }

    $sql = "
        SELECT 
            pr.id, 
            pr.user_name, 
            pr.rating, 
            pr.comment, 
            pr.comment_date,
            p.name AS product_name
        FROM 
            product_reviews pr
        LEFT JOIN
            products p ON pr.product_id = p.id
        " . $where_sql . "
        ORDER BY 
            pr.comment_date DESC
        LIMIT :start, :limit
    ";

    $stmt = $pdo->prepare($sql);
    foreach ($bind_params as $key => $value) {
        $stmt->bindValue($key, $value);
    }
    $stmt->bindValue(':start', $start, PDO::PARAM_INT);
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);

    $stmt->execute();
    $reviews = $stmt->fetchAll();

} catch (\PDOException $e) {
    error_log("Lỗi truy vấn Đánh giá: " . $e->getMessage());
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

        .review-comment {
            max-width: 300px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
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

            <?php if (isset($_GET['review_action'])): ?>
                <script>
                    window.onload = function () {
                        const action = '<?php echo htmlspecialchars($_GET['review_action']); ?>';
                        const name = '<?php echo htmlspecialchars($_GET['name'] ?? "Đánh giá"); ?>';
                        const newStatus = '<?php echo htmlspecialchars($_GET['status'] ?? ""); ?>';

                        if (typeof showToast === 'function') {
                            if (action === 'success') {
                                showToast('success', 'Đã cập nhật "' + name + '" thành trạng thái: ' + newStatus);
                            } else if (action === 'delete_success') {
                                showToast('warning', 'Đã xóa đánh giá thành công!');
                            } else if (action === 'error') {
                                const errorMsg = '<?php echo htmlspecialchars($_GET['msg'] ?? "Có lỗi xảy ra."); ?>';
                                showToast('error', errorMsg);
                            }
                        }
                    };
                </script>
            <?php endif; ?>

            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Danh sách Đánh giá (<?php echo $total_results; ?>)
                    </h6>
                </div>
                <div class="card-body">

                    <form method="get" class="row g-3 mb-4 align-items-center">
                        <div class="col-md-3">
                            <select name="status" class="form-select" onchange="this.form.submit()">
                                <option value="">-- Lọc theo trạng thái --</option>
                                <?php foreach ($all_statuses as $key => $info): ?>
                                    <option value="<?php echo $key; ?>" <?php echo ($filter_status === $key) ? 'selected' : ''; ?>>
                                        <?php echo $info['text']; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <?php if (!empty($filter_status) && $filter_status !== 'pending'): ?>
                            <div class="col-md-auto">
                                <a href="reviews_admin.php" class="btn btn-outline-secondary">Xem Chờ Duyệt</a>
                            </div>
                        <?php endif; ?>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 50px;">ID</th>
                                    <th style="width: 100px;">Sản phẩm</th>
                                    <th style="width: 100px;">Khách hàng</th>
                                    <th style="width: 100px;">Đánh giá</th>
                                    <th>Nội dung</th>
                                    <th style="width: 120px;">Ngày gửi</th>
                                    <th style="width: 150px;">Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($reviews)): ?>
                                    <tr>
                                        <td colspan="7" class="text-center py-4 text-muted">Không tìm thấy đánh giá nào với
                                            trạng thái
                                            **<?php echo $all_statuses[$filter_status]['text'] ?? 'Mặc định'; ?>**.</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($reviews as $review): ?>
                                        <tr>
                                            <td>#<?php echo $review['id']; ?></td>
                                            <td><span
                                                    class="text-primary small"><?php echo htmlspecialchars($review['product_name'] ?? 'Sản phẩm đã xóa'); ?></span>
                                            </td>
                                            <td><?php echo htmlspecialchars($review['user_name']); ?></td>
                                            <td class="text-warning"><?php echo render_stars($review['rating']); ?></td>
                                            <td title="<?php echo htmlspecialchars($review['comment']); ?>"
                                                class="review-comment">
                                                <?php echo htmlspecialchars(substr($review['comment'], 0, 80)); ?>...
                                            </td>
                                            <td><?php echo date('d/m/Y', strtotime($review['comment_date'])); ?></td>
                                            <td>
                                                <?php if ($filter_status === 'pending'): ?>
                                                    <a href="review_process.php?action_type=approve&id=<?php echo $review['id']; ?>"
                                                        class="btn btn-sm btn-success" title="Duyệt">
                                                        <i class="fas fa-check"></i>
                                                    </a>
                                                    <a href="review_process.php?action_type=hide&id=<?php echo $review['id']; ?>"
                                                        class="btn btn-sm btn-secondary" title="Ẩn (Chuyển sang Hidden)">
                                                        <i class="fas fa-eye-slash"></i>
                                                    </a>
                                                <?php elseif ($filter_status === 'approved'): ?>
                                                    <a href="review_process.php?action_type=hide&id=<?php echo $review['id']; ?>"
                                                        class="btn btn-sm btn-secondary" title="Ẩn">
                                                        <i class="fas fa-eye-slash"></i>
                                                    </a>
                                                <?php elseif ($filter_status === 'hidden'): ?>
                                                    <a href="review_process.php?action_type=approve&id=<?php echo $review['id']; ?>"
                                                        class="btn btn-sm btn-success" title="Duyệt">
                                                        <i class="fas fa-check"></i>
                                                    </a>
                                                <?php endif; ?>

                                                <button type="button"
                                                    onclick="confirmDeleteReview(<?php echo $review['id']; ?>)"
                                                    class="btn btn-sm btn-danger" title="Xóa">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <?php if ($total_results > 0): ?>
                        <nav aria-label="Phân trang">
                            <ul class="pagination justify-content-center">
                                <?php
                                $current_query_params = array_filter(['status' => $filter_status]);
                                $query_string = http_build_query($current_query_params);
                                $url_base = 'reviews_admin.php?' . $query_string . (empty($query_string) ? '' : '&') . 'p=';
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

                                for ($i = $start_page; $i <= $end_page; $i++):
                                    ?>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('sidebarCollapse').addEventListener('click', function () {
            document.getElementById('sidebar').classList.toggle('active');
        });

        function confirmDeleteReview(reviewId) {
            if (confirm("Bạn có chắc chắn muốn xóa đánh giá này?")) {
                window.location.href = 'review_process.php?action_type=delete&id=' + reviewId;
            }
        }
    </script>
</body>

</html>