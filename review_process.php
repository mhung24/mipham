<?php
// Tên file: review_process.php
require_once 'config/connect.php';

if (!isset($pdo)) {
    header("Location: reviews_admin.php?review_action=error&msg=Database connection failed");
    exit;
}

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$action_type = $_GET['action_type'] ?? '';
$redirect_url = "reviews_admin.php";

if ($id <= 0 || empty($action_type)) {
    header("Location: $redirect_url");
    exit;
}

try {
    // 1. Lấy tên đánh giá (để thông báo)
    $stmt = $pdo->prepare("SELECT comment FROM product_reviews WHERE id = ?");
    $stmt->execute([$id]);
    $review_comment = substr($stmt->fetchColumn() ?? 'Đánh giá', 0, 30) . '...';

    if ($action_type === 'approve') {
        $new_status = 'approved';
        $sql = "UPDATE product_reviews SET status = ? WHERE id = ?";
        $pdo->prepare($sql)->execute([$new_status, $id]);
        header("Location: $redirect_url?review_action=success&status=" . $new_status . "&name=" . urlencode($review_comment));
        exit;

    } elseif ($action_type === 'hide') {
        $new_status = 'hidden';
        $sql = "UPDATE product_reviews SET status = ? WHERE id = ?";
        $pdo->prepare($sql)->execute([$new_status, $id]);
        header("Location: $redirect_url?review_action=success&status=" . $new_status . "&name=" . urlencode($review_comment));
        exit;

    } elseif ($action_type === 'delete') {
        $sql = "DELETE FROM product_reviews WHERE id = ?";
        $pdo->prepare($sql)->execute([$id]);
        header("Location: $redirect_url?review_action=delete_success");
        exit;

    } else {
        throw new Exception("Hành động không hợp lệ.");
    }

} catch (Exception $e) {
    header("Location: $redirect_url?review_action=error&msg=" . urlencode($e->getMessage()));
    exit;
}
?>