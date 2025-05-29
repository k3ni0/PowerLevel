<?php
session_start();
require_once '../includes/config.php';
require_once "../includes/security.php";

$user_id = $_SESSION['user_id'] ?? null;
if (!verifyCsrfToken($_POST["csrf_token"] ?? "")) exit;

if ($user_id && isset($_POST['rest_day_1'], $_POST['rest_day_2'])) {
    $day1 = $_POST['rest_day_1'];
    $day2 = $_POST['rest_day_2'];

    if ($day1 !== $day2) {
        $stmt = $pdo->prepare("UPDATE users SET rest_day_1 = ?, rest_day_2 = ? WHERE id = ?");
        $stmt->execute([$day1, $day2, $user_id]);
        echo 'ok';
    }
}
