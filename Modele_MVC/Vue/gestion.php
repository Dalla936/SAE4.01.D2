<?php

require '../modele/GameModel.php';
$gamemodel = new GameModel();

session_start();
if(!isset($_SESSION['role_id']) ||($_SESSION['role_id'] != 2 && $_SESSION['role_id'] != 3)) {
    header("Location: ../Vue/accueil.html");
    exit;
}
// Fetch all users and games
$users = $gamemodel->getAllUsers();
$games = $gamemodel->getAllGames();

// Handle deletion of a user
if (isset($_POST['delete_user'])) {
    $userId = $_POST['user_id'];
    $gamemodel->deleteUser($userId);
    header("Location: gestion.php");
    exit;
}

// Handle user update
if (isset($_POST['update_user'])) {
    $userId = $_POST['user_id'];
    $newrole_id = $_POST['role_id'];
    $gamemodel->updateUserRoleId($userId, $newrole_id);
    header("Location: gestion.php");
    exit;
}

// Handle game update
if (isset($_POST['update_game'])) {
    $gameId = $_POST['game_id'];
    $newName = $_POST['name'];
    $newMots_cles = $_POST['mots_cles'];
    $gamemodel->updateGame($gameId, $newName, $newMots_cles);
    header("Location: gestion.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des utilisateurs et des jeux</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="../Vue/gestion_styles.css">



</head>
<body>
<header>
    <a href="../Vue/accueil.php"><img src="../img/LogoUSPN.png" alt="Sorbonne Paris Nord" /></a>
    <nav>
      <a href="../Vue/documentation.html">Documentation</a>
      <a href="../controleurs/info.php">Collection</a>
      <a href="../Vue/reservation_View.php">Réservation</a>
      <a href="https://cas.univ-paris13.fr/cas/login?service=https%3A%2F%2Fent.univ-paris13.fr">ENT</a>
    </nav>
    <div class="search-bar">
      <form action="../controleurs/index.php" method="get">
        <input type="hidden" name="action" value="searchGame" />
        <input type="text" name="query" placeholder="Rechercher un jeu..." required />
        <button type="submit">🔍</button>
      </form>
    </div>
    <div class="profil-utilisateur" id="profilUtilisateur">
    <a class="username" style="color: white;">Bonjour <?php echo isset($_COOKIE['username']) ? htmlspecialchars($_COOKIE['username']) : 'Utilisateur'; ?></a>
      <img src="../img/profile.png" alt="Icône Profil" class="icone-utilisateur" onclick="basculerMenuDeroulant()" />
      <div class="menu-deroulant" id="menuDeroulant">
        <a href="../Vue/compte.php">Gestion du profil</a>
        
            <a href="../Vue/gestion.php">Gestion des utilisateurs et des jeux</a>
        <button class="bouton-deconnexion" onclick="window.location.href='../controleurs/deconnexion.php';">Déconnexion</button>
      </div>
    </div>
  </header>
  <?php if (isset($_COOKIE['role_id']) && $_COOKIE['role_id'] == 2): ?>
    <h1>Gestion des utilisateurs</h1>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Nom</th>
            <th>Rôle</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($users as $user): ?>
        <tr>
            <td><?= htmlspecialchars($user['id']) ?></td>
            <td><?= htmlspecialchars($user['nom']) ?></td>
            <td><?= htmlspecialchars($user['role_id']) ?></td>
            <td>
                <form method="post" style="display:inline;">
                    <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                    <select name="role_id">
                        <option value="1" <?= $user['role_id'] == 1 ? 'selected' : '' ?>>Utilisateur</option>
                        <option value="2" <?= $user['role_id'] == 2 ? 'selected' : '' ?>>Admin</option>
                        <option value="3" <?= $user['role_id'] == 3 ? 'selected' : '' ?>>Gestionnaire</option>
                    </select>
                    <button type="submit" name="update_user">Modifier</button>
                </form>
                <form method="post" style="display:inline;">
                    <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                    <button type="submit" name="delete_user">Supprimer</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
<?php endif;?>

    <h1>Gestion des jeux</h1>
    <table id="gamesTable" border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($games as $game): ?>
            <tr>
                <td><?= htmlspecialchars($game['id_jeu']) ?></td>
                <td><?= htmlspecialchars($game['titre']) ?></td>
                <td>
                    <form method="post" style="display:inline;">
                        <input type="hidden" name="game_id" value="<?= $game['id_jeu'] ?>">
                        <button type="submit" name="edit_game"
                        class="edit-game-button" 
                        data-id="<?= $game['id_jeu'] ?>" 
                        data-titre="<?= htmlspecialchars($game['titre']) ?>">Modifier</button>
                    </form>
                    <form method="post" style="display:inline;">
                        <input type="hidden" name="game_id" value="<?= $game['id_jeu'] ?>">
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <!-- Modal pour modifier un jeu -->
<div id="edit-game-modal" class="modal" style="display: none;">
    <div class="modal-content">
        <span id="close-modal" class="close">&times;</span>
        <h2>Modifier le jeu</h2>
        <form id="edit-game-form" method="post" action="../controleurs/update_game.php">
            <label for="edit-game-id">ID du jeu :</label>
            <input type="text" id="edit-game-id" name="game_id" readonly>
            <div class="form-group">
                <label for="edit-game-name">Nom du jeu :</label>
                <input type="text" id="edit-game-name" name="game_name" required>
            </div>
            <button type="submit" class="btn btn-primary">Enregistrer</button>
        </form>
    </div>
</div>


    <script>
        $(document).ready(function() {
            $('#gamesTable').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.13.6/i18n/French.json"
                },
                "pageLength": 10 // Nombre de lignes par page
                
            });
        });
        
        // Fonction pour basculer l'affichage du menu déroulant
    function basculerMenuDeroulant() {
        const menuDeroulant = document.getElementById('menuDeroulant');
        // Si le menu est affiché, le cacher, sinon l'afficher
        menuDeroulant.style.display = menuDeroulant.style.display === 'block' ? 'none' : 'block';
    }

    // Fermer le menu déroulant si l'utilisateur clique en dehors
    window.onclick = function(event) {
        // Vérifie si l'élément cliqué n'est pas l'icône de l'utilisateur
        if (!event.target.matches('.icone-utilisateur')) {
            const menusDeroulants = document.getElementsByClassName('menu-deroulant');
            // Parcourt tous les menus déroulants pour les cacher
            for (let i = 0; i < menusDeroulants.length; i++) {
                const menuOuvert = menusDeroulants[i];
                if (menuOuvert.style.display === 'block') {
                    menuOuvert.style.display = 'none';
                }
            }
        }
    }

    document.addEventListener("DOMContentLoaded", function() {
        // Sélectionnez tous les boutons "Modifier"
        let editButtons = document.querySelectorAll(".edit-game-button");
        let modal = document.getElementById("edit-game-modal");

        editButtons.forEach(button => {
            button.addEventListener("click", function(event) {
                // Empêche le rechargement de la page
                event.preventDefault();
                // Récupérez les données du jeu à partir des attributs du bouton
                document.getElementById("edit-game-id").value = this.getAttribute("data-id");
                document.getElementById("edit-game-name").value = this.getAttribute("data-titre");

                // Affichez le modal
                modal.style.display = "block";

                // Fermez le modal lorsque l'utilisateur clique sur le bouton de fermeture
                let closeModal = document.getElementById("close-modal");
                closeModal.addEventListener("click", function() {
                    modal.style.display = "none";
                });
            });
        });

        // Fermez le modal si l'utilisateur clique en dehors du contenu
        window.addEventListener("click", function(event) {
            if (event.target === modal) {
                modal.style.display = "none";
            }
        });
    });
    </script>

</body>
</html>