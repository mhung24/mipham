<?php
session_start();
require_once 'config/connect.php';
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Phí vận chuyển | Cocolux Clone</title>
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
        
        .freeship-header {
            background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 99%, #fecfef 100%);
            border-radius: 10px;
            padding: 30px;
            text-align: center;
            margin-bottom: 30px;
            color: #d0021b;
        }
        .freeship-title { font-weight: 900; font-size: 32px; text-transform: uppercase; margin-bottom: 5px; text-shadow: 1px 1px 2px rgba(255,255,255,0.8); }
        .freeship-date { font-weight: 600; font-size: 16px; background: #fff; display: inline-block; padding: 5px 15px; border-radius: 20px; color: #d0021b; }

        /* Policy Box */
        .policy-box {
            border: 2px dashed #ffc107;
            background-color: #fffbf0;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .box-title { font-weight: 700; font-size: 18px; color: #d0021b; margin-bottom: 15px; border-bottom: 1px solid #ffc107; padding-bottom: 10px; text-transform: uppercase; }
        
        .price-tag { font-weight: 800; color: #28a745; font-size: 18px; } /* Màu xanh cho Freeship */
        .fee-tag { font-weight: 800; color: #d0021b; font-size: 18px; } /* Màu đỏ cho phí */

        .condition-list { list-style: none; padding: 0; }
        .condition-list li { padding: 10px 0; border-bottom: 1px solid rgba(0,0,0,0.05); font-size: 15px; display: flex; align-items: flex-start; }
        .condition-list li:last-child { border-bottom: none; }
        .condition-list i { margin-right: 10px; font-size: 18px; margin-top: 2px; }

        .note-text { font-style: italic; font-size: 14px; color: #666; margin-top: 20px; border-top: 1px solid #eee; padding-top: 15px; }

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
                    <li class="breadcrumb-item active" aria-current="page">Phí vận chuyển</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="container pb-5">
        <div class="row">
            
            <?php if (file_exists('sidebar.php')) include 'sidebar.php'; ?>

            <div class="col-lg-9">
                <div class="policy-content">
                    
                    <div class="freeship-header">
                        <i class="bi bi-rocket-takeoff-fill" style="font-size: 50px; margin-bottom: 10px; display: block;"></i>
                        <h1 class="freeship-title">THÔNG BÁO CHÍNH SÁCH FREESHIP</h1>
                        <span class="freeship-date">Áp dụng từ: 05/03/2025</span>
                    </div>

                    <p style="font-size: 16px; margin-bottom: 30px;">
                        Bắt đầu từ ngày <strong>05/03/2025</strong>, Cocolux chính thức áp dụng chính sách vận chuyển mới với nhiều ưu đãi hấp dẫn dành cho khách hàng mua sắm online:
                    </p>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="policy-box">
                                <h3 class="box-title"><i class="bi bi-geo-alt-fill me-2"></i> SHIP NỘI THÀNH HÀ NỘI</h3>
                                <ul class="condition-list">
                                    <li>
                                        <i class="bi bi-check-circle-fill text-success"></i>
                                        <div>
                                            Đơn hàng <strong>từ 99K</strong><br>
                                            <span class="price-tag">FREESHIP</span><br>
                                            <small class="text-muted">(Tối đa 2h - trong bán kính 5km)</small>
                                        </div>
                                    </li>
                                    <li>
                                        <i class="bi bi-info-circle-fill text-danger"></i>
                                        <div>
                                            Đơn hàng <strong>dưới 99K</strong><br>
                                            (hoặc bán kính > 5km)<br>
                                            <span class="fee-tag">ĐỒNG GIÁ 15K</span>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="policy-box" style="background-color: #f0f8ff; border-color: #0dcaf0;">
                                <h3 class="box-title" style="color: #0d6efd; border-color: #0dcaf0;"><i class="bi bi-truck-front-fill me-2"></i> NGOẠI THÀNH / TỈNH</h3>
                                <ul class="condition-list">
                                    <li>
                                        <i class="bi bi-check-circle-fill text-success"></i>
                                        <div>
                                            Đơn hàng <strong>từ 249K</strong><br>
                                            <span class="price-tag">FREESHIP</span>
                                        </div>
                                    </li>
                                    <li>
                                        <i class="bi bi-info-circle-fill text-danger"></i>
                                        <div>
                                            Đơn hàng <strong>dưới 249K</strong><br>
                                            <span class="fee-tag">ĐỒNG GIÁ 15K</span>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="note-text">
                        <p><strong><i class="bi bi-exclamation-triangle-fill text-warning"></i> Lưu ý:</strong></p>
                        <ul>
                            <li>Chương trình không áp dụng với cơ sở Cocolux ở trung tâm thương mại.</li>
                            <li>Thời gian giao hàng có thể thay đổi tùy thuộc vào đơn vị vận chuyển và tình hình thời tiết.</li>
                        </ul>
                    </div>

                    <div class="mt-4 text-center">
                        <a href="https://cocolux.com/cua-hang" target="_blank" class="btn btn-outline-danger btn-lg">
                            <i class="bi bi-shop"></i> Xem hệ thống cửa hàng Cocolux
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <?php if (file_exists('footer.php')) include 'footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>