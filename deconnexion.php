<?php
session_start();
$_SESSION = [];
session_destroy();

// le cookie dernierLogin reste : le login sera encore pré-rempli
header('Location: connexion.php');
exit();
