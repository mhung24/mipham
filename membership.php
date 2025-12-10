 <?php
session_start();
require_once 'config/connect.php';
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Khách hàng thân thiết | Cocolux Clone</title>
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
        .policy-heading { font-weight: 700; color: #000; margin-top: 30px; margin-bottom: 15px; font-size: 18px; text-transform: uppercase; }
        .policy-heading:first-child { margin-top: 0; }
        
        .policy-text { font-size: 15px; line-height: 1.6; color: #444; margin-bottom: 15px; text-align: justify; }
        
        /* Table Styles */
        .table-custom { font-size: 14px; border: 1px solid #dee2e6; }
        .table-custom thead th { background-color: #e9ecef; color: #000; vertical-align: middle; text-align: center; font-weight: 700; padding: 15px; }
        .table-custom tbody td { vertical-align: middle; padding: 12px; }
        .rank-name { font-weight: 700; text-align: center; }
        
        /* Rank Colors */
        .rank-silver { color: #6c757d; }
        .rank-gold { color: #d4af37; }
        .rank-diamond { color: #007bff; }
        
        .check-icon { color: #28a745; font-size: 18px; font-weight: bold; text-align: center; display: block; }
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
                    <li class="breadcrumb-item active" aria-current="page">Khách hàng thân thiết</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="container pb-5">
        <div class="row">
            
            <?php if (file_exists('sidebar.php')) include 'sidebar.php'; ?>

            <div class="col-lg-9">
                <div class="policy-content">
                    <h1 class="text-center mb-4" style="font-size: 24px; font-weight: 800; color: #d0021b;">CHÍNH SÁCH VÀ QUYỀN LỢI KHÁCH HÀNG THÂN THIẾT</h1>
                    
                    <h5 class="policy-heading">1. ĐIỀU KIỆN TRỞ THÀNH THÀNH VIÊN COCOLUX</h5>
                    <div class="policy-text">
                        <ul>
                            <li><strong>Đối với khách hàng mua sắm tại cửa hàng:</strong> Nếu quý khách chưa từng đăng ký thông tin tại Cocolux, sau khi mua hàng tại hệ thống cửa hàng, Quý khách vui lòng liên hệ nhân viên cửa hàng để được hỗ trợ đăng ký thông tin khách hàng thành viên.</li>
                            <li><strong>Đối với khách hàng đặt hàng online:</strong> Khi quý khách đặt hàng lần đầu tiên trên website cocolux.com, hệ thống sẽ tự động tạo tài khoản Khách hàng thành viên cho quý khách dựa trên số điện thoại đặt hàng.</li>
                        </ul>
                    </div>

                    <h5 class="policy-heading">2. QUY ĐỊNH HẠNG THÀNH VIÊN</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered table-custom">
                            <thead>
                                <tr>
                                    <th width="25%">Hạng thành viên</th>
                                    <th>Điều kiện</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="rank-name">White</td>
                                    <td>Khách hàng có tài khoản thành viên của Cocolux.</td>
                                </tr>
                                <tr>
                                    <td class="rank-name rank-silver">Silver</td>
                                    <td>Là khách hàng sở hữu tài khoản Cocolux đã được kích hoạt và tích lũy đủ <strong>20.000 điểm</strong> (tương đương tổng giá trị đơn mua hàng thành công: 2.000.000vnđ).</td>
                                </tr>
                                <tr>
                                    <td class="rank-name rank-gold">Gold</td>
                                    <td>Là khách hàng sở hữu tài khoản Cocolux đã được kích hoạt và tích lũy đủ <strong>50.000 điểm</strong> (tương đương tổng giá trị đơn mua hàng thành công: 5.000.000vnđ).</td>
                                </tr>
                                <tr>
                                    <td class="rank-name rank-diamond">Diamond</td>
                                    <td>Là khách hàng sở hữu tài khoản Cocolux đã được kích hoạt và tích lũy đủ <strong>150.000 điểm</strong> (tương đương tổng giá trị đơn mua hàng thành công: 15.000.000vnđ).</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="policy-text fst-italic text-muted small">
                        (*) Lưu ý quy đổi: 1 điểm tích lũy được tính trên từng giao dịch của Khách hàng. Hệ thống sẽ tự động chuyển đổi hạng trong vòng 24h khi đủ điều kiện.
                    </div>

                    <h5 class="policy-heading">3. QUYỀN LỢI KHÁCH HÀNG THÂN THIẾT</h5>
                    <div class="policy-text">
                        Khách hàng thành viên hạng Diamond, Gold, Silver, White được tích lũy điểm với mỗi hóa đơn mua hàng. Nhận các chương trình ưu đãi vào tháng sinh nhật.
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-bordered table-custom text-center">
                            <thead>
                                <tr>
                                    <th rowspan="2" style="width: 30%;">QUYỀN LỢI</th>
                                    <th colspan="4">HẠNG KHÁCH HÀNG</th>
                                </tr>
                                <tr>
                                    <th>WHITE</th>
                                    <th>SILVER</th>
                                    <th>GOLD</th>
                                    <th>DIAMOND</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="text-start fw-bold">Đặc quyền ưu đãi</td>
                                    <td colspan="4" class="p-0 bg-light"></td>
                                </tr>
                                <tr>
                                    <td class="text-start">Tích điểm thành viên khi mua hàng</td>
                                    <td>1%</td>
                                    <td>1%</td>
                                    <td>1%</td>
                                    <td>2%</td>
                                </tr>
                                <tr>
                                    <td class="text-start">Trải nghiệm sản phẩm mới tại cửa hàng</td>
                                    <td><i class="bi bi-check-lg check-icon"></i></td>
                                    <td><i class="bi bi-check-lg check-icon"></i></td>
                                    <td><i class="bi bi-check-lg check-icon"></i></td>
                                    <td><i class="bi bi-check-lg check-icon"></i></td>
                                </tr>
                                <tr>
                                    <td class="text-start">Tham dự sự kiện đặc biệt</td>
                                    <td></td>
                                    <td></td>
                                    <td><i class="bi bi-check-lg check-icon"></i></td>
                                    <td><i class="bi bi-check-lg check-icon"></i></td>
                                </tr>
                                <tr>
                                    <td class="text-start">Miễn phí vận chuyển khi đặt hàng online</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td><i class="bi bi-check-lg check-icon"></i></td>
                                </tr>
                                
                                <tr>
                                    <td class="text-start fw-bold">Tháng sinh nhật</td>
                                    <td colspan="4" class="p-0 bg-light"></td>
                                </tr>
                                <tr>
                                    <td class="text-start">Ưu đãi gần 100 sản phẩm giá sốc</td>
                                    <td></td>
                                    <td><i class="bi bi-check-lg check-icon"></i></td>
                                    <td><i class="bi bi-check-lg check-icon"></i></td>
                                    <td><i class="bi bi-check-lg check-icon"></i></td>
                                </tr>
                                <tr>
                                    <td class="text-start">Ưu đãi hàng nguyên giá</td>
                                    <td></td>
                                    <td></td>
                                    <td>Giảm 10%</td>
                                    <td>Giảm 10%</td>
                                </tr>
                                <tr>
                                    <td class="text-start">Miễn phí vận chuyển</td>
                                    <td></td>
                                    <td></td>
                                    <td><i class="bi bi-check-lg check-icon"></i></td>
                                    <td><i class="bi bi-check-lg check-icon"></i></td>
                                </tr>
                                <tr>
                                    <td class="text-start">Nhân hai lần điểm thưởng khi mua hàng</td>
                                    <td>2%</td>
                                    <td>2%</td>
                                    <td>2%</td>
                                    <td>4%</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <h5 class="policy-heading">4. QUY ĐỊNH SỬ DỤNG ĐIỂM THÀNH VIÊN</h5>
                    <div class="policy-text">
                        <ul>
                            <li>Điểm tích lũy và thông tin phân hạng được cập nhật chính thức 24 giờ sau khi Khách hàng phát sinh giao dịch.</li>
                            <li>Giá trị sử dụng điểm tích lũy là <strong>20.000 điểm trở lên</strong> (tương đương với 20.000đ) mới được áp dụng trừ vào đơn hàng.</li>
                            <li><strong>Ví dụ:</strong>
                                <ul>
                                    <li>Khách hàng có 19.000 điểm thì phải tích luỹ thêm 1.000 điểm nữa mới được trừ điểm khi mua hàng.</li>
                                    <li>Khách hàng có 23.000 điểm thì có thể được trừ tối đa 23.000đ/lần mua hàng.</li>
                                </ul>
                            </li>
                            <li>Tài khoản Thành viên sẽ tự động xuống hạng White nếu Khách hàng không phát sinh giao dịch trong 1 năm liên tiếp.</li>
                            <li>Điều kiện thanh toán tích điểm: Khách hàng vui lòng cung cấp thông tin thành viên và số điện thoại trùng khớp với thông tin Khách hàng đã đăng ký.</li>
                        </ul>
                        <p class="text-danger fst-italic">
                            * Cocolux có quyền thay đổi hay chỉnh sửa các điều khoản liên quan đến quyền lợi thành viên mà không cần báo trước. Mọi thay đổi sẽ được thông báo chính thức trên website.
                        </p>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <?php if (file_exists('footer.php')) include 'footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>