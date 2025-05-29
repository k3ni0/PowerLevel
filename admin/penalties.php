<?php
require_once 'includes/auth.php';
require_once '../includes/config.php';
require_once "../includes/security.php"; $csrf_token = generateCsrfToken();

// Ajouter une pénalité
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifyCsrfToken($_POST["csrf_token"] ?? '')) { die("Invalid CSRF"); }
    $user_id = $_POST['user_id'];
    $reason = trim($_POST['reason']);
    $level_penalty = (int) $_POST['level_penalty'];

    $stmt = $pdo->prepare("INSERT INTO penalties (user_id, reason, date_applied, level_penalty) VALUES (?, ?, CURDATE(), ?)");
    $stmt->execute([$user_id, $reason, $level_penalty]);

    $pdo->prepare("UPDATE users SET level = GREATEST(1, level - ?) WHERE id = ?")->execute([$level_penalty, $user_id]);

    header('Location: penalties.php');
    exit;
}

$users = $pdo->query("SELECT id, username, level FROM users ORDER BY username")->fetchAll();
$history = $pdo->query("
    SELECT p.*, u.username FROM penalties p
    JOIN users u ON p.user_id = u.id
    ORDER BY p.date_applied DESC
")->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Admin – Pénalités</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 text-white min-h-screen p-6">
    <div class="max-w-3xl mx-auto space-y-8">

        <h1 class="text-3xl font-bold text-red-400">⚠️ Gestion des Pénalités</h1>

        <!-- Formulaire -->
        <div class="bg-gray-800 p-6 rounded-xl shadow-md border border-red-500">
            <h2 class="text-xl font-semibold text-red-300 mb-4">➕ Appliquer une pénalité</h2>
            <form method="POST" class="space-y-4">
                <div>
                <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                    <label class="block text-sm text-gray-300 mb-1">Utilisateur :</label>
                    <select name="user_id" required class="w-full bg-gray-700 border border-gray-600 text-white rounded px-4 py-2">
                        <?php foreach ($users as $user): ?>
                            <option value="<?= $user['id'] ?>"><?= htmlspecialchars($user['username']) ?> (Lvl <?= $user['level'] ?>)</option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label class="block text-sm text-gray-300 mb-1">Motif :</label>
                    <textarea name="reason" rows="3" class="w-full bg-gray-700 border border-gray-600 text-white rounded px-4 py-2" required></textarea>
                </div>

                <div>
                    <label class="block text-sm text-gray-300 mb-1">Perte de niveaux :</label>
                    <input type="number" name="level_penalty" value="1" min="1" max="50"
                           class="w-full bg-gray-700 border border-gray-600 text-white rounded px-4 py-2">
                </div>

                <button type="submit"
                        class="bg-red-600 hover:bg-red-700 text-white px-5 py-2 rounded-full">
                    Appliquer
                </button>
            </form>
        </div>

        <!-- Historique -->
        <div>
            <h2 class="text-xl font-semibold text-purple-300 mb-4">📋 Historique des pénalités</h2>
            <?php foreach ($history as $penalty): ?>
                <div class="bg-gray-800 border border-gray-700 rounded p-4 mb-3 shadow-sm">
                    <p><strong class="text-white"><?= htmlspecialchars($penalty['username']) ?></strong> – <span class="text-red-400"><?= $penalty['level_penalty'] ?> niveau(x)</span></p>
                    <p class="text-sm text-gray-300 italic">Motif : <?= htmlspecialchars($penalty['reason']) ?></p>
                    <p class="text-xs text-gray-500">📅 <?= $penalty['date_applied'] ?></p>
                </div>
            <?php endforeach; ?>
        </div>

        <a href="dashboard.php" class="text-purple-400 hover:underline text-sm">⬅️ Retour au panneau admin</a>
    </div>
</body>
</html>
