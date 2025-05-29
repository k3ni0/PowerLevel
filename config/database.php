<?php
define('DB_HOST', '127.0.0.1:3306');
define('DB_NAME', 'u629142522_PowerLevel');
define('DB_USER', 'u629142522_K3NII0');
define('DB_PASS', '@78Aee4afdc6');

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