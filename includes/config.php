<?php
$envPath = __DIR__ . '/../.env';
$env = [];
if (file_exists($envPath)) {
    $env = parse_ini_file($envPath);
}

$host = $env['DB_HOST'] ?? getenv('DB_HOST') ?? 'localhost';
$db   = $env['DB_NAME'] ?? getenv('DB_NAME') ?? 'database';
$user = $env['DB_USER'] ?? getenv('DB_USER') ?? 'user';
$pass = $env['DB_PASS'] ?? getenv('DB_PASS') ?? 'password';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
    // echo "Connexion réussie !";
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}
?>
