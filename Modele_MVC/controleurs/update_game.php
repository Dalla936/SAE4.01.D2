<?php
require_once '../modele/GameModel.php'; // Inclure le modèle

// Démarrer la session pour accéder à l'utilisateur connecté
session_start();

$gamemodel = new GameModel();
if (!isset($_SESSION['role_id']) || $_SESSION['role_id'] != 2) {
    header("Location: ../Vue/accueil.html");
    exit;
}
if (!isset($_POST['game_name']) || empty(trim($_POST['game_name']))) {
    die("Le nom du jeu est requis.");
}
$newTitle = trim($_POST['game_name']); // Récupérer le nom du jeu
$gameId = (int) $_POST['game_id']; // Récupérer l'ID du jeu et le convertir en entier

try {
    // Appeler la méthode pour mettre à jour le titre du jeu
    $gamemodel->updateGameTitle($gameId, $newTitle);

    // Rediriger
    header("Location: ../Vue/gestion.php"); // Redirige vers la page de gestion avec un message de succès
    exit();
} catch (Exception $e) {
    // Gérer les erreurs
    die("Erreur lors de la mise à jour du jeu : " . $e->getMessage());
}

