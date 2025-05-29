<?php if (isset($_GET['reset'])): ?>
    <p style="color: orange;">⚠️ Statistiques utilisateur réinitialisées.</p>
<?php endif; ?>

<?php if (isset($_GET['levelup']) && $_GET['levelup'] == 1): ?>
    <p style="color: green;">🔼 Niveau augmenté avec succès.</p>
<?php elseif (isset($_GET['levelup']) && $_GET['levelup'] === 'max'): ?>
    <p style="color: gray;">🔒 Niveau maximum atteint.</p>
<?php endif; ?>

<p><a href="dashboard_parts/reset_links.php?action=reset" onclick="return confirm('Réinitialiser toutes tes stats ?');">🧹 Réinitialiser mes stats</a></p>
<p><a href="dashboard_parts/reset_links.php?action=levelup">🔼 Monter d’un niveau</a></p>
