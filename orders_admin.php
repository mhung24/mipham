<?php
require_once 'admin_guard.php';
$active_page = 'orders';
require_once 'config/connect.php';

if (!isset($pdo)) {
    die("Lỗi: Không tìm thấy biến kết nối \$pdo.");
}

$page_title = "Quản Lý Đơn Hàng";
$orders = [];
$limit = 20; // 20 đơn hàng mỗi trang
$page = isset($_GET['p']) ? (int) $_GET['p'] : 1;
if ($page < 1)
    $page = 1;
$start = ($page - 1) * $limit;
$total_pages = 1;

// --- Bộ lọc ---
$filter_status = $_GET['status'] ?? '';
$search_term = trim($_GET['search'] ?? '');
$where_clauses = ['1=1'];
$bind_params = [];

if (!empty($filter_status)) {
    $where_clauses[] = "o.status = :status";
    $bind_params[':status'] = $filter_status;
}
if (!empty($search_term)) {
    // Tìm kiếm theo Tên khách hàng hoặc ID đơn hàng
    $where_clauses[] = "(o.full_name LIKE :search_term OR o.id = :search_id)";
    $bind_params[':search_term'] = '%' . $search_term . '%';
    $bind_params[':search_id'] = $search_term;
}

$where_sql = " WHERE " . implode(' AND ', $where_clauses);

// Danh sách trạng thái đơn hàng có thể có
$all_statuses = [
    'Đang xử lý' => 'warning',
    'Đang giao' => 'info',
    'Hoàn thành' => 'success',
    'Đã hủy' => 'danger'
];

// Hàm format giá tiền
function format_vnd($amount)
{
    return number_format((float) $amount, 0, ',', '.') . 'đ';
}

function get_status_badge($status)
{
    global $all_statuses;
    $class = $all_statuses[$status] ?? 'secondary';
    return '<span class="badge bg-' . $class . '">' . htmlspecialchars($status) . '</span>';
}


