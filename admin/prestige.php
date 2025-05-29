<?php
require_once 'includes/auth.php';
require_once '../includes/config.php';

// 🔄 Mise à jour icône d’un prestige
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $code = $_POST['code'];
    $icon = trim($_POST['icon_url']);

    $stmt = $pdo->prepare("UPDATE prestige_styles SET icon_url = ? WHERE code = ?");
    $stmt->execute([$icon, $code]);

    header("Location: prestige.php?updated=1");
    exit;
}

// 📋 Récupérer les prestiges
$prestiges = $pdo->query("SELECT * FROM prestige_styles ORDER BY FIELD(code, 'E','D','C','B','A','S','N')")->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Admin – Prestiges</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 text-white min-h-screen p-6">
    <div class="max-w-3xl mx-auto space-y-6">
        <h1 class="text-3xl font-bold text-purple-400 mb-4">🔱 Gestion des Prestiges</h1>

        <?php if (isset($_GET['updated'])): ?>
            <p class="text-green-400 text-sm">✅ Ornement mis à jour avec succès.</p>
        <?php endif; ?>

        <?php foreach ($prestiges as $prestige): ?>
            <div class="bg-gray-800 p-4 rounded-lg shadow-md border border-purple-600 mb-4">
                <h2 class="text-xl font-semibold text-white mb-2">
                    Prestige <?= htmlspecialchars($prestige['code']) ?> – <?= htmlspecialchars($prestige['name']) ?>
                </h2>

                <?php if (!empty($prestige['icon_url'])): ?>
                    <img src="<?= htmlspecialchars($prestige['icon_url']) ?>" alt="Ornement"
                         class="w-12 h-12 mb-2">
                <?php else: ?>
                    <p class="text-sm text-gray-400 italic">Aucun ornement défini.</p>
                <?php endif; ?>

                <form method="POST" class="space-y-2">
                    <input type="hidden" name="code" value="<?= $prestige['code'] ?>">
                    <input type="url" name="icon_url" value="<?= htmlspecialchars($prestige['icon_url']) ?>"
                           placeholder="URL de l’image"
                           class="w-full px-4 py-2 bg-gray-700 text-white border border-gray-600 rounded">

                    <button type="submit"
                            class="bg-purple-600 hover:bg-purple-700 px-4 py-1 rounded text-sm text-white transition">
                        💾 Enregistrer
                    </button>
                </form>
            </div>
        <?php endforeach; ?>

        <a href="dashboard.php" class="text-purple-400 hover:underline text-sm">⬅️ Retour au panneau admin</a>
    </div>
</body>
</html>
