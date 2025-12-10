<?php

if (!isset($active_page)) {
    $active_page = '';
}
?>

<nav id="sidebar">
    <div class="logo"><i class="fas fa-user-shield me-2"></i> Admin Panel</div>
    <ul>
        <li>
            <a href="admin.php" class="<?php echo ($active_page == 'dashboard') ? 'active' : ''; ?>">
                <i class="fas fa-home"></i> Trang chủ
            </a>
        </li>
        <li>
            <a href="products_admin.php" class="<?php echo ($active_page == 'products') ? 'active' : ''; ?>">
                <i class="fas fa-box"></i> Sản phẩm
            </a>
        </li>
        <li>
            <a href="orders_admin.php" class="<?php echo ($active_page == 'orders') ? 'active' : ''; ?>">
                <i class="fas fa-shopping-cart"></i> Đơn hàng
            </a>
        </li>
        <li>
            <a href="reviews_admin.php" class="<?php echo ($active_page == 'reviews') ? 'active' : ''; ?>">
                <i class="fas fa-star"></i> Đánh giá
            </a>
        </li>
        <li>
            <a href="customers_admin.php" class="<?php echo ($active_page == 'customers') ? 'active' : ''; ?>">
                <i class="fas fa-users"></i> Khách hàng
            </a>
        </li>
        <li>
            <a href="settings_admin.php" class="<?php echo ($active_page == 'settings') ? 'active' : ''; ?>">
                <i class="fas fa-cog"></i> Cài đặt
            </a>
        </li>
        <li><a href="#" class="text-danger"><i class="fas fa-sign-out-alt"></i> Đăng xuất</a></li>
    </ul>
</nav>