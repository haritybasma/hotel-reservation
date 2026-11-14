<?php
class Reservation {
    private $login;
    private $chambre;
    private $nuits;
    private $validee = false;

    public function __construct($login, $chambre, $nuits) {
        $this->login   = $login;
        $this->chambre = $chambre;
        $this->nuits   = $nuits;
    }

    public function valider() {
        // on vérifie la durée avant de toucher à la chambre
        if ($this->chambre->montant($this->nuits) === false) {
            return false;
        }
        if (!$this->chambre->reserver()) {
            return false;
        }
        $this->validee = true;
        return true;
    }

    public function getMontant() {
        if (!$this->validee) {
            return false;
        }
        return $this->chambre->montant($this->nuits);
    }
}
