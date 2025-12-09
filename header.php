<?php
$base_url = "/";
$contact_phone = "1900-1234";
$logo_url = "./img/logo-full-black-cocolux.png";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Header Demo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="./css/menu.css">
    <link rel="stylesheet" href="./css/header.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="./js/main.js"></script>
</head>

<body>

    <header>
        <div class="top-bar">
            <div class="container">
                <a href="tel:<?= $contact_phone ?>"><i class="bi bi-telephone-fill me-1"></i> Hotline:
                    <?= $contact_phone ?></a>
            </div>
        </div>

        <div class="main-header">
            <div class="container">
                <div class="row align-items-center">

                    <div class="col-lg-3 col-md-4 text-center text-lg-start mb-3 mb-lg-0">
                        <a href="<?= $base_url ?>"><img src="<?= $logo_url ?>" alt="Logo" style="height: 50px;"></a>
                    </div>

                    <div class="col-lg-6 col-md-8 mb-3 mb-lg-0">
                        <form action="list_products.php" method="GET">
                            <div class="input-group search-group">
                                <input type="text" name="q" class="form-control" placeholder="Tìm kiếm sản phẩm...">

                                <button class="btn-search-custom" type="submit">
                                    <i class="bi bi-search"></i>
                                </button>
                            </div>
                        </form>
                    </div>

                    <div class="col-lg-3 d-none d-lg-block">
                        <div class="header-actions">

                            <div class="cart-wrapper">
                                <div class="position-relative text-center">
                                    <i class="bi bi-bag fs-4 text-dark"></i>
                                    <span id="cart-badge"
                                        class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                                        style="font-size: 10px;">0</span>
                                    <div style="font-size:11px; font-weight:bold;">Giỏ hàng</div>
                                </div>

                                <div class="mini-cart-dropdown" id="mini-cart-content">
                                    <div class="text-center py-3">
                                        <p class="mb-0 text-muted">Đang tải...</p>
                                    </div>
                                </div>
                            </div>

                            <?php
                            if (isset($_SESSION['user_name'])):
                                ?>
                                <div class="dropdown ps-3 border-start">
                                    <a href="#"
                                        class="text-decoration-none d-flex align-items-center text-dark dropdown-toggle"
                                        id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">

                                        <i class="bi bi-person-check-fill fs-3 text-success me-2"></i>

                                        <div style="line-height: 1.2;">
                                            <div style="font-size:11px; color:#888;">Xin chào,</div>
                                            <div
                                                style="font-size:13px; font-weight:bold; max-width: 120px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                                <?= htmlspecialchars($_SESSION['user_name']) ?>
                                            </div>
                                        </div>
                                    </a>

                                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown"
                                        style="z-index: 99999;">
                                        <li><a class="dropdown-item" href="profile.php"><i class="bi bi-person me-2"></i>Tài
                                                khoản của tôi</a></li>
                                        <li><a class="dropdown-item" href="orders.php"><i class="bi bi-bag me-2"></i>Đơn
                                                hàng của tôi</a></li>
                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>
                                        <li><a class="dropdown-item text-danger" href="logout.php"><i
                                                    class="bi bi-box-arrow-right me-2"></i>Đăng xuất</a></li>
                                    </ul>
                                </div>

                            <?php else: ?>

                                <a href="login.php"
                                    class="text-decoration-none d-flex align-items-center text-dark ps-3 border-start">
                                    <i class="bi bi-person-circle fs-3 text-secondary me-2"></i>
                                    <div style="line-height: 1.2;">
                                        <div style="font-size:11px; color:#888;">Tài khoản</div>
                                        <div style="font-size:13px; font-weight:bold;">Đăng nhập</div>
                                    </div>
                                </a>

                            <?php endif; ?>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </header>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="./js/header.js"></script>

</body>

</html>