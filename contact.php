<?php
session_start();
require_once 'config/connect.php';
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liên hệ | Cocolux Clone</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; color: #333; background-color: #f9f9f9; }

        /* --- BREADCRUMB --- */
        .breadcrumb-area { background: #fff; padding: 10px 0; border-bottom: 1px solid #eee; margin-bottom: 30px; }
        .breadcrumb-item a { color: #333; text-decoration: none; font-size: 14px; }
        .breadcrumb-item.active { color: #999; font-size: 14px; }

        /* --- CONTENT STYLES --- */
        .contact-content { background: #fff; padding: 0; font-size: 15px; line-height: 1.8; }
        .contact-content p { margin-bottom: 15px; }
        
        .contact-list { list-style: disc; padding-left: 20px; margin-bottom: 30px; }
        .contact-list li { margin-bottom: 8px; }
        .contact-list strong { font-weight: 600; color: #000; }
        .contact-list a { color: #0d6efd; text-decoration: none; }
        .contact-list a:hover { text-decoration: underline; }

        .divider-line { border-top: 1px solid #eee; margin: 30px 0; width: 100px; }
    </style>
</head>
<body>

    <?php if (file_exists('header.php')) include 'header.php'; ?>
    <?php if (file_exists('menu.php')) include 'menu.php'; ?>

    <div class="breadcrumb-area">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="index.php"><i class="bi bi-house-door-fill"></i> Trang chủ</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Liên hệ</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="container pb-5">
        <div class="row">
            
            <?php if (file_exists('sidebar.php')) include 'sidebar.php'; ?>

            <div class="col-lg-9">
                <div class="contact-content">
                    <p>Trân trọng cảm ơn quý khách đã quan tâm và tham gia trải nghiệm mua sắm tại Cocolux.</p>
                    <p>Nếu có bất kỳ thắc mắc hoặc cần hỗ trợ, đừng ngần ngại liên hệ với chúng tôi. Đội ngũ nhân viên Cocolux luôn sẵn sàng lắng nghe mọi phản hồi của quý khách.</p>
                    <p>Quý khách có thể liên lạc với Cocolux thông qua các kênh sau:</p>

                    <ul class="contact-list">
                        <li><strong>Facebook:</strong> <a href="https://www.facebook.com/cocoluxofficial" target="_blank">https://www.facebook.com/cocoluxofficial</a></li>
                        <li><strong>Zalo:</strong> <a href="https://zalo.me/cocolux" target="_blank">https://zalo.me/cocolux</a></li>
                        <li><strong>Email:</strong> <a href="mailto:cskh@cocolux.com">cskh@cocolux.com</a></li>
                        <li><strong>Hotline:</strong> <span style="color: #333;">098 888 88 25</span></li>
                        <li><strong>Hệ thống cửa hàng:</strong> <a href="https://cocolux.com/cua-hang" target="_blank">https://cocolux.com/cua-hang</a></li>
                    </ul>

                    <div class="divider-line"></div>

                    <p style="font-weight: 600; margin-bottom: 15px;">Hệ thống các kênh và cửa hàng Cocolux:</p>
                    
                    <ul class="contact-list">
                        <li><strong>Website:</strong> <a href="https://cocolux.com/" target="_blank">https://cocolux.com/</a></li>
                        <li><strong>Shopee:</strong> <a href="https://s.shopee.vn/60AFXU3sWA" target="_blank">https://s.shopee.vn/60AFXU3sWA</a></li>
                        <li><strong>TikTok Shop:</strong> <a href="https://www.tiktok.com/@cocoluxofficial" target="_blank">https://www.tiktok.com/@cocoluxofficial</a></li>
                        <li><strong>Lazada:</strong> <a href="https://www.lazada.vn/shop/cocolux-store" target="_blank">https://www.lazada.vn/shop/cocolux-store</a></li>
                        <li><strong>Instagram:</strong> <a href="https://www.instagram.com/cocolux.vn/" target="_blank">https://www.instagram.com/cocolux.vn/</a></li>
                        <li><strong>Tìm cửa hàng Cocolux gần nhất:</strong> <a href="https://cocolux.com/cua-hang" target="_blank">https://cocolux.com/cua-hang</a></li>
                    </ul>

                </div>
            </div>
        </div>
    </div>

    <?php if (file_exists('footer.php')) include 'footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>