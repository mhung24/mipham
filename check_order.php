<?php
session_start();
require_once 'config/connect.php';
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kiểm tra đơn hàng | Cocolux Clone</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; color: #333; background-color: #f9f9f9; }

        /* --- BREADCRUMB --- */
        .breadcrumb-area { background: #fff; padding: 10px 0; border-bottom: 1px solid #eee; margin-bottom: 30px; }
        .breadcrumb-item a { color: #333; text-decoration: none; font-size: 14px; }
        .breadcrumb-item.active { color: #999; font-size: 14px; }

        /* --- CONTENT STYLES --- */
        .policy-content { background: #fff; padding: 40px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.02); }
        
        /* Banner Header */
        .check-header { 
            background: linear-gradient(to right, #fff5eb, #fff);
            padding: 30px; 
            border-radius: 8px; 
            margin-bottom: 35px; 
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 4px solid #f8d7da;
        }
        .header-text h2 { font-family: 'Great Vibes', cursive; color: #d0021b; margin: 0; font-size: 35px; } 
        .header-text h1 { font-weight: 800; color: #5a4b45; font-size: 28px; text-transform: uppercase; margin: 5px 0; }
        .header-text p { color: #d0021b; font-weight: 600; font-size: 14px; margin: 0; text-transform: uppercase; letter-spacing: 1px; }

        .header-icon { font-size: 60px; color: #ffc107; margin-right: 20px; }

        /* Content Text */
        .faq-item { margin-bottom: 25px; }
        .faq-question { font-weight: 700; font-size: 16px; color: #000; margin-bottom: 10px; }
        .faq-answer { font-size: 15px; line-height: 1.6; color: #444; text-align: justify; }

    </style>
    <link href="https://fonts.googleapis.com/css2?family=Great+Vibes&display=swap" rel="stylesheet">
</head>
<body>

    <?php if (file_exists('header.php')) include 'header.php'; ?>
    <?php if (file_exists('menu.php')) include 'menu.php'; ?>

    <div class="breadcrumb-area">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="index.php"><i class="bi bi-house-door-fill"></i> Trang chủ</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Kiểm tra đơn hàng</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="container pb-5">
        <div class="row">
            
            <?php if (file_exists('sidebar.php')) include 'sidebar.php'; ?>

            <div class="col-lg-9">
                <div class="policy-content">
                    
                    <div class="check-header d-none d-md-flex">
                        <div class="header-text">
                            <h2>Chính sách</h2>
                            <h1>KIỂM TRA ĐƠN HÀNG</h1>
                            <p>“ TRA CỨU ĐƠN HÀNG ONLINE NHANH CHÓNG ”</p>
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="bi bi-box-seam-fill text-warning me-3" style="font-size: 60px;"></i>
                            <i class="bi bi-phone-vibrate text-dark" style="font-size: 50px;"></i>
                        </div>
                    </div>

                    <div class="faq-list">
                        
                        <div class="faq-item">
                            <h5 class="faq-question">Tôi có thể đặt hàng qua điện thoại được không?</h5>
                            <div class="faq-answer">
                                <p>Quý khách có thể liên hệ trực tiếp qua Hotline để được hướng dẫn đặt hàng, Cocolux luôn khuyến khích quý khách tạo tài khoản và đặt hàng online để được hưởng các chính sách ưu đãi thành viên tốt hơn.</p>
                                <p>Hoặc bạn có thể kiểm tra lại email Cocolux thông báo bạn đã đặt hàng thành công.</p>
                            </div>
                        </div>

                        <div class="faq-item">
                            <h5 class="faq-question">Có giới hạn về số lượng sản phẩm khi đặt hàng không?</h5>
                            <div class="faq-answer">
                                <p>Quý khách có thể đặt hàng với số lượng sản phẩm tùy ý. Cocolux không giới hạn số lượng sản phẩm trong đơn hàng của quý khách.</p>
                            </div>
                        </div>

                        <div class="mt-4 p-4 bg-light rounded text-center border">
                            <h6 class="fw-bold mb-3">Bạn muốn xem lịch sử đơn hàng của mình?</h6>
                            <?php if(isset($_SESSION['user_id'])): ?>
                                <a href="orders.php" class="btn btn-primary px-4 py-2">
                                    <i class="bi bi-search me-2"></i> Xem đơn hàng của tôi
                                </a>
                            <?php else: ?>
                                <a href="login.php" class="btn btn-outline-primary px-4 py-2">
                                    Đăng nhập để xem đơn hàng
                                </a>
                            <?php endif; ?>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php if (file_exists('footer.php')) include 'footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>