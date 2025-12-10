<?php
session_start();
require_once 'config/connect.php';

$error = '';
$success = '';

// XỬ LÝ KHI BẤM NÚT ĐĂNG KÝ
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $re_password = $_POST['re_password'];

    // 1. Validate
    if (empty($full_name) || empty($email) || empty($password) || empty($re_password)) {
        $error = 'Vui lòng điền đầy đủ thông tin!';
    } elseif ($password != $re_password) {
        $error = 'Mật khẩu nhập lại không khớp!';
    } elseif (strlen($password) < 6) {
        $error = 'Mật khẩu phải có ít nhất 6 ký tự!';
    } else {
        // 2. Kiểm tra email trùng
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = :email");
        $stmt->execute([':email' => $email]);

        if ($stmt->rowCount() > 0) {
            $error = 'Email này đã được sử dụng. Vui lòng chọn email khác!';
        } else {
            // 3. Thêm vào DB
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $role = 'user';

            try {
                $sql = "INSERT INTO users (full_name, email, password, role) VALUES (:name, :email, :pass, :role)";
                $stmt_insert = $pdo->prepare($sql);
                $stmt_insert->execute([
                    ':name' => $full_name,
                    ':email' => $email,
                    ':pass' => $hashed_password,
                    ':role' => $role
                ]);

                // THÔNG BÁO THÀNH CÔNG (Để hiển thị ở HTML bên dưới)
                $success = 'Đăng ký thành công! Đang chuyển hướng đăng nhập...';

            } catch (Exception $e) {
                $error = 'Có lỗi xảy ra: ' . $e->getMessage();
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Đăng ký tài khoản | Cocolux Clone</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <link rel="stylesheet" href="css/menu.css">
    <link rel="stylesheet" href="css/login.css">
    <link rel="stylesheet" href="css/footer.css">
</head>

<body>

    <?php if (file_exists('header.php'))
        include 'header.php'; ?>
    <?php if (file_exists('menu.php'))
        include 'menu.php'; ?>

    <div class="container">
        <div class="login-container">
            <h3 class="login-title">ĐĂNG KÝ TÀI KHOẢN</h3>

            <?php if ($error): ?>
                <div class="alert alert-danger text-center"><i class="fa fa-exclamation-triangle"></i> <?= $error ?></div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="alert alert-success text-center">
                    <i class="fa fa-check-circle"></i> <?= $success ?>
                    <div class="spinner-border spinner-border-sm text-success ms-2" role="status"></div>
                </div>
            <?php endif; ?>

            <?php if (empty($success)): ?>
                <form action="register.php" method="POST">
                    <div class="mb-3">
                        <label class="form-label">Họ và tên</label>
                        <input type="text" class="form-control" name="full_name" placeholder="Nhập họ tên của bạn"
                            value="<?= isset($full_name) ? htmlspecialchars($full_name) : '' ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email / Số điện thoại</label>
                        <input type="email" class="form-control" name="email" placeholder="Nhập email"
                            value="<?= isset($email) ? htmlspecialchars($email) : '' ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Mật khẩu</label>
                        <input type="password" class="form-control" name="password"
                            placeholder="Mật khẩu (tối thiểu 6 ký tự)" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Nhập lại mật khẩu</label>
                        <input type="password" class="form-control" name="re_password" placeholder="Xác nhận lại mật khẩu"
                            required>
                    </div>

                    <button style="background-color: #d2100b; color: #fff" type="submit" class="btn btn-login">ĐĂNG
                        KÝ</button>
                </form>

                <div class="login-divider">
                    <span>Hoặc đăng nhập bằng</span>
                </div>

                <div class="social-login">
                    <a href="#" class="btn-facebook"><i class="fab fa-facebook-f me-2"></i> Facebook</a>
                    <a href="#" class="btn-google"><i class="fab fa-google me-2"></i> Google</a>
                </div>

                <div class="register-link">
                    Bạn đã có tài khoản? <a href="login.php">Đăng nhập ngay</a>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <?php if (file_exists('footer.php'))
        include 'footer.php'; ?>

    <?php if (!empty($success)): ?>
        <script>
            setTimeout(function () {
                window.location.href = 'login.php';
            }, 2000); // Chuyển trang sau 2 giây (2000ms)
        </script>
    <?php endif; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>