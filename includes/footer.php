<script>
let deferredPrompt;
    window.addEventListener('beforeinstallprompt', (e) => {
    // Empêche l’auto prompt
        e.preventDefault();
        deferredPrompt = e;

    // Affiche un bouton ou déclenche manuellement
    const btn = document.getElementById('installPWA');
    if (btn) {
        btn.classList.remove('hidden');
        btn.addEventListener('click', () => {
            btn.classList.add('hidden');
            deferredPrompt.prompt();
            deferredPrompt.userChoice.then(choice => {
                if (choice.outcome === 'accepted') {
                    console.log("✅ PWA installée !");
                } else {
                    console.log("❌ PWA refusée.");
                }
                deferredPrompt = null;
            });
        });
    }
});

function checkMidnightPenalty() {
    const now = new Date();
    if (now.getHours() === 0 && now.getMinutes() === 1) {
        fetch('dashboard_parts/penalty_auto.php');
    }
}

// Check toutes les 60s
setInterval(checkMidnightPenalty, 60000);
function updateCountdown() {
    const now = new Date();
    const midnight = new Date();
    midnight.setHours(24, 0, 0, 0); // prochain minuit
    const diff = midnight - now;
    if (diff <= 0) return;
    const hours = Math.floor(diff / (1000 * 60 * 60));
    const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
    const seconds = Math.floor((diff % (1000 * 60)) / 1000);
    const formatted = `${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
    document.getElementById('dayCountdown').textContent = formatted;
}

// Actualise chaque seconde
setInterval(updateCountdown, 1000);
updateCountdown();
if (window.location.search.includes('levelup=1')) {
        const url = new URL(window.location);
        url.searchParams.delete('levelup');
        window.history.replaceState({}, document.title, url);
    }
</script>
<?php if (isset($_GET['levelup']) && $_GET['levelup'] == 1): ?>
    <audio id="levelup-sound" autoplay>
        <source src="assets/sounds/levelup.mp3" type="audio/mpeg">
    </audio>
<?php endif; ?>
</body>
</html>