<?php
session_start();

if (isset($_SESSION['admin_role']) && $_SESSION['admin_role'] === 'admin') {
    header("Location: admin.php");
    exit;
}

$page_title = "Đăng Nhập Admin";
$error_message = $_GET['error'] ?? '';
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .login-container {
            max-width: 400px;
            width: 100%;
            padding: 30px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            background-color: #fff;
        }
    </style>
</head>

<body>
    <div class="login-container">
        <h3 class="text-center mb-4 text-primary">
            <i class="fas fa-user-shield me-2"></i> Admin Login
        </h3>

        <?php if (!empty($error_message)): ?>
            <div class="alert alert-danger text-center">
                <?php
                if ($error_message === 'invalid_credentials') {
                    echo "Email hoặc mật khẩu không đúng.";
                } else if ($error_message === 'no_admin_role') {
                    echo "Tài khoản không có quyền truy cập Admin.";
                } else {
                    echo "Lỗi đăng nhập không xác định.";
                }
                ?>
            </div>
        <?php endif; ?>

        <form action="auth_admin.php" method="POST">
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required placeholder="admin@gmail.com">
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Mật khẩu</label>
                <input type="password" class="form-control" id="password" name="password" required
                    placeholder="Nhập mật khẩu">
            </div>
            <button type="submit" class="btn btn-primary w-100 mt-3">Đăng Nhập</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>