<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion – Admin Power Level</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 text-white min-h-screen flex items-center justify-center p-6">
    <div class="bg-gray-800 border border-purple-600 rounded-xl shadow-lg p-8 w-full max-w-sm space-y-6 text-center">

        <h1 class="text-3xl font-bold text-purple-400">🔐 Connexion Admin</h1>
        <p class="text-sm text-gray-400">Accès réservé à l'équipe Power Level</p>

        <form action="process_login.php" method="POST" class="space-y-4 text-left">
            <div>
                <label class="block text-sm text-gray-300">Nom d'utilisateur :</label>
                <input type="text" name="username" required
                       class="w-full bg-gray-700 text-white px-4 py-2 rounded border border-gray-600">
            </div>

            <div>
                <label class="block text-sm text-gray-300">Mot de passe :</label>
                <input type="password" name="password" required
                       class="w-full bg-gray-700 text-white px-4 py-2 rounded border border-gray-600">
            </div>

            <button type="submit"
                    class="w-full bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-full transition">
                ✅ Se connecter
            </button>
        </form>

        <p class="text-xs text-gray-500 italic mt-4">Besoin d'aide ? Contacte le développeur 👨‍💻</p>
    </div>
</body>
</html>
