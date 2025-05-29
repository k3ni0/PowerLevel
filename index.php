<?php
session_start();

if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit;
} else {
    header("Location: login.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Power Level</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'Exo', sans-serif;
        }
        @import url('https://fonts.googleapis.com/css2?family=Exo:wght@500;700&display=swap');

    </style>
</head>
<body class="bg-gradient-to-br from-gray-900 to-black text-white min-h-screen flex items-center justify-center">
    <div class="bg-gradient-to-br from-[#1f2937] to-[#111827] p-8 rounded-2xl shadow-2xl border border-purple-500 max-w-md w-full text-center">
    <h1 class="text-4xl tracking-wide mb-3" data-text="Power Level">Power Level</h1>

        <p class="text-lg text-gray-300 mb-6">Bienvenue, challenger.</p>
        <p class="text-sm text-purple-200 mb-6 italic">Prépare-toi à monter en puissance.</p>

        <div class="flex flex-col gap-4">
            <a href="login.php" class="bg-purple-600 hover:bg-purple-700 transition px-5 py-2 rounded-full text-white font-semibold shadow-md">
                ▶️ Se connecter
            </a>
            <a href="register.php" class="bg-gray-800 hover:bg-gray-700 transition px-5 py-2 rounded-full text-purple-300 font-medium border border-purple-500">
                🎮 Créer un compte
            </a>
        </div>

        <div class="mt-8 text-sm text-gray-500 italic">
            ⚡ Le jeu commence ici. Chaque séance compte.
        </div>
    </div>
</body>
</html>
