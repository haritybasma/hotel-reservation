<?php
class Chambre {
    private $numero;
    private $type;
    private $etage;
    private $prix;
    private $libre;

    public function __construct($numero, $type, $etage, $prix, $libre = true) {
        $this->numero = $numero;
        $this->type   = $type;
        $this->etage  = $etage;
        $this->setPrix($prix);
        $this->libre  = $libre;
    }

    public function getNumero() {
        return $this->numero;
    }

    public function getType() {
        return $this->type;
    }

    public function getEtage() {
        return $this->etage;
    }

    public function getPrix() {
        return $this->prix;
    }

    public function estLibre() {
        return $this->libre;
    }

    // le prix doit rester entre 300 et 3000 DH
    public function setPrix($prix) {
        if ($prix < 300 || $prix > 3000) {
            return false;
        }
        $this->prix = $prix;
        return true;
    }

    public function reserver() {
        if (!$this->libre) {
            return false;
        }
        $this->libre = false;
        return true;
    }

    public function liberer() {
        if ($this->libre) {
            return false;
        }
        $this->libre = true;
        return true;
    }

    // un séjour dure entre 1 et 10 nuits
    public function montant($nuits) {
        if ($nuits < 1 || $nuits > 10) {
            return false;
        }
        return $this->prix * $nuits;
    }

    public function afficher() {
        return $this->numero . ' - ' . $this->type . ' - ' . $this->prix . ' DH';
    }
}
