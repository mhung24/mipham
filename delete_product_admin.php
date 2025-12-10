<?php
// Tên file: delete_product_admin.php
require_once 'config/connect.php'; // Cần có biến $pdo

if (isset($_GET['id'])) {
    $product_id = (int) $_GET['id'];

    try {
        // SQL DELETE: Xóa sản phẩm dựa trên ID
        $sql = "DELETE FROM products WHERE id = ?";

        // Sử dụng Prepared Statement để an toàn
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$product_id]);

        // Chuyển hướng về trang danh sách sản phẩm sau khi xóa
        // Đã đổi tên thành products_admin.php
        header("Location: products_admin.php?delete=success");
        exit();

    } catch (\PDOException $e) {
        // Xử lý lỗi nếu xóa thất bại
        header("Location: products_admin.php?delete=error&msg=" . urlencode($e->getMessage()));
        exit();
    }
} else {
    // Nếu không có ID, chuyển hướng về trang danh sách
    header("Location: products_admin.php");
    exit();
}
?>