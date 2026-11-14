<?php
require_once 'includes/auth.php';
require_once 'includes/config.php';

// seulement les réservations du client connecté
$stmt = $pdo->prepare('SELECT id, chambre, nuits, montant
                       FROM reservations
                       WHERE login = :login
                       ORDER BY id');
$stmt->execute(['login' => $_SESSION['login']]);
$reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);

$titre = 'Mes réservations';
require 'includes/header.php';
?>
<h2>Mes réservations</h2>

<p><a class="bouton" href="reserver.php">Nouvelle réservation</a></p>

<?php if (count($reservations) === 0) { ?>
    <p>Aucune réservation pour le moment.</p>
<?php } else { ?>
    <table>
        <tr>
            <th>N°</th>
            <th>Chambre</th>
            <th>Nuits</th>
            <th>Montant (DH)</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($reservations as $r) { ?>
            <tr>
                <td><?php echo $r['id']; ?></td>
                <td><?php echo $r['chambre']; ?></td>
                <td><?php echo $r['nuits']; ?></td>
                <td><?php echo $r['montant']; ?></td>
                <td>
                    <a href="modifier.php?id=<?php echo $r['id']; ?>">Modifier</a> |
                    <a href="supprimer.php?id=<?php echo $r['id']; ?>">Supprimer</a>
                </td>
            </tr>
        <?php } ?>
    </table>
<?php } ?>
<?php require 'includes/footer.php'; ?>
