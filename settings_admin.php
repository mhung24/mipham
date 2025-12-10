<?php
// Tên file: settings_admin.php
$active_page = 'settings';
require_once 'config/connect.php';

if (!isset($pdo)) {
    die("Lỗi: Không tìm thấy biến kết nối \$pdo.");
}

$page_title = "Cài Đặt Hệ Thống";
$banners = [];
$menus = [];

try {
    // Lấy danh sách Banners
    $stmt_banners = $pdo->query("SELECT id, name, image FROM banners ORDER BY id ASC");
    $banners = $stmt_banners->fetchAll(PDO::FETCH_ASSOC);

    // Lấy danh sách Menus
    $stmt_menus = $pdo->query("SELECT id, name, link, is_flash, sort_order FROM menus ORDER BY sort_order ASC");
    $menus = $stmt_menus->fetchAll(PDO::FETCH_ASSOC);

} catch (\PDOException $e) {
    error_log("Lỗi truy vấn Cài đặt: " . $e->getMessage());
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

        .banner-img {
            max-height: 80px;
            width: auto;
            object-fit: cover;
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
                <div class="card-header p-0">
                    <ul class="nav nav-tabs" id="settingsTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="banners-tab" data-bs-toggle="tab"
                                data-bs-target="#banners" type="button" role="tab" aria-controls="banners"
                                aria-selected="true">
                                <i class="fas fa-image me-1"></i> Quản lý Banners
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="menus-tab" data-bs-toggle="tab" data-bs-target="#menus"
                                type="button" role="tab" aria-controls="menus" aria-selected="false">
                                <i class="fas fa-bars me-1"></i> Quản lý Menu
                            </button>
                        </li>
                    </ul>
                </div>

                <div class="card-body">
                    <div class="tab-content" id="settingsTabContent">

                        <div class="tab-pane fade show active" id="banners" role="tabpanel"
                            aria-labelledby="banners-tab">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6>Danh sách Banners Trang chủ</h6>
                                <button type="button" onclick="showBannerModal('add')" class="btn btn-primary btn-sm"
                                    data-bs-toggle="modal" data-bs-target="#bannerModal">
                                    <i class="fas fa-plus me-1"></i> Thêm Banner
                                </button>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="width: 50px;">ID</th>
                                            <th style="width: 150px;">Ảnh Banner</th>
                                            <th>Tên Banner</th>
                                            <th style="width: 120px;">Hành động</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (empty($banners)): ?>
                                            <tr>
                                                <td colspan="4" class="text-center py-4 text-muted">Chưa có banner nào được
                                                    thêm.</td>
                                            </tr>
                                        <?php else: ?>
                                            <?php foreach ($banners as $banner): ?>
                                                <tr>
                                                    <td><?php echo $banner['id']; ?></td>
                                                    <td>
                                                        <img src="<?php echo htmlspecialchars($banner['image'] ?? 'https://via.placeholder.com/150x80?text=No+Img'); ?>"
                                                            alt="<?php echo htmlspecialchars($banner['name']); ?>"
                                                            class="banner-img img-thumbnail">
                                                    </td>
                                                    <td><?php echo htmlspecialchars($banner['name']); ?></td>
                                                    <td>
                                                        <button type="button"
                                                            onclick="showBannerModal('edit', <?php echo $banner['id']; ?>, '<?php echo htmlspecialchars($banner['name'], ENT_QUOTES); ?>', '<?php echo htmlspecialchars($banner['image'], ENT_QUOTES); ?>')"
                                                            class="btn btn-sm btn-info text-white" data-bs-toggle="modal"
                                                            data-bs-target="#bannerModal" title="Sửa">
                                                            <i class="fas fa-edit"></i>
                                                        </button>
                                                        <button type="button"
                                                            onclick="confirmDeleteBanner(<?php echo $banner['id']; ?>)"
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
                        </div>

                        <div class="tab-pane fade" id="menus" role="tabpanel" aria-labelledby="menus-tab">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6>Danh sách Menu Chính</h6>
                                <button type="button" onclick="showMenuModal('add')" class="btn btn-primary btn-sm"
                                    data-bs-toggle="modal" data-bs-target="#menuModal">
                                    <i class="fas fa-plus me-1"></i> Thêm Menu
                                </button>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="width: 50px;">ID</th>
                                            <th>Tên Menu</th>
                                            <th>Đường dẫn</th>
                                            <th>Thứ tự</th>
                                            <th>Flash Sale</th>
                                            <th style="width: 120px;">Hành động</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (empty($menus)): ?>
                                            <tr>
                                                <td colspan="6" class="text-center py-4 text-muted">Chưa có mục menu nào
                                                    được thêm.</td>
                                            </tr>
                                        <?php else: ?>
                                            <?php foreach ($menus as $menu): ?>
                                                <tr>
                                                    <td><?php echo $menu['id']; ?></td>
                                                    <td><?php echo htmlspecialchars($menu['name']); ?></td>
                                                    <td><span
                                                            class="text-muted small"><?php echo htmlspecialchars($menu['link']); ?></span>
                                                    </td>
                                                    <td><?php echo $menu['sort_order']; ?></td>
                                                    <td><?php echo $menu['is_flash'] ? '<span class="badge bg-danger">Có</span>' : '—'; ?>
                                                    </td>
                                                    <td>
                                                        <button type="button"
                                                            onclick="showMenuModal('edit', <?php echo $menu['id']; ?>, '<?php echo htmlspecialchars($menu['name'], ENT_QUOTES); ?>', '<?php echo htmlspecialchars($menu['link'], ENT_QUOTES); ?>', <?php echo $menu['sort_order']; ?>, <?php echo $menu['is_flash']; ?>)"
                                                            class="btn btn-sm btn-info text-white" data-bs-toggle="modal"
                                                            data-bs-target="#menuModal" title="Sửa">
                                                            <i class="fas fa-edit"></i>
                                                        </button>
                                                        <button type="button"
                                                            onclick="confirmDeleteMenu(<?php echo $menu['id']; ?>)"
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
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="modal fade" id="bannerModal" tabindex="-1" aria-labelledby="bannerModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="bannerForm" method="post" action="settings_process.php" enctype="multipart/form-data">
                    <input type="hidden" name="id" id="banner_id" value="0">
                    <input type="hidden" name="action_type" id="banner_action_type" value="add_banner">

                    <div class="modal-header">
                        <h5 class="modal-title" id="bannerModalLabel">Thêm Banner Mới</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="banner_name" class="form-label">Tên Banner</label>
                            <input type="text" class="form-control" id="banner_name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="banner_image" class="form-label">Ảnh Banner (Đề nghị kích thước lớn)</label>
                            <input type="file" class="form-control" id="banner_image" name="image" accept="image/*">
                            <div id="current_banner_img" class="mt-2" style="display:none;">
                                <small class="text-muted">Ảnh hiện tại:</small><br>
                                <img id="banner_preview" src="" alt="Banner hiện tại" style="max-height: 100px;">
                                <input type="hidden" name="current_image_url" id="current_image_url">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn btn-primary" id="saveBannerBtn">Lưu Banner</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="menuModal" tabindex="-1" aria-labelledby="menuModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="menuForm" method="post" action="settings_process.php">
                    <input type="hidden" name="id" id="menu_id" value="0">
                    <input type="hidden" name="action_type" id="menu_action_type" value="add_menu">

                    <div class="modal-header">
                        <h5 class="modal-title" id="menuModalLabel">Thêm Menu Mới</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="menu_name" class="form-label">Tên Menu</label>
                            <input type="text" class="form-control" id="menu_name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="menu_link" class="form-label">Đường dẫn (Link)</label>
                            <input type="text" class="form-control" id="menu_link" name="link"
                                placeholder="/products/abc hoặc #section" required>
                        </div>
                        <div class="mb-3 row">
                            <div class="col-6">
                                <label for="menu_sort_order" class="form-label">Thứ tự hiển thị</label>
                                <input type="number" class="form-control" id="menu_sort_order" name="sort_order"
                                    value="0" required>
                            </div>
                            <div class="col-6 pt-4">
                                <div class="form-check pt-2">
                                    <input class="form-check-input" type="checkbox" value="1" name="is_flash"
                                        id="menu_is_flash">
                                    <label class="form-check-label" for="menu_is_flash">
                                        Hiển thị Flash Sale
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn btn-primary" id="saveMenuBtn">Lưu Menu</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('sidebarCollapse').addEventListener('click', function () {
            document.getElementById('sidebar').classList.toggle('active');
        });

        // ==========================================================
        // JS Xử lý BANNER MODAL
        // ==========================================================

        function showBannerModal(type, id = 0, name = '', imageUrl = '') {
            document.getElementById('bannerForm').reset();
            document.getElementById('banner_id').value = id;
            document.getElementById('banner_action_type').value = type + '_banner';

            if (type === 'add') {
                document.getElementById('bannerModalLabel').textContent = 'Thêm Banner Mới';
                document.getElementById('saveBannerBtn').textContent = 'Lưu Banner';
                document.getElementById('current_banner_img').style.display = 'none';
                document.getElementById('banner_image').required = true;
            } else {
                document.getElementById('bannerModalLabel').textContent = 'Chỉnh Sửa Banner: ' + name;
                document.getElementById('saveBannerBtn').textContent = 'Cập Nhật Banner';
                document.getElementById('banner_name').value = name;
                document.getElementById('banner_image').required = false; // Không cần upload lại ảnh

                // Hiển thị ảnh hiện tại
                if (imageUrl) {
                    document.getElementById('banner_preview').src = imageUrl;
                    document.getElementById('current_image_url').value = imageUrl;
                    document.getElementById('current_banner_img').style.display = 'block';
                } else {
                    document.getElementById('current_banner_img').style.display = 'none';
                }
            }
        }

        function confirmDeleteBanner(id) {
            if (confirm("Bạn có chắc chắn muốn xóa Banner này không?")) {
                window.location.href = 'settings_process.php?action_type=delete_banner&id=' + id;
            }
        }

        // ==========================================================
        // JS Xử lý MENU MODAL
        // ==========================================================

        function showMenuModal(type, id = 0, name = '', link = '', sortOrder = 0, isFlash = 0) {
            document.getElementById('menuForm').reset();
            document.getElementById('menu_id').value = id;
            document.getElementById('menu_action_type').value = type + '_menu';

            if (type === 'add') {
                document.getElementById('menuModalLabel').textContent = 'Thêm Menu Mới';
                document.getElementById('saveMenuBtn').textContent = 'Lưu Menu';
            } else {
                document.getElementById('menuModalLabel').textContent = 'Chỉnh Sửa Menu: ' + name;
                document.getElementById('saveMenuBtn').textContent = 'Cập Nhật Menu';
                document.getElementById('menu_name').value = name;
                document.getElementById('menu_link').value = link;
                document.getElementById('menu_sort_order').value = sortOrder;
                document.getElementById('menu_is_flash').checked = (isFlash == 1);
            }
        }

        function confirmDeleteMenu(id) {
            if (confirm("Bạn có chắc chắn muốn xóa mục Menu này không?")) {
                window.location.href = 'settings_process.php?action_type=delete_menu&id=' + id;
            }
        }
    </script>
</body>

</html>