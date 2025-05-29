<?php
session_start();
require_once '../includes/config.php';
require_once "../includes/security.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$today = date('Y-m-d');

// Récup utilisateur
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

$level = (int) $user['level'];

// Vérifie si déjà fait
$stmt = $pdo->prepare("SELECT id FROM user_workouts WHERE user_id = ? AND date_performed = ?");
$stmt->execute([$user_id, $today]);
$already_done = $stmt->fetch();

// Jour de repos ?
$jourActuel = strtolower(date('l'));
$jour_de_repos = (
    $user['rest_day_1'] === $jourActuel ||
    $user['rest_day_2'] === $jourActuel
);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
if (!verifyCsrfToken($_POST["csrf_token"] ?? "")) { header("Location: ../dashboard.php"); exit; }
// Vérif du token pour éviter soumission multiple
if (
    !isset($_POST['training_token']) ||
    !isset($_SESSION['training_token']) ||
    $_POST['training_token'] !== $_SESSION['training_token']
) {
    // Redirection silencieuse pour éviter double soumission
    header("Location: ../dashboard.php");
    exit;
}

// Une fois validé, invalider le token
unset($_SESSION['training_token']);

    // ❌ Pénalité
    if (isset($_POST['failed']) && $_POST['failed'] === "1" && !$jour_de_repos) {
        $pdo->prepare("INSERT INTO penalties (user_id, reason, date_applied, level_penalty)
                       VALUES (?, ?, CURDATE(), 1)")
            ->execute([$user_id, 'Entraînement non validé à temps']);

        $pdo->prepare("UPDATE users SET level = GREATEST(1, level - 1) WHERE id = ?")
            ->execute([$user_id]);

        $_SESSION['level'] = max(1, $user['level'] - 1);
        header("Location: ../dashboard.php?fail=1");
        exit;
    }

    // ✅ Entraînement validé
    if (!$already_done && !$jour_de_repos) {
        // Récup bloc
        $stmt = $pdo->prepare("SELECT * FROM workout_blocks WHERE level_min <= ? AND level_max >= ? AND prestige_required = ? AND profile_type = ? LIMIT 1");
        $stmt->execute([$level, $level, $user['prestige'], $user['profile_type']]);
        $block = $stmt->fetch();

        if ($block) {
            // Enregistrement de la séance
            $stmt = $pdo->prepare("INSERT INTO user_workouts (user_id, workout_id, date_performed, success)
                                   VALUES (?, ?, ?, 1)");
            $stmt->execute([$user_id, $block['id'], $today]);

            // Ajouter 100 XP
            $xp_gain = 100;
            $stmt = $pdo->prepare("UPDATE users SET experience = experience + ? WHERE id = ?");
            $stmt->execute([$xp_gain, $user_id]);

// Monter d’un niveau (si < 50)
if ($level < 50) {
    $new_level = $level + 1;
    $pdo->prepare("UPDATE users SET level = ? WHERE id = ?")->execute([$new_level, $user_id]);
    $_SESSION['level'] = $new_level;

    // ✅ Redirection avec indicateur de level-up
    header("Location: ../dashboard.php?success=1&levelup=1");
    exit;
}


            // Vérif badges à débloquer
            $stmt = $pdo->prepare("
            SELECT * FROM badges
            WHERE level_required <= ? AND id NOT IN (
                SELECT badge_id FROM user_badges WHERE user_id = ?
            )
        ");
        $stmt->execute([$_SESSION['level'], $user_id]);
        $potential_badges = $stmt->fetchAll();
        

            foreach ($new_badges as $badge) {
                $pdo->prepare("INSERT INTO user_badges (user_id, badge_id, date_obtained)
                               VALUES (?, ?, CURDATE())")->execute([$user_id, $badge['id']]);
            }

            header("Location: ../dashboard.php?success=1");
            exit;
        }
    }
}
foreach ($potential_badges as $badge) {
    $conditions = json_decode($badge['conditions_json'], true);

    // Si pas de conditions personnalisées → badge attribué directement
    if (!$conditions) {
        $pdo->prepare("INSERT INTO user_badges (user_id, badge_id, date_obtained)
                       VALUES (?, ?, CURDATE())")->execute([$user_id, $badge['id']]);
        continue;
    }

    $canAward = true;

    // Condition : nombre d'exercices complétés (totaux)
    if (isset($conditions['exercises_completed'])) {
        $stmt = $pdo->prepare("
            SELECT COUNT(*) FROM user_workouts
            WHERE user_id = ? AND success = 1
        ");
        $stmt->execute([$user_id]);
        $completed = (int) $stmt->fetchColumn();
        if ($completed < $conditions['exercises_completed']) $canAward = false;
    }

    // Condition : aucune pénalité sur X jours
    if (isset($conditions['no_penalty_days'])) {
        $stmt = $pdo->prepare("
            SELECT COUNT(*) FROM penalties
            WHERE user_id = ? AND date_applied >= DATE_SUB(CURDATE(), INTERVAL ? DAY)
        ");
        $stmt->execute([$user_id, $conditions['no_penalty_days']]);
        $penalties = (int) $stmt->fetchColumn();
        if ($penalties > 0) $canAward = false;
    }

    // Condition : prestige requis
    if (isset($conditions['required_prestige'])) {
        if ($user['prestige'] !== $conditions['required_prestige']) $canAward = false;
    }

    // Si toutes les conditions sont remplies → on l’ajoute
    if ($canAward) {
        $pdo->prepare("INSERT INTO user_badges (user_id, badge_id, date_obtained)
                       VALUES (?, ?, CURDATE())")->execute([$user_id, $badge['id']]);
    }
}

// Fallback
header("Location: ../dashboard.php");
exit;
