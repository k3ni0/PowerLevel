<?php
require_once "includes/security.php";
$csrf_token = generateCsrfToken();
// Récup ornement prestige
$stmt = $pdo->prepare("SELECT icon_url FROM prestige_styles WHERE code = ?");
$stmt->execute([$user["prestige"]]);
$prestigeIcon = $stmt->fetchColumn();

// Profil public
$token = $user['public_token'];
$isPublic = (bool)$user['is_public'];
$baseUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://{$_SERVER['HTTP_HOST']}/profil/";
$publicUrl = $token ? $baseUrl . $token : '';
?>

<div class="relative bg-gradient-to-r from-slate-900/40 to-slate-700/20 border border-gray-800 rounded-xl p-6 shadow-lg overflow-hidden">
        <!-- Pattern SVG en fond -->
        <div class="absolute inset-0 opacity-10 pointer-events-none"
             style="background-image: radial-gradient(gray 1px, transparent 1px); background-size: 20px 20px;">
        </div>
    <!-- GRILLE EN 2 COLONNES -->
    <div class="flex flex-col sm:flex-row items-center sm:items-start gap-6">

<!-- Avatar + ornement + prestige en dessous -->
<div class="flex flex-col items-center space-y-2 shrink-0">
    <div class="relative w-28 h-28 group select-none">
        <?php if (!empty($prestigeIcon)): ?>
            <img src="<?= htmlspecialchars($prestigeIcon) ?>" alt="Ornement prestige"
                class="absolute inset-0 w-full h-full object-contain pointer-events-none z-0">
        <?php endif; ?>
        <div class="absolute inset-0 flex items-center justify-center z-10">
            <img src="<?= !empty($user['avatar']) ? htmlspecialchars($user['avatar']) : 'assets/default-avatar.png' ?>"
                alt="Avatar"
                id="profileAvatar"
                class="w-20 h-20 rounded-full object-cover border-2 border-purple-500 shadow-md">
        </div>
        <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition z-20">
            <button onclick="toggleAvatarEdit()" class="text-purple-300 hover:text-white text-xl">✏️</button>
        </div>
    </div>

    <!-- Prestige -->
    <p class="text-sm text-purple-300 font-semibold mt-1">Prestige <?= $user['prestige'] ?></p>
</div>


        <!-- Infos utilisateur -->
        <div class="flex-1 space-y-3 w-full">

            <!-- Form URL avatar -->
            <div id="avatarEdit" class="hidden">
                <input type="url" id="avatar_url" placeholder="URL de ton avatar (jpg, png, gif)"
                       class="w-full px-3 py-2 rounded bg-gray-900 border border-gray-600 text-white text-sm"
                       onchange="updateProfile('avatar', this.value)">
                       
            </div>
            

            <!-- Pseudo -->
            <div class="flex justify-between items-center">
                <span class="text-white text-lg font-bold"><span id="usernameDisplay"><?= htmlspecialchars($user['username']) ?></span></span>
                <button onclick="editField('username')" class="text-sm text-purple-400 hover:text-purple-300">✏️</button>
            </div>
            <div id="usernameEdit" class="hidden">
            <input type="text"
       id="usernameInput"
       class="w-full bg-gray-900 text-white border border-gray-600 rounded px-3 py-2"
       value="<?= htmlspecialchars($user['username']) ?>"
       onblur="updateProfile('username', this.value)">

            </div>

            <!-- Âge -->
            <div class="flex justify-between items-center">
                <span class="text-white">Âge : <span id="ageDisplay"><?= htmlspecialchars($user['age']) ?> </span> ans</span>
                <button onclick="editField('age')" class="text-sm text-purple-400 hover:text-purple-300">✏️</button>
            </div>
            <div id="ageEdit" class="hidden">
            <input type="number"
       id="ageInput"
       class="w-full bg-gray-900 text-white border border-gray-600 rounded px-3 py-2"
       value="<?= htmlspecialchars($user['age']) ?>"
       onblur="updateProfile('age', this.value)">

            </div>

            <!-- Niveau + Prestige + Barre -->
            <div class="pt-2 space-y-2">
                <p class="text-gray-100 font-semibold">Niveau <span class="text-purple-300 font-semibold"><?= $_SESSION['level'] = $user['level']; ?></span> <span class="text-gray-500 font-semibold">/ 50</span></p>
                <div class="w-full h-2 bg-gray-700 rounded">
                    <div class="h-2 bg-purple-500 rounded" style="width: <?= ($_SESSION['level'] / 50) * 100 ?>%;"></div>
                </div>
                <div class="pt-4">
                <a href="user_reset_stats.php?action=reset" onclick="return confirm('Réinitialiser toutes tes stats ?');" class="group relative px-3 p-2 text-sm/6 text-sky-800 dark:text-sky-300">
    <span class="absolute inset-0 border border-dashed border-sky-300/60 bg-sky-400/10 group-hover:bg-sky-400/15 dark:border-sky-300/30">       
    </span>Réinitialiser mes stats<svg width="5" height="5" viewBox="0 0 5 5" class="absolute top-[-2px] left-[-2px] fill-sky-300 dark:fill-sky-300/50">
        <path d="M2 0h1v2h2v1h-2v2h-1v-2h-2v-1h2z"></path></svg><svg width="5" height="5" viewBox="0 0 5 5" class="absolute top-[-2px] right-[-2px] fill-sky-300 dark:fill-sky-300/50">
            <path d="M2 0h1v2h2v1h-2v2h-1v-2h-2v-1h2z"></path></svg><svg width="5" height="5" viewBox="0 0 5 5" class="absolute bottom-[-2px] left-[-2px] fill-sky-300 dark:fill-sky-300/50">
                <path d="M2 0h1v2h2v1h-2v2h-1v-2h-2v-1h2z"></path></svg><svg width="5" height="5" viewBox="0 0 5 5" class="absolute right-[-2px] bottom-[-2px] fill-sky-300 dark:fill-sky-300/50">
                    <path d="M2 0h1v2h2v1h-2v2h-1v-2h-2v-1h2z"></path></svg></a>
        </div>
            </div>
        </div>
    </div>

    <!-- Profil public -->
