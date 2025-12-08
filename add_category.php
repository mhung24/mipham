<?php
require_once 'config/connect.php';

$msg = '';
$edit_mode = false;
$edit_data = [];

// 1. XỬ LÝ: RESET TOÀN BỘ (XÓA SẠCH)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btn_reset'])) {
    try {
        // Tắt kiểm tra khóa ngoại để xóa được ngay cả khi có ràng buộc
        $pdo->exec("SET FOREIGN_KEY_CHECKS = 0");
        $pdo->exec("TRUNCATE TABLE categories");
        $pdo->exec("SET FOREIGN_KEY_CHECKS = 1");

        $msg = '<div class="alert alert-danger">🗑️ Đã xóa sạch dữ liệu và Reset ID về 1!</div>';
        header("Refresh: 2; url=add_category.php"); // Tự load lại trang sau 2s
    } catch (Exception $e) {
        $msg = '<div class="alert alert-danger">Lỗi: ' . $e->getMessage() . '</div>';
    }
}

// 2. XỬ LÝ: THÊM MỚI HOẶC CẬP NHẬT
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btn_save'])) {
    $name = trim($_POST['name']);
    $parent_id = $_POST['parent_id'];
    $sort_order = $_POST['sort_order'];
    $current_id = $_POST['current_id'] ?? '';

    if (!empty($name)) {
        try {
            if (!empty($current_id)) {
                // UPDATE
                $stmt = $pdo->prepare("UPDATE categories SET name = :name, parent_id = :parent_id, sort_order = :sort_order WHERE id = :id");
                $stmt->execute([':name' => $name, ':parent_id' => $parent_id, ':sort_order' => $sort_order, ':id' => $current_id]);
                $msg = '<div class="alert alert-success">✅ Đã cập nhật: <b>' . htmlspecialchars($name) . '</b></div>';
            } else {
                // INSERT
                $stmt = $pdo->prepare("INSERT INTO categories (name, parent_id, sort_order) VALUES (:name, :parent_id, :sort_order)");
                $stmt->execute([':name' => $name, ':parent_id' => $parent_id, ':sort_order' => $sort_order]);
                $msg = '<div class="alert alert-success">✅ Đã thêm mới: <b>' . htmlspecialchars($name) . '</b></div>';
            }
        } catch (Exception $e) {
            $msg = '<div class="alert alert-danger">Lỗi: ' . $e->getMessage() . '</div>';
        }
    } else {
        $msg = '<div class="alert alert-warning">⚠️ Tên không được để trống!</div>';
    }
}

// 3. XỬ LÝ: CHỌN SỬA
if (isset($_GET['edit_id'])) {
    $id = $_GET['edit_id'];
    $stmt = $pdo->prepare("SELECT * FROM categories WHERE id = ?");
    $stmt->execute([$id]);
    $edit_data = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($edit_data)
        $edit_mode = true;
}

// 4. XỬ LÝ: XÓA 1 DÒNG
if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];
    try {
        $check = $pdo->prepare("SELECT COUNT(*) FROM categories WHERE parent_id = ?");
        $check->execute([$id]);
        if ($check->fetchColumn() > 0) {
            $msg = '<div class="alert alert-danger">❌ Không thể xóa! Có danh mục con.</div>';
        } else {
            $pdo->prepare("DELETE FROM categories WHERE id = ?")->execute([$id]);
            header("Location: add_category.php");
            exit;
        }
    } catch (Exception $e) {
        $msg = '<div class="alert alert-danger">Lỗi: ' . $e->getMessage() . '</div>';
    }
}

// HÀM HIỂN THỊ
function showCategorySelect($categories, $parent_id = 0, $char = '', $selected_id = 0, $exclude_id = 0)
{
    foreach ($categories as $key => $item) {
        if ($item['parent_id'] == $parent_id) {
            if ($item['id'] == $exclude_id)
                continue;
            $selected = ($item['id'] == $selected_id) ? 'selected' : '';
            echo '<option value="' . $item['id'] . '" ' . $selected . '>' . $char . htmlspecialchars($item['name']) . '</option>';
            $cats_copy = $categories;
            unset($cats_copy[$key]);
            showCategorySelect($categories, $item['id'], $char . '|--- ', $selected_id, $exclude_id);
        }
    }
}

