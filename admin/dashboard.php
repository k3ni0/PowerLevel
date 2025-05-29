<?php
require_once 'includes/auth.php';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Admin – Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 text-white min-h-screen flex items-center justify-center p-6">
    <div class="bg-gray-800 border border-purple-600 rounded-xl shadow-xl p-8 max-w-lg w-full space-y-6 text-center">

        <h1 class="text-3xl font-bold text-purple-400">Bienvenue, <?= htmlspecialchars($_SESSION['admin_username']) ?> 👑</h1>
        <p class="text-gray-400 text-sm">Panneau de contrôle administrateur</p>

        <ul class="space-y-3 text-left">
            <li>
                <a href="workouts.php"
                   class="block px-4 py-2 bg-purple-700 hover:bg-purple-800 rounded-lg transition text-white">
                   🦾 Gérer les entraînements
                </a>
            </li>
            <li>
                <a href="badges.php"
                   class="block px-4 py-2 bg-purple-700 hover:bg-purple-800 rounded-lg transition text-white">
                   🏅 Gérer les badges
                </a>
            </li>
            <li>
                <a href="users.php"
                   class="block px-4 py-2 bg-purple-700 hover:bg-purple-800 rounded-lg transition text-white">
                   👥 Gérer les utilisateurs
                </a>
            </li>
            <li>
                <a href="penalties.php"
                   class="block px-4 py-2 bg-purple-700 hover:bg-purple-800 rounded-lg transition text-white">
                   ⚠️ Appliquer des pénalités
                </a>
            </li>
            <li>
                <a href="prestige.php"
                   class="block px-4 py-2 bg-purple-700 hover:bg-purple-800 rounded-lg transition text-white">
                   🔱 Gérer les ornements de prestige
                </a>
            </li>
        </ul>

        <a href="logout.php"
           class="inline-block mt-4 text-sm text-red-400 hover:underline">🚪 Déconnexion</a>
    </div>
</body>
</html>
