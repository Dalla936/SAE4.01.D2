<?php
require '../modele/GameModel.php';
$gamemodel = new GameModel();

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $numero_etu = trim($_POST['numero_etu']);
    $mot_de_passe = trim($_POST['mdp']);

    // Vérifier les informations de l'utilisateur
    $user = $gamemodel->getUserByNumeroEtu($numero_etu); // Adapte le nom de la méthode si besoin

    if ($user && $mot_de_passe === $user['mot_de_passe_nonh']) { // ou password_verify si hashé
        $_SESSION['numero_etu'] = $user['numero_etu'];
        $_SESSION['username'] = $user['nom'];
        $_SESSION['role_id'] = $user['role_id'];

        setcookie('numero_etu', $user['numero_etu'], time() + 7246060, "/");
        setcookie('username', $user['nom'], time() + 7246060, "/");
        setcookie('role_id', $user['role_id'], time() + 72460*60, "/");

        header("Location: ../Vue/accueil.html");
        exit;
    } else {
        header("Location: ../Vue/mdp_refus.html");
        exit;
    }
}
?>