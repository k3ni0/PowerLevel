<?php
session_start();
require_once '../includes/config.php';

$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id || !isset($_POST['action'])) exit;

if ($_POST['action'] === 'public') {
    $token = strtolower($_SESSION['username']) . '-' . bin2hex(random_bytes(4));
    $stmt = $pdo->prepare("UPDATE users SET is_public = 1, public_token = ? WHERE id = ?");
    $stmt->execute([$token, $user_id]);
} elseif ($_POST['action'] === 'private') {
    $stmt = $pdo->prepare("UPDATE users SET is_public = 0, public_token = NULL WHERE id = ?");
    $stmt->execute([$user_id]);
}
