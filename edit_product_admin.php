<?php
$active_page = 'products';
require_once 'config/connect.php';

if (!isset($pdo)) {
    die("Lỗi: Không tìm thấy biến kết nối \$pdo.");
}

$product_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$product_id) {
    header("Location: products_admin.php?action=error&msg=ID sản phẩm không hợp lệ.");
    exit;
}

$product = null;
$brands = $pdo->query("SELECT id, name FROM brands ORDER BY name ASC")->fetchAll(PDO::FETCH_ASSOC);
$categories = $pdo->query("SELECT id, name, parent_id FROM categories ORDER BY name ASC")->fetchAll(PDO::FETCH_ASSOC);

$gallery = [];
$specs = [];
$content_blocks = [];

try {
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$product_id]);
    $product = $stmt->fetch();
    if (!$product)
        throw new Exception("Sản phẩm không tồn tại.");

    $stmt = $pdo->prepare("SELECT id, image_url FROM product_gallery WHERE product_id = ? ORDER BY id ASC");
    $stmt->execute([$product_id]);
    $gallery = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stmt = $pdo->prepare("SELECT id, spec_name, spec_value FROM product_specifications WHERE product_id = ? ORDER BY id ASC");
    $stmt->execute([$product_id]);
    $specs = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stmt = $pdo->prepare("SELECT id, section_type, image_url, content_text FROM product_content_blocks WHERE product_id = ? ORDER BY sort_order ASC");
    $stmt->execute([$product_id]);
    $content_blocks = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (Exception $e) {
    header("Location: products_admin.php?action=error&msg=" . urlencode($e->getMessage()));
    exit;
}

if (!function_exists('showCategoryTree')) {
    function showCategoryTree($categories, $selected_id = 0, $parent_id = 0, $level = 0)
    {
        $html = '';
        foreach ($categories as $key => $item) {
            if ($item['parent_id'] == $parent_id) {
                $style = ($level == 0) ? "font-weight: bold; color: #000; background-color: #f0f0f0;" : "color: #333;";
                $indent = str_repeat("&nbsp;&nbsp;&nbsp;&nbsp;", $level);
                $icon = ($level > 0) ? '└─ ' : '';
                $name_display = ($level == 0) ? mb_strtoupper($item['name'], 'UTF-8') : $indent . $icon . $item['name'];
                $selected = ($item['id'] == $selected_id) ? 'selected' : '';

                $html .= '<option value="' . $item['id'] . '" style="' . $style . '" ' . $selected . '>';
                $html .= $name_display;
                $html .= '</option>';

                $html .= showCategoryTree($categories, $selected_id, $item['id'], $level + 1);
            }
        }
        return $html;
    }
}

$content_blocks_grouped = [];
foreach ($content_blocks as $block) {
    $content_blocks_grouped[$block['section_type']][] = $block;
}

function renderJsContentBlocks($grouped_blocks)
{
    $output = '';
    $section_maps = [
        'use' => 'uses_blocks',
        'usage' => 'usage_blocks',
        'description' => 'content_blocks',
        'review' => 'review_blocks',
    ];

    foreach ($section_maps as $type => $namePrefix) {
        if (isset($grouped_blocks[$type])) {
            $output .= "let {$namePrefix}Index = 0;\n";
            $output .= "let {$namePrefix}Container = document.getElementById('{$namePrefix}Container');\n";

            foreach ($grouped_blocks[$type] as $block) {
                $text = htmlspecialchars(addslashes($block['content_text'] ?? ''));
                $image_url = htmlspecialchars(addslashes($block['image_url'] ?? ''));

                $output .= "addLoadedBlock('{$namePrefix}', {$namePrefix}Container, '{$text}', '{$image_url}', {$namePrefix}Index);\n";
                $output .= "{$namePrefix}Index++;\n";
            }
        }
    }
    return $output;
}
?>
<!DOCTYPE html>

<html lang="vi">

