<?php
$host = 'localhost';
$db = 'web_server';
$user = 'root';
$pass = '';
$charset = 'utf8mb4'; // Thiết lập mã hóa (UTF-8)

// Chuỗi DSN (Data Source Name)
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];

$pdo = null;

try {
    $pdo = new PDO($dsn, $user, $pass, $options);

} catch (\PDOException $e) {
    die(" Lỗi kết nối CSDL: " . $e->getMessage());
}
?>