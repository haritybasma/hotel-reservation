<?php
// à inclure en haut des pages réservées aux clients connectés
session_start();

if (!isset($_SESSION['login'])) {
    header('Location: connexion.php');
    exit();
}
