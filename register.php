<?php
session_start();
require_once "includes/security.php";
$csrf_token = generateCsrfToken();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription – Power Level</title>
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
        <h1 class="text-4xl font-bold text-purple-400 tracking-wide mb-3">Créer un compte</h1>
        <p class="text-sm text-purple-200 mb-6 italic">Entre dans la Légende</p>

        <form action="process_register.php" method="POST" class="space-y-4 text-left">
            <input type="text" name="username" required placeholder="Nom d'utilisateur" class="w-full px-4 py-2 rounded bg-gray-800 text-white border border-gray-600 focus:ring-2 focus:ring-purple-500">
            <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
            <input type="number" name="age" required min="13" max="99" placeholder="Âge" class="w-full px-4 py-2 rounded bg-gray-800 text-white border border-gray-600 focus:ring-2 focus:ring-purple-500">

            <input type="email" name="email" required placeholder="Email" class="w-full px-4 py-2 rounded bg-gray-800 text-white border border-gray-600 focus:ring-2 focus:ring-purple-500">

            <input type="password" name="password" required placeholder="Mot de passe" class="w-full px-4 py-2 rounded bg-gray-800 text-white border border-gray-600 focus:ring-2 focus:ring-purple-500">

            <label for="profile_type" class="block text-sm text-purple-200">Choix du profil :</label>
            <select name="profile_type" required class="w-full px-4 py-2 rounded bg-gray-800 text-white border border-gray-600 focus:ring-2 focus:ring-purple-500">
                <option value="">-- Sélectionner --</option>
                <option value="débutant">Débutant</option>
                <option value="amateur">Amateur</option>
            </select>

            <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white font-semibold w-full py-2 rounded-full transition">🚀 Créer mon compte</button>
        </form>

        <div class="mt-6 text-sm text-gray-500 italic text-center">
            Déjà un compte ? <a href="login.php" class="text-purple-400 hover:underline">Se connecter</a>
        </div>
    </div>
</body>
</html>
