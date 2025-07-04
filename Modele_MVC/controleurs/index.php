    <?php
    // Inclusion du modèle
    require_once '../modele/nbpn.php';
    require_once '../modele/GameModel.php';

    // Initialisation du modèle

    // Vérification si une recherche a été soumise

// Initialisation du modèle
$gameModel = new GameModel();

$jeuxParPage = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;

$query = $_GET['query'] ?? null;

$errorMessage = "";
$games = [];
$nbPages = 1;

if ($query) {
    // Compter le nombre de résultats correspondant à la recherche
    $nbJeux = GameModel::countGamesByName($connection, $query);
    $nbPages = max(1, ceil($nbJeux / $jeuxParPage));
    if ($page > $nbPages) $page = $nbPages;

    $offset = ($page - 1) * $jeuxParPage;
    $games = GameModel::getGamesByNamePaginated($connection, $query, $offset, $jeuxParPage);

    if (empty($games)) {
        $errorMessage = "Aucun jeu trouvé pour votre recherche.";
    }
} else {
    // Si aucune recherche : récupérer tous les jeux paginés
    $nbJeux = GameModel::countGames($connection);
    $nbPages = max(1, ceil($nbJeux / $jeuxParPage));
    if ($page > $nbPages) $page = $nbPages;

    $offset = ($page - 1) * $jeuxParPage;
    $games = GameModel::getGamesPaginated($connection, $offset, $jeuxParPage);
}

    //changement pour reserver un jeu 


    if (isset($_POST['reserverGame'])) {
        // Récupérer les données du formulaire
        $nom = trim($_POST['nom']);
        $email = trim($_POST['email']);
        $adresse = trim($_POST['adresse']);
        $jeu = trim($_POST['game-select']);
        $dates = explode(' au ', trim($_POST['date-range'])); // Supposons que les dates sont séparées par "à"

        // Assurez-vous que les dates sont au format correct
        if (isset($_POST['date-range'])) {
            $dateRange = $_POST['date-range']; // "16/01/2025 au 19/01/2025"
        
            // Séparation des dates en utilisant " au "            $dates = explode(" au ", $dateRange);
        
            if (count($dates) === 2) {
                $startDate = $dates[0]; // "16/01/2025" or "2025-01-16"
                $endDate = $dates[1];   // "19/01/2025" or "2025-01-19"
        
                // Essayer d'abord le format Y-m-d (format SQL)
                $startDateFormatted = DateTime::createFromFormat('Y-m-d', $startDate);
                $endDateFormatted = DateTime::createFromFormat('Y-m-d', $endDate);
                
                // Si ça ne fonctionne pas, essayer le format d/m/Y (format français)
                if (!$startDateFormatted || !$endDateFormatted) {
                    $startDateFormatted = DateTime::createFromFormat('d/m/Y', $startDate);
                    $endDateFormatted = DateTime::createFromFormat('d/m/Y', $endDate);
                }
        
                if ($startDateFormatted && $endDateFormatted) {
                    $startDateSQL = $startDateFormatted->format('Y-m-d'); // Format SQL
                    $endDateSQL = $endDateFormatted->format('Y-m-d');     // Format SQL
        
                    // Debugging output (à retirer dans un environnement de production)
                    echo "Date de début (SQL) : $startDateSQL<br>";
                    echo "Date de fin (SQL) : $endDateSQL<br>";
        
                    // Vous pouvez maintenant utiliser $startDateSQL et $endDateSQL pour vos requêtes SQL
                } else {
                    //echo "Erreur lors de la conversion des dates.";
                }
            } else {
                echo "Format de plage de dates incorrect.";
            }
        } else {
            echo "Aucune date sélectionnée.";
        }

        try {
            // Récupérer l'emprunteur ou le créer
            $emprunteurId = $gameModel->getOrCreateEmprunteur($nom, $email, $adresse);
        
            // Récupérer l'ID du jeu
            $game = $gameModel->getGameByName($jeu);
            if (!$game) {
                throw new Exception("Le jeu sélectionné n'existe pas dans la base de données.");
            }
            $jeuId = $game[0]['id_jeu']; //faut faire un var_dump car les infos sont dans un tab associatif et sont dans $game[0]
        
            // Récupérer l'ID de la boîte associée au jeu
            $boite = $gameModel->getBoiteIdByGameId($jeuId);
            if (!$boite) {
                throw new Exception("Aucune boîte disponible pour le jeu sélectionné.");
            }
            $boiteId = $boite['boite_id'];
        
            // Créer la réservation
            if ($gameModel->isGameAvailable($boiteId, $startDate, $endDate)) {
            $gameModel->createPret($boiteId, $emprunteurId, $startDate, $endDate);
        
            }
            else {
                echo "<script>alert('Le jeu est déjà réservé.')</script>";
            }
         }
        catch (Exception $e) {
            echo "Erreur lors de la réservation : " . $e->getMessage();
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
        //$games = $gameModel->getAllGames();
    }

    // Inclusion de la vue
    include "../Vue/infos_view.php";