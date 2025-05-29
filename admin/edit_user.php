<?php
require_once 'includes/auth.php';
require_once '../includes/config.php';
require_once '../includes/functions.php';

if (!isset($_GET['id'])) {
    header('Location: users.php');
    exit;
}

$user_id = (int) $_GET['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $level = (int) $_POST['level'];
    $prestige = $_POST['prestige'];
    $profile_type = $_POST['profile_type'];

    $stmt = $pdo->prepare("UPDATE users SET username = ?, email = ?, level = ?, prestige = ?, profile_type = ? WHERE id = ?");
    $stmt->execute([$username, $email, $level, $prestige, $profile_type, $user_id]);

    header("Location: users.php?edited=1");
    exit;
}

$user = getUserData($pdo, $user_id);

if (!$user) {
    echo "Utilisateur introuvable.";
    exit;
}

$prestiges = ['E', 'D', 'C', 'B', 'A', 'S', 'N'];
$profiles = ['débutant', 'amateur'];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier Utilisateur – Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 text-white min-h-screen p-6">
    <div class="max-w-xl mx-auto space-y-6">

        <h1 class="text-3xl font-bold text-purple-400">✏️ Modifier l'utilisateur</h1>

        <div class="bg-gray-800 border border-purple-600 p-6 rounded-xl shadow-md">
            <form method="POST" class="space-y-4">
                <div>
                    <label class="block text-sm text-gray-300">Pseudo :</label>
                    <input type="text" name="username" value="<?= htmlspecialchars($user['username']) ?>" required
                           class="w-full bg-gray-700 text-white px-4 py-2 rounded border border-gray-600">
                </div>

                <div>
                    <label class="block text-sm text-gray-300">Email :</label>
                    <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required
                           class="w-full bg-gray-700 text-white px-4 py-2 rounded border border-gray-600">
                </div>

                <div>
                    <label class="block text-sm text-gray-300">Niveau :</label>
                    <input type="number" name="level" value="<?= $user['level'] ?>" min="1" max="50"
                           class="w-full bg-gray-700 text-white px-4 py-2 rounded border border-gray-600">
                </div>

                <div>
                    <label class="block text-sm text-gray-300">Prestige :</label>
                    <select name="prestige" class="w-full bg-gray-700 text-white px-4 py-2 rounded border border-gray-600">
                        <?php foreach ($prestiges as $p): ?>
                            <option value="<?= $p ?>" <?= ($user['prestige'] === $p) ? 'selected' : '' ?>><?= $p ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label class="block text-sm text-gray-300">Type de profil :</label>
                    <select name="profile_type" class="w-full bg-gray-700 text-white px-4 py-2 rounded border border-gray-600">
                        <?php foreach ($profiles as $p): ?>
                            <option value="<?= $p ?>" <?= ($user['profile_type'] === $p) ? 'selected' : '' ?>><?= ucfirst($p) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <button type="submit"
                        class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-2 rounded-full transition">
                    💾 Enregistrer les modifications
                </button>
            </form>
        </div>

        <a href="users.php" class="text-purple-400 hover:underline text-sm">⬅️ Retour à la liste</a>
    </div>
</body>
</html>
