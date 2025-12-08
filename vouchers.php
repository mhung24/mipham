<?php
// Tên file này là vouchers.php, giả sử nó được đặt ngang hàng với thư mục config/

// 1. NHÚNG FILE KẾT NỐI
require_once __DIR__ . '/config/connect.php';

$vouchers = [];

if (isset($pdo) && $pdo) {
    try {
        // Cập nhật SELECT để lấy tất cả dữ liệu cần thiết cho giao diện và Modal
        $sql = "SELECT code, discount_text, condition_text, quantity, used_count, 
                       start_date, end_date, discount_amount, min_order_amount
                FROM vouchers 
                WHERE is_active = 1 
                ORDER BY discount_amount DESC 
                LIMIT 4";

        $stmt = $pdo->query($sql);
        $vouchers = $stmt->fetchAll();

    } catch (\PDOException $e) {
        // Xử lý lỗi nếu truy vấn thất bại
        echo "<div class='container py-3'><p style='color: red;'>Lỗi truy vấn voucher: " . $e->getMessage() . "</p></div>";
        $vouchers = [];
    }
}
?>

<div class="container py-4" style="background-color: #f7f7f7;">
    <h2 class="text-center mb-4 voucher-title">Mã khuyến mại</h2>
    <div class="row g-3">

        <?php
        if (!empty($vouchers)):
            foreach ($vouchers as $voucher):

                $quantity = (int) $voucher['quantity'];
                $used_count = (int) $voucher['used_count'];

                if ($quantity > 0) {
                    $progress = round(($used_count / $quantity) * 100);
                } else {
                    $progress = 0;
                }

                $discount_amount_formatted = number_format($voucher['discount_amount'], 0, ',', '.');
                $min_order_amount_formatted = number_format($voucher['min_order_amount'], 0, ',', '.');
                ?>
                <div class="col-lg-3 col-md-6 col-sm-12">
                    <div class="voucher-card p-3 bg-white position-relative overflow-hidden">

                        <div class="ribbon-effect"></div>

                        <div class="d-flex align-items-center mb-3">
                            <div class="voucher-icon-box flex-shrink-0 me-3">
                                <i class="bi bi-gift-fill text-white fs-3"></i>
                            </div>

                            <div>
                                <p class="mb-0 fw-bold fs-5 text-dark">
                                    <?php echo htmlspecialchars($voucher['discount_text']); ?></p>
                                <p class="small text-muted mb-0"><?php echo htmlspecialchars($voucher['condition_text']); ?></p>
                            </div>
                        </div>

                        <div class="progress voucher-progress-bar mb-3" style="height: 5px;">
                            <div class="progress-bar bg-danger" role="progressbar" style="width: <?php echo $progress; ?>%;"
                                aria-valuenow="<?php echo $progress; ?>" aria-valuemin="0" aria-valuemax="100">
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center">

                            <a href="#" class="text-danger small fw-bold text-decoration-none view-detail-btn"
                                data-bs-toggle="modal" data-bs-target="#voucherDetailModal"
                                data-discount-text="<?php echo htmlspecialchars($voucher['discount_text']); ?>"
                                data-condition-text="<?php echo htmlspecialchars($voucher['condition_text']); ?>"
                                data-voucher-code="<?php echo htmlspecialchars($voucher['code']); ?>"
                                data-start-date="<?php echo htmlspecialchars($voucher['start_date']); ?>"
                                data-end-date="<?php echo htmlspecialchars($voucher['end_date']); ?>"
                                data-full-description="GIẢM <?php echo $discount_amount_formatted; ?>Đ CHO HOÁ ĐƠN TỪ <?php echo $min_order_amount_formatted; ?>Đ">Chi
                                tiết</a>

                            <button class="btn btn-dark btn-sm rounded-0 copy-btn"
                                data-code="<?php echo htmlspecialchars($voucher['code']); ?>">
                                Sao chép
                            </button>
                        </div>

                    </div>
                </div>
            <?php endforeach;
        else: ?>
            <div class="col-12 text-center py-5">
                <p class="text-muted">Hiện chưa có mã khuyến mại nào đang áp dụng.</p>
            </div>
        <?php endif; ?>

    </div>