<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin - Chỉnh Sửa Sản Phẩm: <?php echo htmlspecialchars($product['name'] ?? 'ID ' . $product_id); ?></title>
<link rel="stylesheet" href="./css/admin_new_product.css">
    </head>

<body>

<div class="container">
<h1>✍️ Chỉnh Sửa Sản Phẩm</h1>

<form action="product_update.php" method="POST" enctype="multipart/form-data" id="productForm">
            <input type="hidden" name="id" value="<?php echo $product_id; ?>">
            
<div class="section">
<h3 class="section-title">1. Thông tin sản phẩm & Hình ảnh</h3>
<div class="row">
<div class="col">
<label>Tên sản phẩm <span style="color:red">*</span></label>
<input type="text" name="name" placeholder="VD: Son Kem Lì Black Rouge A12" required
                            value="<?php echo htmlspecialchars($product['name'] ?? ''); ?>">
</div>
<div class="col-30">
<label>Mã SKU <span style="color:red">*</span></label>
<input type="text" name="sku" placeholder="VD: BR-A12" required
                            value="<?php echo htmlspecialchars($product['sku'] ?? ''); ?>">
</div>
</div>
<div class="row">
<div class="col">
<label>Giá bán thực tế <span style="color:red">*</span></label>
<input type="number" name="price" placeholder="VD: 150000" required
                            value="<?php echo htmlspecialchars($product['price'] ?? ''); ?>">
</div>

<div class="col">
<label>Giá cũ (Giá niêm yết)</label>
<input type="number" name="old_price" placeholder="VD: 200000 (Để 0 nếu k giảm)"
                            value="<?php echo htmlspecialchars($product['old_price'] ?? 0); ?>">
</div>

<div class="col">
<label>Số lượng tồn kho</label>
<input type="number" name="stock_quantity" placeholder="100" 
                            value="<?php echo htmlspecialchars($product['stock_quantity'] ?? 0); ?>">
</div>
</div>

<div class="row">
<div class="col">
<label>Trạng thái</label>
<select name="status">
<option value="published" <?php echo ($product['status'] == 'published' ? 'selected' : ''); ?>>Đang bán</option>
<option value="draft" <?php echo ($product['status'] == 'draft' ? 'selected' : ''); ?>>Bản nháp</option>
<option value="out_of_stock" <?php echo ($product['status'] == 'out_of_stock' ? 'selected' : ''); ?>>Hết hàng</option>
</select>
</div>
<div class="col">
<label>Nổi bật</label>
<div
style="padding: 10px; border: 1px solid #dfe4ea; border-radius: 6px; background: #fff; display: flex; align-items: center; gap: 8px;">
<input type="checkbox" name="is_hot" id="is_hot" value="1"
style="width: 20px; height: 20px; cursor: pointer;"
                                <?php echo ($product['is_hot'] ? 'checked' : ''); ?>>
<label for="is_hot"
style="margin: 0; cursor: pointer; color: #d63384; font-weight: bold;">Là sản phẩm Hot
🔥</label>
</div>
</div>
</div>
<div class="row">
<div class="col">
<label>Thương hiệu</label>
<div class="quick-add-wrapper" id="wrapper_brand">
<select name="brand_id" class="form-select" onchange="checkQuickAdd(this, 'brand')">
<option value="">-- Chọn thương hiệu --</option>
<?php foreach ($brands as $brand): ?>
    <option value="<?= $brand['id'] ?>" <?php echo ($product['brand_id'] == $brand['id'] ? 'selected' : ''); ?>>
                                            <?= htmlspecialchars($brand['name']) ?></option>
<?php endforeach; ?>
<option value="create_new" style="font-weight:bold; color:#d63384;">+ Tạo thương hiệu
mới...</option>
</select>

<input type="text" class="form-input-new" id="input_new_brand"
placeholder="Nhập tên thương hiệu mới rồi ấn Enter..." style="display: none;"
onkeydown="handleEnter(event, this, 'brand')" onblur="cancelQuickAdd(this, 'brand')">
</div>
</div>

