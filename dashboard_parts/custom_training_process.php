<?php
session_start();
require_once '../includes/config.php';
require_once '../includes/functions.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$today = date('Y-m-d');

$user = getUserData($pdo, $user_id);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'add') {
        $name = trim($_POST['name'] ?? '');
        $duration = max(1, (int)($_POST['duration'] ?? 0));

        $stmt = $pdo->prepare("SELECT COUNT(*) FROM custom_trainings WHERE user_id = ?");
        $stmt->execute([$user_id]);
        $count = (int)$stmt->fetchColumn();

        if ($count < 5 && $name !== '') {
            $stmt = $pdo->prepare("INSERT INTO custom_trainings (user_id, name, duration) VALUES (?, ?, ?)");
            $stmt->execute([$user_id, $name, $duration]);
        }
        header('Location: ../dashboard.php');
        exit;
    }

    if ($action === 'validate') {
        $last = $user['last_custom_training'];
        if (!$last || substr($last, 0, 10) !== $today) {
            $xp_gain = 50;
            $stmt = $pdo->prepare("UPDATE users SET custom_xp = custom_xp + ?, last_custom_training = ? WHERE id = ?");
            $stmt->execute([$xp_gain, $today, $user_id]);
        }
        header('Location: ../dashboard.php');
        exit;
    }
}

header('Location: ../dashboard.php');
exit;
?>
