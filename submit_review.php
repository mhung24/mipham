<?php
session_start();
require_once 'config/connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // 1. Kiểm tra đăng nhập
    if (!isset($_SESSION['user_id'])) {
        echo "<script>alert('Bạn cần đăng nhập để đánh giá!'); window.history.back();</script>";
        exit;
    }

    // Lấy thông tin từ Form và Session
    $product_id = intval($_POST['product_id']);
    // Vì bảng của bạn dùng user_name chứ không phải user_id, ta lấy tên từ session
    $user_name = $_SESSION['user_name'] ?? 'Khách hàng';
    $rating = intval($_POST['rating']);
    $comment = trim($_POST['comment']);
    $image_path = null;

    // 2. Validate
    if ($rating < 1 || $rating > 5 || empty($comment)) {
        echo "<script>alert('Vui lòng chọn số sao và nhập nội dung!'); window.history.back();</script>";
        exit;
    }

    // 3. Xử lý Upload ảnh (Nếu có)
    if (isset($_FILES['review_image']) && $_FILES['review_image']['error'] == 0) {
        $target_dir = "uploads/reviews/";
        if (!file_exists($target_dir))
            mkdir($target_dir, 0777, true); // Tạo thư mục nếu chưa có

        // Đổi tên file để tránh trùng
        $file_name = time() . '_' . basename($_FILES["review_image"]["name"]);
        $target_file = $target_dir . $file_name;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Chỉ cho phép ảnh
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        if (in_array($imageFileType, $allowed)) {
            if (move_uploaded_file($_FILES["review_image"]["tmp_name"], $target_file)) {
                $image_path = $target_file;
            }
        }
    }

    // 4. Lưu vào Database
    try {
        // Cột status mặc định là 'approved' theo DB của bạn, nên không cần insert
        $sql = "INSERT INTO product_reviews (product_id, user_name, rating, comment, image, comment_date) 
                VALUES (:pid, :uname, :rating, :comment, :img, NOW())";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':pid' => $product_id,
            ':uname' => $user_name,
            ':rating' => $rating,
            ':comment' => $comment,
            ':img' => $image_path
        ]);

        // Quay lại trang sản phẩm
        header("Location: product_detail.php?id=" . $product_id . "&review=success");
        exit;

    } catch (Exception $e) {
        echo "Lỗi: " . $e->getMessage();
    }
}
?>