<div class="col">
<label>Danh mục</label>
<div class="quick-add-wrapper" id="wrapper_cat">
<select name="category_id" id="categorySelect" class="form-select"
onchange="checkQuickAdd(this, 'category')">
<option value="">-- Chọn danh mục --</option>

<?php echo showCategoryTree($categories, $product['category_id'] ?? 0); ?>

<option value="create_new" style="font-weight:bold; color:#d63384;">+ Tạo danh mục
mới...</option>
</select>
</div>
</div>
</div>
<div style="margin-top: 25px;">
<label>📸 Thư viện ảnh sản phẩm (Ảnh bìa & Slide):</label>
                    <div id="currentGalleryList" class="gallery-preview" style="display: flex;">
                        <?php foreach ($gallery as $img): ?>
                                <div class="gallery-item-edit" id="img-<?php echo $img['id']; ?>">
                                    <img width="200px" src="<?php echo htmlspecialchars($img['image_url']); ?>" alt="Ảnh Gallery">
                                    <input type="hidden" name="current_gallery[]" value="<?php echo $img['image_url']; ?>">
                                    <button type="button" class="btn-remove-img" data-img-id="<?php echo $img['id']; ?>" 
                                        onclick="removeImage(this, '<?php echo $img['image_url']; ?>')">✕</button>
                                </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="gallery-upload-box" onclick="document.getElementById('galleryInput').click()">
<p style="margin:0; font-weight:bold; color:var(--primary-color);">+ Nhấn để chọn ảnh mới hoặc Kéo
thả vào đây</p>
<p style="margin:5px 0 0 0; font-size:13px; color:#888;">Hỗ trợ JPG, PNG. (Các ảnh cũ sẽ được giữ lại trừ khi bạn xóa thủ công)</p>
<input type="file" id="galleryInput" name="gallery_new[]" multiple accept="image/*"
style="display: none;" onchange="previewGalleryFiles()">
</div>
<div id="galleryFileList" class="gallery-preview"></div>
</div>
</div>

<div class="section">
<h3 class="section-title">2. Đặc tính & Thành phần</h3>
<div style="margin-bottom: 30px;">
<label>Thông số kỹ thuật (Loại da, Xuất xứ...):</label>
<table class="dynamic-table" id="specTable">
<thead>
<tr>
<th width="35%">Tên thông số</th>
<th>Giá trị</th>
<th width="80px" style="text-align:center;">Xóa</th>
</tr>
</thead>
<tbody>
<?php
$spec_index = 0;
if (!empty($specs)):
    foreach ($specs as $spec):
        ?>
                                        <tr>
                                            <td><input type="text" name="specs[<?php echo $spec_index; ?>][name]" value="<?php echo htmlspecialchars($spec['spec_name']); ?>"></td>
                                            <td><input type="text" name="specs[<?php echo $spec_index; ?>][value]" value="<?php echo htmlspecialchars($spec['spec_value']); ?>"></td>
                                            <td style="text-align:center;"><button type="button" class="btn btn-danger-sm"
                                                    onclick="this.closest('tr').remove()">✕</button></td>
                                        </tr>
                                    <?php $spec_index++; endforeach;
else:
    ?>
                                    <tr>
                                        <td><input type="text" name="specs[0][name]" value="Loại da phù hợp"></td>
                                        <td><input type="text" name="specs[0][value]" value="Mọi loại da"></td>
                                        <td style="text-align:center;"><button type="button" class="btn btn-danger-sm"
                                                onclick="this.closest('tr').remove()">✕</button></td>
                                    </tr>
                            <?php endif; ?>
                            <tr id="specTemplate" style="display:none;">
                                <td><input type="text" name="specs[_idx_][name]" value=""></td>
                                <td><input type="text" name="specs[_idx_][value]" value=""></td>
                                <td style="text-align:center;"><button type="button" class="btn btn-danger-sm" onclick="this.closest('tr').remove()">✕</button></td>
                            </tr>
