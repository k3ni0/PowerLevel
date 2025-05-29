<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion – Power Level</title>
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
        <h1 class="text-4xl font-bold text-purple-400 tracking-wide mb-3">Connexion</h1>
        <p class="text-sm text-purple-200 mb-6 italic">Entre dans la zone de puissance</p>

        <?php if ($error): ?>
            <p class="text-red-400 font-semibold mb-4"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <form action="process_login.php" method="POST" class="space-y-4">
            <input type="text" name="identifier" required placeholder="Pseudo ou email" class="w-full px-4 py-2 rounded bg-gray-800 text-white border border-gray-600 focus:outline-none focus:ring-2 focus:ring-purple-500">

            <input type="password" name="password" required placeholder="Mot de passe" class="w-full px-4 py-2 rounded bg-gray-800 text-white border border-gray-600 focus:outline-none focus:ring-2 focus:ring-purple-500">

            <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white font-semibold px-5 py-2 rounded-full transition">🎮 Se connecter</button>
        </form>

        <div class="mt-6 text-sm text-gray-500 italic">
            Pas encore inscrit ? <a href="register.php" class="text-purple-400 hover:underline">Créer un compte</a>
        </div>
    </div>
</body>
</html>