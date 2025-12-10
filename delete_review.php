<?php
require_once 'config/connect.php'; // Cần có $pdo

if (isset($_GET['id'])) {
    $review_id = (int) $_GET['id'];

    try {
        $sql = "DELETE FROM product_reviews WHERE id = ?";
        // Sử dụng Prepared Statement (PDO)
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$review_id]);

        // Chuyển hướng về trang dashboard sau khi xóa
        header("Location: dashboard.php");
        exit();

    } catch (\PDOException $e) {
        die("Lỗi khi xóa đánh giá: " . $e->getMessage());
    }
} else {
    header("Location: dashboard.php");
    exit();
}
?>