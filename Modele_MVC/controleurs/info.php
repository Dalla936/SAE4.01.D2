<?php

// Inclusion du modèle
require_once "../modele/GameModel.php";

// Initialisation du modèle
$gameModel = new GameModel();

// Gestion des actions via GET
$action = $_GET['action'] ?? null;
$query = $_GET['query'] ?? null;

if ($action === "searchGame" && !empty($query)) {
    // Rechercher un jeu spécifique
    $game = $gameModel->getGameByName($query);

    // Si le jeu n'est pas trouvé, afficher un message
    if (!$game) {
        $errorMessage = "Jeu introuvable.";
    }
} else {
    // Par défaut, on récupère le premier jeu de la base de données
    $games = $gameModel->getAllGames();
    $game = $games[0] ?? null;

    if (!$game) {
        $errorMessage = "Aucun jeu trouvé dans la base de données.";
    }
}
$action = $_GET['bouton'] ?? null;
$tri = $_GET['tri'] ?? null;

if ($action == 'validation' && $tri) {
    if ($tri === 'ancien') {
        // Récupérer les jeux du plus ancien au plus récent
        $games = $gameModel->getGamesOrderedByDate("ASC");
    } elseif ($tri === 'recent') {
        // Récupérer les jeux du plus récent au plus ancien
        $games = $gameModel->getGamesOrderedByDate("DESC");
    } else {
        $games = [];
        $errorMessage = "Critère de tri invalide.";
    }
} else {
    // Par défaut, afficher tous les jeux
    $games = $gameModel->getAllGames();
}
$ajout = $_GET['ajout'] ?? null;
$titre = $_GET['titreJeu'] ?? null; //si tu ne renseignes pas de titre, ce n'est pas grave
$dateParutionDebut = $_GET['dateParutionDebut'] ?? null;
$dateParutionFin = $_GET['dateParutionFin'] ?? null;
$Nbjoueurs = $_GET['Nbjoueurs'] ?? null;
$age = $_GET['Age'] ?? null;

// Vérification des données P

if ($ajout ==="add") {
    $games = $gameModel->addGames($titre, $dateParutionDebut, $dateParutionFin, $Nbjoueurs, $age);
    header("Location: ../Vue/accueil.html");
    exit();
}


// Inclusion de la vue
include "../Vue/infos_view.php";