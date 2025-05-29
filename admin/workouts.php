<?php
require_once 'includes/auth.php';
require_once '../includes/config.php';
require_once '../includes/csrf.php';

// Ajouter un bloc
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $level_min = $_POST['level_min'];
    $level_max = $_POST['level_max'];
    $duration = $_POST['duration_minutes'];
    $prestige = $_POST['prestige_required'];
    $exercises = $_POST['exercises'];
    $profile_type = isset($_POST['is_beginner']) ? 'débutant' : 'amateur';

    $stmt = $pdo->prepare("INSERT INTO workout_blocks (level_min, level_max, duration_minutes, prestige_required, profile_type)
    VALUES (?, ?, ?, ?, ?)");
$stmt->execute([$level_min, $level_max, $duration, $prestige, $profile_type]);
    $block_id = $pdo->lastInsertId();

    foreach ($exercises as $exo) {
        if (!empty($exo['name'])) {
            $stmt = $pdo->prepare("INSERT INTO workout_exercises (block_id, name, repetitions) VALUES (?, ?, ?)");
            $stmt->execute([$block_id, $exo['name'], $exo['repetitions']]);
        }
    }

    header('Location: workouts.php');
    exit;
}

// Suppression
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    if (!isset($_POST['csrf_token']) || !check_csrf_token($_POST['csrf_token'])) {
        die('Invalid CSRF token');
    }
    $block_id = (int) $_POST['delete_id'];
    $pdo->prepare("DELETE FROM workout_blocks WHERE id = ?")->execute([$block_id]);
    header('Location: workouts.php');
    exit;
}

// Récupération
$blocks = $pdo->query("SELECT * FROM workout_blocks ORDER BY level_min")->fetchAll();