</div>
<div class="modal fade" id="voucherDetailModal" tabindex="-1" aria-labelledby="voucherDetailModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-0">

            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold" id="voucherDetailModalLabel">Chi tiết Mã khuyến mại</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body pt-0">

                <div class="discount-summary pb-3 mb-3 border-bottom">
                    <p class="fs-4 fw-bold text-dark mb-1" id="modalDiscountText"></p>
                    <p class="text-muted" id="modalConditionText"></p>
                </div>

                <div class="bg-light p-3 mb-3 d-flex align-items-center justify-content-between">
                    <div class="flex-grow-1">
                        <span class="d-block text-muted small mb-1">Mã giảm giá</span>
                        <span class="fs-5 fw-bold text-dark" id="modalVoucherCode"></span>
                    </div>
                    <button class="btn btn-sm btn-outline-dark ms-3 copy-btn-modal" id="modalCopyButton"
                        style="width: 100px;">
                        <i class="bi bi-files me-1"></i> Sao chép
                    </button>
                </div>

                <div class="mb-3">
                    <span class="d-block text-muted small mb-1">Áp dụng từ</span>
                    <span id="modalExpiryDate" class="fw-bold"></span>
                </div>

                <div class="mb-3">
                    <span class="d-block fw-bold mb-1 border-bottom pb-2">Chi tiết</span>
                    <p class="mb-1" id="modalFullDescription"></p>
                    <p class="text-muted small">Có hiệu lực từ <span id="modalEffectDate"></span></p>
                    <p class="text-danger small mt-2">Không áp dụng đồng thời CTKM khác</p>
                </div>

            </div>

            <div class="modal-footer border-0">
                <button type="button" class="btn btn-light rounded-0" data-bs-dismiss="modal">Đóng</button>
                <button type="button" class="btn btn-dark rounded-0 copy-btn-modal" id="modalCopyFooterButton">Sao
                    chép</button>
            </div>

        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const voucherDetailModal = document.getElementById('voucherDetailModal');

        // =======================================================================
        // 1. CHỨC NĂNG BƠM DỮ LIỆU VÀO MODAL KHI MỞ
        // =======================================================================

        voucherDetailModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;

            const discountText = button.getAttribute('data-discount-text');
            const conditionText = button.getAttribute('data-condition-text');
            const voucherCode = button.getAttribute('data-voucher-code');
            const startDate = button.getAttribute('data-start-date');
            const endDate = button.getAttribute('data-end-date');
            const fullDescription = button.getAttribute('data-full-description');

            const formattedDateRange = `${startDate} - ${endDate}`;

            document.getElementById('modalDiscountText').textContent = discountText;
            document.getElementById('modalConditionText').textContent = conditionText;
            document.getElementById('modalVoucherCode').textContent = voucherCode;
            document.getElementById('modalExpiryDate').textContent = formattedDateRange;
            document.getElementById('modalEffectDate').textContent = formattedDateRange;
            document.getElementById('modalFullDescription').textContent = fullDescription;

            const copyButtonsModal = voucherDetailModal.querySelectorAll('.copy-btn-modal');
            copyButtonsModal.forEach(btn => {
                btn.setAttribute('data-code', voucherCode);
            });
        });

        // =======================================================================
        // 2. CHỨC NĂNG SAO CHÉP (Dùng chung cho thẻ và Modal)
        // =======================================================================

        const copyHandler = function (event) {
            const button = event.currentTarget;
            const voucherCode = button.getAttribute('data-code');

            if (!voucherCode) {
                alert("Không tìm thấy mã voucher.");
                return;
            }

            navigator.clipboard.writeText(voucherCode).then(() => {

                const isModalButton = button.classList.contains('copy-btn-modal');

                if (isModalButton) {
                    // Xử lý nút copy trong Modal
                    button.textContent = 'Đã copy!';
                    setTimeout(() => {
                        button.textContent = 'Sao chép';
                    }, 2000);
                } else {
                    // Xử lý nút copy trên thẻ
                    const originalText = button.textContent;
                    button.textContent = 'Đã copy!';
                    button.classList.add('btn-success');
                    button.classList.remove('btn-dark');

                    setTimeout(() => {
                        button.textContent = originalText;
                        button.classList.add('btn-dark');
                        button.classList.remove('btn-success');
                    }, 2000);
                }

            }).catch(err => {
                alert('Lỗi: Trình duyệt không thể sao chép tự động. Vui lòng thử lại.');
            });
        };

        document.querySelectorAll('.copy-btn').forEach(button => button.addEventListener('click', copyHandler));
        document.querySelectorAll('.copy-btn-modal').forEach(button => button.addEventListener('click', copyHandler));
    });
</script>

<style>
    .voucher-icon-box {
        width: 45px;
        height: 45px;
        background-color: #dc3545;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        box-shadow: 0 0 0 5px rgba(220, 53, 69, 0.2);
    }

    .voucher-progress-bar {
        background-color: #e9ecef;
        border-radius: 0;
    }

    .voucher-card {
        border: 1px solid #e0e0e0;
        border-radius: 5px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
    }

    .copy-btn {
        font-size: 0.9rem;
        padding: 6px 15px;
    }

    .ribbon-effect {
        position: absolute;
        top: 0;
        left: 0;
        width: 8px;
        height: 100%;
        background: repeating-linear-gradient(180deg, #f7f7f7, #f7f7f7 5px, #fff 5px, #fff 10px);
    }

    .voucher-card {
        padding-left: 20px !important;
    }

    /* CSS RIÊNG CHO MODAL */
    .modal-content {
        border-radius: 5px !important;
    }

    .discount-summary {
        border-bottom: 1px solid #eee;
    }

    #modalFullDescription {
        font-weight: 500;
    }
</style>