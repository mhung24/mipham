<?php
require_once 'config/connect.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $type = $_POST['type'] ?? '';
    $name = trim($_POST['name'] ?? '');

    if (empty($name)) {
        echo json_encode(['success' => false, 'message' => 'Tên không được để trống']);
        exit;
    }

    $table = ($type === 'brand') ? 'brands' : 'categories';

    try {
        $check = $pdo->prepare("SELECT id FROM $table WHERE name = :name");
        $check->execute([':name' => $name]);

        if ($check->rowCount() > 0) {
            $row = $check->fetch(PDO::FETCH_ASSOC);
            echo json_encode(['success' => true, 'id' => $row['id'], 'name' => $name]);
        } else {
            // Thêm mới
            $stmt = $pdo->prepare("INSERT INTO $table (name) VALUES (:name)");
            $stmt->execute([':name' => $name]);
            echo json_encode(['success' => true, 'id' => $pdo->lastInsertId(), 'name' => $name]);
        }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
}
?>