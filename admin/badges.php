<?php
require_once 'includes/auth.php';
require_once '../includes/config.php';

// Ajouter un badge
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $icon = trim($_POST['icon']);
    $level = (int) $_POST['level_required'];
    $conditions_json = !empty($_POST['conditions_json']) ? trim($_POST['conditions_json']) : null;

    // Sécurité JSON minimal
    if ($conditions_json && json_decode($conditions_json) === null) {
        die('❌ Format JSON invalide dans les conditions.');
    }
    $stmt = $pdo->prepare("INSERT INTO badges (name, description, icon, level_required, conditions_json)
    VALUES (?, ?, ?, ?, ?)");
$stmt->execute([$name, $description, $icon, $level, $conditions_json]);


    header('Location: badges.php');
    exit;
}

// Supprimer un badge
if (isset($_GET['delete'])) {
    $badge_id = (int) $_GET['delete'];

    $pdo->prepare("DELETE FROM user_badges WHERE badge_id = ?")->execute([$badge_id]);
    $pdo->prepare("DELETE FROM badges WHERE id = ?")->execute([$badge_id]);

    header('Location: badges.php');
    exit;
}

// Récupération des badges
$badges = $pdo->query("SELECT * FROM badges ORDER BY level_required")->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Admin – Badges</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 text-white min-h-screen p-6">
    <div class="max-w-4xl mx-auto space-y-10">

        <h1 class="text-3xl font-bold text-purple-400">🏅 Gestion des Badges</h1>

        <!-- Formulaire -->
        <div class="bg-gray-800 border border-purple-600 p-6 rounded-xl shadow-md">
            <h2 class="text-xl font-semibold text-purple-300 mb-4">➕ Ajouter un badge</h2>

            <form method="POST" class="space-y-4">
                <div>
                    <label class="block text-sm text-gray-300">Nom :</label>
                    <input type="text" name="name" required
                           class="w-full bg-gray-700 text-white px-4 py-2 rounded border border-gray-600">
                </div>

                <div>
                    <label class="block text-sm text-gray-300">Description :</label>
                    <textarea name="description" rows="3" required
                              class="w-full bg-gray-700 text-white px-4 py-2 rounded border border-gray-600"></textarea>
                </div>

                <div>
                    <label class="block text-sm text-gray-300">Icône (URL ou nom de fichier) :</label>
                    <input type="text" name="icon"
                           class="w-full bg-gray-700 text-white px-4 py-2 rounded border border-gray-600">
                </div>

                <div>
                    <label class="block text-sm text-gray-300">Niveau requis :</label>
                    <input type="number" name="level_required" required
                           class="w-full bg-gray-700 text-white px-4 py-2 rounded border border-gray-600">
                </div>
                <div>
    <label class="block text-sm text-gray-300 mb-1">Conditions supplémentaires (optionnel) :</label>
    <textarea name="conditions_json" rows="4"
              class="w-full bg-gray-700 text-white px-4 py-2 rounded border border-gray-600 text-sm"
              placeholder='{"exercises_completed":100,"no_penalty_days":7,"required_prestige":"C"}'></textarea>
    <p class="text-xs text-gray-400 mt-1 italic">
        Format : {"exercises_completed":100, "no_penalty_days":7, "required_prestige":"C"}
    </p>
</div>

                <button type="submit"
                        class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-2 rounded-full transition">
                    💾 Ajouter le badge
                </button>
            </form>
        </div>

        <!-- Liste des badges -->
        <div>
            <h2 class="text-xl font-semibold text-purple-300 mb-4">📋 Badges existants</h2>

            <?php foreach ($badges as $badge): ?>
                <div class="bg-gray-800 border border-gray-700 rounded-lg p-4 mb-4 space-y-2 shadow">
                    <div class="flex items-center gap-4">
                        <?php if (!empty($badge['icon'])): ?>
                            <img src="<?= htmlspecialchars($badge['icon']) ?>" alt="Icône"
                                 class="w-12 h-12 object-contain rounded">
                        <?php endif; ?>
                        <?php if (!empty($badge['conditions_json'])): ?>
    <pre class="text-xs text-gray-400 bg-gray-900 rounded p-2 overflow-x-auto">
        <?= htmlspecialchars($badge['conditions_json']) ?>
    </pre>
<?php endif; ?>

                        <div>
                            <p class="text-white font-semibold"><?= htmlspecialchars($badge['name']) ?>
                                <span class="text-sm text-purple-300">(Niveau <?= $badge['level_required'] ?>)</span></p>
                            <p class="text-sm text-gray-300 italic"><?= htmlspecialchars($badge['description']) ?></p>
                        </div>
                    </div>
                    <a href="badges.php?delete=<?= $badge['id'] ?>"
                       onclick="return confirm('Supprimer ce badge ?')"
                       class="text-red-400 hover:text-red-600 text-sm inline-block mt-2">🗑️ Supprimer</a>
                </div>
            <?php endforeach; ?>
        </div>

        <a href="dashboard.php" class="text-purple-400 hover:underline text-sm">⬅️ Retour au panneau admin</a>
    </div>
</body>
</html>