function showCategoryTable($categories, $parent_id = 0, $char = '')
{
    foreach ($categories as $key => $item) {
        if ($item['parent_id'] == $parent_id) {
            $bgInfo = ($parent_id == 0) ? 'background-color:#fff3cd; font-weight:bold;' : '';
            echo '<tr style="' . $bgInfo . '">';
            echo '<td>' . $item['id'] . '</td>';
            echo '<td>' . $char . htmlspecialchars($item['name']) . '</td>';
            echo '<td>' . $item['sort_order'] . '</td>';
            echo '<td>
                    <a href="?edit_id=' . $item['id'] . '" class="btn btn-warning btn-sm">✏️</a>
                    <a href="?delete_id=' . $item['id'] . '" class="btn btn-danger btn-sm" onclick="return confirm(\'Xóa?\')">🗑️</a>
                  </td>';
            echo '</tr>';
            showCategoryTable($categories, $item['id'], $char . '---- ');
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Quản lý Danh mục</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            padding: 20px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2 class="text-center mb-4 text-primary">🛠️ Tool Quản Lý Danh Mục</h2>
        <div class="row">
            <div class="col-md-4">
                <div class="card shadow-sm sticky-top" style="top: 20px;">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><?= $edit_mode ? '✏️ Cập nhật' : '➕ Thêm mới' ?></h5>
                    </div>
                    <div class="card-body">
                        <?= $msg ?>
                        <form method="POST">
                            <input type="hidden" name="current_id" value="<?= $edit_data['id'] ?? '' ?>">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Tên:</label>
                                <input type="text" name="name" class="form-control"
                                    value="<?= htmlspecialchars($edit_data['name'] ?? '') ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Cha:</label>
                                <select name="parent_id" class="form-select" size="8">
                                    <option value="0" <?= (isset($edit_data['parent_id']) && $edit_data['parent_id'] == 0) ? 'selected' : '' ?>>-- Gốc (Cấp 1) --</option>
                                    <?php
                                    $catList = $pdo->query("SELECT * FROM categories ORDER BY name ASC")->fetchAll(PDO::FETCH_ASSOC);
                                    showCategorySelect($catList, 0, '', $edit_data['parent_id'] ?? 0, $edit_data['id'] ?? 0);
                                    ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Thứ tự:</label>
                                <input type="number" name="sort_order" class="form-control"
                                    value="<?= $edit_data['sort_order'] ?? 0 ?>">
                            </div>
                            <div class="d-grid gap-2">
                                <button type="submit" name="btn_save"
                                    class="btn btn-success"><?= $edit_mode ? 'LƯU LẠI' : 'THÊM MỚI' ?></button>
                                <?php if ($edit_mode): ?><a href="add_category.php"
                                        class="btn btn-secondary">HỦY</a><?php endif; ?>
                            </div>
                        </form>

                        <hr>
                        <form method="POST"
                            onsubmit="return confirm('⚠️ CẢNH BÁO: Hành động này sẽ XÓA SẠCH toàn bộ danh mục và reset ID về 1.\n\nBạn có chắc chắn không?');">
                            <button type="submit" name="btn_reset" class="btn btn-danger w-100">⚠️ RESET (XÓA SẠCH)
                                DATABASE</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-dark text-white d-flex justify-content-between">
                        <h5 class="mb-0">Danh sách hiện tại</h5>
                        <a href="add_category.php" class="btn btn-sm btn-light">Làm mới</a>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-bordered table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th width="50">ID</th>
                                    <th>Tên</th>
                                    <th width="50">Sort</th>
                                    <th width="100">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $catTable = $pdo->query("SELECT * FROM categories ORDER BY parent_id ASC, sort_order ASC, id ASC")->fetchAll(PDO::FETCH_ASSOC);
                                if (empty($catTable))
                                    echo '<tr><td colspan="4" class="text-center p-3">Trống</td></tr>';
                                else
                                    showCategoryTable($catTable);
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>