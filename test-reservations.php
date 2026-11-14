<?php
require_once 'classes/Chambre.php';
require_once 'classes/ChambreDouble.php';
require_once 'classes/Reservation.php';

echo '<h3>Test Chambre</h3>';
$c = new Chambre(201, 'Simple', 2, 850);
echo $c->reserver() ? 'OK' : 'Refus';
echo '<br>';
echo $c->reserver() ? 'OK' : 'Refus';
echo '<br>';
var_dump($c->setPrix(5000));
echo '<br>';
echo $c->getPrix();
echo '<br>';
echo $c->afficher();
echo '<br>';
var_dump($c->montant(3));
echo '<br>';
var_dump($c->montant(12));

echo '<h3>Test ChambreDouble</h3>';
$d = new ChambreDouble(202, 2, 1250);
var_dump($d->montantParEtudiant(4));
echo '<br>';
var_dump($d->ajouterColocataire('sara'));
echo '<br>';
var_dump($d->montantParEtudiant(4));
echo '<br>';
var_dump($d->ajouterColocataire('yassir'));
echo '<br>';
var_dump($d->getColocataire());
echo '<br>';
$d->retirerColocataire();
var_dump($d->getColocataire());
echo '<br>';
var_dump($d->montantParEtudiant(12));
echo '<br>';
echo $d->afficher();

echo '<h3>Test Reservation</h3>';
$c201 = new Chambre(201, 'Simple', 2, 850);
$c202 = new ChambreDouble(202, 2, 1250);

$r1 = new Reservation('sara', $c201, 3);
var_dump($r1->getMontant());
echo '<br>';
var_dump($r1->valider());
echo '<br>';
var_dump($r1->getMontant());
echo '<br>';

$r2 = new Reservation('yassir', $c201, 2);
var_dump($r2->valider());
echo '<br>';

$r3 = new Reservation('yassir', $c202, 12);
var_dump($r3->valider());
echo '<br>';
var_dump($c202->estLibre());
echo '<br>';

$r4 = new Reservation('yassir', $c202, 4);
var_dump($r4->valider());
echo '<br>';
$c202->ajouterColocataire('sara');
var_dump($r4->getMontant());
echo '<br>';
var_dump($c202->montantParEtudiant(4));
