<?php
session_start();
require_once 'config/connect.php';
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Điều khoản sử dụng | Cocolux Clone</title>
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
        
        /* Banner Style */
        .terms-banner {
            background: linear-gradient(135deg, #fff5f5 0%, #ffe3e3 100%);
            padding: 30px;
            border-radius: 8px;
            margin-bottom: 30px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-left: 5px solid #d0021b;
        }
        .terms-banner h1 { color: #d0021b; font-weight: 800; text-transform: uppercase; font-size: 28px; margin: 0; }
        .terms-banner p { color: #555; margin: 5px 0 0 0; font-size: 14px; font-style: italic; }
        .terms-icon { font-size: 60px; color: #d0021b; opacity: 0.2; }

        /* Section Styles */
        .section-title { font-weight: 700; color: #000; font-size: 16px; margin-top: 25px; margin-bottom: 10px; }
        .policy-text { font-size: 15px; line-height: 1.6; color: #444; margin-bottom: 15px; text-align: justify; }
        
        .highlight-text { color: #d0021b; font-weight: 500; }
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
                    <li class="breadcrumb-item active" aria-current="page">Điều khoản sử dụng</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="container pb-5">
        <div class="row">
            
            <?php if (file_exists('sidebar.php')) include 'sidebar.php'; ?>

            <div class="col-lg-9">
                <div class="policy-content">
                    
                    <div class="terms-banner">
                        <div>
                            <p>Chính sách &</p>
                            <h1>ĐIỀU KHOẢN SỬ DỤNG</h1>
                        </div>
                        <i class="bi bi-file-earmark-text-fill terms-icon"></i>
                    </div>

                    <h5 class="section-title">1. Quy định sử dụng</h5>
                    <div class="policy-text">
                        Khi khách hàng vào Website Cocolux.com với tư cách khách ghé thăm hay thành viên đã đăng ký, xin vui lòng đọc kỹ quy định sử dụng. Cocolux có quyền thay đổi, chỉnh sửa, thêm hoặc lược bỏ bất kỳ phần nào trong Điều khoản sử dụng này, vào bất cứ lúc nào. Các thay đổi có hiệu lực ngay khi được đăng trên Website Cocolux.com mà không cần thông báo trước với quý khách. Vui lòng kiểm tra thường xuyên để cập nhật những thay đổi của chúng tôi.
                    </div>
                    <div class="policy-text">
                        Cocolux không chịu trách nhiệm về chất lượng đường truyền Internet ảnh hưởng đến tốc độ truy cập của bạn vào Website Cocolux.com.
                    </div>

                    <h5 class="section-title">2. Quy định và hướng dẫn liên quan đến khách hàng</h5>
                    <div class="policy-text">
                        Khách hàng phải đảm bảo đủ 18 tuổi, hoặc truy cập dưới sự giám sát của cha mẹ hay người giám hộ hợp pháp. Khách hàng đảm bảo có đầy đủ hành vi dân sự để thực hiện các giao dịch mua bán hàng hóa theo quy định hiện hành của pháp luật Việt Nam.
                    </div>
                    <div class="policy-text">
                        Cocolux có quyền hỏi thêm về thông tin khách hàng khi xác nhận đơn hàng để đảm bảo mọi thông tin liên lạc về đơn hàng được xử lý chính xác nhất.
                    </div>
                    <div class="policy-text">
                        Nghiêm cấm sử dụng bất kỳ phần nào của trang Cocolux.com với mục đích thương mại hoặc nhân danh bất kỳ đối tác thứ ba nào nếu không được Cocolux cho phép bằng văn bản. Nếu vi phạm bất cứ điều nào trong đây, Cocolux sẽ hủy tài khoản của khách mà không cần báo trước.
                    </div>

                    <h5 class="section-title">3. Chấp nhận đơn hàng, giá cả và điều khoản bất khả kháng</h5>
                    <div class="policy-text">
                        Cocolux luôn nỗ lực để đảm bảo rằng mọi thông tin và tài liệu trên website Cocolux.com là chính xác. Tuy nhiên, không có đảm bảo hay mô tả nào, dù là được nêu rõ hay ngụ ý, được đưa ra để chắc chắn rằng mọi thông tin và tài liệu là hoàn thiện, chính xác, mới nhất, phù hợp cho mục đích cụ thể và trong phạm vi cho phép.
                    </div>
                    <div class="policy-text">
                        Đôi lúc vẫn có sai sót xảy ra tùy theo từng trường hợp, Cocolux sẽ liên hệ hướng dẫn hoặc thông báo hủy đơn hàng đó cho quý khách. Cocolux cũng có quyền từ chối hoặc hủy bỏ bất kỳ đơn hàng nào dù đơn hàng đó chưa hay đã được xác nhận hoặc đã thanh toán.
                    </div>
                    <div class="policy-text">
                        Cocolux sẽ không chịu trách nhiệm nếu quy trình mua hàng không diễn ra đúng cam kết do những nguyên nhân bất khả kháng như tai nạn, hiểm họa thiên nhiên, hành động của bên thứ ba (bao gồm và không giới hạn trong tin tặc, các nhà cung cấp, chính phủ, thuộc chính phủ, cơ quan đa quốc gia hay các chính quyền địa phương), bạo loạn, chiến tranh, trường hợp quốc gia khẩn cấp, khủng bố, dịch bệnh, hỏa hoạn, bão lụt, sự cố kỹ thuật của bên thứ ba, gián đoạn của tiện ích công cộng (bao gồm điện, viễn thông, hay Internet), thiếu hụt hoặc không có khả năng để lấy sản phẩm, vật liệu, thiết bị, hay vận chuyển trong điều kiện bất khả kháng.
                    </div>

                    <h5 class="section-title">4. Thay đổi hoặc hủy bỏ giao dịch</h5>
                    <div class="policy-text">
                        Khách hàng có trách nhiệm cung cấp thông tin đầy đủ và chính xác khi tham gia giao dịch tại Website Cocolux.com. Trong trường hợp khách hàng nhập sai thông tin Cocolux có quyền từ chối thực hiện giao dịch.
                    </div>
                    <div class="policy-text">
                        Trong trường hợp bạn muốn khiếu nại về sản phẩm Cocolux mong bạn có thể hợp tác bằng cách cung cấp thông tin càng chi tiết càng tốt về tình trạng sản phẩm và các thông tin liên quan đến việc mua hàng như mã đơn hàng, xác nhận đơn hàng, hóa đơn mua hàng. Quý khách có thể tham khảo thêm về quy định đổi trả hàng qua Chính sách đổi trả để Cocolux có thể phục vụ khách hàng tốt nhất.
                    </div>

                    <h5 class="section-title">5. Quảng cáo trên trang Cocolux.com</h5>
                    <div class="policy-text">
                        Cocolux luôn tuân theo các quy định về trang website do Cục Quản lý Tiêu chuẩn Quảng Cáo quy định.
                    </div>

                    <h5 class="section-title">6. Thông báo</h5>
                    <div class="policy-text">
                        Mọi thông báo liên quan đến việc mua hàng sẽ được gửi văn bản đến bạn qua email hoặc bằng điện thoại cho khách hàng.
                    </div>

                    <h5 class="section-title">7. Thương hiệu và bản quyền sở hữu trí tuệ</h5>
                    <div class="policy-text">
                        Mọi quyền sở hữu trí tuệ (đã đăng ký hoặc chưa đăng ký), nội dung thông tin và tất cả các logo, biểu tượng thiết kế, văn bản, đồ họa, phần mềm, hình ảnh, video, âm nhạc, âm thanh, biên dịch phần mềm, mã nguồn và phần mềm cơ bản đều là tài sản của Cocolux. Toàn bộ nội dung của trang Cocolux.com được bảo vệ bởi luật bản quyền của Việt Nam và các công ước quốc tế. Bản quyền đã được bảo lưu.
                    </div>

                    <h5 class="section-title">8. Giải quyết tranh chấp</h5>
                    <div class="policy-text">
                        Bất kỳ tranh cãi, khiếu nại hoặc tranh chấp phát sinh từ hoặc liên quan đến giao dịch tại Cocolux.com hoặc các Quy định và Điều kiện này đều sẽ được giải quyết bằng hình thức thương lượng, hòa giải, trọng tài và/hoặc Tòa án theo Luật bảo vệ Người tiêu dùng về Giải quyết tranh chấp giữa người tiêu dùng và tổ chức, cá nhân kinh doanh hàng hóa, dịch vụ.
                    </div>

                    <h5 class="section-title">9. Luật pháp và thẩm quyền tại Lãnh thổ Việt Nam</h5>
                    <div class="policy-text">
                        Tất cả các Điều Khoản và Điều Kiện này và Hợp Đồng (và tất cả nghĩa vụ phát sinh ngoài hợp đồng hoặc có liên quan) sẽ bị chi phối và được hiểu theo luật pháp của Việt Nam. Nếu có tranh chấp phát sinh bởi các Quy định Sử dụng này, quý khách hàng có quyền gửi khiếu nại/khiếu kiện lên Tòa án có thẩm quyền tại Việt Nam để giải quyết.
                    </div>

                    <h5 class="section-title">10. Ngoại lệ</h5>
                    <div class="policy-text">
                        Nếu có mục nào bị cho là sai luật, không hợp pháp hoặc không có hiệu lực vì bất kỳ lí do luật pháp nào của bất kì tiểu bang hay đất nước nào mà những điều khoản này được định sẽ có hiệu lực, thì trong phạm vi cho phép của cơ quan có thẩm quyền, những điều khoản đó sẽ bị loại trừ và những điều khoản còn lại trong Quy định Sử dụng vẫn được sử dụng và giữ nguyên hiệu lực.
                    </div>

                </div>
            </div>
        </div>
    </div>

    <?php if (file_exists('footer.php')) include 'footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>