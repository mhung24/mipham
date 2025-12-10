<?php
// Tên file: admin.php (Dashboard)
$active_page = 'dashboard';
require_once 'config/connect.php';

$page_title = "Trang chủ Admin Dashboard";
$user_name = "Quản trị viên";
$total_revenue = "0đ";
$new_orders = 0;
$new_reviews_count = 0;
$total_members = 0;
$pending_reviews = [];

function format_vnd($amount)
{
    return number_format((float) $amount, 0, ',', '.') . 'đ';
}

try {
    // 1. TRUY VẤN DỮ LIỆU THỐNG KÊ (METRICS)

    // A. Tổng doanh thu (Các đơn hàng Đã Hoàn thành)
    $stmt = $pdo->query("SELECT SUM(total_money) AS total FROM orders WHERE status = 'Hoàn thành'");
    $total = $stmt->fetchColumn() ?? 0;
    $total_revenue = format_vnd($total);

    // B. Đơn hàng mới (Đang xử lý)
    $stmt = $pdo->query("SELECT COUNT(id) AS count FROM orders WHERE status = 'Đang xử lý'");
    $new_orders = $stmt->fetchColumn() ?? 0;

    // C. Đánh giá mới (Chờ duyệt: 'pending')
    $stmt = $pdo->query("SELECT COUNT(id) AS count FROM product_reviews WHERE status = 'pending'");
    $new_reviews_count = $stmt->fetchColumn() ?? 0;

    // D. Tổng Thành viên (role = 'user')
    $stmt = $pdo->query("SELECT COUNT(id) AS count FROM users WHERE role = 'user'");
    $total_members = $stmt->fetchColumn() ?? 0;

    // 2. TRUY VẤN DỮ LIỆU BẢNG "ĐÁNH GIÁ CẦN DUYỆT"
    $sql_pending_reviews = "
        SELECT
            pr.id, pr.user_name AS customer_name, p.name AS product_name, pr.rating, pr.comment
        FROM
            product_reviews pr
        LEFT JOIN
            products p ON pr.product_id = p.id
        WHERE
            pr.status = 'pending'
        ORDER BY
            pr.comment_date DESC
        LIMIT 5
    ";
    $stmt = $pdo->query($sql_pending_reviews);
    $pending_reviews = $stmt->fetchAll();

} catch (\PDOException $e) {
    error_log("Lỗi truy vấn Dashboard: " . $e->getMessage());
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

        .stat-card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            transition: transform 0.2s;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .icon-box {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
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
            <h5 class="m-0 d-none d-md-block">Dashboard</h5>
            <div class="user-info d-flex align-items-center">
                <span class="me-2">Xin chào, <strong><?php echo htmlspecialchars($user_name); ?></strong></span>
                <img src="https://via.placeholder.com/40" class="rounded-circle" alt="Admin">
            </div>
        </div>

        <div class="container-fluid p-4">

            <?php if (isset($_GET['add'])): ?>
                <script>
                    window.onload = function () {
                        const addStatus = '<?php echo htmlspecialchars($_GET['add']); ?>';
                        const productName = '<?php echo htmlspecialchars($_GET['name'] ?? "Sản phẩm"); ?>';
                        const errorMsg = '<?php echo htmlspecialchars($_GET['msg'] ?? "Có lỗi xảy ra."); ?>';

                        if (typeof showToast === 'function') {
                            if (addStatus === 'success') {
                                showToast('success', 'Đã thêm sản phẩm "' + productName + '" thành công!');
                            } else if (addStatus === 'error') {
                                showToast('error', errorMsg);
                            }
                        } else {
                            console.log('Thông báo:', addStatus === 'success' ? 'Thêm thành công' : 'Lỗi');
                        }

                        // Xóa tham số khỏi URL sau khi hiển thị
                        if (window.history.replaceState) {
                            const url = new URL(window.location.href);
                            url.searchParams.delete('add');
                            url.searchParams.delete('name');
                            url.searchParams.delete('msg');
                            window.history.replaceState({ path: url.href }, '', url.href);
                        }
                    };
                </script>
            <?php endif; ?>

            <div class="row mb-4">
                <div class="col-md-3 mb-3">
                    <div class="card stat-card p-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted mb-1">Tổng doanh thu</p>
                                <h4 class="mb-0"><?php echo $total_revenue; ?></h4>
                            </div>
                            <div class="icon-box bg-primary bg-opacity-10 text-primary">
                                <i class="fas fa-wallet"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card stat-card p-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted mb-1">Đơn hàng mới</p>
                                <h4 class="mb-0"><?php echo $new_orders; ?></h4>
                            </div>
                            <div class="icon-box bg-success bg-opacity-10 text-success">
                                <i class="fas fa-shopping-bag"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card stat-card p-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted mb-1">Đánh giá mới</p>
                                <h4 class="mb-0"><?php echo $new_reviews_count; ?></h4>
                            </div>
                            <div class="icon-box bg-warning bg-opacity-10 text-warning">
                                <i class="fas fa-star"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card stat-card p-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted mb-1">Thành viên</p>
                                <h4 class="mb-0"><?php echo $total_members; ?></h4>
                            </div>
                            <div class="icon-box bg-info bg-opacity-10 text-info">
                                <i class="fas fa-users"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Đánh giá cần duyệt</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Khách hàng</th>
                                    <th>Sản phẩm</th>
                                    <th>Đánh giá</th>
                                    <th>Nội dung</th>
                                    <th>Trạng thái</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($pending_reviews)): ?>
                                    <tr>
                                        <td colspan="6" class="text-center py-4 text-muted">Không có đánh giá mới nào cần
                                            duyệt.</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($pending_reviews as $review): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($review['customer_name']); ?></td>
                                            <td><?php echo htmlspecialchars($review['product_name']); ?></td>
                                            <td class="text-warning">
                                                <?php echo str_repeat('⭐', (int) $review['rating']); ?>
                                            </td>
                                            <td><?php echo htmlspecialchars(substr($review['comment'], 0, 50)); ?>...</td>
                                            <td>
                                                <span class="badge bg-warning text-dark">Chờ duyệt</span>
                                            </td>
                                            <td>
                                                <button class="btn btn-sm btn-success" title="Duyệt"
                                                    onclick="window.location.href='approve_review.php?id=<?php echo $review['id']; ?>'">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                                <button class="btn btn-sm btn-danger" title="Xóa"
                                                    onclick="window.location.href='delete_review.php?id=<?php echo $review['id']; ?>'">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('sidebarCollapse').addEventListener('click', function () {
            document.getElementById('sidebar').classList.toggle('active');
        });
    </script>
</body>

</html>