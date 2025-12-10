<?php
// Tên file: product_update.php
require_once 'config/connect.php';

if (!isset($pdo)) {
    header("Location: products_admin.php?edit=error&msg=Database connection failed");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: products_admin.php');
    exit;
}

// =======================================================================
// HÀM HỖ TRỢ (Dùng lại từ save_product.php)
// =======================================================================

define('UPLOAD_DIR', 'uploads/');

function uploadImage($fileInfo, $targetDir = UPLOAD_DIR)
{
    if ($fileInfo['error'] !== UPLOAD_ERR_OK)
        return null;
    $allowed = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    if (!in_array($fileInfo['type'], $allowed))
        return null;

    $fileName = uniqid('img_', true) . '.' . pathinfo($fileInfo['name'], PATHINFO_EXTENSION);
    if (move_uploaded_file($fileInfo['tmp_name'], $targetDir . $fileName)) {
        return $targetDir . $fileName;
    }
    return null;
}

/**
 * Xử lý cập nhật khối nội dung động (Specs, Gallery, Content Blocks)
 * Hàm này sẽ xóa hết dữ liệu cũ và insert dữ liệu mới vào DB.
 */
function saveContentBlocks($pdo, $pid, $type, $postName)
{
    // BƯỚC 1: Xóa tất cả các khối nội dung cũ của sản phẩm này theo type
    $sql_delete = "DELETE FROM product_content_blocks WHERE product_id = ? AND section_type = ?";
    $stmt_delete = $pdo->prepare($sql_delete);
    $stmt_delete->execute([$pid, $type]);

    // BƯỚC 2: Thêm các khối nội dung mới
    if (empty($_POST[$postName]))
        return;

    try {
        // Giả định bảng product_content_blocks có cột sort_order
        $sql = "INSERT INTO product_content_blocks (product_id, section_type, image_url, content_text, sort_order) VALUES (:pid, :type, :url, :text, :sort_order)";
        $stmt = $pdo->prepare($sql);

        $sort_order = 1;
        foreach ($_POST[$postName] as $i => $data) {
            $text = $data['text'] ?? '';
            $imgUrl = null;

            // Xử lý upload ảnh mới
            if (!empty($_FILES[$postName]['name'][$i]['image']) && $_FILES[$postName]['error'][$i]['image'] === 0) {
                $fileData = [
                    'name' => $_FILES[$postName]['name'][$i]['image'],
                    'type' => $_FILES[$postName]['type'][$i]['image'],
                    'tmp_name' => $_FILES[$postName]['tmp_name'][$i]['image'],
                    'error' => $_FILES[$postName]['error'][$i]['image'],
                    'size' => $_FILES[$postName]['size'][$i]['image']
                ];
                $imgUrl = uploadImage($fileData, UPLOAD_DIR . 'products_content/');
            }

            // Giữ ảnh cũ nếu không có upload mới (sử dụng trường hidden current_image)
            if (empty($imgUrl) && !empty($data['current_image'])) {
                $imgUrl = $data['current_image'];
            }

            if (!empty($text) || !empty($imgUrl)) {
                $stmt->execute([':pid' => $pid, ':type' => $type, ':url' => $imgUrl, ':text' => $text, ':sort_order' => $sort_order++]);
            }
        }
    } catch (Exception $e) { /* Lỗi */
    }
}

// =======================================================================
// XỬ LÝ CẬP NHẬT CHÍNH
// =======================================================================

$product_id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
$product_name = trim($_POST['name'] ?? 'Sản phẩm');

if ($product_id <= 0) {
    header("Location: products_admin.php?edit=error&msg=ID sản phẩm không tồn tại.");
    exit;
}

// 1. Chuẩn bị dữ liệu từ POST
$name = $product_name;
$sku = trim($_POST['sku']);
$price = filter_input(INPUT_POST, 'price', FILTER_VALIDATE_FLOAT) ?: 0;
$old_price = filter_input(INPUT_POST, 'old_price', FILTER_VALIDATE_FLOAT) ?: 0;
$stock_quantity = filter_input(INPUT_POST, 'stock_quantity', FILTER_VALIDATE_INT) ?: 0;
$status = $_POST['status'] ?? 'draft';
$is_hot = isset($_POST['is_hot']) ? 1 : 0;
$brand_id = filter_input(INPUT_POST, 'brand_id', FILTER_VALIDATE_INT);
$category_id = filter_input(INPUT_POST, 'category_id', FILTER_VALIDATE_INT);
$ingredients = $_POST['ingredients'] ?? '';

