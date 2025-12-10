<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<link rel="stylesheet" href="css/footer.css">

<footer class="coco-footer">
    <div class="container">

        <div class="row">

            <div class="col-lg-4 col-md-6 mb-4">
                <img src="img/logo_cocolux.png" alt="Logo Cocolux" class="footer-logo">

                <p>
                    Cocolux là hệ thống phân phối mỹ phẩm chính hãng uy tín và dịch vụ chăm sóc khách hàng tận tâm.
                </p>
                <p>
                    Đến với Cocolux bạn có thể hoàn toàn yên tâm khi lựa chọn cho mình những bộ sản phẩm phù hợp.
                </p>
            </div>

            <div class="col-lg-2 col-md-3 col-6 mb-4">
                <h4>VỀ COCOLUX</h4>
                <a href="about.php">Về chúng tôi</a>
                <a href="about.php">Câu chuyện thương hiệu</a>
                <a href="contact.php">Liên hệ</a>
                <a href="contact.php">Hệ thống cửa hàng</a>
            </div>

            <div class="col-lg-3 col-md-3 col-6 mb-4">
                <h4>CHÍNH SÁCH</h4>
                <a href="terms_of_use.php">Điều khoản sử dụng</a>
                <a href="privacy_policy.php">Chính sách bảo mật thông tin</a>
                <a href="delivery_process.php">Chính sách giao hàng</a>
                <a href="membership.php">Khách hàng thân thiết</a>
            </div>

            <div class="col-lg-3 col-md-12 mb-4">
                <div class="mb-3">
                    <img src="https://images.dmca.com/Badges/dmca_protected_sml_120n.png?ID=YOUR_ID" alt="DMCA"
                        style="height: 25px;">
                </div>

                <div class="mb-3">
                    <img src="http://online.gov.vn/Content/EndUser/Logo/logo-da-thong-bao-bo-cong-thuong.png"
                        alt="Đã thông báo BCT" style="width: 130px;">
                </div>
            </div>
        </div>

        <div class="footer-divider"></div>

        <div class="row">

            <div class="col-lg-3 col-md-6 mb-3">
                <p class="fw-bold text-white">Cocolux.com thuộc bản quyền của Cocolux</p>
                <a href="#" class="text-white">Hệ thống cửa hàng</a>
                <p>Hotline: 0988888825<br>Email: cskh@cocolux.com</p>
            </div>

            <div class="col-lg-4 col-md-6 mb-3">
                <p class="fw-bold text-white">Công Ty TNHH Thương Mại Và Đầu Tư XNK Việt Nam</p>
                <p>
                    Địa Chỉ: Số 80 Phố Chùa Bộc, P. Kim Liên, Hà Nội<br>
                    Đăng ký lần đầu: 10/05/2017<br>
                    MST: 0107837344
                </p>
            </div>

            <div class="col-lg-2 col-md-6 mb-3">

                <h4 style="font-size: 12px; margin-bottom: 10px;">KẾT NỐI</h4>
                <div class="mb-3 d-flex flex-wrap">
                    <a href="#" class="social-btn"><i class="fa-brands fa-facebook-f"></i></a>
                    <a href="#" class="social-btn"><i class="fa-brands fa-instagram"></i></a>
                    <a href="#" class="social-btn"><i class="fa-brands fa-youtube"></i></a>
                    <a href="#" class="social-btn"><i class="fa-brands fa-tiktok"></i></a>
                    <a href="#" class="social-btn"><i class="fa-solid fa-z"></i></a>
                </div>

                <h4 style="font-size: 12px; margin-bottom: 10px;">THANH TOÁN</h4>
                <div class="d-flex flex-wrap">
                    <span class="payment-btn"><i class="fa-brands fa-cc-visa"></i></span>
                    <span class="payment-btn"><i class="fa-brands fa-cc-mastercard"></i></span>
                    <span class="payment-btn"><i class="fa-solid fa-building-columns"></i></span>
                    <span class="payment-btn"><i class="fa-solid fa-money-bill-wave"></i></span>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-3">
                <h4>ĐĂNG KÝ NHẬN BẢN TIN</h4>
                <p>Đừng bỏ lỡ hàng ngàn sản phẩm hấp dẫn</p>
                <form action="" method="post">
                    <div class="input-group mb-3">
                        <input type="email" class="form-control footer-newsletter-input"
                            placeholder="Nhập email của bạn...">
                        <button class="btn footer-newsletter-btn" type="submit">ĐĂNG KÝ</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</footer>
<script>
    const isLoggedIn = <?= isset($_SESSION['user_id']) ? 'true' : 'false' ?>;

    // --- 1. HÀM LOAD MINI CART (MỚI) ---
    function renderMiniCart() {
        if (!isLoggedIn) return;

        fetch('api_get_cart.php')
            .then(response => response.text())
            .then(html => {
                const miniCart = document.getElementById('mini-cart-content');
                if (miniCart) {
                    miniCart.innerHTML = html;
                }
            })
            .catch(err => console.error(err));
    }

    document.addEventListener('DOMContentLoaded', function () {
        renderMiniCart();
    });

    function addToCart(element) {
        if (!isLoggedIn) {
            if (confirm('Bạn cần đăng nhập để mua hàng. Đăng nhập ngay?')) {
                window.location.href = 'login.php';
            }
            return;
        }

        let productId = element.getAttribute('data-id');
        let productName = element.getAttribute('data-name') || 'Sản phẩm';
        let qtyInput = document.getElementById('qty');
        let quantity = qtyInput ? qtyInput.value : 1;

        const formData = new FormData();
        formData.append('product_id', productId);
        formData.append('quantity', quantity);

        let originalText = element.innerHTML;
        element.innerHTML = '...';
        element.disabled = true;

        fetch('api_add_cart.php', {
            method: 'POST',
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                // Trả lại trạng thái nút bấm
                element.innerHTML = originalText;
                element.disabled = false;

                if (data.status === 'success') {
                    // 1. Cập nhật số trên icon Header (Giao diện)
                    const cartBadge = document.querySelector('#cart-badge');
                    if (cartBadge) {
                        cartBadge.innerText = data.total_count;

                        localStorage.setItem('cart_count', data.total_count);

                        cartBadge.classList.add('animate-bounce');
                        setTimeout(() => cartBadge.classList.remove('animate-bounce'), 500);
                    }

                    if (typeof renderMiniCart === 'function') renderMiniCart();

                    if (typeof showToast === 'function') {
                        showToast(`Đã thêm <b>${productName}</b> vào giỏ!`, 'success');
                    } else {
                        alert("Đã thêm vào giỏ hàng!");
                    }
                } else {
                    alert(data.message);
                }
            })
    }

    document.addEventListener("DOMContentLoaded", function () {
        const savedCount = localStorage.getItem('cart_count');
        const cartBadge = document.querySelector('#cart-badge');

        if (savedCount && cartBadge) {
            if (cartBadge.innerText == '0' || cartBadge.innerText == '') {
                cartBadge.innerText = savedCount;
            }
        }
    });
</script>

<style>
    @keyframes bounce {

        0%,
        100% {
            transform: translate(-50%, -50%) scale(1);
        }

        50% {
            transform: translate(-50%, -50%) scale(1.5);
        }
    }

    .animate-bounce {
        animation: bounce 0.5s ease;
    }
</style>