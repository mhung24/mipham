<?php
require_once 'admin_guard.php';
$active_page = 'customers';
require_once 'config/connect.php';

if (!isset($pdo)) {
    die("Lỗi: Không tìm thấy biến kết nối \$pdo.");
}

$page_title = "Quản Lý Khách Hàng";
$customers = [];
$limit = 20; // 20 khách hàng mỗi trang
$page = isset($_GET['p']) ? (int) $_GET['p'] : 1;
if ($page < 1)
    $page = 1;
$start = ($page - 1) * $limit;
$total_pages = 1;

// --- Bộ lọc và Tìm kiếm ---
$search_term = trim($_GET['search'] ?? '');
$where_clauses = ["u.role = 'user'"];
$bind_params = [];

if (!empty($search_term)) {
    // Tìm kiếm theo Tên, Email, hoặc Phone
    $where_clauses[] = "(u.full_name LIKE :search_term OR u.email LIKE :search_term OR u.phone LIKE :search_term)";
    $bind_params[':search_term'] = '%' . $search_term . '%';
}

$where_sql = " WHERE " . implode(' AND ', $where_clauses);

// Hàm format ngày tháng
function format_date($date)
{
    return date('d/m/Y', strtotime($date));
}

try {
    // 1. Đếm tổng số lượng khách hàng
    $count_sql = "SELECT COUNT(id) FROM users u" . $where_sql;
    $count_stmt = $pdo->prepare($count_sql);
    $count_stmt->execute($bind_params);
    $total_results = $count_stmt->fetchColumn();
    $total_pages = ceil($total_results / $limit);

    if ($page > $total_pages && $total_pages > 0) {
        $page = $total_pages;
        $start = ($page - 1) * $limit;
    }

    // 2. Lấy dữ liệu khách hàng
    $sql = "
        SELECT 
            u.id, 
            u.full_name, 
            u.email, 
            u.phone, 
            u.address,
            u.created_at,
            (SELECT COUNT(id) FROM orders o WHERE o.user_id = u.id) AS total_orders
        FROM 
            users u
        " . $where_sql . "
        ORDER BY 
            u.created_at DESC
        LIMIT :start, :limit
    ";

    $stmt = $pdo->prepare($sql);
    // Bind các tham số cho WHERE
    foreach ($bind_params as $key => $value) {
        $stmt->bindValue($key, $value);
    }
    // Bind tham số cho LIMIT
    $stmt->bindValue(':start', $start, PDO::PARAM_INT);
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);

    $stmt->execute();
    $customers = $stmt->fetchAll();

} catch (\PDOException $e) {
    error_log("Lỗi truy vấn Khách hàng: " . $e->getMessage());
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

        /* ... CSS chung cho Admin Panel ... */
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

            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Danh sách Khách hàng (<?php echo $total_results; ?>)
                    </h6>
                </div>
                <div class="card-body">

                    <form method="get" class="row g-3 mb-4 align-items-center">
                        <div class="col-md-4">
                            <input type="text" name="search" class="form-control"
                                placeholder="Tìm tên, email, hoặc SĐT..."
                                value="<?php echo htmlspecialchars($search_term); ?>">
                        </div>
                        <div class="col-md-auto">
                            <button type="submit" class="btn btn-secondary"><i class="fas fa-search me-1"></i> Tìm
                                kiếm</button>
                        </div>
                        <?php if (!empty($search_term)): ?>
                            <div class="col-md-auto">
                                <a href="customers_admin.php" class="btn btn-outline-secondary">Xóa tìm kiếm</a>
                            </div>
                        <?php endif; ?>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 50px;">ID</th>
                                    <th>Tên Khách hàng</th>
                                    <th>Email</th>
                                    <th>Điện thoại</th>
                                    <th>Đơn hàng</th>
                                    <th>Ngày tham gia</th>
                                    <th style="width: 100px;">Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($customers)): ?>
                                    <tr>
                                        <td colspan="7" class="text-center py-4 text-muted">Không tìm thấy khách hàng nào.
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($customers as $customer): ?>
                                        <tr>
                                            <td>#<?php echo $customer['id']; ?></td>
                                            <td><?php echo htmlspecialchars($customer['full_name']); ?></td>
                                            <td><?php echo htmlspecialchars($customer['email']); ?></td>
                                            <td><?php echo htmlspecialchars($customer['phone'] ?? 'N/A'); ?></td>
                                            <td><span class="badge bg-primary"><?php echo $customer['total_orders']; ?></span>
                                            </td>
                                            <td><?php echo format_date($customer['created_at']); ?></td>
                                            <td>
                                                <button type="button"
                                                    onclick="showCustomerDetails(<?php echo $customer['id']; ?>)"
                                                    class="btn btn-sm btn-secondary" title="Xem chi tiết">
                                                    <i class="fas fa-user-circle"></i>
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
                                $current_query_params = array_filter(['search' => $search_term]);
                                $query_string = http_build_query($current_query_params);
                                $url_base = 'customers_admin.php?' . $query_string . (empty($query_string) ? '' : '&') . 'p=';
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

    <div class="modal fade" id="customerDetailModal" tabindex="-1" aria-labelledby="customerDetailModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="customerDetailModalLabel">Chi tiết Khách hàng #<span
                            id="detailCustomerId"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="customerDetailModalBody">
                    Đang tải dữ liệu...
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                </div>
            </div>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('sidebarCollapse').addEventListener('click', function () {
            document.getElementById('sidebar').classList.toggle('active');
        });

        /**
         * Tải và hiển thị chi tiết khách hàng (sử dụng AJAX)
         */
        function showCustomerDetails(customerId) {
            const modalBody = document.getElementById('customerDetailModalBody');
            document.getElementById('detailCustomerId').textContent = customerId;
            modalBody.innerHTML = 'Đang tải chi tiết khách hàng #' + customerId + '...';

            // GỌI AJAX đến file xử lý chi tiết (Cần tạo file này)
            fetch('customer_detail_ajax.php?id=' + customerId)
                .then(response => response.text())
                .then(data => {
                    modalBody.innerHTML = data;
                })
                .catch(error => {
                    modalBody.innerHTML = '<p class="text-danger">Không thể tải chi tiết khách hàng. Vui lòng kiểm tra kết nối mạng hoặc server.</p>';
                    console.error('Lỗi tải chi tiết khách hàng:', error);
                });

            // Kích hoạt Modal Bootstrap 5
            const modal = new bootstrap.Modal(document.getElementById('customerDetailModal'));
            modal.show();
        }
    </script>
</body>

</html>