<?php
require_once __DIR__ . '/Chambre.php';

class ChambreDouble extends Chambre {
    private $colocataire = null;

    public function __construct($numero, $etage, $prix) {
        parent::__construct($numero, 'Double', $etage, $prix);
    }

    // part de chaque personne : le total, ou la moitié s'il y a un colocataire
    public function montantParEtudiant($nuits) {
        $total = $this->montant($nuits);

        if ($total === false) {
            return false;
        }
        if ($this->colocataire !== null) {
            return $total / 2;
        }
        return $total;
    }

    public function ajouterColocataire($login) {
        if ($this->colocataire !== null) {
            return false;
        }
        if (trim($login) === '') {
            return false;
        }
        $this->colocataire = $login;
        return true;
    }

    public function retirerColocataire() {
        $this->colocataire = null;
    }

    public function getColocataire() {
        return $this->colocataire;
    }
}
