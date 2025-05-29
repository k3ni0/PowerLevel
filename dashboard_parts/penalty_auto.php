<?php
session_start();
require_once '../includes/config.php';

$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) exit;

$today = date('Y-m-d');

// Vérifie si entraînement du jour NON validé
$stmt = $pdo->prepare("SELECT COUNT(*) FROM user_workouts WHERE user_id = ? AND date_performed = ?");
$stmt->execute([$user_id, $today]);
$already_done = (int) $stmt->fetchColumn();

// Vérifie si jour de repos
$stmt = $pdo->prepare("SELECT rest_day_1, rest_day_2 FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();
$jourActuel = strtolower(date('l'));

$isRestDay = ($user['rest_day_1'] === $jourActuel || $user['rest_day_2'] === $jourActuel);

if (!$already_done && !$isRestDay) {
    // Appliquer pénalité
    $pdo->prepare("INSERT INTO penalties (user_id, reason, date_applied, level_penalty)
                   VALUES (?, ?, CURDATE(), 1)")
        ->execute([$user_id, 'Entraînement non validé avant minuit']);

    $pdo->prepare("UPDATE users SET level = GREATEST(1, level - 1) WHERE id = ?")
        ->execute([$user_id]);

    $_SESSION['penalty_modal'] = true;
}
