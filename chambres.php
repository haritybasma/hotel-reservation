<?php
require_once 'includes/config.php';
require_once 'classes/Chambre.php';

// filtre sur le type : chambres.php?type=Simple
$type = isset($_GET['type']) ? $_GET['type'] : '';

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

$titre = 'Chambres';
require 'includes/header.php';
?>
<h2>Nos chambres</h2>

<p>
    <a href="chambres.php">Toutes</a> |
    <a href="chambres.php?type=Simple">Simple</a> |
    <a href="chambres.php?type=Double">Double</a> |
    <a href="chambres.php?type=Suite">Suite</a>
</p>

<table>
    <tr>
        <th>Numéro</th>
        <th>Type</th>
        <th>Étage</th>
        <th>Prix (DH / nuit)</th>
        <th>Disponibilité</th>
    </tr>
    <?php
    $nbLibres = 0;
    foreach ($chambres as $chambre) {
        if ($type !== '' && $chambre->getType() !== $type) {
            continue;
        }
        if ($chambre->estLibre()) {
            $nbLibres++;
        }
    ?>
        <tr>
            <td><?php echo $chambre->getNumero(); ?></td>
            <td><?php echo htmlspecialchars($chambre->getType()); ?></td>
            <td><?php echo $chambre->getEtage(); ?></td>
            <td><?php echo $chambre->getPrix(); ?></td>
            <td>
                <?php if ($chambre->estLibre()) { ?>
                    <span class="libre">Libre</span>
                <?php } else { ?>
                    <span class="occupee">Occupée</span>
                <?php } ?>
            </td>
        </tr>
    <?php } ?>
</table>

<p>Chambres libres : <?php echo $nbLibres; ?></p>
<?php require 'includes/footer.php'; ?>
