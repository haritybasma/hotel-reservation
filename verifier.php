<?php
session_start();
require_once 'includes/config.php';

// on n'arrive ici qu'avec le formulaire de connexion
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: connexion.php');
    exit();
}

$login = trim($_POST['login'] ?? '');
$motDePasse = $_POST['mot_de_passe'] ?? '';

$stmt = $pdo->prepare('SELECT login, mot_de_passe, nom
                       FROM utilisateurs
                       WHERE login = :login');
$stmt->execute(['login' => $login]);
$utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);

if ($utilisateur && password_verify($motDePasse, $utilisateur['mot_de_passe'])) {
    session_regenerate_id(true);
    $_SESSION['login'] = $utilisateur['login'];
    $_SESSION['nom'] = $utilisateur['nom'];

    // cookie valable 30 jours si la case est cochée, sinon on le supprime
    if (isset($_POST['se_souvenir'])) {
        setcookie('dernierLogin', $utilisateur['login'], time() + 30 * 24 * 3600);
    } else {
        setcookie('dernierLogin', '', time() - 3600);
    }

    header('Location: espace.php');
    exit();
}

header('Location: connexion.php?erreur=1');
exit();
