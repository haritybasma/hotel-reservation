<?php
// copier ce fichier en config.php et mettre ses identifiants
$server   = 'localhost';
$dbname   = 'hotel_resa_db';
$user     = 'votre_utilisateur';
$password = 'votre_mot_de_passe';

$dsn = "mysql:host=$server;dbname=$dbname;charset=utf8mb4";

try {
    $pdo = new PDO($dsn, $user, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
} catch (PDOException $e) {
    error_log($e->getMessage());
    die('Service momentanément indisponible.');
}
