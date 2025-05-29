<?php
session_start();
require_once '../includes/config.php';
require_once "../includes/security.php";

$user_id = $_SESSION['user_id'] ?? null;
if (!verifyCsrfToken($_POST["csrf_token"] ?? "")) { echo json_encode(["success"=>false]); exit; }
if (!$user_id || !isset($_POST['field'], $_POST['value'])) {
    echo json_encode(['success' => false]);
    exit;
}

$field = $_POST['field'];
$value = trim($_POST['value']);
$allowed = ['username', 'age', 'avatar'];

if (!in_array($field, $allowed)) {
    echo json_encode(['success' => false]);
    exit;
}

if ($field === 'age') {
    $value = (int)$value;
    if ($value < 13 || $value > 99) exit(json_encode(['success' => false]));
}

if ($field === 'avatar') {
    $ext = strtolower(pathinfo(parse_url($value, PHP_URL_PATH), PATHINFO_EXTENSION));
    if (!filter_var($value, FILTER_VALIDATE_URL) || !in_array($ext, ['jpg','jpeg','png','gif'])) {
        echo json_encode(['success' => false]);
        exit;
    }
}

$stmt = $pdo->prepare("UPDATE users SET $field = ? WHERE id = ?");
$stmt->execute([$value, $user_id]);

// Si username mis à jour → update session aussi
if ($field === 'username') {
    $_SESSION['username'] = $value;
}

echo json_encode(['success' => true]);