function getExercises($pdo, $block_id) {
    $stmt = $pdo->prepare("SELECT * FROM workout_exercises WHERE block_id = ?");
    $stmt->execute([$block_id]);
    return $stmt->fetchAll();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Admin – Gérer les entraînements</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 text-white min-h-screen p-6">
    <div class="max-w-4xl mx-auto space-y-10">
        <h1 class="text-3xl font-bold text-purple-400 mb-6">🦾 Blocs d'entraînement par niveau</h1>

        <!-- Ajouter un bloc -->
        <div class="bg-gray-800 p-6 rounded-xl border border-purple-600 shadow-md">
            <h2 class="text-xl font-semibold mb-4 text-purple-300">➕ Nouveau bloc</h2>
            <form method="POST" class="space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="space-y-3">
    <div>
        <label class="block text-sm text-gray-300 mb-1">Prestige :</label>
        <select name="prestige_required" class="w-full bg-gray-700 text-white px-3 py-2 rounded border border-gray-600">
            <?php foreach (['E','D','C','B','A','S','N'] as $p): ?>
                <option value="<?= $p ?>"><?= $p ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div>
        <label class="inline-flex items-center space-x-2 text-sm text-gray-300">
            <input type="checkbox" name="is_beginner" value="1" class="form-checkbox text-purple-600 focus:ring focus:ring-purple-500">
            <span>Ce bloc est réservé aux <strong>débutants</strong></span>
        </label>
    </div>
</div>




                    <div>
                        <label class="block text-sm text-gray-300">Durée (minutes) :</label>
                        <input type="number" name="duration_minutes" required
                               class="w-full bg-gray-700 text-white px-3 py-2 rounded border border-gray-600">
                    </div>
                    <div>
                        <label class="block text-sm text-gray-300">Niveau minimum :</label>
                        <input type="number" name="level_min" required
                               class="w-full bg-gray-700 text-white px-3 py-2 rounded border border-gray-600">
                    </div>
                    <div>
                        <label class="block text-sm text-gray-300">Niveau maximum :</label>
                        <input type="number" name="level_max" required
                               class="w-full bg-gray-700 text-white px-3 py-2 rounded border border-gray-600">
                    </div>
                </div>

                <h3 class="text-purple-300 mt-6">Exercices :</h3>
<div id="exercisesContainer" class="space-y-4">
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 exercise-block">
        <input type="text" name="exercises[0][name]" placeholder="Nom (ex: pompes x5)"
               class="bg-gray-700 text-white px-3 py-2 rounded border border-gray-600">
        <input type="text" name="exercises[0][repetitions]" placeholder="Répétitions (x3 / 30s)"
               class="bg-gray-700 text-white px-3 py-2 rounded border border-gray-600">
    </div>
</div>

<button type="button" onclick="addExercise()"
        class="mt-2 bg-gray-700 hover:bg-gray-600 text-white text-sm px-4 py-1 rounded-full">
    ➕ Ajouter un exercice
</button>


                <button type="submit"
                        class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-2 rounded-full mt-4">
                    💾 Ajouter le bloc
                </button>
            </form>
        </div>

        <!-- Liste des blocs -->
        <div>
            <h2 class="text-xl font-semibold text-purple-300 mb-4">📋 Blocs existants</h2>
            <?php foreach ($blocks as $block): ?>
                <div class="bg-gray-800 border border-gray-700 rounded-lg p-4 mb-4 space-y-2">
                    <p class="text-sm text-gray-400">Prestige : <strong><?= $block['prestige_required'] ?></strong></p>
                    <p class="text-sm text-gray-400">
    Profil : <strong><?= $block['profile_type'] === 'débutant' ? 'Débutant' : 'Amateur' ?></strong>
</p>

                    <p><strong class="text-white">Niveaux <?= $block['level_min'] ?> à <?= $block['level_max'] ?></strong></p>
                    <p>🕒 Durée : <?= $block['duration_minutes'] ?> min</p>
                    <ul class="list-disc list-inside text-sm text-gray-300">
                        <?php foreach (getExercises($pdo, $block['id']) as $exo): ?>
                            <li><?= htmlspecialchars($exo['name']) ?> — <em><?= $exo['repetitions'] ?></em></li>
                        <?php endforeach; ?>
                    </ul>
                    <form method="POST" action="workouts.php" onsubmit="return confirm('Supprimer ce bloc ?')" class="inline">
                        <input type="hidden" name="delete_id" value="<?= $block['id'] ?>">
                        <input type="hidden" name="csrf_token" value="<?= get_csrf_token() ?>">
                        <button type="submit" class="text-red-400 hover:text-red-600 text-sm inline-block mt-2 bg-transparent border-0 p-0">🗑️ Supprimer</button>
                    </form>
                    <a href="update_training.php?id=<?= $block['id'] ?>"
   class="text-blue-400 hover:text-blue-500 text-sm">✏️ Modifier</a>

                </div>
            <?php endforeach; ?>
        </div>

        <a href="dashboard.php" class="text-purple-400 hover:underline text-sm">⬅️ Retour admin</a>
    </div>
    <script>
let exerciseCount = 1;

function addExercise() {
    const container = document.getElementById('exercisesContainer');

    const wrapper = document.createElement('div');
    wrapper.className = 'grid grid-cols-1 sm:grid-cols-2 gap-4 exercise-block relative';

    const nameInput = document.createElement('input');
    nameInput.name = `exercises[${exerciseCount}][name]`;
    nameInput.placeholder = "Nom (ex: pompes x5)";
    nameInput.className = "bg-gray-700 text-white px-3 py-2 rounded border border-gray-600";

    const repInput = document.createElement('input');
    repInput.name = `exercises[${exerciseCount}][repetitions]`;
    repInput.placeholder = "Répétitions (x3 / 30s)";
    repInput.className = "bg-gray-700 text-white px-3 py-2 rounded border border-gray-600";

    const removeBtn = document.createElement('button');
    removeBtn.type = "button";
    removeBtn.textContent = "❌";
    removeBtn.className = "absolute top-0 right-0 text-red-400 hover:text-red-600 text-sm";
    removeBtn.onclick = () => container.removeChild(wrapper);

    wrapper.appendChild(nameInput);
    wrapper.appendChild(repInput);
    wrapper.appendChild(removeBtn);

    container.appendChild(wrapper);
    exerciseCount++;
}
</script>

</body>
</html>
