<?php
session_start();
require_once 'config/connect.php';
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đặt hàng tại Cocolux | Cocolux Clone</title>
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
        .faq-header { 
            background: linear-gradient(to right, #fff0f0, #fff);
            padding: 30px; 
            border-radius: 8px; 
            margin-bottom: 30px; 
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-left: 5px solid #d0021b;
        }
        .header-text h2 { font-family: 'Great Vibes', cursive; color: #d0021b; margin: 0; font-size: 35px; } /* Giả lập font chữ viết tay "Hỏi đáp" */
        .header-text h1 { font-weight: 800; color: #5a4b45; font-size: 28px; text-transform: uppercase; margin: 5px 0; }
        .header-text p { color: #d0021b; font-weight: 600; font-size: 14px; margin: 0; text-transform: uppercase; letter-spacing: 1px; }

        .header-img { max-width: 250px; }
        .header-img img { width: 100%; }

        /* FAQ Items */
        .faq-item { margin-bottom: 30px; }
        .faq-question { font-weight: 700; font-size: 16px; color: #000; margin-bottom: 10px; display: flex; align-items: center; }
        .faq-question i { color: #d0021b; margin-right: 10px; font-size: 20px; }
        
        .faq-answer { background-color: #f8f9fa; padding: 20px; border-radius: 6px; border-left: 3px solid #ccc; font-size: 15px; line-height: 1.6; color: #444; text-align: justify; }

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
                    <li class="breadcrumb-item active" aria-current="page">Đặt hàng tại Cocolux</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="container pb-5">
        <div class="row">
            
            <?php if (file_exists('sidebar.php')) include 'sidebar.php'; ?>

            <div class="col-lg-9">
                <div class="policy-content">
                    
                    <div class="faq-header d-none d-md-flex">
                        <div class="header-text">
                            <h2>Hỏi đáp</h2>
                            <h1>ĐẶT HÀNG TẠI COCOLUX</h1>
                            <p>“ ĐẶT HÀNG ONLINE ĐỂ NHẬN ƯU ĐÃI ĐỘC QUYỀN ”</p>
                        </div>
                        <div class="header-img">
                            <i class="bi bi-cart-check-fill text-warning" style="font-size: 80px;"></i>
                            <i class="bi bi-laptop text-dark" style="font-size: 60px; margin-left: -20px;"></i>
                        </div>
                    </div>

                    <div class="faq-list">
                        
                        <div class="faq-item">
                            <h5 class="faq-question">
                                <i class="bi bi-question-circle-fill"></i>
                                Tôi muốn kiểm tra lại đơn hàng đã mua?
                            </h5>
                            <div class="faq-answer">
                                <p>Quý khách bấm vào nút <strong>“tài khoản”</strong> trên góc phải màn hình sau đó chọn vào mục <strong>“Tài khoản của bạn”</strong> vào chọn vào ô <strong>“Đơn hàng của tôi”</strong> để kiểm tra lại các sản phẩm đã đặt mua.</p>
                                <p class="mb-0">Hoặc bạn có thể kiểm tra lại email Cocolux thông báo bạn đã đặt hàng thành công.</p>
                            </div>
                        </div>

                        <div class="faq-item">
                            <h5 class="faq-question">
                                <i class="bi bi-question-circle-fill"></i>
                                Tôi muốn thay đổi hoặc hủy bỏ đơn hàng đã mua thì sao?
                            </h5>
                            <div class="faq-answer">
                                <p>Việc thay đổi sản phẩm trong đơn hàng quý khách vui lòng liên hệ Cocolux Care qua Hotline để được hướng dẫn chi tiết.</p>
                                <p class="mb-0">Đơn hàng chỉ được hủy khi đơn hàng của quý khách chưa chuyển trạng thái cho đơn vị vận chuyển.</p>
                            </div>
                        </div>

                        <div class="faq-item">
                            <h5 class="faq-question">
                                <i class="bi bi-question-circle-fill"></i>
                                Tôi có thể đặt hàng mà không cần tạo tài khoản không?
                            </h5>
                            <div class="faq-answer">
                                <p class="mb-0">Có, bạn hoàn toàn có thể đặt hàng với tư cách "Khách". Tuy nhiên, chúng tôi khuyến khích bạn đăng ký tài khoản để tích điểm thành viên và nhận các ưu đãi độc quyền.</p>
                            </div>
                        </div>

                    </div>

                    <div class="mt-4 pt-3 border-top text-center">
                        <p class="text-muted fst-italic">Nếu bạn có câu hỏi khác, vui lòng liên hệ:</p>
                        <a href="contact.php" class="btn btn-danger"><i class="bi bi-telephone-fill me-2"></i> Liên hệ ngay</a>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <?php if (file_exists('footer.php')) include 'footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>