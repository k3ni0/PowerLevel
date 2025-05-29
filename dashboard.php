<?php require_once 'dashboard_logic.php'; ?>
<?php require_once 'includes/header.php'; ?>
<body class="text-white max-h-screen bg-gradient-to-r from-slate-900 to-slate-700">
    <!-- Vidéo de fond -->
    <video autoplay muted loop playsinline
           class="fixed top-0 left-0 w-full h-full object-cover z-[-1]">
        <source src="assets/vids/5682665_Coll_wavebreak_Animation_1280x720.mp4" type="video/mp4">
    </video> 

    <!-- 🔥 Conteneur centrant la boîte -->
    <div class="flex items-center justify-center min-h-screen">
<!-- Boîte principale -->
<div class="max-w-7xl w-full rounded-2xl shadow-2xl overflow-hidden min-h-[700px] max-h-[700px] flex flex-col backdrop-blur-lg pt-2">
    <!-- Header sticky (avec petit espace) -->
    <div class=" top-4 z-10 px-4">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between py-2 px-4 rounded-2xl">
            <h1 id="welcomeUsername" class="text-lg text-gray-400">
                Bienvenue, <span id="dynamicUsername" class="text-lg font-bold text-purple-400"><?=htmlspecialchars($username) ?></span> !
            </h1>


                <a href="logout.php" onclick="return confirm('Réinitialiser toutes tes stats ?');" class="group relative px-3 p-1 text-sm/6 text-red-800 dark:text-red-300">
    <span class="absolute inset-0 border border-dashed border-red-300/60 bg-red-400/10 group-hover:bg-red-400/15 dark:border-red-300/30">       
    </span>Se déconnecter<svg width="5" height="5" viewBox="0 0 5 5" class="absolute top-[-2px] left-[-2px] fill-red-300 dark:fill-red-300/50">
        <path d="M2 0h1v2h2v1h-2v2h-1v-2h-2v-1h2z"></path></svg><svg width="5" height="5" viewBox="0 0 5 5" class="absolute top-[-2px] right-[-2px] fill-red-300 dark:fill-red-300/50">
            <path d="M2 0h1v2h2v1h-2v2h-1v-2h-2v-1h2z"></path></svg><svg width="5" height="5" viewBox="0 0 5 5" class="absolute bottom-[-2px] left-[-2px] fill-red-300 dark:fill-red-300/50">
                <path d="M2 0h1v2h2v1h-2v2h-1v-2h-2v-1h2z"></path></svg><svg width="5" height="5" viewBox="0 0 5 5" class="absolute right-[-2px] bottom-[-2px] fill-red-300 dark:fill-red-300/50">
                    <path d="M2 0h1v2h2v1h-2v2h-1v-2h-2v-1h2z"></path></svg></a>

        </div>
    </div>


            <!-- Contenu scrollable -->
            <div class="overflow-y-auto p-5 pt-3" style="max-height: calc(800px - 136px);">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-0">
                    <!-- Colonne Gauche -->
                    <div class="order-2 lg:order-1">
                        <?php include 'dashboard_parts/profile_card.php'; ?>
                        <?php include 'dashboard_parts/user_info.php'; ?>


                        <div class="space-y-2 text-sm">
                            <button id="installPWA"
                                    class="hidden bg-purple-600 hover:bg-purple-700 text-white text-sm px-4 py-2 rounded-full mt-4">
                                📲 Ajouter Power Level à mon écran d'accueil
                            </button>
                        </div>
                    </div>

                    <!-- Colonne Droite -->
                    <div class="order-1 lg:order-2 pt-6 lg:pt-0 lg:pl-6">
                        <?php include 'dashboard_parts/training_section.php'; ?>
                    </div>
                </div>
            </div>

    <?php include 'dashboard_parts/badges_section.php'; ?>

        </div>
    </div>

    <?php if (!empty($_SESSION['penalty_modal'])):
        unset($_SESSION['penalty_modal']); ?>
        <div class="fixed inset-0 bg-black bg-opacity-70 flex items-center justify-center z-50">
            <div class="bg-red-900 border border-red-600 rounded-xl p-8 text-center text-white shadow-lg max-w-sm">
                <h2 class="text-2xl font-bold text-red-400 mb-4">⛔ Pénalité appliquée</h2>
                <p>Tu n’as pas validé ton entraînement avant minuit.</p>
                <p class="mt-2">💥 Tu perds <strong>1 niveau</strong>.</p>
                <button onclick="this.parentElement.parentElement.remove()"
                        class="mt-4 px-4 py-2 bg-red-600 hover:bg-red-700 rounded-full text-sm">OK</button>
            </div>
        </div>
    <?php endif; ?>
    <style>
  /* Scrollbar light style */
  ::-webkit-scrollbar {
    width: 8px;
  }

  ::-webkit-scrollbar-track {
    background: transparent;
  }

  ::-webkit-scrollbar-thumb {
    background-color: rgba(0, 0, 0, 0.1);
    border-radius: 9999px;
    border: 2px solid transparent;
    background-clip: content-box;
  }

  ::-webkit-scrollbar-thumb:hover {
    background-color: rgba(0, 0, 0, 0.2);
  }

  * {
    scrollbar-width: thin;
    scrollbar-color: rgba(0, 0, 0, 0.2) transparent;
  }
</style>
    <!-- Modales -->
    <?php include 'dashboard_parts/class_modal.php'; ?>
    <?php include 'dashboard_parts/prestige_modal.php'; ?>
    <?php require_once 'includes/footer.php'; ?>

