<?php
session_start();
require_once '../includes/config.php';
require_once "../includes/security.php";

if (!isset($_POST['username'], $_POST['password'])) {
if (!verifyCsrfToken($_POST["csrf_token"] ?? "")) { die("Invalid CSRF token"); }
    die('Champs manquants.');
}

$username = $_POST['username'];
$password = $_POST['password'];

$stmt = $pdo->prepare("SELECT * FROM admin WHERE username = ?");
$stmt->execute([$username]);
$admin = $stmt->fetch();

if (!$admin || !password_verify($password, $admin['password_hash'])) {
    die('Identifiants incorrects.');
}

$_SESSION['admin_id'] = $admin['id'];
$_SESSION['admin_username'] = $admin['username'];

header('Location: dashboard.php');
exit;
?>
