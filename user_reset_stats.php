<?php
session_start();
require_once 'includes/config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Si action GET pour debug rapide (depuis lien)
if (isset($_GET['action'])) {
    $action = $_GET['action'];

    if ($action === 'reset') {
        $pdo->prepare("DELETE FROM user_workouts WHERE user_id = ?")->execute([$user_id]);
        $pdo->prepare("DELETE FROM user_badges WHERE user_id = ?")->execute([$user_id]);
        $pdo->prepare("DELETE FROM penalties WHERE user_id = ?")->execute([$user_id]);
        $pdo->prepare("UPDATE users SET level = 1, experience = 0 WHERE id = ?")->execute([$user_id]);
        $_SESSION['level'] = 1;
        header("Location: dashboard.php?reset=1");
        exit;
    }

    if ($action === 'levelup') {
        $stmt = $pdo->prepare("SELECT level FROM users WHERE id = ?");
        $stmt->execute([$user_id]);
        $level = $stmt->fetchColumn();

        if ($level < 50) {
            $new_level = $level + 1;
            $new_xp = ($new_level - 1) * 1000;
            $pdo->prepare("UPDATE users SET level = ?, experience = ? WHERE id = ?")->execute([$new_level, $new_xp, $user_id]);
            $_SESSION['level'] = $new_level;
            header("Location: dashboard.php?levelup=1");
        } else {
            header("Location: dashboard.php?levelup=max");
        }
        exit;
    }
}
?>
