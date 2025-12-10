<?php
session_start();
require_once 'config/connect.php';
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quy trình giao hàng | Cocolux Clone</title>
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
        
        /* Header Banner Style */
        .delivery-header { text-align: center; margin-bottom: 40px; border-bottom: 2px dashed #eee; padding-bottom: 20px; }
        .delivery-title { font-weight: 800; color: #5a4b45; font-size: 28px; text-transform: uppercase; margin-bottom: 5px; }
        .delivery-subtitle { color: #d0021b; font-weight: 600; font-size: 14px; letter-spacing: 1px; text-transform: uppercase; }

        /* Process List Style */
        .process-item { display: flex; margin-bottom: 0; border-bottom: 1px solid #eee; }
        .process-item:last-child { border-bottom: none; }
        
        /* Cột Icon bên trái */
        .process-icon-col {
            width: 80px;
            flex-shrink: 0;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            padding-top: 20px;
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
            font-size: 24px;
        }

        /* Cột nội dung bên phải */
        .process-text-col { flex-grow: 1; padding: 20px 20px 20px 30px; }
        .process-text-col ol { margin: 0; padding-left: 20px; }
        .process-text-col li { margin-bottom: 10px; font-size: 15px; line-height: 1.6; color: #333; text-align: justify; }
        .process-text-col li:last-child { margin-bottom: 0; }

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
                    <li class="breadcrumb-item active" aria-current="page">Quy trình giao hàng</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="container pb-5">
        <div class="row">
            
            <?php if (file_exists('sidebar.php')) include 'sidebar.php'; ?>

            <div class="col-lg-9">
                <div class="policy-content">
                    
                    <div class="delivery-header">
                        <div class="mb-3">
                            <i class="bi bi-truck text-secondary" style="font-size: 50px;"></i>
                        </div>
                        <h1 class="delivery-title">QUY TRÌNH GIAO HÀNG</h1>
                        <p class="delivery-subtitle">"GIAO HÀNG NHANH - TOÀN QUỐC VỚI MỌI ĐƠN HÀNG"</p>
                    </div>

                    <div style="border: 1px solid #999;">
                        
                        <div class="process-item">
                            <div class="process-icon-col">
                                <div class="icon-circle"><i class="bi bi-telephone"></i></div>
                            </div>
                            <div class="process-text-col">
                                <ol start="1">
                                    <li>Cocolux liên lạc với bạn để thống nhất thời gian giao hàng sẽ giao sản phẩm đến địa điểm mà bạn đã cung cấp trong đơn đặt hàng. Cocolux sẽ cố gắng giao hàng trong thời gian từ 24h đến 48h giờ làm kể từ lúc quý khách đặt hàng. Tuy nhiên, vẫn có những sự chậm trễ do nguyên nhân khách quan (lễ, tết, địa chỉ nhận hàng khó tìm, sự chậm trễ từ dịch vụ chuyển phát...), rất mong bạn có thể thông cảm vì những lý do ngoài sự chi phối của chúng tôi.</li>
                                </ol>
                            </div>
                        </div>

                        <div class="process-item">
                            <div class="process-icon-col">
                                <div class="icon-circle"><i class="bi bi-truck"></i></div>
                            </div>
                            <div class="process-text-col">
                                <ol start="2">
                                    <li>Nếu quý khách không thể có mặt trong đợt nhận hàng thứ nhất, Cocolux sẽ liên lạc lại với quý khách để sắp xếp thời gian giao hàng hoặc hướng dẫn bạn đến công ty vận chuyển để nhận hàng.</li>
                                    <li>Nếu việc giao và nhận hàng không thành công do không thể liên lạc được với quý khách trong vòng 3 ngày, chúng tôi sẽ thông báo đến bạn về việc hủy đơn hàng và hoàn trả các chi phí mà bạn đã thanh toán trong vòng 30 ngày.</li>
                                </ol>
                            </div>
                        </div>

                        <div class="process-item">
                            <div class="process-icon-col">
                                <div class="icon-circle"><i class="bi bi-bell"></i></div>
                            </div>
                            <div class="process-text-col">
                                <ol start="4">
                                    <li>Cocolux sẽ báo ngay đến bạn nếu có sự chậm trễ khi giao hàng, nhưng trong phạm vi pháp luật cho phép, chúng tôi sẽ không chịu trách nhiệm cho bất cứ tổn thất nào, các khoản nợ, thiệt hại hoặc chi phí phát sinh từ việc giao hàng trễ.</li>
                                    <li>Cocolux lưu ý với bạn rằng có một số địa điểm mà dịch vụ chuyển phát không thể giao hàng được. Khi đó, Cocolux sẽ thông báo đến bạn qua thông tin liên lạc mà bạn đã cung cấp khi đặt hàng. Chúng tôi có thể sắp xếp giao hàng đến một địa chỉ khác thuận tiện hơn hoặc tiến hành hủy đơn hàng.</li>
                                </ol>
                            </div>
                        </div>

                        <div class="process-item">
                            <div class="process-icon-col">
                                <div class="icon-circle"><i class="bi bi-person-check"></i></div>
                            </div>
                            <div class="process-text-col">
                                <ol start="6">
                                    <li>Khi nhận sản phẩm, quý khách vui lòng kiểm tra kỹ sản phẩm trước khi ký nhận hàng hóa. Bạn nên giữ lại biên lai mua hàng để làm bằng chứng trong trường hợp muốn liên hệ lại về sản phẩm đã mua.</li>
                                    <li>Quý khách nên cẩn thận khi sử dụng vật sắc nhọn để mở sản phẩm vì bạn có thể làm hỏng sản phẩm. Cocolux không chịu bất cứ rủi ro, tổn thất, hư hại về sản phẩm sau khi bạn đã kiểm tra kỹ lưỡng và ký nhận sản phẩm.</li>
                                </ol>
                            </div>
                        </div>

                        <div class="process-item">
                            <div class="process-icon-col">
                                <div class="icon-circle"><i class="bi bi-box-seam"></i></div>
                            </div>
                            <div class="process-text-col">
                                <ol start="8">
                                    <li>Sản phẩm được đóng gói theo tiêu chuẩn đóng gói của Cocolux. Nếu bạn có nhu cầu đóng gói đặc biệt khác, vui lòng cộng thêm phí phát sinh.</li>
                                    <li>Trong trường hợp những đơn hàng đã xác nhận của quý khách được đặt ở những ngày gần nhau, Cocolux sẽ cố gắng bổ sung vào đơn hàng và giao chung một lần cho quý khách.</li>
                                </ol>
                            </div>
                        </div>

                        <div class="process-item">
                            <div class="process-icon-col">
                                <div class="icon-circle"><i class="bi bi-list-ul"></i></div>
                            </div>
                            <div class="process-text-col">
                                <ol start="10">
                                    <li>Mọi thông tin về việc thay đổi hay hủy bỏ đơn hàng đề nghị quý khách thông báo sớm để Cocolux có thể hủy hoặc chỉnh sửa đơn hàng cho bạn.</li>
                                    <li>Chỉ nhận đổi trả sản phẩm khi lỗi đến từ nhà sản xuất hoặc bị hư hỏng trong quá trình vận chuyển với điều kiện không quá 3 ngày sau khi giao hàng.</li>
                                </ol>
                            </div>
                        </div>

                        <div class="process-item">
                            <div class="process-icon-col">
                                <div class="icon-circle"><i class="bi bi-gift"></i></div>
                            </div>
                            <div class="process-text-col">
                                <ol start="12">
                                    <li>Cocolux nhận giao sản phẩm đến tận tay khách hàng và thanh toán khi nhận hàng hoặc quý khách hàng có thể chọn hình thức chuyển khoản trước (nếu muốn). Lưu ý các đơn hàng có giá trị trên 5.000.000đ chỉ được áp dụng hình thức chuyển khoản.</li>
                                    <li>Đối với những đơn hàng có sản phẩm Pre-Order (đặt hàng trước), Cocolux sẽ tiến hành giao sau khi các sản phẩm Pre-Order đã về hàng.</li>
                                </ol>
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