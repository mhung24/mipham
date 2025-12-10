<?php
session_start();
require_once 'config/connect.php';
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hướng dẫn đặt hàng | Cocolux Clone</title>
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
        .guide-header { text-align: center; margin-bottom: 30px; background: #fff5f5; padding: 30px; border-radius: 8px; border: 1px dashed #d0021b; }
        .guide-title { font-weight: 800; color: #5a4b45; font-size: 32px; text-transform: uppercase; margin-bottom: 10px; }
        .guide-subtitle { color: #d0021b; font-weight: 600; font-size: 14px; letter-spacing: 2px; text-transform: uppercase; margin: 0; }
        
        /* Intro Text */
        .intro-text { font-style: italic; color: #555; margin-bottom: 25px; text-align: center; }

        /* Process List Style */
        .process-list { border: 1px solid #999; }
        .process-item { display: flex; margin-bottom: 0; border-bottom: 1px solid #eee; }
        .process-item:last-child { border-bottom: none; }
        
        /* Cột Icon bên trái */
        .process-icon-col {
            width: 80px;
            flex-shrink: 0;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            padding-top: 25px;
            border-right: 1px solid #eee;
            background-color: #fdfdfd;
        }
        .icon-circle {
            width: 50px; height: 50px;
            background: #fff;
            border: 2px solid #ccc;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #555;
            font-size: 22px;
        }

        /* Cột nội dung bên phải */
        .process-text-col { flex-grow: 1; padding: 20px 20px 20px 30px; }
        .step-title { font-weight: 700; color: #000; font-size: 16px; margin-bottom: 10px; display: block; }
        .process-text-col p { margin-bottom: 10px; font-size: 15px; line-height: 1.6; color: #333; text-align: justify; }
        .sub-steps { margin-left: 15px; margin-bottom: 10px; }
        .sub-steps li { margin-bottom: 5px; font-size: 15px; color: #444; }

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
                    <li class="breadcrumb-item active" aria-current="page">Hướng dẫn đặt hàng</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="container pb-5">
        <div class="row">
            
            <?php if (file_exists('sidebar.php')) include 'sidebar.php'; ?>

            <div class="col-lg-9">
                <div class="policy-content">
                    
                    <div class="guide-header">
                        <h1 class="guide-title">HƯỚNG DẪN ĐẶT HÀNG</h1>
                        <p class="guide-subtitle">"ĐẶT HÀNG DỄ DÀNG - GIAO HÀNG NHANH CHÓNG"</p>
                    </div>

                    <p class="intro-text">Quý khách có thể đặt hàng trực tuyến ở website Cocolux.com thông qua các bước đặt hàng cơ bản.</p>

                    <div class="process-list">
                        
                        <div class="process-item">
                            <div class="process-icon-col">
                                <div class="icon-circle"><i class="bi bi-search"></i></div>
                            </div>
                            <div class="process-text-col">
                                <span class="step-title">1. Tìm kiếm sản phẩm:</span>
                                <p>Quý khách có thể tìm kiếm sản phẩm theo 2 cách:</p>
                                <ul class="sub-steps">
                                    <li>Gõ tên sản phẩm vào thanh tìm kiếm.</li>
                                    <li>Tìm theo danh mục sản phẩm, thương hiệu sản phẩm hoặc có thể tham khảo những sản phẩm hot, những sản phẩm đang bán chạy nhất tại Cocolux.com.</li>
                                </ul>
                            </div>
                        </div>

                        <div class="process-item">
                            <div class="process-icon-col">
                                <div class="icon-circle"><i class="bi bi-bag-plus"></i></div>
                            </div>
                            <div class="process-text-col">
                                <span class="step-title">2. Đặt hàng:</span>
                                <p>Khi đã tìm được sản phẩm mong muốn, quý khách vui lòng bấm vào hình hoặc tên sản phẩm để vào được trang thông tin chi tiết của sản phẩm.</p>
                                <p>Nếu quý khách chỉ muốn mua 1 sản phẩm vừa chọn thì click vào ô "mua ngay" sau đó làm theo hướng dẫn trên Website.</p>
                                <p>Để đặt nhiều sản phẩm khác nhau vào cùng 1 đơn hàng, quý khách vui lòng thực hiện theo các bước sau:</p>
                                <ul class="sub-steps">
                                    <li><strong>Bước 1:</strong> Quý khách sẽ bấm vào ô "thêm vào giỏ hàng" và tiếp tục tìm thêm các sản phẩm khác.</li>
                                    <li><strong>Bước 2:</strong> Sau khi đã cho các sản phẩm cần mua vào giỏ hàng, quý khách vui lòng bấm vào nút "giỏ hàng" bên góc phải màn hình để xem lại sản phẩm đã chọn.</li>
                                    <li><strong>Bước 3:</strong> Trong giỏ hàng của quý khách bên góc trái có nút tiếp tục mua hàng để quý khách chọn nếu như muốn mua thêm sản phẩm khác.</li>
                                    <li><strong>Bước 4:</strong> Sau khi đã chọn được các sản phẩm cần mua vào giỏ hàng, quý khách vui lòng bấm nút "Tiến hành đặt hàng" bên góc phải màn hình.</li>
                                </ul>
                            </div>
                        </div>

                        <div class="process-item">
                            <div class="process-icon-col">
                                <div class="icon-circle"><i class="bi bi-person-bounding-box"></i></div>
                            </div>
                            <div class="process-text-col">
                                <span class="step-title">3. Đăng nhập hoặc đăng ký tài khoản:</span>
                                <p>Quý khách vui lòng đăng nhập bằng tài khoản đã có ở Cocolux.com hoặc đăng nhập thông qua Facebook hoặc tài khoản Google.</p>
                                <p>Trong trường hợp chưa đăng ký tài khoản, quý khách có thể chọn dòng "Đăng ký ngay" và bắt đầu nhập địa chỉ email, mật khẩu tùy ý để đăng ký tài khoản.</p>
                                <p>Quý khách có thể đặt hàng mà không cần đăng nhập nhưng quý khách lưu ý phải điền đầy đủ và chính xác về thông tin nhận hàng, đặc biệt là địa chỉ mail và số điện thoại để Cocolux xác nhận đơn hàng.</p>
                                <p>Sau khi đã hoàn tất các bước trên, quý khách vui lòng bấm "Tiếp Tục" để đến bước tiếp theo.</p>
                            </div>
                        </div>

                        <div class="process-item">
                            <div class="process-icon-col">
                                <div class="icon-circle"><i class="bi bi-geo-alt"></i></div>
                            </div>
                            <div class="process-text-col">
                                <span class="step-title">4. Điền địa chỉ nhận hàng:</span>
                                <p>Quý khách điền địa chỉ nhận hàng theo như trên trang hướng dẫn. Trường hợp quý khách có nhiều địa chỉ để nhận hàng thì quý khách lưu ý địa chỉ nào nằm trong ô "mặc định" đầu tiên bên trái sẽ là địa chỉ Cocolux liên hệ để giao hàng.</p>
                            </div>
                        </div>

                        <div class="process-item">
                            <div class="process-icon-col">
                                <div class="icon-circle"><i class="bi bi-credit-card"></i></div>
                            </div>
                            <div class="process-text-col">
                                <span class="step-title">5. Chọn phương thức thanh toán:</span>
                                <p>Nếu các thông tin trên đã chính xác, quý khách vui lòng bấm "Đặt hàng", hệ thống sẽ bắt đầu tiến hành tạo đơn hàng dựa trên các thông tin quý khách đã đăng ký.</p>
                                <p>Quý khách có thể tham khảo các phương thức thanh toán sau đây và lựa chọn áp dụng phương thức phù hợp:</p>
                                <ul class="sub-steps">
                                    <li><strong>Cách 1:</strong> Thanh toán sau khi nhận hàng (COD): Quý khách sẽ thanh toán lúc nhận được sản phẩm từ nhân viên giao nhận hoặc nhân viên chuyển phát tại địa chỉ khách hàng đã đăng ký.</li>
                                    <li><strong>Cách 2:</strong> Chọn hình thức chuyển khoản và thực hiện chuyển khoản theo mẫu: Thanh toán đơn hàng số: mã đơn hàng, số điện thoại. Lưu ý các đơn hàng có giá trị trên 5.000.000đ chỉ được áp dụng hình thức chuyển khoản.</li>
                                </ul>
                            </div>
                        </div>

                        <div class="process-item">
                            <div class="process-icon-col">
                                <div class="icon-circle"><i class="bi bi-box-seam"></i></div>
                            </div>
                            <div class="process-text-col">
                                <span class="step-title">6. Kiểm tra và xác nhận đơn hàng:</span>
                                <p>Sau khi hoàn thành tất cả bước đặt mua, hệ thống sẽ gửi đến quý khách mã số đơn hàng để kiểm tra theo dõi tình trạng đơn hàng.</p>
                            </div>
                        </div>

                    </div> </div>
            </div>
        </div>
    </div>

    <?php if (file_exists('footer.php')) include 'footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>