<?php
session_start();
require_once 'config/connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$message = '';

// XỬ LÝ CẬP NHẬT
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);     // <--- Lấy SĐT
    $address = trim($_POST['address']);   // <--- Lấy Địa chỉ

    if (empty($full_name) || empty($email)) {
        $message = '<div class="alert alert-danger">Tên và Email không được để trống!</div>';
    } else {
        try {
            // Cập nhật thêm phone và address
            $sql = "UPDATE users SET full_name = :name, email = :email, phone = :phone, address = :addr WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':name' => $full_name,
                ':email' => $email,
                ':phone' => $phone,
                ':addr' => $address,
                ':id' => $user_id
            ]);

            $_SESSION['user_name'] = $full_name;
            $message = '<div class="alert alert-success">Cập nhật thông tin thành công!</div>';
        } catch (Exception $e) {
            $message = '<div class="alert alert-danger">Lỗi: ' . $e->getMessage() . '</div>';
        }
    }
}

// LẤY THÔNG TIN ĐỂ HIỂN THỊ
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Thông tin tài khoản</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/menu.css">
    <link rel="stylesheet" href="css/footer.css">
    <style>
        .sidebar-menu .list-group-item.active {
            background-color: #fff5f5;
            color: #d0021b;
            border-color: #fff5f5;
            font-weight: bold;
        }

        .btn-save {
            background-color: #d0021b;
            color: #fff;
            border: none;
            padding: 8px 25px;
        }

        .btn-save:hover {
            background-color: #a80014;
            color: #fff;
        }
    </style>
</head>

<body style="background-color: #f8f9fa;">

    <?php if (file_exists('header.php'))
        include 'header.php'; ?>
    <?php if (file_exists('menu.php'))
        include 'menu.php'; ?>

    <div class="container py-4">
        <div class="row">
            <div class="col-lg-3 mb-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center py-4">
                        <i class="bi bi-person-circle text-secondary" style="font-size: 60px;"></i>
                        <h6 class="fw-bold mt-2"><?= htmlspecialchars($user['full_name']) ?></h6>
                        <small class="text-muted"><?= htmlspecialchars($user['email']) ?></small>
                    </div>
                    <div class="list-group list-group-flush sidebar-menu pb-3">
                        <a href="profile.php" class="list-group-item active"><i class="bi bi-person-vcard me-2"></i>
                            Thông tin tài khoản</a>
                        <a href="orders.php" class="list-group-item">
                            <i class="bi bi-bag-check"></i> Đơn hàng của tôi
                        </a>
                        <a href="logout.php" class="list-group-item text-danger"><i
                                class="bi bi-box-arrow-right me-2"></i> Đăng xuất</a>
                    </div>
                </div>
            </div>

            <div class="col-lg-9" style="min-height: 800px;">
                <div class="card border-0 shadow-sm p-4">
                    <h5 class="text-uppercase fw-bold mb-4 border-bottom pb-3">Hồ sơ của tôi</h5>
                    <?= $message ?>

                    <form action="profile.php" method="POST">

                        <div class="row mb-3">
                            <label class="col-md-3 form-label">Họ và tên</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="full_name"
                                    value="<?= htmlspecialchars($user['full_name']) ?>">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-md-3 form-label">Email</label>
                            <div class="col-md-9">
                                <input type="email" class="form-control" name="email"
                                    value="<?= htmlspecialchars($user['email']) ?>">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-md-3 form-label">Số điện thoại</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="phone" placeholder="Thêm số điện thoại"
                                    value="<?= isset($user['phone']) ? htmlspecialchars($user['phone']) : '' ?>">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-md-3 form-label">Địa chỉ</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="address"
                                    placeholder="Thêm địa chỉ giao hàng"
                                    value="<?= isset($user['address']) ? htmlspecialchars($user['address']) : '' ?>">
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-md-3"></div>
                            <div class="col-md-9">
                                <button type="submit" class="btn btn-save">LƯU THAY ĐỔI</button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

    <?php if (file_exists('footer.php'))
        include 'footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>