// Kiểm tra dữ liệu tối thiểu
if (empty($name) || empty($sku) || $price <= 0) {
    header("Location: edit_product_admin.php?id=$product_id&edit=error&msg=Thông tin cơ bản không được để trống.");
    exit;
}

try {
    $pdo->beginTransaction();

    // 2. Cập nhật bảng PRODUCTS chính
    $sql_update_product = "UPDATE products SET 
        name = ?, sku = ?, price = ?, old_price = ?, stock_quantity = ?, status = ?, is_hot = ?, 
        brand_id = ?, category_id = ?, ingredients = ?, updated_at = NOW() 
        WHERE id = ?";

    $stmt = $pdo->prepare($sql_update_product);
    $stmt->execute([
        $name,
        $sku,
        $price,
        $old_price,
        $stock_quantity,
        $status,
        $is_hot,
        $brand_id,
        $category_id,
        $ingredients,
        $product_id
    ]);

    // 3. CẬP NHẬT GALLERY

    // 3.1. Xử lý xóa ảnh Gallery cũ (sử dụng trường hidden deleted_images[])
    if (!empty($_POST['deleted_images'])) {
        $deleted_images = $_POST['deleted_images'];
        $placeholders = implode(',', array_fill(0, count($deleted_images), '?'));

        // Xóa khỏi DB
        $sql_delete_gallery_db = "DELETE FROM product_gallery WHERE product_id = ? AND image_url IN ($placeholders)";
        $stmt_delete_db = $pdo->prepare($sql_delete_gallery_db);
        $exec_params = array_merge([$product_id], $deleted_images);
        $stmt_delete_db->execute($exec_params);

        // Xóa file vật lý (nên dùng @unlink để tránh lỗi nếu file không tồn tại)
        foreach ($deleted_images as $url) {
            if (file_exists($url)) {
                @unlink($url);
            }
        }
    }

    // 3.2. Xử lý Thêm ảnh Gallery mới (gallery_new[])
    if (!empty($_FILES['gallery_new']['name'][0])) {
        $stmtG = $pdo->prepare("INSERT INTO product_gallery (product_id, image_url) VALUES (?, ?)");
        $count = count($_FILES['gallery_new']['name']);

        for ($i = 0; $i < $count; $i++) {
            if ($_FILES['gallery_new']['error'][$i] === 0) {
                $fileData = [
                    'name' => $_FILES['gallery_new']['name'][$i],
                    'type' => $_FILES['gallery_new']['type'][$i],
                    'tmp_name' => $_FILES['gallery_new']['tmp_name'][$i],
                    'error' => $_FILES['gallery_new']['error'][$i],
                    'size' => $_FILES['gallery_new']['size'][$i]
                ];
                $url = uploadImage($fileData, UPLOAD_DIR . 'products_gallery/');
                if ($url) {
                    $stmtG->execute([$product_id, $url]);
                }
            }
        }
    }

    // 4. CẬP NHẬT SPECIFICATIONS (Xóa hết cũ, Thêm mới)
    $sql_delete_spec = "DELETE FROM product_specifications WHERE product_id = ?";
    $pdo->prepare($sql_delete_spec)->execute([$product_id]);

    if (!empty($_POST['specs'])) {
        $sql_spec = "INSERT INTO product_specifications (product_id, spec_name, spec_value) VALUES (?, ?, ?)";
        $stmt_spec = $pdo->prepare($sql_spec);
        foreach ($_POST['specs'] as $spec) {
            if (!empty($spec['name']) && !empty($spec['value'])) {
                $stmt_spec->execute([$product_id, trim($spec['name']), trim($spec['value'])]);
            }
        }
    }

    // 5. CẬP NHẬT CONTENT BLOCKS (Xóa hết cũ, Thêm mới)
    saveContentBlocks($pdo, $product_id, 'use', 'uses_blocks');
    saveContentBlocks($pdo, $product_id, 'usage', 'usage_blocks');
    saveContentBlocks($pdo, $product_id, 'description', 'content_blocks');
    saveContentBlocks($pdo, $product_id, 'review', 'review_blocks');

    $pdo->commit();

    // Chuyển hướng thành công về trang danh sách sản phẩm
    header("Location: products_admin.php?edit=success&name=" . urlencode($product_name));
    exit;

} catch (Exception $e) {
    $pdo->rollBack();
    // Chuyển hướng về trang edit_product_admin.php (để giữ lại URL ID)
    header("Location: edit_product_admin.php?id=$product_id&edit=error&msg=" . urlencode("Cập nhật thất bại: " . $e->getMessage()));
    exit;
}
?>