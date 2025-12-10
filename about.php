<?php
session_start();
require_once 'config/connect.php';
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giới thiệu về Cocolux</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; color: #333; background-color: #f9f9f9; }

        /* --- BREADCRUMB --- */
        .breadcrumb-area { background: #fff; padding: 10px 0; border-bottom: 1px solid #eee; margin-bottom: 30px; }
        .breadcrumb-item a { color: #333; text-decoration: none; font-size: 14px; }
        .breadcrumb-item.active { color: #999; font-size: 14px; }

        /* --- CONTENT STYLES --- */
        .about-text { line-height: 1.8; color: #444; font-size: 15px; text-align: justify; }
        
        /* Trang trí ảnh */
        .img-decoration-wrapper { position: relative; padding: 20px; }
        .about-img { width: 100%; border-radius: 50px 0 50px 0; box-shadow: 0 10px 30px rgba(0,0,0,0.1); position: relative; z-index: 2; }
        
        .decoration-dot { position: absolute; z-index: 1; }
        .circle-bg { width: 100px; height: 100px; background-color: #ffeff0; border-radius: 50%; top: 0; right: 0; }
        .dots-bg { width: 80px; height: 80px; background-image: radial-gradient(#d0021b 1.5px, transparent 1.5px); background-size: 10px 10px; bottom: 0; left: 0; opacity: 0.3; }
        .border-outline { position: absolute; top: 10px; left: 10px; right: 10px; bottom: 10px; border: 1px solid #ffccd0; border-radius: 50px 0 50px 0; z-index: 0; }
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
                    <li class="breadcrumb-item active" aria-current="page">Giới thiệu</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="container pb-5">
        <div class="row">
            
            <?php if (file_exists('sidebar.php')) include 'sidebar.php'; ?>

            <div class="col-lg-9">
                <div class="row align-items-center mb-5">
                    <div class="col-md-7">
                        <div class="about-text">
                            <p>Mới đây các tín đồ làm đẹp lại được 1 phen xôn xao khi Cocolux chính thức khai trương cửa hàng tiếp theo tại khu đô thị mới Vincom Ocean Park. Cocolux đã "vượt mặt" nhiều đối thủ cạnh tranh và trở thành đối tác chiến lược của Vincom với không gian lên đến hàng trăm mét vuông đầy ắp mỹ phẩm đa dạng từ dưỡng da cho đến trang điểm phải lên đến vài nghìn mã hàng.</p>
                            <p>Thiết kế của Cocolux luôn có 1 chất rất riêng theo hơi hướng hiện đại, sang trọng, không gian rộng rãi. Cách bày trí đẹp mắt chia thành từng gian hàng riêng như gian của NYX, Maybelline New York, Vichy, L'Oreal Paris... Các sản phẩm skincare và make up lần lượt được bày trí khoa học, có biển chỉ dẫn các khu sản phẩm riêng rất chuyên nghiệp.</p>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="img-decoration-wrapper">
                            <div class="decoration-dot circle-bg"></div>
                            <div class="decoration-dot dots-bg"></div>
                            <div class="border-outline"></div>
                            <img src="https://media.vneconomy.vn/w800/images/upload/2022/07/26/image2.jpg" alt="Cocolux Store" class="about-img">
                        </div>
                    </div>
                </div>

                <div class="row align-items-center">
                    <div class="col-md-5 order-2 order-md-1">
                        <div class="img-decoration-wrapper">
                            <div class="decoration-dot circle-bg" style="left:0; right:auto; background-color: #e0f7fa;"></div>
                            <div class="decoration-dot dots-bg" style="right:0; left:auto; background-image: radial-gradient(#00bcd4 1.5px, transparent 1.5px);"></div>
                            <div class="border-outline" style="border-radius: 0 50px 0 50px; border-color: #b2ebf2;"></div>
                            <img src="https://toplist.vn/images/800px/cocolux-com-317652.jpg" alt="Mỹ phẩm chính hãng" class="about-img" style="border-radius: 0 50px 0 50px;">
                        </div>
                    </div>
                    <div class="col-md-7 order-1 order-md-2">
                        <div class="about-text">
                            <p>Tại Việt Nam hiện nay, Cocolux là một trong những store mỹ phẩm tiên phong trong lĩnh vực làm đẹp với nhiều sản phẩm mỹ phẩm nhập khẩu đa dạng. Các sản phẩm từ Âu đến Hàn, Pháp, Anh. Không những thế, Cocolux còn là đối tác của Christian Lenart, Bioderma, Maybelline New York, Vichy, Laroche Posay, Senka... và tất nhiên, giấy tờ đều được cấp từ cơ quan có thẩm quyền.</p>
                            <p>Chúng tôi cam kết mang đến cho khách hàng những sản phẩm chính hãng, chất lượng với mức giá tốt nhất thị trường. Đội ngũ nhân viên tư vấn nhiệt tình, am hiểu kiến thức làm đẹp sẽ giúp bạn tìm ra quy trình chăm sóc da phù hợp nhất.</p>
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