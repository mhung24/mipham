<?php
require_once 'config/connect.php';
if (!isset($pdo))
    die("Lỗi kết nối database.");

// Lấy danh sách cho Select box
$brands = $pdo->query("SELECT id, name FROM brands ORDER BY name ASC")->fetchAll(PDO::FETCH_ASSOC);
// Thêm parent_id vào để biết danh mục nào là con của danh mục nào
$categories = $pdo->query("SELECT id, name, parent_id FROM categories ORDER BY name ASC")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>

<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Thêm Sản Phẩm Mỹ Phẩm</title>
    <link rel="stylesheet" href="./css/admin_new_product.css">
</head>

<body>

    <div class="container">
        <h1>💄 Thêm Sản Phẩm Mỹ Phẩm</h1>

        <form action="save_product.php" method="POST" enctype="multipart/form-data" id="productForm">

            <div class="section">
                <h3 class="section-title">1. Thông tin sản phẩm & Hình ảnh</h3>
                <div class="row">
                    <div class="col">
                        <label>Tên sản phẩm <span style="color:red">*</span></label>
                        <input type="text" name="name" placeholder="VD: Son Kem Lì Black Rouge A12" required>
                    </div>
                    <div class="col-30">
                        <label>Mã SKU <span style="color:red">*</span></label>
                        <input type="text" name="sku" placeholder="VD: BR-A12" required disabled>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <label>Giá bán thực tế <span style="color:red">*</span></label>
                        <input type="number" name="price" placeholder="VD: 150000" required>
                    </div>

                    <div class="col">
                        <label>Giá cũ (Giá niêm yết)</label>
                        <input type="number" name="old_price" placeholder="VD: 200000 (Để 0 nếu k giảm)">
                    </div>

                    <div class="col">
                        <label>Số lượng tồn kho</label>
                        <input type="number" name="stock_quantity" placeholder="100" value="0">
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <label>Trạng thái</label>
                        <select name="status">
                            <option value="published">Đang bán</option>
                            <option value="draft">Bản nháp</option>
                            <option value="out_of_stock">Hết hàng</option>
                        </select>
                    </div>
                    <div class="col">
                        <label>Nổi bật</label>
                        <div
                            style="padding: 10px; border: 1px solid #dfe4ea; border-radius: 6px; background: #fff; display: flex; align-items: center; gap: 8px;">
                            <input type="checkbox" name="is_hot" id="is_hot" value="1"
                                style="width: 20px; height: 20px; cursor: pointer;">
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
                                    <option value="<?= $brand['id'] ?>"><?= htmlspecialchars($brand['name']) ?></option>
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

                                <?php
                                // Định nghĩa hàm hiển thị đệ quy với biến $level để kiểm soát giao diện
                                if (!function_exists('showCategoryTree')) {
                                    function showCategoryTree($categories, $parent_id = 0, $level = 0)
                                    {
                                        foreach ($categories as $key => $item) {
                                            if ($item['parent_id'] == $parent_id) {

                                                // Xử lý hiển thị dựa trên cấp độ (Level)
                                                if ($level == 0) {
                                                    // CẤP 1: In đậm, Viết hoa, Màu nền nhẹ
                                                    $style = "font-weight: bold; color: #000; background-color: #f0f0f0;";
                                                    $name_display = mb_strtoupper($item['name'], 'UTF-8');
                                                } else {
                                                    // CẤP CON: Thụt đầu dòng + Icon cây thư mục
                                                    $style = "color: #333;";
                                                    // Tạo khoảng trắng thụt đầu dòng (4 khoảng trắng mỗi cấp)
                                                    $indent = str_repeat("&nbsp;&nbsp;&nbsp;&nbsp;", $level);
                                                    $icon = "└─ ";
                                                    $name_display = $indent . $icon . $item['name'];
                                                }

                                                echo '<option value="' . $item['id'] . '" style="' . $style . '">';
                                                echo $name_display;
                                                echo '</option>';

                                                // Xóa phần tử đã lặp để tối ưu
                                                unset($categories[$key]);

                                                // Gọi đệ quy, tăng level lên 1
                                                showCategoryTree($categories, $item['id'], $level + 1);
                                            }
                                        }
                                    }
                                }

                                // Gọi hàm để chạy
                                showCategoryTree($categories);
                                ?>

                                <option value="create_new" style="font-weight:bold; color:#d63384;">+ Tạo danh mục
                                    mới...</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div style="margin-top: 25px;">
                    <label>📸 Thư viện ảnh sản phẩm (Ảnh bìa & Slide):</label>
                    <div class="gallery-upload-box" onclick="document.getElementById('galleryInput').click()">
                        <p style="margin:0; font-weight:bold; color:var(--primary-color);">+ Nhấn để chọn ảnh hoặc Kéo
                            thả vào đây</p>
                        <p style="margin:5px 0 0 0; font-size:13px; color:#888;">Hỗ trợ JPG, PNG. (Giữ phím Ctrl để chọn
                            nhiều ảnh)</p>
                        <input type="file" id="galleryInput" name="gallery[]" multiple accept="image/*"
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
                            <tr>
                                <td><input type="text" name="specs[0][name]" value="Loại da phù hợp"></td>
                                <td><input type="text" name="specs[0][value]" value="Mọi loại da"></td>
                                <td style="text-align:center;"><button type="button" class="btn btn-danger-sm"
                                        onclick="this.closest('tr').remove()">✕</button></td>
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
                        placeholder="Ví dụ: G2033552 - C18-36 Acid Triglyceride..."></textarea>
                </div>
            </div>

            <div class="section">
                <h3 class="section-title">3. Công dụng sản phẩm (Uses)</h3>
                <p style="font-size: 13px; color: #636e72; margin-top: 0;">Khối nội dung mô tả công dụng chính (Ảnh +
                    Văn bản).</p>
                <div id="usesContainer" class="content-list"></div>
                <button type="button" class="btn-add-big" onclick="addUsesBlock()">
                    <span style="font-size: 24px; line-height: 0; margin-top:-3px;">+</span> THÊM KHỐI CÔNG DỤNG
                </button>
            </div>

            <div class="section">
                <h3 class="section-title">4. Hướng dẫn sử dụng (Usage)</h3>
                <p style="font-size: 13px; color: #636e72; margin-top: 0;">Các bước sử dụng sản phẩm (Ảnh + Hướng dẫn).
                </p>
                <div id="usageContainer" class="content-list"></div>
                <button type="button" class="btn-add-big" onclick="addUsageBlock()">
                    <span style="font-size: 24px; line-height: 0; margin-top:-3px;">+</span> THÊM BƯỚC SỬ DỤNG
                </button>
            </div>

            <div class="section">
                <h3 class="section-title">5. Mô tả chi tiết (Description)</h3>
                <p style="font-size: 13px; color: #636e72; margin-top: 0;">Xây dựng bài viết quảng cáo chi tiết (Ảnh +
                    Văn bản).</p>
                <div id="contentContainer" class="content-list"></div>
                <button type="button" class="btn-add-big" onclick="addNewBlock()">
                    <span style="font-size: 24px; line-height: 0; margin-top:-3px;">+</span> THÊM KHỐI MÔ TẢ
                </button>
            </div>

            <div class="section">
                <h3 class="section-title">6. Review & Feedback (Đánh giá thực tế)</h3>
                <p style="font-size: 13px; color: #636e72; margin-top: 0;">Thêm ảnh review thực tế từ khách hàng hoặc
                    KOLs (Ảnh + Lời bình).</p>
                <div id="reviewContainer" class="content-list"></div>
                <button type="button" class="btn-add-big" onclick="addReviewBlock()">
                    <span style="font-size: 24px; line-height: 0; margin-top:-3px;">+</span> THÊM KHỐI REVIEW
                </button>
            </div>

            <div class="sticky-bottom">
                <button type="button" class="btn btn-secondary" onclick="window.history.back()">
                    <span style="font-size:16px;">↩️</span> Hủy bỏ
                </button>
                <button type="submit" class="btn btn-primary btn-save" id="btnSave">
                    <span class="btn-icon">💾</span>
                    <span class="btn-text">LƯU SẢN PHẨM</span>
                    <div class="loader"></div>
                </button>
            </div>

        </form>
    </div>

    <script src="./js/new_product.js">

    </script>

</body>

</html>