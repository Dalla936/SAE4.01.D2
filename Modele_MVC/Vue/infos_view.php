<?php
$page = $page ?? 1;
$nbPages = $nbPages ?? 1;?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Collection - Université Sorbonne Paris Nord</title>
    <link rel="stylesheet" href="../Vue/collection.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.13/flatpickr.min.css"> <!-- css de la bibliothèque flatpick-->
</head>

<body>    <header>
        <a href="../Vue/accueil.php"><img src="../img/LogoUSPN.png" alt="Sorbonne Paris Nord"></a>
        <nav>
            <a href="../Vue/documentation.php">Documentation</a>
            <a href="../controleurs/info.php">Collection</a>
            <a href="../Vue/reservation_View.php">Réservation</a>
            <a href="https://cas.univ-paris13.fr/cas/login?service=https%3A%2F%2Fent.univ-paris13.fr">ENT</a></nav>
        <div class="search-bar">
            <form action="../controleurs/info.php" method="get">
                <input type="hidden" name="action" value="searchGame">
                <input type="text" name="query" placeholder="Rechercher un jeu..." required>
                <button type="submit">🔍</button>
            </form>
        </div>
    <div class="zone-utilisateur">

        <a class="username" style="color: white;">Bonjour <?php echo isset($_COOKIE['username']) ? htmlspecialchars($_COOKIE['username']) : 'Utilisateur'; ?></a>

        <div class="profil-utilisateur" id="profilUtilisateur">
            <img src="../img/profile.png" alt="Icône Profil" class="icone-utilisateur" onclick="basculerMenuDeroulant()">
            <div class="menu-deroulant" id="menuDeroulant">
                <?php if (isset($_COOKIE['username'])): ?>
                    <a href="../Vue/compte.php">Gestion du profil</a>
                <?php endif; ?>
                <?php if (isset($_COOKIE['role_id']) && ($_COOKIE['role_id'] == 2 || $_COOKIE['role_id'] == 3)): ?>
                    <a href="../Vue/gestion.php">Gestion des utilisateurs et des jeux</a>
                <?php endif; ?>
                <?php if (!isset($_COOKIE['role_id'])): ?>
                    <a href="../Vue/connexion.html"> Se connecter</a>
                <?php endif; ?>
                <?php if (isset($_COOKIE['username'])): ?>
                    <button class="bouton-deconnexion" onclick="window.location.href='../controleurs/deconnexion.php';">Déconnexion</button>
                <?php endif; ?>
            </div>
        </div>
    </div>

    </header>
    <!-- Boutons Filtre et Ajouter un jeu -->
    <div class="actions">
        <button onclick="ouvrirFiltre()"> ⚙️ Filtrer</button>
        <?php if (isset($_COOKIE['role_id']) && ($_COOKIE['role_id'] == 2 || $_COOKIE['role_id'] == 3)): ?>
        <button onclick="ouvrirAjoutJeu()">🎲 Ajouter un jeu</button>
        <?php endif; ?>

    </div>

    <!-- Pop-up pour le filtre -->
    <div class="popup" id="popupFiltre">
        <div class="popup-content">
            <h2>  ⚙️ Filtrer les jeux</h2>
            <form action="../controleurs/info.php" method="get">
                <label>
                    <input type="radio" name="tri" value="ancien"> Du plus ancien au plus récent
                </label>
                <br>
                <label>
                    <input type="radio" name="tri" value="recent"> Du plus récent au plus ancien
                </label>
                <br>
                <button type="submit" name="bouton" value="validation" onclick="fermerFiltre()">Appliquer</button>
            </form>
        </div>
    </div>

    <!-- Pop-up pour ajouter un jeu -->
    <div class="popup" id="popupAjoutJeu">
        <div class="popup-content">
            <h2>Ajouter un jeu</h2>
            <form action="../controleurs/info.php" method="get">
                <label for="titreJeu">Titre du jeu :</label>
                <input type="text" id="titreJeu" name="titreJeu" required>
                <br>
                <label for="dateDebut">Date de début:</label>
                <input type="date" id="dateParutionDebut" name="dateParutionDebut" required>
                <br>
                <label for="dateFin">Date de fin:</label>
                <input type="date" id="dateParutionFin" name="dateParutionFin" required>
                <br>
                <label for="Nbjoueurs">Nombre de joueurs :</label>
                <input type="text" id="Nbjoueurs" name="Nbjoueurs" required>
                <br>
                <label for="Age">Age :</label>
                <input type="text" id="Age" name="Age" required>
                <br>
                <button type="submit" name="ajout" value="add">Ajouter</button>
                <button type="button" onclick="fermerAjoutJeu()">Annuler</button>
            </form>
        </div>
    </div>    <div class="container">
    <h1>Collection de jeux</h1>
    
    <?php if (!empty($errorMessage)): ?>
        <div class="error-message" style="color: #721c24; background-color: #f8d7da; border: 1px solid #f5c6cb; padding: 15px; margin-bottom: 20px; border-radius: 5px; text-align: center;">
            <?= htmlspecialchars($errorMessage) ?>
        </div>
    <?php endif; ?>
    
    <?php if (!empty($games)): ?>
        <?php 
        // Création d'une instance de GameModel pour accéder à la méthode getGameReservationCount
        require_once('../modele/GameModel.php');
        $gameModel = new GameModel();
        ?>
        <?php foreach ($games as $game): ?>
            <div class="game-card">
                <?php 
                // Récupérer le nombre de réservations pour ce jeu
                $reservationCount = $gameModel->getGameReservationCount($game['id_jeu']);
                // Afficher le badge uniquement si le jeu a des réservations
                if ($reservationCount > 0): 
                ?>
                    <div class="reservation-badge">
                        <?= $reservationCount ?> réservation<?= $reservationCount > 1 ? 's' : '' ?>
                    </div>
                <?php endif; ?>
                <h3><?= htmlspecialchars($game['titre']) ?></h3>
                <p><strong>Auteur :</strong> <?= htmlspecialchars($game['auteurs'] ?? '') ?></p>
                <p><strong>Éditeur :</strong> <?= htmlspecialchars($game['editeurs']) ?></p>
                <p><strong>Année de publication :</strong> <?= htmlspecialchars($game['date_parution_debut']) ?></p>
                <p><strong>Nombre de joueurs :</strong> <?= htmlspecialchars($game['nombre_de_joueurs']) ?></p>
                <p><strong>Type de jeu :</strong> <?= htmlspecialchars($game['mecanisme']) ?></p>
                <a href="../Vue/reservation_View.php?game=<?= urlencode($game['titre']) ?>"><button>Réserver</button></a>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Aucun jeu trouvé.</p>
    <?php endif; ?>

    <nav class="pagination" style="text-align:center; margin: 20px 0;">
    <?php
    $maxPagesToShow = 5;
    $half = floor($maxPagesToShow / 2);

    // Calcul du début et fin des pages à afficher
    $startPage = max(1, $page - $half);
    $endPage = min($nbPages, $page + $half);

    // Ajustement si on est proche du début ou de la fin
    if ($page - $startPage < $half) {
        $endPage = min($nbPages, $endPage + ($half - ($page - $startPage)));
    }
    if ($endPage - $page < $half) {
        $startPage = max(1, $startPage - ($half - ($endPage - $page)));
    }
    ?>

    <!-- Bouton Premier -->
    <?php if ($page > 1): ?>
        <a href="?page=1" style="margin-right:5px;">Premier</a>
    <?php endif; ?>

    <!-- Bouton Précédent -->
    <?php if ($page > 1): ?>
        <a href="?page=<?= $page - 1 ?>" style="margin-right:5px;">Précédent</a>
    <?php endif; ?>

    <!-- Pages numérotées -->
    <?php for ($p = $startPage; $p <= $endPage; $p++): ?>
        <?php if ($p == $page): ?>
            <span style="font-weight:bold; margin: 0 5px;"><?= $p ?></span>
        <?php else: ?>

            <?php
