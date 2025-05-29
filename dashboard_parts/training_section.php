<?php
require_once "includes/security.php";
$csrf_token = generateCsrfToken();
?>
<div class="relative bg-gradient-to-r from-slate-900/40 to-slate-700/20 border border-gray-800 rounded-xl py-3 px-6 shadow-lg overflow-hidden mb-5">
<div class="text-sm text-gray-300">
                ⏳ Prochaine échéance dans : <span id="dayCountdown" class="text-purple-400 font-semibold"></span>
            </div>
            </div>
<?php if ($jour_de_repos): ?>
    <div class="relative bg-gradient-to-r from-slate-900/40 to-slate-700/20 border border-gray-800 rounded-xl p-6 shadow-lg overflow-hidden">
        <!-- Pattern SVG en fond -->
        <div class="absolute inset-0 opacity-10 pointer-events-none"
             style="background-image: radial-gradient(gray 1px, transparent 1px); background-size: 20px 20px;">
        </div>

        <!-- Contenu principal -->
        <div class="relative z-1">
            <h2 class="text-2xl font-bold text-purple-100 mb-4">🛏️ Jour de repos</h2>
            <p class="text-gray-300 text-lg">Profite-en pour te ressourcer ✨</p>
        </div>
    </div>

<?php elseif ($block): ?>
    <div class="relative bg-gradient-to-r from-slate-900/40 to-slate-700/20 border border-gray-800 rounded-xl p-6 shadow-lg overflow-hidden">
        <!-- Pattern SVG en fond -->
        <div class="absolute inset-0 opacity-10 pointer-events-none"
             style="background-image: radial-gradient(gray 1px, transparent 1px); background-size: 20px 20px;">
        </div>

        <!-- Contenu principal -->
        <div class="relative ">
            <h2 class="text-2xl font-bold text-gray-100 mb-4">
                🦾 Entraînement du jour <span class="text-sm text-gray-400">(<?= $block['duration_minutes'] ?> min)</span>
            </h2>


            <?php $_SESSION['training_token'] = bin2hex(random_bytes(32)); ?>

            <form action="dashboard_parts/training_process.php" method="POST" id="trainingForm" class="space-y-6">
                <!-- Liste des exercices -->
                <ul id="exerciseList" class="space-y-2">
                    <?php foreach ($exercises as $index => $exo): ?>
                        <li class="exercise-item locked bg-gray-700/10 px-4 py-2 rounded-lg transition-all border border-gray-600">
                            <label class="flex items-center gap-3">
                                <input type="checkbox" class="exercise-checkbox form-checkbox text-purple-400" data-index="<?= $index ?>" disabled>
                                <span class="exercise-text text-sm"><?= htmlspecialchars($exo['name']) ?> — <em><?= htmlspecialchars($exo['repetitions']) ?></em></span>
                            </label>
                        </li>
                    <?php endforeach; ?>
                </ul>

                <!-- Compte à rebours -->
                <div id="countdownContainer" class="hidden text-center text-sm text-purple-300 font-mono">
                    ⏳ Temps restant : <span id="countdown"><?= $block['duration_minutes'] * 60 ?></span>
                </div>

                <!-- Si déjà validé -->
                <?php if ($already_done): ?>
                    <p class="text-green-400 text-center mt-4">✅ Tu as déjà validé ta séance aujourd’hui 💪</p>
                <?php else: ?>
                    <!-- Boutons -->
                    <div class="flex flex-col items-center gap-4">

                        <!-- Bouton Commencer -->
                        <button type="button" id="startBtn"
                                class="group relative px-4 py-2 text-sm text-purple-300 transition-all">
                            <span class="absolute inset-0 border border-dashed border-purple-400/60 bg-purple-500/10 group-hover:bg-purple-500/15 pointer-events-none"></span>
                            ▶️ Commencer l'entraînement
                            <?php for ($i = 0; $i < 4; $i++): ?>
                                <svg width="5" height="5" viewBox="0 0 5 5"
                                     class="absolute <?= $i < 2 ? 'top-[-2px]' : 'bottom-[-2px]' ?> <?= ($i % 2) == 0 ? 'left-[-2px]' : 'right-[-2px]' ?> fill-purple-300/50">
                                    <path d="M2 0h1v2h2v1h-2v2h-1v-2h-2v-1h2z"/>
                                </svg>
                            <?php endfor; ?>
                        </button>

                        <!-- Bouton Valider -->
                        <button type="submit" id="validateBtn"
                                class="group relative px-4 py-2 text-sm text-green-300 transition-all hidden"
                                disabled>
                            <span class="absolute inset-0 border border-dashed border-green-400/60 bg-green-500/10 group-hover:bg-green-500/15 pointer-events-none"></span>
                            <span id="validateText">⏳ Entraînement en cours...</span>
                            <?php for ($i = 0; $i < 4; $i++): ?>
                                <svg width="5" height="5" viewBox="0 0 5 5"
                                     class="absolute <?= $i < 2 ? 'top-[-2px]' : 'bottom-[-2px]' ?> <?= ($i % 2) == 0 ? 'left-[-2px]' : 'right-[-2px]' ?> fill-green-300/50">
                                    <path d="M2 0h1v2h2v1h-2v2h-1v-2h-2v-1h2z"/>
                                </svg>
                            <?php endfor; ?>
                        </button>

                        <!-- Champs cachés -->
                        <input type="hidden" name="training_token" value="<?= $_SESSION['training_token'] ?>">
                        <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                        <input type="hidden" name="failed" id="failFlag" value="0">
                    </div>
                <?php endif; ?>
            </form>
        </div> <!-- Fin .z-10 -->
    </div> <!-- Fin .relative -->


    <script>
        const startBtn = document.getElementById('startBtn');
        const validateBtn = document.getElementById('validateBtn');

        if (startBtn && validateBtn) {
            const exerciseItems = document.querySelectorAll('.exercise-item');
            const checkboxes = document.querySelectorAll('.exercise-checkbox');
            const countdownContainer = document.getElementById('countdownContainer');
            const countdownEl = document.getElementById('countdown');
            const trainingForm = document.getElementById('trainingForm');
            const failFlag = document.getElementById('failFlag');

            let totalSeconds = <?= $block['duration_minutes'] * 60 ?>;
            let remainingSeconds = totalSeconds;
            let timer;
            let clickedBeforeEnd = false;

            function formatTime(sec) {
                const minutes = Math.floor(sec / 60);
                const seconds = sec % 60;
                return `${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
            }

            function unlockNextExercise(index) {
                if (checkboxes[index + 1]) {
                    checkboxes[index + 1].disabled = false;
                    exerciseItems[index + 1].classList.remove('locked');
                    exerciseItems[index + 1].classList.add('unlocked');
                }
            }

            function checkReady() {
                const allChecked = Array.from(checkboxes).every(cb => cb.checked);
                if (allChecked) {
                    validateBtn.disabled = false;
                    document.getElementById('validateText').textContent = "✅ Valider ma séance";
                }
            }

            startBtn.addEventListener('click', () => {
                startBtn.style.display = 'none';
                validateBtn.style.display = 'inline-block';
                validateBtn.classList.remove('hidden');
                countdownContainer.classList.remove('hidden');

                checkboxes[0].disabled = false;
                exerciseItems[0].classList.remove('locked');
                exerciseItems[0].classList.add('unlocked');

                countdownEl.textContent = formatTime(remainingSeconds);
                timer = setInterval(() => {
                    remainingSeconds--;
                    countdownEl.textContent = formatTime(remainingSeconds);
                    if (remainingSeconds <= 0 && !clickedBeforeEnd) {
                        clearInterval(timer);
                        failFlag.value = "1";
                        trainingForm.submit();
                    }
                }, 1000);
            });

            checkboxes.forEach((cb, index) => {
    cb.addEventListener('change', () => {
        if (cb.checked) {
            cb.disabled = true;
            unlockNextExercise(index);
            checkReady();

            const item = exerciseItems[index];
            const text = item.querySelector('.exercise-text');
            cb.classList.add("checked:bg-green-500", "text-green-500");
            item.classList.add("bg-green-800", "border-green-500");
            text.classList.add("text-green-400");
        }
    });
});


            validateBtn.addEventListener('click', () => {
                clickedBeforeEnd = true;
            });

            validateBtn.addEventListener('mouseover', () => {
                if (!validateBtn.disabled) {
                    document.getElementById('validateText').textContent = "✅ Valider ma séance";
                }
            });

            validateBtn.addEventListener('mouseout', () => {
                if (!validateBtn.disabled && remainingSeconds > 0) {
                    document.getElementById('validateText').textContent = "⏳ Entraînement en cours...";

                }
            });
        }
    </script>

<?php else: ?>
    <p class="text-gray-400 italic">Aucun entraînement disponible pour ton niveau.</p>
<?php endif; ?>
