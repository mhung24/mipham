<?php
session_start();

if (!isset($_SESSION['admin_role']) || $_SESSION['admin_role'] !== 'admin') {
    header("Location: login_admin.php");
    exit;
}
?>