// Construit l'URL avec les paramètres
$queryString = "?page=$p";
if (!empty($query)) {
    $queryString .= "&query=" . urlencode($query) . "&action=searchGame";
}
?>
<a href="<?= $queryString ?>" style="margin: 0 5px;"><?= $p ?></a>
            <?php endif; ?>
    <?php endfor; ?>

    <!-- Bouton Suivant -->
    <?php if ($page < $nbPages): ?>
        <a href="?page=<?= $page + 1 ?>" style="margin-left:5px;">Suivant</a>
    <?php endif; ?>

    <!-- Bouton Dernier -->
    <?php if ($page < $nbPages): ?>
        <a href="?page=<?= $nbPages ?>" style="margin-left:5px;">Dernier</a>
    <?php endif; ?>
    </nav>
</div>
    <!-- Ajoute ce bloc pagination ici -->
    
    <footer class="footer">
        <p>
            <a href="../Vue/mentions.php">Mentions légales</a> |
            <a href="../Vue/politique.php">Politique de cookies</a> |
            <a href="../Vue/protection.php">Protection de données</a>
        </p>
    </footer>

    <!--Configuration de la biliothèque Flatpickr pour pouvoir utiliser un calendrier -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.13/flatpickr.min.js"></script>
    <!--Pour que les noms soit traduits en français dans le calendrier-->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/fr.js"></script>
    <script>
        // Initialisation du calendrier
        document.addEventListener('DOMContentLoaded', () => {
            flatpickr("#dateParutionDebut", {
                dateFormat: "Y/m/d",
                locale: "fr"
            });
            flatpickr("#dateParutionFin", {
                dateFormat: "Y/m/d",
                locale: "fr"
            });
        });


        // Fonction d'ouverture et fermeture du pop-up filtre
        function ouvrirFiltre() {
            document.getElementById('popupFiltre').style.display = 'block';
        }
        function fermerFiltre() {
            document.getElementById('popupFiltre').style.display = 'none';
        }

        // Fonction d'ouverture et fermeture du pop-up jeu
        function ouvrirAjoutJeu() {
            document.getElementById('popupAjoutJeu').style.display = 'block';
        }
        function fermerAjoutJeu() {
            document.getElementById('popupAjoutJeu').style.display = 'none';
        }

        // Affichage du menu déroulant
        function basculerMenuDeroulant() {
            const menuDeroulant = document.getElementById('menuDeroulant');
            menuDeroulant.style.display = menuDeroulant.style.display === 'block' ? 'none' : 'block';
        }

        // Fermer le menu déroulant lorsque l'on clique en dehors du menu
        window.onclick = function(event) {
            if (!event.target.matches('.icone-utilisateur')) {
                const menusDeroulants = document.getElementsByClassName('menu-deroulant');
                for (let i = 0; i < menusDeroulants.length; i++) {
                    const menuOuvert = menusDeroulants[i];
                    if (menuOuvert.style.display === 'block') {
                        menuOuvert.style.display = 'none';
                    }
                }   
            }
        }
        
    </script>
</body>
</html>