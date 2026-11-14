<?php
session_start();

// déjà connecté : on va directement à l'espace client
if (isset($_SESSION['login'])) {
    header('Location: espace.php');
    exit();
}

$erreur = isset($_GET['erreur']);
// login retenu grâce au cookie
$dernierLogin = $_COOKIE['dernierLogin'] ?? '';

$titre = 'Connexion';
require 'includes/header.php';
?>
<h2>Connexion</h2>

<?php if ($erreur) { ?>
    <p class="erreur">Login ou mot de passe incorrect.</p>
<?php } ?>

<form method="post" action="verifier.php">
    <label>Login
        <input type="text" name="login" value="<?php echo htmlspecialchars($dernierLogin); ?>">
    </label>
    <label>Mot de passe
        <input type="password" name="mot_de_passe">
    </label>
    <label>
        <input type="checkbox" name="se_souvenir"> Se souvenir de mon login
    </label>
    <input type="submit" value="Se connecter">
</form>
<?php require 'includes/footer.php'; ?>
