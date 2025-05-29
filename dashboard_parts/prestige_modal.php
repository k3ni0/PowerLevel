<?php
require_once "includes/security.php";
$csrf_token = generateCsrfToken();
?>
<?php if ($show_prestige_modal): ?>
    <div id="modalOverlay" style="position: fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.6); display:flex; align-items:center; justify-content:center; z-index:1000;">
        <div style="background:#fff; padding:30px; border-radius:10px; max-width:500px; text-align:center;">
            <h2>🌟 Nouveau Prestige Disponible !</h2>
            <p>Tu as atteint le niveau 50 !</p>
            <p>Souhaites-tu passer au prestige <strong><?= $next_prestige ?></strong> ?</p>
            <p><em>(Tu conserveras ton profil, mais ton niveau et ton XP seront remis à zéro)</em></p>

            <form method="POST">
                <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                <button name="prestige_choice" value="prestige" style="margin:10px; padding:10px;">🔱 Passer Prestige <?= $next_prestige ?></button>
                <button name="prestige_choice" value="stay" style="margin:10px; padding:10px;">♻️ Rester en Prestige <?= $current_prestige ?></button>
            </form>
        </div>
    </div>
<?php endif; ?>
