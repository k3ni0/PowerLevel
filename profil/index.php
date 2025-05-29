<?php
require_once '../includes/config.php';

$token = basename($_SERVER['REQUEST_URI']);
$stmt = $pdo->prepare("SELECT * FROM users WHERE public_token = ? AND is_public = 1");
$stmt->execute([$token]);
$user = $stmt->fetch();

if (!$user) {
    http_response_code(404);
    ?>
    <!DOCTYPE html>
    <html lang="fr">
    <head>
        <meta charset="UTF-8">
        <title>Profil introuvable</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <script src="https://cdn.tailwindcss.com"></script>
        <style>
            body {
                font-family: 'Exo', sans-serif;
            }
            @import url('https://fonts.googleapis.com/css2?family=Orbitron:wght@500;700&display=swap');
        </style>
    </head>
    <body class="bg-gradient-to-br from-gray-900 to-black text-white flex items-center justify-center min-h-screen">
        <div class="text-center space-y-4">
            <h1 class="text-5xl font-bold text-purple-500">Erreur 404</h1>
            <p class="text-lg text-gray-300">Ce profil est introuvable ou privé 🕵️</p>
            <a href="/" class="mt-4 inline-block px-5 py-2 bg-purple-600 text-white rounded-full hover:bg-purple-700 transition">⬅️ Retour à l'accueil</a>
        </div>
    </body>
    </html>
    <?php exit; }
?>

<?php
// Ornement prestige
$stmt = $pdo->prepare("SELECT icon_url FROM prestige_styles WHERE code = ?");
$stmt->execute([$user['prestige']]);
$prestigeIcon = $stmt->fetchColumn();

// Badges
$stmt = $pdo->prepare("
    SELECT b.* FROM badges b
    JOIN user_badges ub ON ub.badge_id = b.id
    WHERE ub.user_id = ?
    ORDER BY b.level_required
");
$stmt->execute([$user['id']]);
$badges = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Profil de <?= htmlspecialchars($user['username']) ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'Exo', sans-serif;
        }
        @import url('https://fonts.googleapis.com/css2?family=Orbitron:wght@500;700&display=swap');
    </style>
</head>
<body class="bg-gray-900 text-white min-h-screen p-6">
    <div class="max-w-xl mx-auto bg-gray-800 p-6 rounded-xl shadow-md text-center space-y-6">

        <h1 class="text-3xl text-purple-400 font-bold">🎮 <?= htmlspecialchars($user['username']) ?></h1>

        <!-- Avatar avec ornement -->
        <div class="relative w-28 h-28 mx-auto group select-none">
            <?php if (!empty($prestigeIcon)): ?>
                <img src="<?= htmlspecialchars($prestigeIcon) ?>" alt="Ornement prestige"
                     class="absolute inset-0 w-full h-full object-contain pointer-events-none z-0">
            <?php endif; ?>
            <div class="absolute inset-0 flex items-center justify-center z-10">
                <img src="<?= $user['avatar'] ?: '../assets/default-avatar.png' ?>"
                     alt="Avatar"
                     class="w-20 h-20 rounded-full object-cover border-2 border-purple-500 shadow-md">
            </div>
        </div>

        <p class="text-gray-400">Niveau <?= $user['level'] ?> | Prestige <?= $user['prestige'] ?></p>
        <p class="text-sm text-purple-200">Âge : <?= $user['age'] ?> ans</p>

        <!-- Badges -->
        <div class="mt-6">
            <h2 class="text-lg font-bold text-purple-400 mb-3">🏅 Badges débloqués</h2>
            <?php if (count($badges) > 0): ?>
                <div class="space-y-3">
                    <?php foreach ($badges as $badge): ?>
                        <div class="bg-gray-700 border border-gray-600 rounded p-3 flex items-center gap-4">
                            <?php if (!empty($badge['icon'])): ?>
                                <img src="<?= htmlspecialchars($badge['icon']) ?>" alt="Icône"
                                     class="w-10 h-10 object-contain rounded">
                            <?php else: ?>
                                <div class="w-10 h-10 flex items-center justify-center bg-gray-600 text-purple-300 rounded">🎖️</div>
                            <?php endif; ?>
                            <div class="text-left">
                                <p class="font-semibold text-white text-sm"><?= htmlspecialchars($badge['name']) ?> <span class="text-xs text-purple-300">(Niveau <?= $badge['level_required'] ?>)</span></p>
                                <p class="text-xs text-gray-300 italic"><?= htmlspecialchars($badge['description']) ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p class="text-sm text-gray-400 italic">Aucun badge débloqué pour le moment.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
