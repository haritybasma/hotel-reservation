<?php
require_once 'includes/auth.php';
require_once 'includes/config.php';

$id = (int) ($_GET['id'] ?? $_POST['id'] ?? 0);

$stmt = $pdo->prepare('SELECT id, chambre, nuits, montant
                       FROM reservations
                       WHERE id = :id AND login = :login');
$stmt->execute(['id' => $id, 'login' => $_SESSION['login']]);
$reservation = $stmt->fetch(PDO::FETCH_ASSOC);

if ($reservation === false) {
    $titre = 'Réservation introuvable';
    require 'includes/header.php';
    echo '<p>Réservation introuvable.</p>';
    echo '<p><a href="liste.php">Retour à la liste</a></p>';
    require 'includes/footer.php';
    exit();
}

$message = '';

// on ne supprime qu'après la confirmation (formulaire en POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $pdo->beginTransaction();

        $stmt = $pdo->prepare('DELETE FROM reservations WHERE id = :id AND login = :login');
        $stmt->execute(['id' => $id, 'login' => $_SESSION['login']]);

        // la chambre redevient libre
        $stmt = $pdo->prepare('UPDATE chambres SET libre = 1 WHERE numero = :numero');
        $stmt->execute(['numero' => $reservation['chambre']]);

        $pdo->commit();
        header('Location: liste.php');
        exit();
    } catch (PDOException $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        error_log($e->getMessage());
        $message = 'Service momentanément indisponible.';
    }
}

$titre = 'Supprimer la réservation';
require 'includes/header.php';
?>
<h2>Supprimer la réservation n° <?php echo $reservation['id']; ?></h2>

<?php if ($message !== '') { ?>
    <p class="erreur"><?php echo htmlspecialchars($message); ?></p>
<?php } ?>

<ul>
    <li>Chambre : <?php echo $reservation['chambre']; ?></li>
    <li>Nuits : <?php echo $reservation['nuits']; ?></li>
    <li>Montant : <?php echo $reservation['montant']; ?> DH</li>
</ul>

<form method="post" action="supprimer.php">
    <input type="hidden" name="id" value="<?php echo $reservation['id']; ?>">
    <input type="submit" value="Confirmer la suppression">
</form>

<p><a href="liste.php">Annuler</a></p>
<?php require 'includes/footer.php'; ?>
