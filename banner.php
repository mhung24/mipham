<?php
// Dữ liệu mẫu cho Sidebar Danh mục Sản phẩm
$sidebar_items = [
    ['text' => 'Trang Điểm', 'link' => '#trangdiem'],
    ['text' => 'Mascara', 'link' => '#mascara'],
    ['text' => 'Son Môi', 'link' => '#sonmoi'],
    ['text' => 'Chăm Sóc Da', 'link' => '#chamsocda'],
    ['text' => 'Chăm Sóc Cơ Thể', 'link' => '#chamsocco the'],
    ['text' => 'Chăm Sóc Tóc', 'link' => '#chamsoc toc'],
    ['text' => 'Dụng Cụ', 'link' => '#dungcu'],
    ['text' => 'Nước Hoa', 'link' => '#nuochoa'],
    ['text' => 'Mỹ Phẩm High-End', 'link' => '#highend'],
    ['text' => 'Thực Phẩm Chức Năng', 'link' => '#tpcn'],
];
?>

<div class="container">
    <div class="row">

        <div class="col-lg-3 col-md-4 d-none d-md-block">
            <div class="sidebar-menu card border-0 rounded-0">
                <ul class="list-group list-group-flush">

                    <?php foreach ($sidebar_items as $item): ?>
                        <li
                            class="list-group-item d-flex justify-content-between align-items-center <?php echo $item['class'] ?? ''; ?>">
                            <a href="<?php echo $item['link']; ?>" class="text-decoration-none text-dark sidebar-link">
                                <?php echo $item['text']; ?>
                            </a>
                            <i class="bi bi-chevron-right small text-muted"></i>
                        </li>
                    <?php endforeach; ?>

                </ul>
            </div>
        </div>

        <div class="col-lg-9 col-md-8">
            <div class="row g-3">

                <div class="col-lg-8">
                    <a href="#combo-xin" class="d-block">
                        <img src="./img/banner3.png" class="img-fluid main-banner-img" alt="Banner Combo Xịn Sale 40%">
                    </a>
                </div>

                <div class="col-lg-4">
                    <div class="d-flex flex-column justify-content-between h-100">
                        <a href="#deal-khung" class="d-block mb-3 h-50">
                            <img src="./img/banner1.png" class="img-fluid sub-banner-img h-100" alt="Banner Deal Khủng">
                        </a>

                        <a href="#freeship" class="d-block h-50">
                            <img src="./img/banner2.png" class="img-fluid sub-banner-img h-100" alt="Banner Freeship">
                        </a>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>
<style>
    /* CSS TÙY CHỈNH CHO MAIN CONTENT (SIDEBAR & BANNER) */

    /* 1. Sidebar Styling */
    .sidebar-menu {
        border: 1px solid #e0e0e0;
        /* Viền xám nhạt */
        height: 100%;
        /* Đảm bảo chiều cao bằng với Banner lớn */
        overflow: hidden;
    }

    .sidebar-menu .list-group-item {
        border-left: none !important;
        border-right: none !important;
        border-radius: 0 !important;
        padding: 10px 15px;
        /* Khoảng cách item thoáng hơn */
        font-size: 14.5px;
        /* Kích thước chữ vừa phải */
    }

    /* Hiệu ứng hover cho sidebar item */
    .sidebar-menu .list-group-item:hover {
        background-color: #f5f5f5;
        cursor: pointer;
    }

    /* Link trong sidebar */
    .sidebar-link {
        color: #333 !important;
    }

    /* 2. Banner Styling */
    .main-banner-img,
    .sub-banner-img {
        width: 100%;
        border-radius: 0 !important;
    }

    /* Cân đối chiều cao Banner Phụ */
    .col-lg-4>.d-flex {
        height: 100%;
    }

    .sub-banner-img {
        object-fit: cover;
    }

    /* Khoảng cách giữa hai banner phụ */
    .col-lg-4 .mb-3 {
        margin-bottom: 12px !important;
    }

    /* Điều chỉnh banner phụ cuối cùng để không có margin bottom */
    .col-lg-4 .d-flex a:last-child {
        margin-bottom: 0 !important;
    }
</style>