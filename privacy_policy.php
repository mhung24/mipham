<?php
session_start();
require_once 'config/connect.php';
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chính sách bảo mật | Cocolux Clone</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; color: #333; background-color: #f9f9f9; }

        /* --- BREADCRUMB --- */
        .breadcrumb-area { background: #fff; padding: 10px 0; border-bottom: 1px solid #eee; margin-bottom: 30px; }
        .breadcrumb-item a { color: #333; text-decoration: none; font-size: 14px; }
        .breadcrumb-item.active { color: #999; font-size: 14px; }

        /* --- CONTENT STYLES --- */
        .policy-content { background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.02); }
        
        .policy-banner { width: 100%; border-radius: 8px; margin-bottom: 25px; object-fit: cover; }
        
        .section-title { font-weight: 700; color: #000; font-size: 16px; margin-top: 25px; margin-bottom: 10px; }
        .sub-title { font-weight: 700; color: #333; font-size: 15px; margin-top: 15px; margin-bottom: 8px; }
        
        .policy-text { font-size: 15px; line-height: 1.6; color: #444; margin-bottom: 10px; text-align: justify; }
        
        .policy-list { list-style: none; padding-left: 0; }
        .policy-list li { position: relative; padding-left: 15px; margin-bottom: 8px; line-height: 1.6; color: #444; font-size: 15px; text-align: justify; }
        .policy-list li::before { content: "-"; position: absolute; left: 0; font-weight: bold; color: #666; }
        
        .contact-info-list { list-style: disc; padding-left: 20px; margin-top: 10px; color: #444; }
        .contact-info-list li { margin-bottom: 5px; }
        .contact-info-list a { text-decoration: none; color: #0d6efd; }
        .contact-info-list strong { color: #000; }
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
                    <li class="breadcrumb-item active" aria-current="page">Chính sách bảo mật</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="container pb-5">
        <div class="row">
            
            <?php if (file_exists('sidebar.php')) include 'sidebar.php'; ?>

            <div class="col-lg-9">
                <div class="policy-content">
                    <div style="background: linear-gradient(90deg, #4facfe 0%, #00f2fe 100%); padding: 30px; border-radius: 8px; color: white; margin-bottom: 25px; text-align: center; position: relative; overflow: hidden;">
                        <div style="position: relative; z-index: 2;">
                            <h4 style="text-transform: uppercase; font-weight: 300; margin: 0;">Chính sách</h4>
                            <h1 style="text-transform: uppercase; font-weight: 800; font-size: 40px; margin: 0; text-shadow: 0 2px 4px rgba(0,0,0,0.1);">BẢO MẬT</h1>
                            <h4 style="text-transform: uppercase; font-weight: 300; margin: 0; letter-spacing: 2px;">THÔNG TIN</h4>
                        </div>
                        <i class="bi bi-shield-lock-fill" style="position: absolute; right: 20px; bottom: -20px; font-size: 150px; opacity: 0.2;"></i>
                    </div>

                    <h5 class="section-title">1. Mục đích:</h5>
                    <div class="policy-text">
                        Hiện nay, vấn đề bảo mật thông tin đã trở thành vấn đề nóng trên tất cả các diễn đàn, điều này không chỉ gây khó khăn cho các tổ chức, cá nhân tham gia truy cập và giao dịch mà còn gây nên những phiền toái vượt ra ngoài tầm kiểm soát của hệ thống.
                    </div>
                    <div class="policy-text">
                        Chính sách bảo mật và chia sẻ thông tin khách hàng của Cocolux.com như một lời cam kết với mong muốn nâng cao chất lượng dịch vụ, bảo đảm an toàn thông tin của các cá nhân, tổ chức khi tham gia truy cập hoặc giao dịch trực tiếp trên website Cocolux.com. Chúng tôi hiểu bảo vệ và sử dụng hợp lí thông tin của bạn cũng chính là bảo vệ lòng tin và sự yêu mến của bạn dành cho chúng tôi.
                    </div>

                    <h5 class="section-title">2. Các điều khoản áp dụng</h5>
                    
                    <h6 class="sub-title">2.1. Cập nhật thông tin khách hàng:</h6>
                    <ul class="policy-list">
                        <li>Khi tham gia mở tài khoản hoặc giao dịch trực tiếp trên Cocolux.com, khách hàng cần phải cung cấp đầy đủ các thông tin bắt buộc bao gồm tên, địa chỉ, số điện thoại... cụ thể để có thể đáp ứng các điều kiện giao dịch cần thiết.</li>
                        <li>Các thông tin cá nhân quý khách cung cấp cho Cocolux.com cần đảm bảo độ chính xác và đầy đủ để phòng tránh trường hợp nhầm lẫn hoặc giả mạo. Mọi sự sai sót hoặc không chính xác trong thông tin được cung cấp sẽ ảnh hưởng trực tiếp đến quyền lợi của quý khách hàng. Do đó, Cocolux.com sẽ không chịu trách nhiệm đối với những tranh chấp phát sinh có liên quan trong trường hợp này.</li>
                    </ul>

                    <h6 class="sub-title">2.2. Lưu giữ và bảo mật thông tin khách hàng</h6>
                    <ul class="policy-list">
                        <li>Tất cả các thông tin khách hàng cung cấp và nội dung giao dịch đều được Cocolux.com lưu giữ bảo mật tuyệt đối trên hệ thống. Cocolux.com cam đoan sẽ không bán hoặc chia sẻ dẫn đến làm lộ thông tin cá nhân của bạn, không vì những mục đích thương mại mà vi phạm cam kết của chúng tôi ghi trong chính sách bảo mật này.</li>
                        <li>Cocolux.com luôn sẵn sàng về đội ngũ kĩ thuật và an ninh để có những biện pháp đối phó với những trường hợp cố tình xâm nhập và sử dụng trái phép thông tin của khách hàng. Khi thu thập dữ liệu, Cocolux.com thực hiện lưu giữ và bảo mật thông tin khách hàng tại hệ thống máy chủ và các thông tin khách hàng này được bảo đảm an toàn bằng các hệ thống bảo vệ tốt nhất hiện nay, cùng các biện pháp kiểm soát truy cập và mã hóa dữ liệu.</li>
                        <li>Khách hàng không được phép sử dụng bất kì chương trình hay công cụ nào nhằm mục đích khai thác, thay đổi dữ liệu bất hợp pháp trên hệ thống Cocolux.com. Mọi hành vi cố tình xâm phạm, tùy theo tính chất sự việc, chúng tôi có quyền khởi tố với các cơ quan có thẩm quyền theo quy định pháp luật hiện hành.</li>
                        <li>Khách hàng nên tự bảo vệ thông tin bảo mật của mình bằng cách không chia sẻ các thông tin cá nhân cũng như các thông tin giao dịch với bên thứ ba, cẩn thận trong việc đăng nhập/đăng xuất tài khoản để loại trừ những sự cố rò rỉ thông tin không đáng có.</li>
                    </ul>

                    <h6 class="sub-title">2.3. Sử dụng thông tin</h6>
                    <div class="policy-text">
                        Cocolux.com có quyền sử dụng hợp pháp các thông tin cá nhân của khách hàng trong các trường hợp để đảm bảo quyền lợi của quý khách như:
                    </div>
                    <ul class="policy-list">
                        <li>Thông báo các thông tin về dịch vụ quảng cáo, các chương trình khuyến mãi... đến khách hàng theo nhu cầu và thói quen của quý khách khi truy cập</li>
                        <li>Liên lạc và hỗ trợ khách hàng trong những trường hợp đặc biệt</li>
                        <li>Phát hiện và ngăn chặn ngay lập tức các hành vi can thiệp hoặc phá hoại tài khoản của khách hàng</li>
                    </ul>

                    <h6 class="sub-title">2.4. Chia sẻ thông tin</h6>
                    <div class="policy-text">
                        Cocolux.com cam kết không tiết lộ thông tin khách hàng cho bên thứ ba ngoại trừ các trường hợp sau:
                    </div>
                    <ul class="policy-list">
                        <li>Thực hiện theo yêu cầu của các các cá nhân, tổ chức có thẩm quyền theo quy định của pháp luật.</li>
                        <li>Cần thiết phải sử dụng các thông tin để phục vụ cho việc cung cấp các dịch vụ/tiện ích cho khách hàng.</li>
                        <li>Nghiên cứu thị trường và đánh giá phân tích - Trao đổi thông tin khách hàng với đối tác hoặc đại lí phân phối để phân tích nâng cao chất lượng dịch vụ.</li>
                        <li>Ngoài các trường hợp trên, khi cần phải trao đổi thông tin khách hàng cho bên thứ ba, Cocolux.com sẽ thông báo trực tiếp với khách hàng và sẽ chỉ thực hiện việc trao đổi thông tin khi có sự đồng thuận từ phía khách hàng.</li>
                    </ul>

                    <h5 class="section-title">3. Liên hệ và giải đáp thắc mắc</h5>
                    <ul class="contact-info-list">
                        <li><strong>Facebook:</strong> <a href="https://www.facebook.com/cocoluxofficial" target="_blank">https://www.facebook.com/cocoluxofficial</a></li>
                        <li><strong>Zalo:</strong> <a href="https://zalo.me/cocolux" target="_blank">https://zalo.me/cocolux</a></li>
                        <li><strong>Email:</strong> <a href="mailto:cskh@cocolux.com">cskh@cocolux.com</a></li>
                        <li><strong>Hotline:</strong> 098 888 88 25 - CSKH: Nhánh 106</li>
                        <li><strong>Hệ thống cửa hàng:</strong> <a href="https://cocolux.com/cua-hang" target="_blank">https://cocolux.com/cua-hang</a></li>
                    </ul>

                </div>
            </div>
        </div>
    </div>

    <?php if (file_exists('footer.php')) include 'footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>