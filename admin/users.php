<?php
require_once 'includes/auth.php';
require_once '../includes/config.php';
require_once '../includes/csrf.php';

// Suppression d’un utilisateur (avec confirmation)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    if (!isset($_POST['csrf_token']) || !check_csrf_token($_POST['csrf_token'])) {
        die('Invalid CSRF token');
    }
    $user_id = (int) $_POST['delete_id'];
    $pdo->prepare("DELETE FROM users WHERE id = ?")->execute([$user_id]);
    header('Location: users.php');
    exit;
}

// Récupération des utilisateurs
$users = $pdo->query("SELECT id, username, email, level, prestige, profile_type, created_at FROM users ORDER BY level DESC, username ASC")->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Admin – Utilisateurs</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 text-white min-h-screen p-6">
    <div class="max-w-6xl mx-auto space-y-6">

        <h1 class="text-3xl font-bold text-purple-400">👥 Gestion des Utilisateurs</h1>

        <?php if (isset($_GET['edited'])): ?>
            <p class="text-green-400 text-sm">✅ Utilisateur modifié avec succès.</p>
        <?php endif; ?>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left bg-gray-800 border border-gray-700 rounded-lg">
                <thead class="bg-gray-700 text-gray-300 uppercase text-xs">
                    <tr>
                        <th class="px-4 py-3">Pseudo</th>
                        <th class="px-4 py-3">Email</th>
                        <th class="px-4 py-3">Niveau</th>
                        <th class="px-4 py-3">Prestige</th>
                        <th class="px-4 py-3">Profil</th>
                        <th class="px-4 py-3">Inscription</th>
                        <th class="px-4 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr class="border-t border-gray-700 hover:bg-gray-700 transition">
                            <td class="px-4 py-2"><?= htmlspecialchars($user['username']) ?></td>
                            <td class="px-4 py-2"><?= htmlspecialchars($user['email']) ?></td>
                            <td class="px-4 py-2"><?= $user['level'] ?></td>
                            <td class="px-4 py-2"><?= $user['prestige'] ?></td>
                            <td class="px-4 py-2"><?= $user['profile_type'] ?></td>
                            <td class="px-4 py-2"><?= $user['created_at'] ?></td>
                            <td class="px-4 py-2 space-x-2">
                                <a href="penalties.php?user_id=<?= $user['id'] ?>" class="text-yellow-400 hover:underline text-xs">⚠️ Sanctionner</a>
                                <a href="edit_user.php?id=<?= $user['id'] ?>" class="text-blue-400 hover:underline text-xs">✏️ Modifier</a>
                                <form method="POST" action="users.php" onsubmit="return confirm('Supprimer cet utilisateur ?')" class="inline">
                                    <input type="hidden" name="delete_id" value="<?= $user['id'] ?>">
                                    <input type="hidden" name="csrf_token" value="<?= get_csrf_token() ?>">
                                    <button type="submit" class="text-red-400 hover:underline text-xs bg-transparent border-0 p-0">🗑️ Supprimer</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <a href="dashboard.php" class="text-purple-400 hover:underline text-sm">⬅️ Retour au panneau admin</a>
    </div>
</body>
</html>
