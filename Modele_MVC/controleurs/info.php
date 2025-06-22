<?php
require_once '../modele/nbpn.php';
require_once "../modele/GameModel.php";

$gameModel = new GameModel();

$jeuxParPage = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;

$action = $_GET['action'] ?? null;    
$query = $_GET['query'] ?? null;
$tri = $_GET['tri'] ?? null;          
$bouton = $_GET['bouton'] ?? null;    
$ajout = $_GET['ajout'] ?? null;      

$errorMessage = "";
$games = [];
$nbPages = 1;

if ($ajout === "add") {
    // Ajout de jeu, pas lié à pagination donc OK
    $titre = $_GET['titreJeu'] ?? null;
    $dateParutionDebut = $_GET['dateParutionDebut'] ?? null;
    $dateParutionFin = $_GET['dateParutionFin'] ?? null;
    $Nbjoueurs = $_GET['Nbjoueurs'] ?? null;
    $age = $_GET['Age'] ?? null;

    $gameModel->addGames($titre, $dateParutionDebut, $dateParutionFin, $Nbjoueurs, $age);
    header("Location: ../Vue/accueil.html");
    exit();
}

if ($action === "searchGame" && !empty($query)) {
    // Recherche paginée
    $nbJeux = GameModel::countGamesByName($connection, $query);
    $nbPages = max(1, ceil($nbJeux / $jeuxParPage));
    if ($page > $nbPages) $page = $nbPages;

    $offset = ($page - 1) * $jeuxParPage;
    
    if ($nbJeux > 0) {
        $games = GameModel::getGamesByNamePaginated($connection, $query, $offset, $jeuxParPage);
    } else {
        $games = [];
        $errorMessage = "Aucun jeu trouvé correspondant à \"" . htmlspecialchars($query) . "\".";
    }
} elseif ($bouton === 'validation' && $tri) {
    // Tri, pas paginé ici, tu peux l’ajouter plus tard si tu veux
    if ($tri === 'ancien') {
        $games = $gameModel->getGamesOrderedByDate("ASC");
    } elseif ($tri === 'recent') {
        $games = $gameModel->getGamesOrderedByDate("DESC");
    } else {
        $games = [];
        $errorMessage = "Critère de tri invalide.";
    }
} else {
    // Cas normal pagination classique
    $nbJeux = GameModel::countGames($connection);
    $nbPages = ceil($nbJeux / $jeuxParPage);
    if ($page > $nbPages) $page = $nbPages > 0 ? $nbPages : 1;

    $offset = ($page - 1) * $jeuxParPage;
    $games = GameModel::getGamesPaginated($connection, $offset, $jeuxParPage);
}

include "../Vue/infos_view.php";
 