</tbody>
</table>
<button type="button" class="btn btn-secondary" onclick="addSpecRow()">+ Thêm dòng thông số</button>
</div>
<div class="ingredient-area">
<label
style="color:var(--primary-color); font-size:16px; margin-bottom:8px; display:block; font-weight:bold;">🧪
Thành phần chi tiết (Ingredients)</label>
<p style="font-size:13px; color:#666; margin-top:0; margin-bottom:10px;">Copy toàn bộ bảng thành
phần và phần giải thích công dụng dán vào đây.</p>
<textarea name="ingredients" rows="10"
placeholder="Ví dụ: G2033552 - C18-36 Acid Triglyceride..."><?php echo htmlspecialchars($product['ingredients'] ?? ''); ?></textarea>
</div>
</div>

<div class="section">
<h3 class="section-title">3. Công dụng sản phẩm (Uses)</h3>
<p style="font-size: 13px; color: #636e72; margin-top: 0;">Khối nội dung mô tả công dụng chính (Ảnh +
Văn bản).</p>
<div id="uses_blocksContainer" class="content-list"></div>
<button type="button" class="btn-add-big" onclick="addContentBlock('uses_blocks', 'uses_blocksContainer')">
<span style="font-size: 24px; line-height: 0; margin-top:-3px;">+</span> THÊM KHỐI CÔNG DỤNG
</button>
</div>

<div class="section">
<h3 class="section-title">4. Hướng dẫn sử dụng (Usage)</h3>
<p style="font-size: 13px; color: #636e72; margin-top: 0;">Các bước sử dụng sản phẩm (Ảnh + Hướng dẫn).
</p>
<div id="usage_blocksContainer" class="content-list"></div>
<button type="button" class="btn-add-big" onclick="addContentBlock('usage_blocks', 'usage_blocksContainer')">
<span style="font-size: 24px; line-height: 0; margin-top:-3px;">+</span> THÊM BƯỚC SỬ DỤNG
</button>
</div>

<div class="section">
<h3 class="section-title">5. Mô tả chi tiết (Description)</h3>
<p style="font-size: 13px; color: #636e72; margin-top: 0;">Xây dựng bài viết quảng cáo chi tiết (Ảnh +
Văn bản).</p>
<div id="content_blocksContainer" class="content-list"></div>
<button type="button" class="btn-add-big" onclick="addContentBlock('content_blocks', 'content_blocksContainer')">
<span style="font-size: 24px; line-height: 0; margin-top:-3px;">+</span> THÊM KHỐI MÔ TẢ
</button>
</div>

<div class="section">
<h3 class="section-title">6. Review & Feedback (Đánh giá thực tế)</h3>
<p style="font-size: 13px; color: #636e72; margin-top: 0;">Thêm ảnh review thực tế từ khách hàng hoặc
KOLs (Ảnh + Lời bình).</p>
<div id="review_blocksContainer" class="content-list"></div>
<button type="button" class="btn-add-big" onclick="addContentBlock('review_blocks', 'review_blocksContainer')">
<span style="font-size: 24px; line-height: 0; margin-top:-3px;">+</span> THÊM KHỐI REVIEW
</button>
</div>

<div class="sticky-bottom">
<button type="button" class="btn btn-secondary" onclick="window.history.back()">
<span style="font-size:16px;">↩️</span> Hủy bỏ
</button>
<button type="submit" class="btn btn-primary btn-save" id="btnSave">
<span class="btn-icon">💾</span>
<span class="btn-text">CẬP NHẬT SẢN PHẨM</span>
<div class="loader"></div>
</button>
</div>

</form>
</div>

