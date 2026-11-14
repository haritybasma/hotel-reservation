<?php
require_once 'includes/auth.php';
require_once 'includes/config.php';

// nombre de réservations du client connecté
$stmt = $pdo->prepare('SELECT COUNT(*) FROM reservations WHERE login = :login');
$stmt->execute(['login' => $_SESSION['login']]);
$nbReservations = $stmt->fetchColumn();

$titre = 'Mon espace';
require 'includes/header.php';
?>
<h2>Bienvenue <?php echo htmlspecialchars($_SESSION['nom']); ?></h2>

<p>Vous avez <?php echo $nbReservations; ?> réservation(s).</p>

<p>
    <a class="bouton" href="reserver.php">Réserver une chambre</a>
    <a class="bouton" href="liste.php">Mes réservations</a>
</p>
<?php require 'includes/footer.php'; ?>
