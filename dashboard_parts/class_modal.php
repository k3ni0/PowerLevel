<?php
require_once "includes/security.php";
$csrf_token = generateCsrfToken();
?>
<?php if ($show_class_choice): ?>
    <div id="modalOverlay" style="position: fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.6); display:flex; align-items:center; justify-content:center; z-index:1000;">
        <div style="background:#fff; padding:30px; border-radius:10px; max-width:500px; text-align:center;">
            <h2>🎓 Nouveau palier atteint !</h2>
            <p>Tu es arrivé au niveau 10 ! Que souhaites-tu faire ?</p>

            <form method="POST">
                <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                <button name="class_choice" value="amateur" style="margin:10px; padding:10px;">🚀 Passer en Amateur (conserver progression)</button>
                <button name="class_choice" value="reset" style="margin:10px; padding:10px;">🔄 Rester Débutant (reset et rejouer)</button>
            </form>
        </div>
    </div>
<?php endif; ?>