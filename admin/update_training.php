<?php
require_once 'includes/auth.php';
require_once '../includes/config.php';
require_once "../includes/security.php"; $csrf_token = generateCsrfToken();

if (!isset($_GET['id'])) {
    header('Location: workouts.php');
    exit;
}

$block_id = (int) $_GET['id'];

// Récupérer le bloc
$stmt = $pdo->prepare("SELECT * FROM workout_blocks WHERE id = ?");
$stmt->execute([$block_id]);
$block = $stmt->fetch();

if (!$block) {
    die("Bloc introuvable.");
}

// Récupérer ses exercices
$stmt = $pdo->prepare("SELECT * FROM workout_exercises WHERE block_id = ?");
$stmt->execute([$block_id]);
$exercises = $stmt->fetchAll();

// Modifier le bloc
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifyCsrfToken($_POST["csrf_token"] ?? "")) { die("Invalid CSRF"); }
    $level_min = $_POST['level_min'];
    $level_max = $_POST['level_max'];
    $duration = $_POST['duration_minutes'];
    $prestige = $_POST['prestige_required'];
    $profile_type = isset($_POST['is_beginner']) ? 'débutant' : 'amateur';
    $exercises = $_POST['exercises'];

    // Update bloc
    $stmt = $pdo->prepare("UPDATE workout_blocks SET level_min = ?, level_max = ?, duration_minutes = ?, prestige_required = ?, profile_type = ? WHERE id = ?");
    $stmt->execute([$level_min, $level_max, $duration, $prestige, $profile_type, $block_id]);

    // Supprimer les anciens exos
    $pdo->prepare("DELETE FROM workout_exercises WHERE block_id = ?")->execute([$block_id]);

    // Réinsérer les exercices
    foreach ($exercises as $exo) {
        if (!empty($exo['name'])) {
            $stmt = $pdo->prepare("INSERT INTO workout_exercises (block_id, name, repetitions) VALUES (?, ?, ?)");
            $stmt->execute([$block_id, $exo['name'], $exo['repetitions']]);
        }
    }

    header("Location: workouts.php?updated=1");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier un bloc</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 text-white min-h-screen p-6">
<div class="max-w-3xl mx-auto space-y-6">
    <h1 class="text-2xl font-bold text-purple-400">✏️ Modifier le bloc</h1>

    <form method="POST" class="space-y-4 bg-gray-800 p-6 rounded-xl border border-purple-600">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
            <div>
                <label class="block text-sm text-gray-300">Prestige :</label>
                <select name="prestige_required" class="w-full bg-gray-700 text-white px-3 py-2 rounded border border-gray-600">
                    <?php foreach (['E','D','C','B','A','S','N'] as $p): ?>
                        <option value="<?= $p ?>" <?= $block['prestige_required'] === $p ? 'selected' : '' ?>><?= $p ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="inline-flex items-center space-x-2 text-sm text-gray-300 mt-6">
                    <input type="checkbox" name="is_beginner" value="1" class="form-checkbox text-purple-600"
                        <?= $block['profile_type'] === 'débutant' ? 'checked' : '' ?>>
                    <span>Bloc réservé aux débutants</span>
                </label>
            </div>
            <div>
                <label class="block text-sm text-gray-300">Durée (minutes) :</label>
                <input type="number" name="duration_minutes" value="<?= $block['duration_minutes'] ?>" required
                       class="w-full bg-gray-700 text-white px-3 py-2 rounded border border-gray-600">
            </div>
            <div>
                <label class="block text-sm text-gray-300">Niveau minimum :</label>
                <input type="number" name="level_min" value="<?= $block['level_min'] ?>" required
                       class="w-full bg-gray-700 text-white px-3 py-2 rounded border border-gray-600">
            </div>
            <div>
                <label class="block text-sm text-gray-300">Niveau maximum :</label>
                <input type="number" name="level_max" value="<?= $block['level_max'] ?>" required
                       class="w-full bg-gray-700 text-white px-3 py-2 rounded border border-gray-600">
            </div>
        </div>

        <h3 class="text-purple-300 mt-6">Exercices :</h3>
        <div id="exercisesContainer" class="space-y-4">
            <?php foreach ($exercises as $i => $exo): ?>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 exercise-block relative">
                    <input type="text" name="exercises[<?= $i ?>][name]" value="<?= htmlspecialchars($exo['name']) ?>"
                           class="bg-gray-700 text-white px-3 py-2 rounded border border-gray-600" placeholder="Nom">
                    <input type="text" name="exercises[<?= $i ?>][repetitions]" value="<?= htmlspecialchars($exo['repetitions']) ?>"
                           class="bg-gray-700 text-white px-3 py-2 rounded border border-gray-600" placeholder="Répétitions">
                    <button type="button" class="absolute top-0 right-0 text-red-400 hover:text-red-600 text-sm"
                            onclick="this.parentElement.remove()">❌</button>
                </div>
            <?php endforeach; ?>
        </div>

        <button type="button" onclick="addExercise()"
                class="mt-2 bg-gray-700 hover:bg-gray-600 text-white text-sm px-4 py-1 rounded-full">
            ➕ Ajouter un exercice
        </button>

        <button type="submit"
                class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-2 rounded-full mt-4">
            💾 Enregistrer les modifications
        </button>
    </form>

    <a href="workouts.php" class="text-purple-400 hover:underline text-sm">⬅️ Retour aux blocs</a>
</div>

<script>
let exerciseCount = <?= count($exercises) ?>;

function addExercise() {
    const container = document.getElementById('exercisesContainer');

    const wrapper = document.createElement('div');
    wrapper.className = 'grid grid-cols-1 sm:grid-cols-2 gap-4 exercise-block relative';

    const nameInput = document.createElement('input');
    nameInput.name = `exercises[${exerciseCount}][name]`;
    nameInput.placeholder = "Nom";
    nameInput.className = "bg-gray-700 text-white px-3 py-2 rounded border border-gray-600";

    const repInput = document.createElement('input');
    repInput.name = `exercises[${exerciseCount}][repetitions]`;
    repInput.placeholder = "Répétitions";
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
