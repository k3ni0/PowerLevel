<?php if (!empty($_SESSION['penalty_modal'])):
    unset($_SESSION['penalty_modal']); ?>
    <div class="fixed inset-0 bg-black bg-opacity-70 flex items-center justify-center z-50">
        <div class="bg-red-900 border border-red-600 rounded-xl p-8 text-center text-white shadow-lg max-w-sm">
            <h2 class="text-2xl font-bold text-red-400 mb-4">⛔ Pénalité appliquée</h2>
            <p>Tu n’as pas validé ton entraînement avant minuit.</p>
            <p class="mt-2">💥 Tu perds <strong>1 niveau</strong>.</p>
            <button onclick="this.parentElement.parentElement.remove()" class="mt-4 px-4 py-2 bg-red-600 hover:bg-red-700 rounded-full text-sm">OK</button>
        </div>
    </div>
<?php
endif; ?>