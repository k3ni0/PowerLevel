<?php
if (!isset($user_id)) {
    session_start();
    $user_id = $_SESSION["user_id"];
    require_once "includes/config.php";
}
require_once "includes/security.php";
$csrf_token = generateCsrfToken();
$jours = ['lundi','mardi','mercredi','jeudi','vendredi','samedi','dimanche'];

// Mise à jour des jours de repos
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_rest_days'])) {
    $day1 = $_POST['rest_day_1'];
    $day2 = $_POST['rest_day_2'];

    if ($day1 !== $day2) {
        $stmt = $pdo->prepare("UPDATE users SET rest_day_1 = ?, rest_day_2 = ? WHERE id = ?");
        $stmt->execute([$day1, $day2, $user_id]);
        $message = ["✅ Jours de repos mis à jour.", "green"];
    } else {
        $message = ["⚠️ Les deux jours doivent être différents.", "red"];
    }
}

// Récupération des jours de repos actuels
$stmt = $pdo->prepare("SELECT rest_day_1, rest_day_2 FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$rest = $stmt->fetch();
?>

<!-- Accordéon Jours de repos -->
<div class="bg-gray-900/40 hover:bg-purple-700/10 rounded-xl border border-gray-700 mt-6">

    <button onclick="toggleAccordion2('restDaysBlock')"
            class="w-full text-left px-6 py-2 flex items-center justify-between  transition rounded-xl">
            <div id="currentRestDays"><span class="text-gray-300 font-semibold text-sm">Mes jours de repos <?php if ($rest['rest_day_1'] && $rest['rest_day_2']): ?>
                    <strong><?= ucfirst($rest['rest_day_1']) ?></strong> et
                    <strong><?= ucfirst($rest['rest_day_2']) ?></strong>
                
            <?php endif; ?></span></div>
        <svg id="restToggleIcon" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-purple-400 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
    </button>

    <div id="restDaysBlock" class="px-6 py-4 space-y-3 hidden">
        <?php if (isset($message)): ?>
            <p class="text-<?= $message[1] ?>-400 text-sm"><?= $message[0] ?></p>
        <?php endif; ?>

        <div id="restDays" class="grid grid-cols-2 sm:grid-cols-3 gap-4 text-sm text-white">
    <?php foreach ($jours as $jour): ?>
        <label class="flex items-center gap-2 bg-gray-900/40 hover:bg-green-700/10 hover:text-gray-100 text-gray-400 p-2 rounded cursor-pointer">
                    <input type="checkbox" name="jours[]" value="<?= $jour ?>"
                        class="repos-checkbox form-checkbox "
                        <?= ($rest['rest_day_1'] === $jour || $rest['rest_day_2'] === $jour) ? 'checked' : '' ?>>
                    <?= ucfirst($jour) ?>
                </label>
            <?php endforeach; ?>
        </div>

        <p id="restDaysMessage" class="text-sm text-purple-300 mt-2">✅ Sélectionne 2 jours</p>



        
    </div>
</div>

<script>
const csrfToken = "<?= $csrf_token ?>";
document.addEventListener('DOMContentLoaded', () => {
    const checkboxes = document.querySelectorAll('input[type="checkbox"][name="jours[]"]');
    const message = document.getElementById('restDaysMessage');

    function updateRestDays() {
        let checked = Array.from(checkboxes).filter(cb => cb.checked);

        if (checked.length > 2) {
            // Décocher la dernière cochée
            this.checked = false;
            return;
        }

        const selected = checked.map(cb => cb.value);

        message.textContent = selected.length < 2
            ? '⚠️ Choisis 2 jours de repos'
            : '✅ Jours de repos mis à jour';

        message.className = selected.length < 2
            ? 'text-sm text-yellow-400 mt-2'
            : 'text-sm text-green-400 mt-2';

        // Met à jour les jours uniquement si 2 jours sont cochés
        if (selected.length === 2) {
            fetch('dashboard_parts/update_rest_days.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: `rest_day_1=${encodeURIComponent(selected[0])}&rest_day_2=${encodeURIComponent(selected[1])}&csrf_token=${encodeURIComponent(csrfToken)}`
            }).then(() => {
                fetch('dashboard_parts/get_rest_days.php')
                    .then(res => res.text())
                    .then(html => {
                        document.getElementById('currentRestDays').innerHTML = html;
                    });
            });
        }
    }

    checkboxes.forEach(cb => {
        cb.addEventListener('change', updateRestDays);
    });
});
</script>

