<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>

<div class="col-lg-3 d-none d-lg-block">
    <div class="sidebar-section">
        <h5 class="sidebar-heading" style="font-size: 14px; font-weight: 700; text-transform: uppercase; margin-bottom: 15px; color: #000;">THÔNG TIN</h5>
        <ul class="sidebar-menu" style="list-style: none; padding: 0; margin: 0;">
            <li><a href="contact.php" class="<?= ($current_page == 'contact.php') ? 'active' : '' ?>">Liên hệ</a></li>
            
            <li><a href="membership.php" class="<?= ($current_page == 'membership.php') ? 'active' : '' ?>">Khách hàng thân thiết</a></li>
            <li><a href="privacy_policy.php" class="<?= ($current_page == 'privacy_policy.php') ? 'active' : '' ?>">Chính sách bảo mật</a></li>
            <li><a href="terms_of_use.php" class="<?= ($current_page == 'terms_of_use.php') ? 'active' : '' ?>">Điều khoản sử dụng</a></li>
            
            <li><a href="about.php" class="<?= ($current_page == 'about.php') ? 'active' : '' ?>">Giới thiệu</a></li>
            
            <li><a href="delivery_process.php" class="<?= ($current_page == 'delivery_process.php') ? 'active' : '' ?>">Quy trình giao hàng</a></li>
            <li><a href="ordering_guide.php" class="<?= ($current_page == 'ordering_guide.php') ? 'active' : '' ?>">Hướng dẫn đặt hàng</a></li>
        </ul>
    </div>

    <div class="sidebar-section" style="margin-top: 30px;">
        <h5 class="sidebar-heading" style="font-size: 14px; font-weight: 700; text-transform: uppercase; margin-bottom: 15px; color: #000;">HỎI ĐÁP</h5>
        <ul class="sidebar-menu" style="list-style: none; padding: 0; margin: 0;">
            <li><a href="shipping_fee.php" class="<?= ($current_page == 'shipping_fee.php') ? 'active' : '' ?>">Phí vận chuyển</a></li>
            <li><a href="ordering_faq.php" class="<?= ($current_page == 'ordering_faq.php') ? 'active' : '' ?>">Đặt hàng tại Cocolux</a></li>
            <li><a href="check_order.php" class="<?= ($current_page == 'check_order.php') ? 'active' : '' ?>">Kiểm tra đơn hàng</a></li>
        </ul>
    </div>
</div>

<style>
    /* CSS nhúng trực tiếp vào đây để đi theo sidebar luôn */
    .sidebar-menu li { border-bottom: 1px solid #eee; }
    .sidebar-menu li:last-child { border-bottom: none; }
    .sidebar-menu a { display: block; padding: 10px 0; color: #555; text-decoration: none; font-size: 14px; transition: all 0.2s; }
    .sidebar-menu a:hover { color: #d0021b; padding-left: 5px; }
    .sidebar-menu a.active { color: #333; font-weight: bold; background-color: #e9ecef; padding-left: 10px; border-left: 3px solid #333; }
</style>