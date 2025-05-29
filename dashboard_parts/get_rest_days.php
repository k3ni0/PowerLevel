<?php
session_start();
require_once '../includes/config.php';

$user_id = $_SESSION['user_id'] ?? null;

if ($user_id) {
    $stmt = $pdo->prepare("SELECT rest_day_1, rest_day_2 FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $rest = $stmt->fetch();

    if ($rest && $rest['rest_day_1'] && $rest['rest_day_2']) {
        echo '<span class="text-gray-300 font-semibold text-sm">Mes jours de repos ';
        echo '<strong>' . ucfirst($rest['rest_day_1']) . '</strong> et ';
        echo '<strong>' . ucfirst($rest['rest_day_2']) . '</strong>';
        echo '</span>';
    }
}
?>
