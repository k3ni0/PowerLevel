<?php
$envPath = __DIR__ . '/../.env';
$env = [];
if (file_exists($envPath)) {
    $env = parse_ini_file($envPath);
}

define('DB_HOST', $env['DB_HOST'] ?? getenv('DB_HOST') ?? '127.0.0.1');
define('DB_NAME', $env['DB_NAME'] ?? getenv('DB_NAME') ?? 'database');
define('DB_USER', $env['DB_USER'] ?? getenv('DB_USER') ?? 'user');
define('DB_PASS', $env['DB_PASS'] ?? getenv('DB_PASS') ?? 'password');

try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ]
    );
} catch(PDOException $e) {
    error_log("Erreur de connexion BDD : " . $e->getMessage());
    die("Erreur de connexion à la base de données. Contactez l'administrateur.");
}