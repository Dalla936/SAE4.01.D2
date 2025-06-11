<?php
require '../modele/GameModel.php';
$gamemodel = new GameModel();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $identifiant = htmlspecialchars($_POST['id']);
    $mot_de_passe = htmlspecialchars($_POST['mdp']);

    try {
        if ($gamemodel->connecterUtilisateur($identifiant, $mot_de_passe)) {
            // Connexion réussie
            require '../Vue/accueil.html';
            exit;
        }
    } catch (Exception $e) {
        echo "Erreur : " . $e->getMessage();
    }
}
session_start(); // Démarrer une session


// Exemple de récupération des données du formulaire
$numero_etu = trim($_POST['id']);
$mot_de_passe = trim($_POST['mdp']);


// Vérifier les informations de l'utilisateur
$user = $gamemodel->getUserById($numero_etu); // Une méthode pour récupérer l'utilisateur via son numéro étudiant

if ($user && password_verify($mot_de_passe, $user['mdp'])) {
    // Si l'utilisateur est authentifié avec succès, stocker ses informations dans la session
    $_SESSION['user_id'] = $user['id']; // ID de l'utilisateur
    $_SESSION['username'] = $user['nom']; // Nom de l'utilisateur
    $_SESSION['role_id'] = $user['role_id']; // Rôle de l'utilisateur (ex. utilisateur, gestionnaire, admin)

    // Rediriger vers une page (par exemple, la page d'accueil)
    header("Location: ../Vue/accueil.html");
    exit;
} else {
    // redirige vers la page de refus
    header("Location : ../Vue/mdp_refus.html");
    exit();
}
    ?>