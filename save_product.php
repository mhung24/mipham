<?php
require_once 'config/connect.php';

if (!isset($pdo)) {
    die("Lỗi: Không tìm thấy biến kết nối \$pdo.");
}

define('UPLOAD_DIR', 'uploads/');
if (!is_dir(UPLOAD_DIR)) {
    mkdir(UPLOAD_DIR, 0777, true);
}

// Hàm upload ảnh
function uploadImage($fileInfo)
{
    if ($fileInfo['error'] !== UPLOAD_ERR_OK)
        return null;
    $allowed = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    if (!in_array($fileInfo['type'], $allowed))
        return null;

    $fileName = uniqid('img_', true) . '.' . pathinfo($fileInfo['name'], PATHINFO_EXTENSION);
    if (move_uploaded_file($fileInfo['tmp_name'], UPLOAD_DIR . $fileName)) {
        return UPLOAD_DIR . $fileName;
    }
    return null;
}

// Hàm lưu các khối nội dung (Công dụng, Hướng dẫn...)
function saveContentBlocks($pdo, $pid, $type, $postName)
{
    if (empty($_POST[$postName]))
        return;

    // Kiểm tra bảng tồn tại trước khi insert (tránh lỗi nếu chưa tạo bảng content)
    try {
        $sql = "INSERT INTO product_content_blocks (product_id, section_type, image_url, content_text) VALUES (:pid, :type, :url, :text)";
        $stmt = $pdo->prepare($sql);

        foreach ($_POST[$postName] as $i => $data) {
            $text = $data['text'] ?? '';
            $imgUrl = null;
            if (!empty($_FILES[$postName]['name'][$i]['image'])) {
                $fileData = [
                    'name' => $_FILES[$postName]['name'][$i]['image'],
                    'type' => $_FILES[$postName]['type'][$i]['image'],
                    'tmp_name' => $_FILES[$postName]['tmp_name'][$i]['image'],
                    'error' => $_FILES[$postName]['error'][$i]['image'],
                    'size' => $_FILES[$postName]['size'][$i]['image']
                ];
                $imgUrl = uploadImage($fileData);
            }
            if (!empty($text) || !empty($imgUrl)) {
                $stmt->execute([':pid' => $pid, ':type' => $type, ':url' => $imgUrl, ':text' => $text]);
            }
        }
    } catch (Exception $e) { /* Bỏ qua nếu chưa có bảng content */
    }
}

function generateSKU()
{
    return 'MP' . date('ymd') . rand(100, 999);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $pdo->beginTransaction();

        // 1. CHUẨN BỊ DỮ LIỆU CƠ BẢN
        $sku = !empty($_POST['sku']) ? $_POST['sku'] : generateSKU();
        $old_price = !empty($_POST['old_price']) ? $_POST['old_price'] : 0;
        $is_hot = isset($_POST['is_hot']) ? 1 : 0;
        $brand_id = (!empty($_POST['brand_id']) && $_POST['brand_id'] !== 'create_new') ? $_POST['brand_id'] : null;
        $category_id = (!empty($_POST['category_id']) && $_POST['category_id'] !== 'create_new') ? $_POST['category_id'] : null;
        $ingredients = $_POST['ingredients'] ?? '';

        // 2. INSERT SẢN PHẨM (ĐÃ XÓA CỘT IMAGE)
        // Chỉ lưu thông tin text vào bảng products
        $sql = "INSERT INTO products (
                    name, sku, price, old_price, stock_quantity, 
                    status, is_hot, brand_id, category_id, 
                    ingredients, created_at
                ) VALUES (
                    :name, :sku, :price, :old_price, :stock, 
                    :status, :is_hot, :brand, :cat, 
                    :ing, NOW()
                )";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':name' => $_POST['name'],
            ':sku' => $sku,
            ':price' => $_POST['price'],
            ':old_price' => $old_price,
            ':stock' => $_POST['stock_quantity'],
            ':status' => $_POST['status'],
            ':is_hot' => $is_hot,
            ':brand' => $brand_id,
            ':cat' => $category_id,
            ':ing' => $ingredients
        ]);

        $pid = $pdo->lastInsertId(); // Lấy ID sản phẩm vừa tạo

        // 3. XỬ LÝ ẢNH (Lưu tất cả vào bảng product_gallery)
        if (!empty($_FILES['gallery']['name'][0])) {
            $stmtG = $pdo->prepare("INSERT INTO product_gallery (product_id, image_url) VALUES (:pid, :url)");

            $count = count($_FILES['gallery']['name']);
            for ($i = 0; $i < $count; $i++) {
                if ($_FILES['gallery']['error'][$i] === 0) {
                    $fileData = [
                        'name' => $_FILES['gallery']['name'][$i],
                        'type' => $_FILES['gallery']['type'][$i],
                        'tmp_name' => $_FILES['gallery']['tmp_name'][$i],
                        'error' => $_FILES['gallery']['error'][$i],
                        'size' => $_FILES['gallery']['size'][$i]
                    ];

                    $url = uploadImage($fileData);

                    if ($url) {
                        $stmtG->execute([
                            ':pid' => $pid,
                            ':url' => $url
                        ]);
                    }
                }
            }
        }

        // 4. XỬ LÝ CONTENT BLOCKS
        saveContentBlocks($pdo, $pid, 'use', 'uses_blocks');
        saveContentBlocks($pdo, $pid, 'usage', 'usage_blocks');
        saveContentBlocks($pdo, $pid, 'description', 'content_blocks');
        saveContentBlocks($pdo, $pid, 'review', 'review_blocks');

        $pdo->commit();

        echo "<script>alert('✅ Thêm sản phẩm thành công!'); window.location.href = 'test_new.php';</script>";

    } catch (Exception $e) {
        $pdo->rollBack();
        echo "<script>alert('❌ Lỗi: " . $e->getMessage() . "'); history.back();</script>";
    }
}
?>