try {
    // 1. Đếm tổng số lượng đơn hàng
    $count_sql = "SELECT COUNT(id) FROM orders o" . $where_sql;
    $count_stmt = $pdo->prepare($count_sql);
    $count_stmt->execute($bind_params);
    $total_results = $count_stmt->fetchColumn();
    $total_pages = ceil($total_results / $limit);

    if ($page > $total_pages && $total_pages > 0) {
        $page = $total_pages;
        $start = ($page - 1) * $limit;
    }

    // 2. Lấy dữ liệu đơn hàng
    $sql = "
        SELECT 
            o.id, 
            o.full_name, 
            o.total_money, 
            o.status, 
            o.created_at,
            o.user_id,
            u.email
        FROM 
            orders o
        LEFT JOIN
            users u ON o.user_id = u.id
        " . $where_sql . "
        ORDER BY 
            o.created_at DESC
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
    $orders = $stmt->fetchAll();

} catch (\PDOException $e) {
    error_log("Lỗi truy vấn Đơn hàng: " . $e->getMessage());
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
                    <h6 class="m-0 font-weight-bold text-primary">Danh sách Đơn hàng (<?php echo $total_results; ?>)
                    </h6>
                </div>
                <div class="card-body">

                    <form method="get" class="row g-3 mb-4 align-items-center">
                        <div class="col-md-3">
                            <input type="text" name="search" class="form-control"
                                placeholder="Tìm ID đơn/Tên khách hàng..."
                                value="<?php echo htmlspecialchars($search_term); ?>">
                        </div>
                        <div class="col-md-3">
                            <select name="status" class="form-select">
                                <option value="">-- Lọc theo trạng thái --</option>
                                <?php foreach ($all_statuses as $status => $class): ?>
                                    <option value="<?php echo $status; ?>" <?php echo ($filter_status === $status) ? 'selected' : ''; ?>>
                                        <?php echo $status; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-auto">
                            <button type="submit" class="btn btn-secondary"><i class="fas fa-filter me-1"></i>
                                Lọc</button>
                        </div>
                        <?php if (!empty($filter_status) || !empty($search_term)): ?>
                            <div class="col-md-auto">
                                <a href="orders_admin.php" class="btn btn-outline-secondary">Xóa bộ lọc</a>
                            </div>
                        <?php endif; ?>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 80px;">ID</th>
                                    <th>Khách hàng</th>
                                    <th>Email (ID: User)</th>
                                    <th>Ngày đặt</th>
                                    <th>Tổng tiền</th>
                                    <th style="width: 150px;">Trạng thái</th>
                                    <th style="width: 120px;">Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($orders)): ?>
                                    <tr>
                                        <td colspan="7" class="text-center py-4 text-muted">Không tìm thấy đơn hàng nào.
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($orders as $order): ?>
                                        <tr>
                                            <td>#<?php echo $order['id']; ?></td>
                                            <td><?php echo htmlspecialchars($order['full_name']); ?></td>
                                            <td><?php echo htmlspecialchars($order['email'] ?? 'N/A'); ?> (ID:
                                                <?php echo $order['user_id']; ?>)
                                            </td>
                                            <td><?php echo date('d/m/Y H:i', strtotime($order['created_at'])); ?></td>
                                            <td class="fw-bold text-primary"><?php echo format_vnd($order['total_money']); ?>
                                            </td>
                                            <td><?php echo get_status_badge($order['status']); ?></td>
                                            <td>
                                                <button type="button" onclick="showOrderDetails(<?php echo $order['id']; ?>)"
                                                    class="btn btn-sm btn-info text-white" title="Xem chi tiết">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button type="button"
                                                    onclick="openStatusModal(<?php echo $order['id']; ?>, '<?php echo $order['status']; ?>')"
                                                    class="btn btn-sm btn-warning" title="Cập nhật trạng thái">
                                                    <i class="fas fa-sync-alt"></i>
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
                                // Xây dựng chuỗi query string cho lọc/tìm kiếm
                                $current_query_params = array_filter(['search' => $search_term, 'status' => $filter_status]);
                                $query_string = http_build_query($current_query_params);
                                $url_base = 'orders_admin.php?' . $query_string . (empty($query_string) ? '' : '&') . 'p=';
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

    <div class="modal fade" id="statusModal" tabindex="-1" aria-labelledby="statusModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <form id="updateStatusForm" method="post" action="order_process.php">
                    <input type="hidden" name="order_id" id="status_order_id">
                    <input type="hidden" name="action_type" value="update_status">

                    <div class="modal-header bg-warning text-dark">
                        <h5 class="modal-title" id="statusModalLabel">Cập nhật trạng thái #<span
                                id="modalOrderId"></span></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="new_status" class="form-label">Trạng thái mới</label>
                            <select class="form-select" id="new_status" name="new_status" required>
                                <?php foreach ($all_statuses as $status => $class): ?>
                                    <option value="<?php echo $status; ?>"><?php echo $status; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn btn-warning text-dark">Lưu Thay Đổi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailModalLabel">Chi tiết Đơn hàng #<span id="detailOrderId"></span>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="detailModalBody">
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
         * Mở Modal cập nhật trạng thái
         */
        function openStatusModal(orderId, currentStatus) {
            document.getElementById('modalOrderId').textContent = orderId;
            document.getElementById('status_order_id').value = orderId;
            document.getElementById('new_status').value = currentStatus;

            // Kích hoạt Modal Bootstrap 5
            const modal = new bootstrap.Modal(document.getElementById('statusModal'));
            modal.show();
        }

        /**
         * Tải và hiển thị chi tiết đơn hàng (sử dụng AJAX)
         */
        function showOrderDetails(orderId) {
            const modalBody = document.getElementById('detailModalBody');
            document.getElementById('detailOrderId').textContent = orderId;
            modalBody.innerHTML = 'Đang tải chi tiết đơn hàng #' + orderId + '...';

            fetch('order_detail_ajax.php?id=' + orderId)
                .then(response => response.text())
                .then(data => {
                    modalBody.innerHTML = data;
                })
                .catch(error => {
                    modalBody.innerHTML = '<p class="text-danger">Không thể tải chi tiết đơn hàng. Vui lòng kiểm tra kết nối mạng hoặc server.</p>';
                    console.error('Lỗi tải chi tiết đơn hàng:', error);
                });

            // Kích hoạt Modal Bootstrap 5
            const modal = new bootstrap.Modal(document.getElementById('detailModal'));
            modal.show();
        }
    </script>
</body>
<?php if (isset($_GET['update'])): ?>
    <script>
        window.onload = function () {
            const updateStatus = '<?php echo htmlspecialchars($_GET['update']); ?>';
            const statusName = '<?php echo htmlspecialchars($_GET['status_name'] ?? ""); ?>';
            const errorMsg = '<?php echo htmlspecialchars($_GET['msg'] ?? "Có lỗi xảy ra."); ?>';

            if (typeof showToast === 'function') {
                if (updateStatus === 'success') {
                    showToast('success', 'Đã cập nhật đơn hàng thành công! Trạng thái mới: ' + statusName);
                } else if (updateStatus === 'error') {
                    showToast('error', errorMsg);
                }
            } else {
                console.log('Thông báo:', updateStatus === 'success' ? 'Cập nhật thành công' : 'Lỗi');
            }

            // Xóa tham số khỏi URL sau khi hiển thị
            if (window.history.replaceState) {
                const url = new URL(window.location.href);
                url.searchParams.delete('update');
                url.searchParams.delete('status_name');
                url.searchParams.delete('msg');
                window.history.replaceState({ path: url.href }, '', url.href);
            }
        };
    </script>
<?php endif; ?>

</html>