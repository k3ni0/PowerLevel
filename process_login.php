<?php
session_start();
require_once 'includes/config.php';

if (!isset($_POST['identifier'], $_POST['password'])) {
    die('Champs manquants.');
}

$identifier = $_POST['identifier'];
$password = $_POST['password'];

// Recherche par email OU username
$stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? OR username = ?");
$stmt->execute([$identifier, $identifier]);
$user = $stmt->fetch();

if (!$user || !password_verify($password, $user['password_hash'])) {
    die('Identifiants incorrects.');
}

// Connexion réussie, on lance la session
$_SESSION['user_id'] = $user['id'];
$_SESSION['username'] = $user['username'];
$_SESSION['level'] = $user['level'];
$_SESSION['prestige'] = $user['prestige'];

header('Location: dashboard.php');
exit;
?>