<!-- Accordéon Profil public -->
<div class="bg-gray-900/40 hover:bg-purple-700/10 rounded-xl border border-gray-700 mt-6">

    <button onclick="toggleAccordion('publicProfileBlock')"
            class="w-full text-left px-6 py-2 flex items-center justify-between  transition rounded-xl">
        <span class="text-gray-300 font-semibold text-sm">🔗 Profil public</span>
        <svg id="publicToggleIcon" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-purple-400 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
    </button>

    <div id="publicProfileBlock" class="px-6 py-4 space-y-3 hidden">
        <p id="publicStatus" class="text-sm <?= $isPublic ? 'text-green-400' : 'text-yellow-400' ?>">
            <?= $isPublic
                ? '✅ Ton profil est actuellement <strong>public</strong>.'
                : '🚫 Ton profil est <strong>privé</strong>.' ?>
        </p>

        <?php if ($isPublic): ?>
            <input id="publicLink" type="text" readonly value="<?= htmlspecialchars($publicUrl) ?>"
                   class="w-full bg-gray-800 border border-gray-600 rounded px-3 py-2 text-sm text-white">
        <?php endif; ?>

        <button id="togglePublicBtn"
                onclick="togglePublicProfile(<?= $isPublic ? 'false' : 'true' ?>)"
                class="<?= $isPublic
                    ? 'bg-red-600 hover:bg-red-700'
                    : 'bg-green-600 hover:bg-green-700' ?> text-white text-sm px-4 py-1 rounded-full">
            <?= $isPublic ? '🚫 Rendre privé' : '🌐 Rendre public' ?>
        </button>
    </div>
</div>
<div><?php include 'dashboard_parts/rest_days.php'; ?> </div>
</div>


<!-- Script JS -->
<script>
const csrfToken = "<?= $csrf_token ?>";
function editField(field) {
    document.getElementById(field + 'Display').parentElement.style.display = 'none';
    document.getElementById(field + 'Edit').style.display = 'block';
}

function toggleAvatarEdit() {
    const input = document.getElementById("avatarEdit");
    input.classList.toggle("hidden");
}

function updateProfile(field, value) {
    fetch('dashboard_parts/update_profile.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: 'field=' + encodeURIComponent(field) + '&value=' + encodeURIComponent(value) + '&csrf_token=' + encodeURIComponent(csrfToken)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            if (field === 'username') {
                const usernameDisplay = document.getElementById('usernameDisplay');
                const dynamic = document.getElementById('dynamicUsername');
                if (usernameDisplay) usernameDisplay.textContent = value;
                if (dynamic) animateText("dynamicUsername", value);
            } else if (field === 'age') {
                const ageDisplay = document.getElementById('ageDisplay');
                if (ageDisplay) ageDisplay.textContent = value;
            } else if (field === 'avatar') {
                const avatar = document.getElementById('profileAvatar');
                if (avatar) avatar.src = value;
            }
        }

        if (field !== 'avatar') {
            const edit = document.getElementById(field + 'Edit');
            const display = document.getElementById(field + 'Display');
            if (edit && display && display.parentElement) {
                edit.style.display = 'none';
                display.parentElement.style.display = 'flex';
            }
        } else {
            const avatarEdit = document.getElementById('avatarEdit');
            if (avatarEdit) avatarEdit.classList.add('hidden');
        }
    });
}

// Animation texte (username)
function animateText(target, newText) {
    const chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
    const element = document.getElementById(target);
    let iterations = 0;
    const length = newText.length;
    let interval = setInterval(() => {
        element.textContent = newText
            .split("")
            .map((char, i) => {
                if (i < iterations) return char;
                return chars[Math.floor(Math.random() * chars.length)];
            })
            .join("");
        if (iterations >= length) clearInterval(interval);
        iterations += 1 / 2;
    }, 30);
}
function togglePublicProfile(makePublic) {
    fetch('dashboard_parts/toggle_public.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: 'action=' + (makePublic ? 'public' : 'private') + '&csrf_token=' + encodeURIComponent(csrfToken)
    }).then(() => window.location.reload());
}
function toggleAccordion(id) {
    const block = document.getElementById(id);
    const icon = document.getElementById('publicToggleIcon');

    block.classList.toggle('hidden');
    icon.classList.toggle('rotate-180');
}
function toggleAccordion2(id) {
    const block = document.getElementById(id);
    const icon = document.getElementById('restToggleIcon');

    block.classList.toggle('hidden');
    icon.classList.toggle('rotate-180');
}
document.addEventListener("DOMContentLoaded", () => {
    const usernameInput = document.getElementById("usernameInput");
    const ageInput = document.getElementById("ageInput");

    if (usernameInput) {
        usernameInput.addEventListener("keydown", (e) => {
            if (e.key === "Enter") {
                e.preventDefault();
                updateProfile("username", usernameInput.value);
            }
        });
    }

    if (ageInput) {
        ageInput.addEventListener("keydown", (e) => {
            if (e.key === "Enter") {
                e.preventDefault();
                updateProfile("age", ageInput.value);
            }
        });
    }
});

</script>
