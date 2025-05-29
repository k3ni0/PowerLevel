<!-- Section badge dans le dashboard -->
<div id="badgeWrapper" class="relative mt-auto w-full z-50">
    <!-- Bouton flottant 🏅 -->
    <div class="flex justify-center py-2">
        <button id="toggleBadgePanel"
                class="w-[40px] h-[40px] bg-purple-600 hover:bg-purple-700 text-white rounded-full flex items-center justify-center shadow-lg transition-all duration-300 z-50">
            🏅
        </button>
    </div>

    <!-- Panneau badges masqué par défaut -->
    <div id="badgePanel"
         class="absolute bottom-0 left-0 w-full h-[700px] bg-gradient-to-r from-slate-900/70 to-slate-700/0 z-40 overflow-y-auto p-6 transform scale-y-0 origin-bottom transition-transform duration-300 rounded-t-2xl">
        <!-- Pattern SVG en fond -->
        <div class="absolute inset-0 opacity-10 pointer-events-none"
             style="background-image: radial-gradient(gray 1px, transparent 1px); background-size: 20px 20px;">
        </div>
        <div class="py px-">
            <h2 class="text-2xl font-bold text-purple-400 mb-6">🏅 Badges débloqués</h2>

            <?php
            $stmt = $pdo->prepare("
                SELECT b.* FROM badges b
                JOIN user_badges ub ON ub.badge_id = b.id
                WHERE ub.user_id = ?
                ORDER BY b.level_required
            ");
            $stmt->execute([$user_id]);
            $earned_badges = $stmt->fetchAll();
            ?>

            <?php if (count($earned_badges) > 0): ?>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    <?php foreach ($earned_badges as $badge): ?>
                        <div class="relative group bg-gray-800 border border-gray-700 rounded-xl p-4 flex items-center gap-4 shadow hover:shadow-lg transition">
                            <!-- Icône -->
                            <?php if (!empty($badge['icon'])): ?>
                                <img src="<?= htmlspecialchars($badge['icon']) ?>" alt="Icône"
                                     class="w-14 h-14 object-contain">
                            <?php else: ?>
                                <div class="w-14 h-14 bg-gray-700 text-purple-300 flex items-center justify-center rounded">
                                    🎖️
                                </div>
                            <?php endif; ?>

                            <!-- Texte -->
                            <div class="text-left space-y-1">
                                <p class="text-sm font-semibold text-white"><?= htmlspecialchars($badge['name']) ?></p>
                                <p class="text-xs text-purple-400">(Lvl <?= $badge['level_required'] ?>)</p>
                            </div>

                            <!-- Tooltip -->
                            <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 hidden group-hover:block bg-black bg-opacity-80 text-white text-xs rounded px-2 py-1 shadow z-10 max-w-xs">
                                <?= htmlspecialchars($badge['description']) ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p class="text-gray-400 italic mt-6">Pas encore de badge débloqué. Garde le rythme !</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Script toggle -->
<script>
    const toggleBtn = document.getElementById('toggleBadgePanel');
    const badgePanel = document.getElementById('badgePanel');

    toggleBtn.addEventListener('click', () => {
        badgePanel.classList.toggle('scale-y-0');
        badgePanel.classList.toggle('scale-y-100');
    });
</script>
