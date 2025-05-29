<div class="relative bg-gradient-to-r from-slate-900/40 to-slate-700/20 border border-gray-800 rounded-xl p-6 shadow-lg overflow-hidden mb-5">
    <h2 class="text-2xl font-bold text-blue-400 mb-4">🎯 Entraînement perso</h2>
    <?php if (count($custom_trainings) > 0): ?>
        <ul class="space-y-2">
            <?php foreach ($custom_trainings as $ct): ?>
                <li class="flex items-center justify-between bg-gray-800/50 px-3 py-2 rounded">
                    <span class="text-sm text-gray-300"><?= htmlspecialchars($ct['name']) ?> (<?= $ct['duration'] ?> min)</span>
                    <button data-duration="<?= $ct['duration'] ?>" class="startCustom bg-blue-600 hover:bg-blue-700 text-white text-xs px-3 py-1 rounded-full">▶️ Démarrer</button>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p class="text-gray-400 italic">Aucun entraînement perso pour l'instant.</p>
    <?php endif; ?>

    <?php if (count($custom_trainings) < 5): ?>
        <form method="POST" action="dashboard_parts/custom_training_process.php" class="mt-4 space-y-2">
            <input type="hidden" name="action" value="add">
            <input type="text" name="name" required placeholder="Nom" class="w-full bg-gray-800 border border-gray-600 text-white px-3 py-1 rounded">
            <input type="number" name="duration" min="1" required placeholder="Durée (min)" class="w-full bg-gray-800 border border-gray-600 text-white px-3 py-1 rounded">
            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded-full">➕ Ajouter</button>
        </form>
    <?php endif; ?>

    <div id="customCountdown" class="hidden text-center text-sm text-blue-300 font-mono mt-4">
        ⏳ Temps restant : <span id="customTime"></span>
    </div>

    <?php if (!$custom_done_today): ?>
        <form method="POST" action="dashboard_parts/custom_training_process.php" class="mt-4 text-center">
            <input type="hidden" name="action" value="validate">
            <button type="submit" id="validateCustomBtn" class="group relative px-4 py-2 text-sm text-blue-300 transition-all hidden" disabled>
                <span class="absolute inset-0 border border-dashed border-blue-400/60 bg-blue-500/10 pointer-events-none"></span>
                <span id="validateCustomText">⏳ Entraînement en cours...</span>
            </button>
        </form>
    <?php else: ?>
        <p class="text-green-400 text-center mt-4">✅ Entraînement perso validé aujourd'hui !</p>
    <?php endif; ?>
</div>
<script>
const startButtons = document.querySelectorAll('.startCustom');
const countdownContainer = document.getElementById('customCountdown');
const countdownEl = document.getElementById('customTime');
const validateBtn = document.getElementById('validateCustomBtn');

let remaining = 0;
let timer;

function format(sec){
    const m = Math.floor(sec/60);
    const s = sec%60;
    return `${String(m).padStart(2,'0')}:${String(s).padStart(2,'0')}`;
}

startButtons.forEach(btn => {
    btn.addEventListener('click', () => {
        remaining = parseInt(btn.dataset.duration) * 60;
        countdownEl.textContent = format(remaining);
        countdownContainer.classList.remove('hidden');
        validateBtn.classList.remove('hidden');
        validateBtn.disabled = true;
        timer = setInterval(() => {
            remaining--;
            countdownEl.textContent = format(remaining);
            if (remaining <= 0) {
                clearInterval(timer);
                validateBtn.disabled = false;
                document.getElementById('validateCustomText').textContent = '✅ Valider l\'entraînement perso';
            }
        },1000);
    });
});
</script>