<script>
        // Khởi tạo index cho Specs
        let specIndex = <?php echo $spec_index ?? 1; ?>; 
        
        function addSpecRow() {
            const template = document.getElementById('specTemplate');
            let newRowHtml = template.outerHTML
                .replace(/id="specTemplate"/g, '')
                .replace(/style="display:none;"/g, '')
                .replace(/_idx_/g, specIndex);

            document.getElementById('specTable').getElementsByTagName('tbody')[0].insertAdjacentHTML('beforeend', newRowHtml);
            specIndex++;
        }
        
        // Khởi tạo index cho Content Blocks
        let contentBlockIndices = {
            'uses_blocks': <?php echo count($content_blocks_grouped['use'] ?? []); ?>,
            'usage_blocks': <?php echo count($content_blocks_grouped['usage'] ?? []); ?>,
            'content_blocks': <?php echo count($content_blocks_grouped['description'] ?? []); ?>,
            'review_blocks': <?php echo count($content_blocks_grouped['review'] ?? []); ?>
        };

        function addContentBlock(namePrefix, containerId, loadedText = '', loadedImageUrl = '') {
            let index = contentBlockIndices[namePrefix];
            contentBlockIndices[namePrefix]++;

            const imageUrlDisplay = loadedImageUrl ? `<p class="small text-muted">Ảnh hiện tại: <img src="${loadedImageUrl}" style="max-height: 50px;"></p>` : '';
            
            const blockHtml = `
                <div class="card p-3 mb-2 border-primary">
                    ${imageUrlDisplay}
                    <textarea class="form-control mb-2" name="${namePrefix}[${index}][text]" placeholder="Nội dung văn bản">${loadedText}</textarea>
                    <label>Tải ảnh mới:</label>
                    <input type="file" name="${namePrefix}[${index}][image]" class="form-control form-control-sm">
                    <input type="hidden" name="${namePrefix}[${index}][current_image]" value="${loadedImageUrl}">
                    <button type="button" class="btn btn-danger btn-sm mt-2" onclick="this.closest('.card').remove()">Xóa khối</button>
                </div>
            `;
            document.getElementById(containerId).insertAdjacentHTML('beforeend', blockHtml);
        }

        // Hàm để load dữ liệu Content Blocks khi trang tải
        function loadContentBlocks() {
            // Định nghĩa hàm để add block đã tải (để JS chạy được)
            if (typeof addLoadedBlock !== 'function') {
                window.addLoadedBlock = function(namePrefix, containerElement, loadedText, loadedImageUrl, index) {
                    const imageUrlDisplay = loadedImageUrl ? `<p class="small text-muted">Ảnh hiện tại: <img src="${loadedImageUrl}" style="max-height: 50px;"></p>` : '';
                    
                    const blockHtml = `
                        <div class="card p-3 mb-2 border-primary">
                            ${imageUrlDisplay}
                            <textarea class="form-control mb-2" name="${namePrefix}[${index}][text]" placeholder="Nội dung văn bản">${loadedText}</textarea>
                            <label>Tải ảnh mới (sẽ thay thế):</label>
                            <input type="file" name="${namePrefix}[${index}][image]" class="form-control form-control-sm">
                            <input type="hidden" name="${namePrefix}[${index}][current_image]" value="${loadedImageUrl}">
                            <button type="button" class="btn btn-danger btn-sm mt-2" onclick="this.closest('.card').remove()">Xóa khối</button>
                        </div>
                    `;
                    containerElement.insertAdjacentHTML('beforeend', blockHtml);
                };
            }
            
            // Chạy logic render PHP
            <?php echo renderJsContentBlocks($content_blocks_grouped); ?>
        }
        window.onload = loadContentBlocks;
        
        // Hàm xóa ảnh Gallery 
        function removeImage(buttonElement, imageUrl) {
            if (confirm("Xóa ảnh này khỏi Gallery? Ảnh sẽ bị xóa khi bạn bấm CẬP NHẬT.")) {
                buttonElement.closest('.gallery-item-edit').remove();
                
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = 'deleted_images[]';
                hiddenInput.value = imageUrl;
                document.getElementById('productForm').appendChild(hiddenInput);
            }
        }
        
        // Hàm Preview Gallery Files 
        function previewGalleryFiles() {
            // (Chức năng này cần JS chi tiết hơn để xử lý preview file input multiple)
            console.log("File(s) mới đã được chọn. Sẽ được upload khi bạn CẬP NHẬT.");
        }

    </script>
    <script src="./js/new_product.js"></script>

</body>

</html>