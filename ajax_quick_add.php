<?php
// File: ajax_quick_add.php
require_once 'config/connect.php';

header('Content-Type: application/json');

if (!isset($pdo)) {
    echo json_encode(['success' => false, 'message' => 'Lỗi config: Không tìm thấy biến $pdo']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $type = $_POST['type'] ?? '';
    $name = trim($_POST['name'] ?? '');

    if (empty($name)) {
        echo json_encode(['success' => false, 'message' => 'Tên không được để trống']);
        exit;
    }


    $table = ($type === 'brand') ? 'brands' : 'categories';

    try {
        $checkStmt = $pdo->prepare("SELECT id FROM $table WHERE name = :name");
        $checkStmt->execute([':name' => $name]);

        if ($checkStmt->rowCount() > 0) {
            $row = $checkStmt->fetch(PDO::FETCH_ASSOC);
            echo json_encode(['success' => true, 'id' => $row['id'], 'name' => $name]);
        } else {
            $insertStmt = $pdo->prepare("INSERT INTO $table (name) VALUES (:name)");
            $insertStmt->execute([':name' => $name]);

            echo json_encode(['success' => true, 'id' => $pdo->lastInsertId(), 'name' => $name]);
        }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Lỗi SQL: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Phương thức không hợp lệ']);
}
?>