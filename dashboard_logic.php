<?php

if (session_status() === PHP_SESSION_NONE) session_start();
require_once 'includes/config.php';


$user_id = $_SESSION['user_id'];

$username = $_SESSION['username'] ?? '';
$level = $_SESSION['level'] ?? 1;

if (!$user_id) {
    header('Location: login.php');
    exit;
}

// 🔎 Récupération user
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

$experience = $user['experience'];
$prestige = $user['prestige'];
$current_xp = $experience % 1000;
$xp_needed = $level * 1000;

// 🔄 Badges manquants (si oubli logique précédente)
$stmt = $pdo->prepare("
    SELECT b.id FROM badges b
    WHERE b.level_required <= ? AND b.id NOT IN (
        SELECT badge_id FROM user_badges WHERE user_id = ?
    )
");
$stmt->execute([$level, $user_id]);
$new_badges = $stmt->fetchAll();

foreach ($new_badges as $badge) {
    $stmt = $pdo->prepare("INSERT INTO user_badges (user_id, badge_id, date_obtained) VALUES (?, ?, CURDATE())");
    $stmt->execute([$user_id, $badge['id']]);
}

// 🎓 Choix amateur (niv.10)
$show_class_choice = ($level >= 10 && $user['profile_type'] === 'débutant' && !isset($_GET['class_decided']));

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['class_choice'])) {
    $choice = $_POST['class_choice'];
    if ($choice === 'amateur') {
        $pdo->prepare("UPDATE users SET profile_type = 'amateur' WHERE id = ?")->execute([$user_id]);
    } elseif ($choice === 'reset') {
        $pdo->prepare("UPDATE users SET level = 1, experience = 0 WHERE id = ?")->execute([$user_id]);
        $_SESSION['level'] = 1;
    }
    header("Location: dashboard.php?class_decided=1");
    exit;
}

// 🔱 Prestige (niv.50)
$now = new DateTime();
$lastOfferDate = $user['last_prestige_offer'] ? new DateTime($user['last_prestige_offer']) : null;
$cooldown_ok = !$lastOfferDate || $lastOfferDate->diff($now)->days >= 7;

$prestige_order = ['E', 'D', 'C', 'B', 'A', 'S', 'N'];
$current_prestige = $user['prestige'];
$next_prestige_index = array_search($current_prestige, $prestige_order) + 1;
$next_prestige = $prestige_order[$next_prestige_index] ?? null;

$show_prestige_modal = (
    $level >= 50 && $next_prestige && $cooldown_ok && !isset($_GET['prestige_decided'])
);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['prestige_choice'])) {
    $choice = $_POST['prestige_choice'];
    if ($choice === 'prestige' && $next_prestige) {
        $stmt = $pdo->prepare("UPDATE users SET prestige = ?, level = 1, experience = 0, last_prestige_offer = NOW() WHERE id = ?");
        $stmt->execute([$next_prestige, $user_id]);
        $_SESSION['level'] = 1;
    } elseif ($choice === 'stay') {
        $pdo->prepare("UPDATE users SET last_prestige_offer = NOW() WHERE id = ?")->execute([$user_id]);
    }
    header("Location: dashboard.php?prestige_decided=1");
    exit;
}

// 🔍 Bloc d'entraînement
$stmt = $pdo->prepare("SELECT * FROM workout_blocks WHERE level_min <= ? AND level_max >= ? AND prestige_required = ? AND profile_type = ? LIMIT 1");
$stmt->execute([$level, $level, $user['prestige'], $user['profile_type']]);

$block = $stmt->fetch();

$exercises = [];
if ($block) {
    $stmt = $pdo->prepare("SELECT * FROM workout_exercises WHERE block_id = ?");
    $stmt->execute([$block['id']]);
    $exercises = $stmt->fetchAll();
}

// 📅 Jours de repos
$todayName = strtolower(date('l'));
$jourActuel = [
    'monday' => 'lundi', 'tuesday' => 'mardi', 'wednesday' => 'mercredi',
    'thursday' => 'jeudi', 'friday' => 'vendredi',
    'saturday' => 'samedi', 'sunday' => 'dimanche'
][$todayName];

$jour_de_repos = ($user['rest_day_1'] === $jourActuel || $user['rest_day_2'] === $jourActuel);

// ⏱️ Vérification séance du jour
$today = date('Y-m-d');
$stmt = $pdo->prepare("SELECT id FROM user_workouts WHERE user_id = ? AND date_performed = ?");
$stmt->execute([$user_id, $today]);
$already_done = $stmt->fetch();

// ❌ Échec de validation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['failed']) && $_POST['failed'] === "1" && !$jour_de_repos) {
    $pdo->prepare("INSERT INTO penalties (user_id, reason, date_applied, level_penalty) VALUES (?, ?, CURDATE(), 1)")
        ->execute([$user_id, 'Entraînement non validé à temps']);
    $pdo->prepare("UPDATE users SET level = GREATEST(1, level - 1) WHERE id = ?")->execute([$user_id]);
    $_SESSION['level'] = max(1, $_SESSION['level'] - 1);
    header("Location: dashboard.php?fail=1");
    exit;
}

// ✅ Validation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$already_done && $block && !$jour_de_repos) {
    // 1. Enregistrer la séance
    $stmt = $pdo->prepare("INSERT INTO user_workouts (user_id, workout_id, date_performed, success) VALUES (?, ?, ?, 1)");
    $stmt->execute([$user_id, $block['id'], $today]);

    // 2. Ajouter 100 XP

    $pdo->prepare("UPDATE users SET experience = experience + ? WHERE id = ?")->execute([$xp_gain, $user_id]);

    // 3. Monter de 1 niveau (max 50)
    if ($user['level'] < 50) {
        $new_level = $user['level'] + 1;
        
        $pdo->prepare("UPDATE users SET level = ? WHERE id = ?")->execute([$new_level, $user_id]);
        $level = $user['level'];
    }

    // 4. Vérifier nouveaux badges
    $stmt = $pdo->prepare("
        SELECT b.id FROM badges b
        WHERE b.level_required <= ? AND b.id NOT IN (
            SELECT badge_id FROM user_badges WHERE user_id = ?
        )
    ");
    $stmt->execute([$_SESSION['level'], $user_id]);
    $new_badges = $stmt->fetchAll();

    foreach ($new_badges as $badge) {
        $pdo->prepare("INSERT INTO user_badges (user_id, badge_id, date_obtained) VALUES (?, ?, CURDATE())")
            ->execute([$user_id, $badge['id']]);
    }

    header("Location: dashboard.php?success=1");
    exit;
}

