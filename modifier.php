<?php
require_once 'includes/auth.php';
require_once 'includes/config.php';
require_once 'classes/Chambre.php';

// l'id arrive par l'adresse, puis par le champ caché quand on envoie le formulaire
$id = (int) ($_GET['id'] ?? $_POST['id'] ?? 0);

// la réservation doit appartenir au client connecté
$stmt = $pdo->prepare('SELECT r.id, r.chambre, r.nuits, c.type, c.etage, c.prix
                       FROM reservations r
                       JOIN chambres c ON c.numero = r.chambre
                       WHERE r.id = :id AND r.login = :login');
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
$nuits = $reservation['nuits'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nuits = $_POST['nuits'] ?? '';

    // on recalcule le montant avec la classe Chambre (elle vérifie aussi 1 à 10 nuits)
    $chambre = new Chambre(
        $reservation['chambre'],
        $reservation['type'],
        $reservation['etage'],
        $reservation['prix']
    );
    $montant = $chambre->montant((int) $nuits);

    if ($montant === false) {
        $message = 'Le nombre de nuits doit être compris entre 1 et 10.';
    } else {
        try {
            $stmt = $pdo->prepare('UPDATE reservations
                                   SET nuits = :nuits, montant = :montant
                                   WHERE id = :id AND login = :login');
            $stmt->execute([
                'nuits'   => (int) $nuits,
                'montant' => $montant,
                'id'      => $id,
                'login'   => $_SESSION['login']
            ]);
            header('Location: liste.php');
            exit();
        } catch (PDOException $e) {
            error_log($e->getMessage());
            $message = 'Service momentanément indisponible.';
        }
    }
}

$titre = 'Modifier la réservation';
require 'includes/header.php';
?>
<h2>Modifier la réservation n° <?php echo $reservation['id']; ?></h2>

<?php if ($message !== '') { ?>
    <p class="erreur"><?php echo htmlspecialchars($message); ?></p>
<?php } ?>

<form method="post" action="modifier.php">
    <input type="hidden" name="id" value="<?php echo $reservation['id']; ?>">

    <p>Chambre : <?php echo $reservation['chambre']; ?></p>

    <label>Nuits
        <select name="nuits">
            <?php for ($i = 1; $i <= 10; $i++) { ?>
                <option value="<?php echo $i; ?>" <?php echo ($i == $nuits) ? 'selected' : ''; ?>>
                    <?php echo $i; ?>
                </option>
            <?php } ?>
        </select>
    </label>

    <input type="submit" value="Enregistrer">
</form>

<p><a href="liste.php">Retour à la liste</a></p>
<?php require 'includes/footer.php'; ?>
