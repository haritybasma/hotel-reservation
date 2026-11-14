<?php
require_once 'includes/auth.php';
require_once 'includes/config.php';
require_once 'classes/Chambre.php';
require_once 'classes/Reservation.php';

$login = $_SESSION['login'];
$message = '';

// chambres pour la liste déroulante
$stmt = $pdo->query('SELECT numero, type, etage, prix, libre
                     FROM chambres
                     ORDER BY numero');
$lignes = $stmt->fetchAll(PDO::FETCH_ASSOC);

$chambres = [];
foreach ($lignes as $ligne) {
    $chambres[] = new Chambre(
        $ligne['numero'],
        $ligne['type'],
        $ligne['etage'],
        $ligne['prix'],
        (bool) $ligne['libre']
    );
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $numero = $_POST['chambre'] ?? '';
    $nuits = $_POST['nuits'] ?? '';

    if ($numero === '' || $nuits === '') {
        $message = 'Tous les champs sont obligatoires.';
    } elseif ((int) $nuits < 1 || (int) $nuits > 10) {
        $message = 'Le nombre de nuits doit être compris entre 1 et 10.';
    } else {
        $nuits = (int) $nuits;

        $stmt = $pdo->prepare('SELECT numero, type, etage, prix, libre
                               FROM chambres
                               WHERE numero = :numero');
        $stmt->execute(['numero' => $numero]);
        $ligne = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($ligne === false) {
            $message = 'Chambre introuvable.';
        } else {
            $chambre = new Chambre(
                $ligne['numero'],
                $ligne['type'],
                $ligne['etage'],
                $ligne['prix'],
                (bool) $ligne['libre']
            );
            $reservation = new Reservation($login, $chambre, $nuits);

            if (!$reservation->valider()) {
                $message = 'Réservation impossible : chambre occupée ou durée invalide.';
            } else {
                // l'insertion et la mise à jour de la chambre vont ensemble
                try {
                    $pdo->beginTransaction();

                    $stmt = $pdo->prepare('INSERT INTO reservations (login, chambre, nuits, montant)
                                           VALUES (:login, :chambre, :nuits, :montant)');
                    $stmt->execute([
                        'login'   => $login,
                        'chambre' => $chambre->getNumero(),
                        'nuits'   => $nuits,
                        'montant' => $reservation->getMontant()
                    ]);

                    $stmt = $pdo->prepare('UPDATE chambres SET libre = 0 WHERE numero = :numero');
                    $stmt->execute(['numero' => $chambre->getNumero()]);

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
        }
    }
}

$titre = 'Réserver';
require 'includes/header.php';
?>
<h2>Réserver une chambre</h2>

<?php if ($message !== '') { ?>
    <p class="erreur"><?php echo htmlspecialchars($message); ?></p>
<?php } ?>

<form method="post" action="reserver.php">
    <p>Client : <strong><?php echo htmlspecialchars($login); ?></strong></p>

    <label>Chambre
        <select name="chambre">
            <?php foreach ($chambres as $chambre) { ?>
                <?php if ($chambre->estLibre()) { ?>
                    <option value="<?php echo $chambre->getNumero(); ?>">
                        <?php echo htmlspecialchars($chambre->afficher()); ?>
                    </option>
                <?php } ?>
            <?php } ?>
        </select>
    </label>

    <label>Nuits
        <select name="nuits">
            <?php for ($i = 1; $i <= 10; $i++) { ?>
                <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
            <?php } ?>
        </select>
    </label>

    <input type="submit" value="Réserver">
</form>
<?php require 'includes/footer.php'; ?>
