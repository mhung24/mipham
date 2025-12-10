<?php
require_once 'config/connect.php';

$product_id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($product_id == 0)
    die("Sản phẩm không tồn tại.");

$sql = "SELECT p.*, b.name as brand_name 
        FROM products p 
        LEFT JOIN brands b ON p.brand_id = b.id 
        WHERE p.id = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute([':id' => $product_id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product)
    die("Không tìm thấy sản phẩm.");

$stmt_gallery = $pdo->prepare("SELECT image_url FROM product_gallery WHERE product_id = :id");
$stmt_gallery->execute([':id' => $product_id]);
$gallery = $stmt_gallery->fetchAll(PDO::FETCH_COLUMN);

if (empty($gallery))
    $gallery[] = 'img/no-image.png';

$stmt_specs = $pdo->prepare("SELECT * FROM product_specifications WHERE product_id = :id");
$stmt_specs->execute([':id' => $product_id]);
$specs = $stmt_specs->fetchAll(PDO::FETCH_ASSOC);

$stmt_blocks = $pdo->prepare("SELECT * FROM product_content_blocks WHERE product_id = :id ORDER BY id ASC");
$stmt_blocks->execute([':id' => $product_id]);
$all_blocks = $stmt_blocks->fetchAll(PDO::FETCH_ASSOC);

$blocks_use = [];
$blocks_usage = [];
$blocks_review = [];
$blocks_feedback = [];

foreach ($all_blocks as $block) {
    if ($block['section_type'] == 'use')
        $blocks_use[] = $block;
    if ($block['section_type'] == 'usage')
        $blocks_usage[] = $block;
    if ($block['section_type'] == 'review')
        $blocks_review[] = $block;
    if ($block['section_type'] == 'feedback')
        $blocks_feedback[] = $block;
}

$stmt_vouchers = $pdo->prepare("SELECT * FROM vouchers WHERE is_active = 1 AND end_date >= CURDATE() ORDER BY discount_amount DESC");
$stmt_vouchers->execute();
$vouchers = $stmt_vouchers->fetchAll(PDO::FETCH_ASSOC);

$price = $product['price'];
$old_price = $product['old_price'];
$discount = ($old_price > $price) ? round((($old_price - $price) / $old_price) * 100) : 0;
$breadcrumbs = [];
if (!empty($product['category_id'])) {
    $curr_cat_id = $product['category_id'];

    while ($curr_cat_id) {
        $stmt_cat = $pdo->prepare("SELECT id, name, parent_id FROM categories WHERE id = ?");
        $stmt_cat->execute([$curr_cat_id]);
        $curr_cat = $stmt_cat->fetch(PDO::FETCH_ASSOC);

        if ($curr_cat) {
            $breadcrumbs[] = $curr_cat;
            $curr_cat_id = $curr_cat['parent_id'];
        } else {
            break;
        }
    }
    $breadcrumbs = array_reverse($breadcrumbs);
}
$stmt_reviews = $pdo->prepare("SELECT * FROM product_reviews WHERE product_id = ? AND status = 'approved' ORDER BY comment_date DESC");
$stmt_reviews->execute([$product_id]);
$reviews = $stmt_reviews->fetchAll(PDO::FETCH_ASSOC);

$count_reviews = count($reviews);
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($product['name']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="css/product_detail.css">
    <link rel="stylesheet" href="./css/menu.css">
</head>

<body>
    <?php include 'header.php'; ?>
    <?php include 'menu.php'; ?>

    <div class="container container-main">
        <nav aria-label="breadcrumb" style="margin-bottom: 15px; margin-top: -30px;">
            <ol class="breadcrumb" style="background: transparent; padding: 0; font-size: 14px;">
                <li class="breadcrumb-item">
                    <a href="index.php" style="color: #666; text-decoration: none; font-weight: 600">Trang chủ</a>
                </li>

                <?php foreach ($breadcrumbs as $cat): ?>
                    <li class="breadcrumb-item">
                        <a href="category.php?id=<?= $cat['id'] ?>" style="color: #666; text-decoration: none;">
                            <?= htmlspecialchars($cat['name']) ?>
                        </a>
                    </li>
                <?php endforeach; ?>

                <li class="breadcrumb-item active" aria-current="page" style="color: #333; font-weight: 500;">
                    <?= htmlspecialchars($product['name']) ?>
                </li>
            </ol>
        </nav>

        <div class="row">
            <div class="col-lg-4 col-md-5">
                <div class="main-image-container">
                    <img id="mainImage" src="<?= htmlspecialchars($gallery[0]) ?>" alt="Ảnh chính">
                </div>
                <div class="thumb-list">
                    <?php foreach ($gallery as $index => $img): ?>
                        <div class="thumb-item <?= $index == 0 ? 'active' : '' ?>"
                            onclick="changeImage(this, '<?= htmlspecialchars($img) ?>')">
                            <img src="<?= htmlspecialchars($img) ?>" alt="Thumb">
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="col-lg-5 col-md-7">
                <div class="product-brand"><?= htmlspecialchars($product['brand_name'] ?? 'No Brand') ?></div>
                <h1 class="product-title"><?= htmlspecialchars($product['name']) ?></h1>

                <div class="product-meta">
                    <span>Mã SKU: <strong><?= htmlspecialchars($product['sku']) ?></strong></span>
                    <span class="mx-2">|</span>
                    <span>Tình trạng: <span
                            class="text-success fw-bold"><?= $product['stock_quantity'] > 0 ? 'Còn hàng' : 'Hết hàng' ?></span></span>
                </div>

                <div class="price-box">
                    <span class="current-price"><?= number_format($price, 0, ',', '.') ?>đ</span>
                    <?php if ($discount > 0): ?>
                        <span class="old-price"><?= number_format($old_price, 0, ',', '.') ?>đ</span>
                        <span class="discount-tag">-<?= $discount ?>%</span>
                    <?php endif; ?>
                </div>

                <div class="alert alert-success py-2 px-3 mb-4" style="font-size: 13px; border: 1px dashed #198754;">
                    <i class="bi bi-gift-fill me-2"></i> <strong>Ưu đãi:</strong> Miễn phí vận chuyển cho đơn hàng từ
                    299k.
                </div>

                <div class="quantity-wrapper">
                    <span class="qty-label">Số lượng:</span>
                    <div class="qty-input-group">
                        <button class="qty-btn" onclick="updateQty(-1)">-</button>
                        <input type="number" id="qty" class="qty-input" value="1" min="1" readonly>
                        <button class="qty-btn" onclick="updateQty(1)">+</button>
                    </div>
                </div>

                <div class="btn-buy-group">
                    <?php
                    $thumb = !empty($gallery) && isset($gallery[0]) ? $gallery[0] : 'https://via.placeholder.com/300';
                    ?>

                    <button type="button" class="btn btn-danger btn-add-cart w-100 fw-bold py-3"
                        onclick="addToCart(this, event)" data-id="<?= $product['id'] ?>"
                        data-name="<?= htmlspecialchars($product['name']) ?>">
                        <i class="bi bi-bag-plus me-1"></i> THÊM VÀO GIỎ
                    </button>
                    <button class="btn-buy-now">MUA NGAY</button>
                </div>
            </div>

            <div class="col-lg-3 col-md-12 coupon-sidebar">
                <div class="coupon-title">Mã khuyến mại</div>
                <?php if (count($vouchers) > 0): ?>
                    <?php foreach ($vouchers as $v): ?>
                        <?php
                        $percent_used = ($v['quantity'] > 0) ? ($v['used_count'] / $v['quantity']) * 100 : 0;
                        ?>
                        <div class="coupon-item">
                            <div class="coupon-amount"><?= htmlspecialchars($v['discount_text']) ?></div>
                            <div class="coupon-desc"><?= htmlspecialchars($v['condition_text']) ?></div>
                            <div class="progress-mini">
                                <div class="progress-bar-red" style="width: <?= $percent_used ?>%;"></div>
                            </div>
                            <div class="coupon-footer">
                                <span style="font-size:12px; color:#999;">Đã dùng <?= $percent_used ?>%</span>
                                <button class="btn-copy" onclick="copyCode(this, '<?= $v['code'] ?>')">Sao chép</button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p style="font-size:13px; color:#888;">Không có mã giảm giá nào.</p>
                <?php endif; ?>
            </div>
        </div>

        <div class="content-section">
            <div class="product-nav">
                <a href="#info" class="active">Thông tin</a>
                <a href="#uses">Công dụng</a>
                <a href="#usage">Cách dùng</a>
                <a href="#review">Review</a>
                <a href="#feedback">Đánh giá (<?= count($blocks_feedback) ?>)</a>
            </div>

            <div id="info" class="scroll-target">
                <h3 class="section-heading">Thông số & Thành phần</h3>

                <?php if (!empty($specs)): ?>
                    <table class="specs-table">
                        <?php foreach ($specs as $s): ?>
                            <tr>
                                <td class="label"><?= htmlspecialchars($s['spec_name']) ?></td>
                                <td><?= htmlspecialchars($s['spec_value']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                <?php endif; ?>

                <div class="ingredients-box">
                    <strong style="display:block; margin-bottom:10px;">Thành phần chi tiết:</strong>
                    <?= nl2br(htmlspecialchars($product['ingredients'] ?? 'Đang cập nhật...')) ?>
                </div>
            </div>

            <div id="uses" class="scroll-target">
                <h3 class="section-heading">Công dụng</h3>
                <?php foreach ($blocks_use as $block): ?>
                    <div class="detail-block">
                        <div class="detail-text"><?= nl2br(htmlspecialchars($block['content_text'])) ?></div>
                        <?php if ($block['image_url']): ?>
                            <img style="width: 500px;" src="<?= htmlspecialchars($block['image_url']) ?>" alt="Công dụng">
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>

            <div id="usage" class="scroll-target">
                <h3 class="section-heading">Hướng dẫn sử dụng</h3>
                <?php foreach ($blocks_usage as $block): ?>
                    <div class="detail-block">
                        <div class="detail-text"><?= nl2br(htmlspecialchars($block['content_text'])) ?></div>
                        <?php if ($block['image_url']): ?>
                            <img style="width: 500px;" src="<?= htmlspecialchars($block['image_url']) ?>" alt="Cách dùng">
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>

            <div id="review" class="scroll-target">
                <h3 class="section-heading">Review Sản phẩm</h3>
                <?php foreach ($blocks_review as $block): ?>
                    <div class="detail-block">
                        <?php if ($block['image_url']): ?>
                            <img style="width: 500px;" src="<?= htmlspecialchars($block['image_url']) ?>"
                                alt="Marketing Review">
                        <?php endif; ?>
                        <div class="detail-text"><?= nl2br(htmlspecialchars($block['content_text'])) ?></div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div id="feedback" class="scroll-target mt-4">
                <h3 class="section-heading border-bottom pb-2">Đánh giá & Nhận xét (<?= $count_reviews ?>)</h3>

                <div class="card mb-4 border-0 bg-light">
                    <div class="card-body">
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <h6 class="fw-bold">Gửi đánh giá của bạn</h6>
                            <form action="submit_review.php" method="POST" enctype="multipart/form-data">
                                <input type="hidden" name="product_id" value="<?= $product['id'] ?>">

                                <div class="d-flex align-items-center mb-2">
                                    <div class="rate">
                                        <input type="radio" id="star5" name="rating" value="5" checked />
                                        <label for="star5" title="5 sao">5 stars</label>
                                        <input type="radio" id="star4" name="rating" value="4" />
                                        <label for="star4" title="4 sao">4 stars</label>
                                        <input type="radio" id="star3" name="rating" value="3" />
                                        <label for="star3" title="3 sao">3 stars</label>
                                        <input type="radio" id="star2" name="rating" value="2" />
                                        <label for="star2" title="2 sao">2 stars</label>
                                        <input type="radio" id="star1" name="rating" value="1" />
                                        <label for="star1" title="1 sao">1 star</label>
                                    </div>
                                </div>

                                <div class="mb-2">
                                    <textarea class="form-control" name="comment" rows="3"
                                        placeholder="Sản phẩm thế nào? Hãy chia sẻ cảm nhận của bạn..." required></textarea>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label small fw-bold">Thêm ảnh thực tế (nếu có):</label>
                                    <input type="file" class="form-control form-control-sm" name="review_image"
                                        accept="image/*">
                                </div>

                                <button type="submit" class="btn btn-danger btn-sm px-4">Gửi Đánh Giá</button>
                            </form>
                        <?php else: ?>
                            <div class="text-center py-3">
                                <p class="mb-2 text-muted">Bạn cần đăng nhập để gửi đánh giá và hình ảnh.</p>
                                <a href="login.php" class="btn btn-outline-danger btn-sm">Đăng nhập ngay</a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="review-list" id="review-list-container">
                    <?php if (isset($count_reviews) && $count_reviews > 0): ?>
                        <?php foreach ($reviews as $rv): ?>
                            <div class="review-item d-flex mb-3 border-bottom pb-3">

                                <div class="review-avatar me-3"
                                    style="width: 50px; height: 50px; background: #eee; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; color: #555;">
                                    <?= strtoupper(substr($rv['user_name'], 0, 1)) ?>
                                </div>

                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <strong class="text-dark"><?= htmlspecialchars($rv['user_name']) ?></strong>
                                        <small class="text-muted" style="font-size: 12px;">
                                            <?= date('d/m/Y H:i', strtotime($rv['comment_date'])) ?>
                                        </small>
                                    </div>

                                    <div class="star-display mb-1 text-warning">
                                        <?php for ($i = 1; $i <= 5; $i++):
                                            echo ($i <= $rv['rating']) ? '★' : '☆';
                                        endfor; ?>
                                    </div>

                                    <p class="mb-2 text-secondary" style="font-size: 14px;">
                                        <?= nl2br(htmlspecialchars($rv['comment'])) ?>
                                    </p>

                                    <?php if (!empty($rv['image'])): ?>
                                        <div class="mt-2">
                                            <a href="<?= htmlspecialchars($rv['image']) ?>" target="_blank">
                                                <img src="<?= htmlspecialchars($rv['image']) ?>"
                                                    class="review-img-preview border rounded"
                                                    style="max-width: 100px; max-height: 100px; object-fit: cover;"
                                                    alt="Review Image">
                                            </a>
                                        </div>
                                    <?php endif; ?>

                                    <?php if (isset($rv['is_admin_seed']) && $rv['is_admin_seed']): ?>
                                        <div class="badge bg-success bg-opacity-10 text-success mt-1">
                                            <i class="fa fa-check-circle"></i> Đã mua hàng
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-muted text-center py-4">Chưa có đánh giá nào. Hãy là người đầu tiên!</p>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </div>
    </div> <?php include 'footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // 1. Hàm đổi ảnh khi click vào ảnh nhỏ
        function changeImage(element, src) {
            const mainImage = document.getElementById("mainImage");
            if (mainImage) mainImage.src = src;

            document.querySelectorAll(".thumb-item").forEach((el) => el.classList.remove("active"));
            element.classList.add("active");
        }

        // 2. Hàm copy mã voucher
        function copyCode(btn, code) {
            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(code).then(() => {
                    let originalText = btn.innerText;
                    btn.innerText = "Đã chép";
                    btn.style.background = "#2ecc71";
                    setTimeout(() => {
                        btn.innerText = originalText;
                        btn.style.background = "#222";
                    }, 2000);
                });
            } else {
                alert("Mã voucher: " + code);
            }
        }

        const sections = document.querySelectorAll(".scroll-target");
        const navLinks = document.querySelectorAll(".product-nav a");

        window.addEventListener("scroll", () => {
            let current = "";
            sections.forEach((section) => {
                const sectionTop = section.offsetTop;
                if (scrollY >= sectionTop - 180) {
                    current = section.getAttribute("id");
                }
            });

            navLinks.forEach((link) => {
                link.classList.remove("active");
                if (current && link.getAttribute("href").includes(current)) {
                    link.classList.add("active");
                }
            });
        });

        function updateQty(change) {
            const input = document.getElementById("qty");
            if (input) {
                let currentVal = parseInt(input.value) || 1;
                let newVal = currentVal + change;
                if (newVal < 1) newVal = 1;
                input.value = newVal;
            }
        }

        function reloadReviewList() {
            fetch('get_reviews.php?product_id=123')
                .then(response => response.text())
                .then(html => {
                    document.getElementById('review-list-container').innerHTML = html;
                })
                .catch(err => console.error('Lỗi tải đánh giá:', err));
        }


    </script>

</body>



</html>