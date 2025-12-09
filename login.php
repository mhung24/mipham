<?php
session_start();
require_once 'config/connect.php';

$error = '';

// XỬ LÝ KHI NGƯỜI DÙNG BẤM NÚT ĐĂNG NHẬP
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        $error = 'Vui lòng nhập đầy đủ email và mật khẩu!';
    } else {
        // 1. Tìm user theo email
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email LIMIT 1");
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // 2. Kiểm tra mật khẩu
        if ($user) {
            // Dùng password_verify để so sánh mật khẩu nhập vào với mã hash trong DB
            // (Lưu ý: Nếu bạn nhập tay data vào DB mà không mã hóa thì hàm này sẽ sai. 
            // Password mẫu '123456' phải được hash chuẩn).

            // *MẸO TEST NHANH (Nếu bạn lười hash password)*: 
            // Bạn có thể sửa dòng dưới thành: if ($password == $user['password']) 
            // NHƯNG CÁCH ĐÓ KHÔNG BẢO MẬT. NÊN DÙNG password_verify.

            if (password_verify($password, $user['password'])) {
                // Đăng nhập thành công -> Lưu session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['full_name'];
                $_SESSION['user_role'] = $user['role'];

                // Chuyển hướng về trang chủ
                header("Location: index.php");
                exit;
            } else {
                $error = 'Mật khẩu không chính xác!';
            }
        } else {
            $error = 'Email này chưa được đăng ký!';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Đăng nhập | Cocolux Clone</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- <link rel="stylesheet" href="./css/menu.css"> -->
    <link rel="stylesheet" href="./css/login.css">
    <!-- <link rel="stylesheet" href="./css/footer.css"> -->
</head>

<body>

    <?php if (file_exists('header.php'))
        include 'header.php'; ?>
    <?php if (file_exists('menu.php'))
        include 'menu.php'; ?>

    <div class="container">
        <div class="login-container">
            <h3 class="login-title">ĐĂNG NHẬP</h3>

            <?php if ($error): ?>
                <div class="alert alert-danger text-center" role="alert">
                    <i class="fa fa-exclamation-circle"></i> <?= $error ?>
                </div>
            <?php endif; ?>

            <form action="login.php" method="POST">
                <div class="mb-3">
                    <label for="email" class="form-label">Email / Số điện thoại</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Nhập email của bạn"
                        required value="<?= isset($email) ? htmlspecialchars($email) : '' ?>">
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Mật khẩu</label>
                    <input type="password" class="form-control" id="password" name="password"
                        placeholder="Nhập mật khẩu" required>
                </div>

                <div class="d-flex justify-content-between mb-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="remember">
                        <label class="form-check-label" for="remember" style="font-size: 13px;">Ghi nhớ đăng
                            nhập</label>
                    </div>
                    <a href="#" class="text-decoration-none" style="font-size: 13px; color: #666;">Quên mật khẩu?</a>
                </div>

                <button type="submit" class="btn btn-login">ĐĂNG NHẬP</button>
            </form>

            <div class="login-divider">
                <span>Hoặc đăng nhập bằng</span>
            </div>

            <div class="social-login">
                <a href="#" class="btn-facebook"><i class="fab fa-facebook-f me-2"></i> Facebook</a>
                <a href="#" class="btn-google"><i class="fab fa-google me-2"></i> Google</a>
            </div>

            <div class="register-link">
                Bạn chưa có tài khoản? <a href="register.php">Đăng ký ngay</a>
            </div>
        </div>
    </div>

    <?php if (file_exists('footer.php'))
        include 'footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>