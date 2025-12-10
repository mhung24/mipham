<?php
session_start();
require_once 'config/connect.php';

// 1. CHẶN KHÁCH CHƯA ĐĂNG NHẬP
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Vui lòng đăng nhập để thanh toán!'); window.location.href='login.php';</script>";
    exit;
}

$user_id = $_SESSION['user_id'];
$error = '';

// 2. LẤY GIỎ HÀNG
$sql = "SELECT c.quantity, p.id, p.name, p.price, 
       (SELECT image_url FROM product_gallery WHERE product_id = p.id LIMIT 1) as thumbnail
        FROM cart c
        JOIN products p ON c.product_id = p.id
        WHERE c.user_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$user_id]);
$cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (empty($cart_items)) {
    header("Location: index.php");
    exit;
}

// Tính tổng tiền gốc
$total_money = 0;
foreach ($cart_items as $item) {
    $total_money += $item['price'] * $item['quantity'];
}

// 3. LẤY THÔNG TIN USER
$stmt_user = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt_user->execute([$user_id]);
$user = $stmt_user->fetch(PDO::FETCH_ASSOC);

// 4. XỬ LÝ POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $full_name = trim($_POST['full_name']);
    $phone     = trim($_POST['phone']);
    $address   = trim($_POST['address']);
    $note      = trim($_POST['note']);
    $voucher_code = trim($_POST['voucher_code'] ?? '');
    // Thêm phương thức thanh toán vào logic (nếu cần lưu vào DB thì bạn cần thêm cột payment_method vào bảng orders)
    $payment_method = $_POST['payment_method'] ?? 'cod'; 
    
    $discount_amount = 0;

    if (empty($full_name) || empty($phone) || empty($address)) {
        $error = "Vui lòng điền đầy đủ thông tin nhận hàng!";
    } else {
        try {
            $pdo->beginTransaction();
            // Xử lý Voucher
            if (!empty($voucher_code)) {
                $stmt_v = $pdo->prepare("SELECT * FROM vouchers WHERE code = :code AND is_active = 1 FOR UPDATE");
                $stmt_v->execute([':code' => $voucher_code]);
                $voucher = $stmt_v->fetch(PDO::FETCH_ASSOC);
                
                if ($voucher && $voucher['used_count'] < $voucher['quantity'] && 
                    date('Y-m-d') <= $voucher['end_date'] && date('Y-m-d') >= $voucher['start_date'] &&
                    $total_money >= $voucher['min_order_amount']) {
                    
                    $discount_amount = $voucher['discount_amount'];
                    
                    // Cập nhật lượt dùng voucher
                    $stmt_upd = $pdo->prepare("UPDATE vouchers SET used_count = used_count + 1 WHERE id = :id");
                    $stmt_upd->execute([':id' => $voucher['id']]);
                } else {
                    $voucher_code = null; $discount_amount = 0;
                }
            }
            
            $final_total = $total_money - $discount_amount;
            if ($final_total < 0) $final_total = 0;

            // Lưu đơn hàng
            // Lưu ý: Nếu bảng orders của bạn chưa có cột 'payment_method', bạn có thể thêm vào hoặc ghi chú vào field 'note'
            $order_note = $note . " | TT qua: " . strtoupper($payment_method);

            $sql_order = "INSERT INTO orders (user_id, full_name, phone, address, note, voucher_code, discount_amount, total_money, status, created_at) 
                          VALUES (:uid, :name, :phone, :addr, :note, :v_code, :discount, :total, 'Đang xử lý', NOW())";
            $stmt_order = $pdo->prepare($sql_order);
            $stmt_order->execute([
                ':uid'=>$user_id,
                ':name'=>$full_name,
                ':phone'=>$phone,
                ':addr'=>$address,
                ':note'=>$order_note, // Gộp phương thức thanh toán vào ghi chú
                ':v_code'=>$voucher_code,
                ':discount'=>$discount_amount,
                ':total'=>$final_total
            ]);
            $order_id = $pdo->lastInsertId();

            // Lưu chi tiết đơn hàng
            $sql_item = "INSERT INTO order_items (order_id, product_id, product_name, price, quantity) VALUES (:oid, :pid, :pname, :price, :qty)";
            $stmt_item = $pdo->prepare($sql_item);
            foreach ($cart_items as $item) {
                $stmt_item->execute([':oid'=>$order_id,':pid'=>$item['id'],':pname'=>$item['name'],':price'=>$item['price'],':qty'=>$item['quantity']]);
            }

            // Xóa giỏ hàng
            $stmt_del = $pdo->prepare("DELETE FROM cart WHERE user_id = ?");
            $stmt_del->execute([$user_id]);
            $_SESSION['cart_count'] = 0;
            
            $pdo->commit();
            header("Location: order_success.php?id=" . $order_id);
            exit;
        } catch (Exception $e) {
            $pdo->rollBack();
            $error = "Lỗi hệ thống: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thanh toán | Cocolux Clone</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        :root {
            --primary-color: #d0021b;
            --secondary-bg: #f5f5fa;
            --qr-bg: #f8fafc;
        }
        body { background-color: var(--secondary-bg); font-family: 'Segoe UI', sans-serif; }
        
        .checkout-card {
            background: #fff; border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.03);
            padding: 30px; margin-bottom: 20px; border: 1px solid #eee;
        }
        .section-title { font-size: 18px; font-weight: 700; margin-bottom: 20px; color: #333; display: flex; align-items: center; }
        .section-title i { margin-right: 10px; color: var(--primary-color); }

        .form-control:focus { border-color: var(--primary-color); box-shadow: 0 0 0 3px rgba(208, 2, 27, 0.1); }
        .form-label { font-weight: 600; font-size: 13px; color: #555; }

        .summary-sticky { position: sticky; top: 20px; }
        .summary-item { display: flex; align-items: center; padding-bottom: 15px; margin-bottom: 15px; border-bottom: 1px dashed #eee; }
        .summary-img { width: 65px; height: 65px; object-fit: cover; border-radius: 8px; border: 1px solid #f0f0f0; margin-right: 15px; }
        .product-name { font-size: 14px; font-weight: 600; color: #333; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }

        /* Payment Radio Custom */
        .payment-option {
            border: 1px solid #eee; border-radius: 8px; padding: 15px; margin-bottom: 10px; cursor: pointer; transition: all 0.2s;
        }
        .payment-option:hover { background: #f9f9f9; border-color: #ccc; }
        .payment-option.active { border-color: var(--primary-color); background: #fff5f5; }
        .form-check-input:checked { background-color: var(--primary-color); border-color: var(--primary-color); }

        /* QR CODE STYLES */
        .payment-qr-area {
            display: none; /* Ẩn mặc định */
            text-align: center; margin-top: 20px; background: var(--qr-bg); 
            padding: 20px; border-radius: 10px; border: 1px solid #e2e8f0;
            animation: fadeIn 0.5s ease;
        }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

        .timer-badge {
            display: inline-block; background: #fffbeb; color: #b45309;
            font-size: 13px; font-weight: 600; padding: 6px 12px;
            border-radius: 20px; margin-bottom: 15px; border: 1px solid #fcd34d;
        }
        .qr-wrapper {
            position: relative; width: 200px; margin: 0 auto 15px; padding: 10px;
            background: white; border-radius: 10px; border: 1px solid #eee;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }
        .qr-wrapper img { width: 100%; display: block; }
        .qr-expired-overlay {
            position: absolute; top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(255,255,255,0.95); display: none;
            flex-direction: column; justify-content: center; align-items: center;
            border-radius: 10px; z-index: 10;
        }

        /* Nút Đặt Hàng */
        .btn-order {
            background: linear-gradient(90deg, #d0021b 0%, #ff4d4d 100%);
            border: none; border-radius: 50px; padding: 14px;
            font-size: 16px; font-weight: 700; text-transform: uppercase;
            color: white; width: 100%; box-shadow: 0 5px 15px rgba(208, 2, 27, 0.3);
            transition: all 0.3s ease;
        }
        .btn-order:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(208, 2, 27, 0.4); color: white; }
        .btn-order.banking-mode { background: #10b981; box-shadow: 0 5px 15px rgba(16, 185, 129, 0.3); } /* Màu xanh lá khi chuyển khoản */
        .btn-order.banking-mode:hover { box-shadow: 0 8px 20px rgba(16, 185, 129, 0.4); }

        .btn-refresh { background-color: #2563eb; color: white; padding: 8px 16px; font-size: 14px; border-radius: 6px; border: none; cursor: pointer; margin-top: 5px; }
    </style>
</head>
<body>

    <?php if (file_exists('header.php')) include 'header.php'; ?>
    <?php if (file_exists('menu.php')) include 'menu.php'; ?>

    <div class="container py-5">
        
        <?php if ($error): ?>
            <div class="alert alert-danger shadow-sm border-0 rounded-3 mb-4">
                <i class="bi bi-exclamation-octagon-fill me-2"></i> <?= $error ?>
            </div>
        <?php endif; ?>

        <form action="checkout.php" method="POST" id="checkoutForm">
            <div class="row g-4"> 
                <div class="col-lg-7">
                    <div class="checkout-card h-100">
                        <h4 class="section-title">
                            <i class="bi bi-geo-alt-fill"></i> Thông tin giao hàng
                        </h4>
                        
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Họ và tên <span class="text-danger">*</span></label>
                                <input type="text" name="full_name" class="form-control" 
                                       value="<?= htmlspecialchars($user['full_name'] ?? '') ?>" required placeholder="Nguyễn Văn A">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Số điện thoại <span class="text-danger">*</span></label>
                                <input type="text" name="phone" class="form-control" 
                                       value="<?= htmlspecialchars($user['phone'] ?? '') ?>" required placeholder="0988...">
                            </div>
                            <div class="col-12">
                                <label class="form-label">Địa chỉ nhận hàng <span class="text-danger">*</span></label>
                                <textarea name="address" class="form-control" rows="2" required placeholder="Số nhà, ngõ, tên đường, phường/xã..."><?= htmlspecialchars($user['address'] ?? '') ?></textarea>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Ghi chú (Tùy chọn)</label>
                                <textarea name="note" class="form-control" rows="2" placeholder="Ví dụ: Giao giờ hành chính, gọi trước khi giao..."></textarea>
                            </div>
                        </div>

                        <div class="mt-4 pt-3 border-top">
                            <h4 class="section-title" style="font-size: 16px;">
                                <i class="bi bi-credit-card-2-front-fill text-primary"></i> Phương thức thanh toán
                            </h4>
                            
                            <div class="payment-option active" id="opt-cod" onclick="selectPayment('cod')">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="payment_method" id="cod" value="cod" checked>
                                    <label class="form-check-label fw-bold d-flex align-items-center" for="cod">
                                        <img src="https://cdn-icons-png.flaticon.com/512/2331/2331941.png" width="24" class="me-3">
                                        Thanh toán khi nhận hàng (COD)
                                    </label>
                                </div>
                            </div>

                            <div class="payment-option" id="opt-banking" onclick="selectPayment('banking')">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="payment_method" id="banking" value="banking">
                                    <label class="form-check-label fw-bold d-flex align-items-center" for="banking">
                                        <img src="https://cdn-icons-png.flaticon.com/512/3013/3013661.png" width="24" class="me-3">
                                        Chuyển khoản Ngân hàng (QR Code)
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-5">
                    <div class="summary-sticky"> 
                        <div class="checkout-card">
                            <h4 class="section-title">
                                <i class="bi bi-bag-check-fill"></i> Đơn hàng của bạn
                            </h4>

                            <div class="order-summary-list pe-2" style="max-height: 250px; overflow-y: auto;">
                                <?php foreach ($cart_items as $item): ?>
                                    <div class="summary-item">
                                        <div class="position-relative">
                                            <img src="<?= $item['thumbnail'] ?: 'https://via.placeholder.com/60' ?>" class="summary-img">
                                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-secondary" style="font-size: 10px;">
                                                <?= $item['quantity'] ?>
                                            </span>
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="product-name"><?= htmlspecialchars($item['name']) ?></div>
                                            <div class="d-flex justify-content-between align-items-center mt-1">
                                                <small class="text-muted"><?= number_format($item['price'], 0, ',', '.') ?>đ</small>
                                                <span class="text-danger fw-bold">
                                                    <?= number_format($item['price'] * $item['quantity'], 0, ',', '.') ?>đ
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>

                            <div class="mt-3">
                                <label class="form-label small text-muted text-uppercase fw-bold">Mã khuyến mãi</label>
                                <div class="input-group voucher-group">
                                    <input type="text" id="voucher_input" class="form-control" placeholder="Nhập mã giảm giá">
                                    <button class="btn btn-outline-secondary" type="button" onclick="checkVoucher()">Áp dụng</button>
                                </div>
                                <div id="voucher_message" class="small mt-2 fw-bold"></div>
                            </div>

                            <div class="mt-4 pt-3 border-top">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">Tạm tính:</span>
                                    <span class="fw-bold"><?= number_format($total_money, 0, ',', '.') ?>đ</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">Phí vận chuyển:</span>
                                    <span class="text-success fw-bold">Miễn phí</span>
                                </div>
                                <div class="d-flex justify-content-between mb-3 d-none" id="discount_row">
                                    <span class="text-success">Giảm giá:</span>
                                    <span class="text-success fw-bold" id="discount_display">-0đ</span>
                                </div>
                                
                                <div class="d-flex justify-content-between border-top pt-3 mb-2">
                                    <span class="fw-bold fs-5 text-dark">TỔNG CỘNG</span>
                                    <span class="fw-bold fs-3 text-danger" id="total_display" data-original="<?= $total_money ?>">
                                        <?= number_format($total_money, 0, ',', '.') ?>đ
                                    </span>
                                </div>

                                <div id="qr-area" class="payment-qr-area">
                                    <div class="timer-badge">
                                        ⏱ Hết hạn sau: <span id="countdown-timer">10:00</span>
                                    </div>

                                    <div class="qr-wrapper">
                                        <img id="qr-image" src="" alt="QR Code">
                                        
                                        <div class="qr-expired-overlay" id="expired-overlay">
                                            <div style="font-weight: bold; color: #ef4444; margin-bottom: 5px;">Mã QR đã hết hạn</div>
                                            <button type="button" class="btn-refresh" onclick="resetTimer()">Lấy mã mới</button>
                                        </div>
                                    </div>
                                    <p style="font-size: 13px; color: #666;">
                                        Nội dung CK: <strong id="qr-content">DH<?= time() ?></strong><br>
                                        Mở App ngân hàng quét mã để thanh toán.
                                    </p>
                                </div>

                                <input type="hidden" name="voucher_code" id="hidden_voucher_code" value="">
                                <input type="hidden" name="discount_amount" id="hidden_discount_amount" value="0">

                                <button type="submit" id="btn-submit-order" class="btn btn-order shadow mt-3">
                                    ĐẶT HÀNG NGAY <i class="bi bi-arrow-right ms-2"></i>
                                </button>
                                
                                <div class="text-center mt-3">
                                    <a href="cart.php" class="text-muted small text-decoration-none hover-link">
                                        <i class="bi bi-chevron-left"></i> Quay lại giỏ hàng
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <?php if (file_exists('footer.php')) include 'footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
    // --- 1. LOGIC CHỌN PHƯƠNG THỨC THANH TOÁN ---
    let currentTotal = <?= $total_money ?>;
    let countdownInterval;

    function selectPayment(method) {
        // Cập nhật UI Radio box
        document.getElementById('opt-cod').classList.remove('active');
        document.getElementById('opt-banking').classList.remove('active');
        document.getElementById('opt-' + method).classList.add('active');
        document.getElementById(method).checked = true;

        const qrArea = document.getElementById('qr-area');
        const submitBtn = document.getElementById('btn-submit-order');

        if (method === 'banking') {
            // Hiện QR
            qrArea.style.display = 'block';
            submitBtn.innerHTML = '<i class="bi bi-check-circle-fill me-2"></i> XÁC NHẬN ĐÃ CHUYỂN KHOẢN';
            submitBtn.classList.add('banking-mode');
            updateQRCode(); // Tạo mã QR
            startTimer(600); // Bắt đầu đếm 10 phút
        } else {
            // Ẩn QR (Về COD)
            qrArea.style.display = 'none';
            submitBtn.innerHTML = 'ĐẶT HÀNG NGAY <i class="bi bi-arrow-right ms-2"></i>';
            submitBtn.classList.remove('banking-mode');
            clearInterval(countdownInterval); // Dừng đếm
        }
    }

    // --- 2. LOGIC TẠO MÃ QR ĐỘNG ---
    function updateQRCode() {
        // Lấy số tiền hiện tại (đã trừ voucher nếu có)
        // Lưu ý: currentTotal được cập nhật ở hàm checkVoucher
        let orderContent = 'DH' + Math.floor(Date.now() / 1000); // Tạo mã đơn giả lập để demo nội dung
        document.getElementById('qr-content').innerText = orderContent;
        
        // Link API VietQR (Thay số tài khoản của bạn vào đây)
        // Cấu trúc: https://img.vietqr.io/image/[BankID]-[AccountNo]-[Template].png?amount=[Amount]&addInfo=[Content]
        let bankId = 'MB'; // Ví dụ MB Bank
        let accountNo = '0000000000'; // Số tài khoản demo
        let url = `https://img.vietqr.io/image/${bankId}-${accountNo}-compact.png?amount=${currentTotal}&addInfo=${orderContent}&accountName=COCOLUX SHOP`;
        
        document.getElementById('qr-image').src = url;
    }

    // --- 3. ĐỒNG HỒ ĐẾM NGƯỢC ---
    function startTimer(duration) {
        let timer = duration, minutes, seconds;
        const display = document.getElementById('countdown-timer');
        const overlay = document.getElementById('expired-overlay');
        const submitBtn = document.getElementById('btn-submit-order');
        
        // Reset trạng thái
        clearInterval(countdownInterval);
        overlay.style.display = 'none';
        submitBtn.disabled = false;
        display.style.color = '#b45309';

        countdownInterval = setInterval(function () {
            minutes = parseInt(timer / 60, 10);
            seconds = parseInt(timer % 60, 10);

            minutes = minutes < 10 ? "0" + minutes : minutes;
            seconds = seconds < 10 ? "0" + seconds : seconds;

            display.textContent = minutes + ":" + seconds;

            if (--timer < 0) {
                // Hết giờ
                clearInterval(countdownInterval);
                display.textContent = "00:00";
                display.style.color = "red";
                overlay.style.display = "flex";
                submitBtn.disabled = true; // Khóa nút đặt hàng
                submitBtn.innerText = "Hết thời gian thanh toán";
            }
        }, 1000);
    }

    function resetTimer() {
        startTimer(600);
        updateQRCode(); // Load lại QR cho chắc
        const submitBtn = document.getElementById('btn-submit-order');
        submitBtn.innerHTML = '<i class="bi bi-check-circle-fill me-2"></i> XÁC NHẬN ĐÃ CHUYỂN KHOẢN';
        submitBtn.disabled = false;
    }

    // --- 4. LOGIC VOUCHER (GIỮ NGUYÊN CỦA BẠN, CÓ CHỈNH SỬA ĐỂ UPDATE SỐ TIỀN QR) ---
    function checkVoucher() {
        let code = document.getElementById('voucher_input').value;
        let originalTotal = document.getElementById('total_display').getAttribute('data-original');
        let msgBox = document.getElementById('voucher_message');
        
        if(!code) {
            msgBox.className = 'small mt-2 fw-bold text-danger';
            msgBox.innerHTML = '<i class="bi bi-x-circle"></i> Vui lòng nhập mã!';
            return;
        }

        const formData = new FormData();
        formData.append('code', code);
        formData.append('total_order', originalTotal);

        let btn = document.querySelector('button[onclick="checkVoucher()"]');
        let oldText = btn.innerText;
        btn.innerText = '...';
        btn.disabled = true;

        fetch('api_check_voucher.php', { method: 'POST', body: formData })
        .then(res => res.text())
        .then(text => {
            try { return JSON.parse(text); } 
            catch (e) { throw new Error("Lỗi hệ thống."); }
        })
        .then(data => {
            btn.innerText = oldText;
            btn.disabled = false;
            if(data.status === 'success') {
                msgBox.className = 'small mt-2 fw-bold text-success';
                msgBox.innerHTML = '<i class="bi bi-check-circle-fill"></i> ' + data.message;
                
                let discount = parseFloat(data.discount_amount);
                let newTotal = Math.max(0, parseFloat(originalTotal) - discount);
                
                // CẬP NHẬT BIẾN TOÀN CỤC CHO QR CODE
                currentTotal = newTotal; 

                document.getElementById('discount_row').classList.remove('d-none');
                document.getElementById('discount_display').innerText = '-' + new Intl.NumberFormat('vi-VN').format(discount) + 'đ';
                document.getElementById('total_display').innerText = new Intl.NumberFormat('vi-VN').format(newTotal) + 'đ';
                document.getElementById('hidden_voucher_code').value = data.code;
                document.getElementById('hidden_discount_amount').value = discount;
                document.getElementById('voucher_input').disabled = true;
                btn.disabled = true; btn.innerText = 'Đã dùng';

                // NẾU ĐANG Ở TAB BANKING THÌ CẬP NHẬT LẠI QR THEO GIÁ MỚI
                if(document.getElementById('banking').checked) {
                    updateQRCode();
                }

            } else {
                msgBox.className = 'small mt-2 fw-bold text-danger';
                msgBox.innerHTML = '<i class="bi bi-x-circle-fill"></i> ' + data.message;
            }
        })
        .catch(err => {
            msgBox.className = 'small mt-2 fw-bold text-danger';
            msgBox.innerText = err.message;
            btn.innerText = oldText; btn.disabled = false;
        });
    }
    </script>
</body>
</html>