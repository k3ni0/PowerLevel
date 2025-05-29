<?php
session_start();
require_once "includes/security.php";
require_once 'includes/config.php';

if (!verifyCsrfToken($_POST["csrf_token"] ?? "")) { die("Invalid CSRF token"); }
// Vérifier si toutes les données sont là
if (!isset($_POST['username'], $_POST['email'], $_POST['password'], $_POST['profile_type'])) {
    die('Formulaire incomplet.');
}

$username = trim($_POST['username']);
$email = trim($_POST['email']);
$password = $_POST['password'];
$profile_type = $_POST['profile_type'];
$age = (int) $_POST['age'];

if ($age < 13 || $age > 99) {
    die('Âge non valide.');
}

// Sécurité basique
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die('Email invalide.');
}

if (!in_array($profile_type, ['débutant', 'amateur'])) {
    die('Type de profil non valide.');
}

// Vérifier si le nom d’utilisateur ou l'email existent déjà
$stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
$stmt->execute([$username, $email]);

if ($stmt->fetch()) {
    die('Nom d\'utilisateur ou email déjà utilisé.');
}

// Hasher le mot de passe
$password_hash = password_hash($password, PASSWORD_DEFAULT);

// Insérer l’utilisateur
$stmt = $pdo->prepare("INSERT INTO users (username, email, password_hash, profile_type, age)
                       VALUES (?, ?, ?, ?, ?)");
$stmt->execute([$username, $email, $password_hash, $profile_type, $age]);


echo "Inscription réussie ! Tu peux maintenant te connecter.";
?>
