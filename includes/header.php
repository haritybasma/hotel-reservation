<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo htmlspecialchars($titre ?? 'Hotel Reservation'); ?></title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<header>
    <span class="logo">Hotel Reservation</span>
    <nav>
        <a href="chambres.php">Chambres</a>
        <?php if (isset($_SESSION['login'])) { ?>
            <a href="reserver.php">Réserver</a>
            <a href="liste.php">Mes réservations</a>
            <a href="espace.php">Mon espace</a>
            <a href="deconnexion.php">Déconnexion</a>
        <?php } else { ?>
            <a href="connexion.php">Connexion</a>
        <?php } ?>
    </nav>
</header>
<main>
