<?php

require '../modele/GameModel.php';
$gamemodel = new GameModel();

session_start();
// Vérification des sessions ET des cookies pour plus de sécurité
if(!isset($_SESSION['role_id']) ||($_SESSION['role_id'] != 2 && $_SESSION['role_id'] != 3) ||
   !isset($_COOKIE['role_id']) || ($_COOKIE['role_id'] != 2 && $_COOKIE['role_id'] != 3)) {
    header("Location: ../Vue/connexion.html");
    exit;
}

// Messages de succès/erreur pour export
$successMessage = isset($_GET['success']) ? $_GET['success'] : '';
$errorMessage = isset($_GET['error']) ? $_GET['error'] : '';

// Vérifier si l'utilisateur est connecté
$isLoggedIn = isset($_COOKIE['username']);
$username = $isLoggedIn ? $_COOKIE['username'] : '';
$roleId = isset($_COOKIE['role_id']) ? $_COOKIE['role_id'] : 0;

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
    <link rel="stylesheet" href="../Vue/accueil_styles.css">



</head>
<body>
<header>
    <a href="../Vue/accueil.php"><img src="../img/LogoUSPN.png" alt="Sorbonne Paris Nord" /></a>
    <nav>
        <a href="../Vue/documentation.php">Documentation</a>
        <a href="../controleurs/info.php">Collection</a>
        <a href="../Vue/reservation_View.php">Réservation</a>
        <a href="https://cas.univ-paris13.fr/cas/login?service=https%3A%2F%2Fent.univ-paris13.fr">ENT</a>
    </nav>
    <div class="search-bar">
        <form action="../controleurs/info.php" method="get">
            <input type="hidden" name="action" value="searchGame" />
            <input type="text" name="query" placeholder="Rechercher un jeu..." required />
            <button type="submit">🔍</button>
        </form>
    </div>
    <div class="zone-utilisateur">
        <a class="username" style="color: white;">Bonjour <?php echo isset($_COOKIE['username']) ? htmlspecialchars($_COOKIE['username']) : 'Utilisateur'; ?></a>
        <div class="profil-utilisateur" id="profilUtilisateur">
            <img src="../img/profile.png" alt="Icône Profil" class="icone-utilisateur" onclick="basculerMenuDeroulant()" />
            <div class="menu-deroulant" id="menuDeroulant">
                <?php if (isset($_COOKIE['username'])): ?>
                    <a href="../Vue/compte.php">Gestion du profil</a>
                <?php endif; ?>
                <?php if ($roleId == 2 || $roleId == 3): ?>
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

  <!-- Messages de succès/erreur -->
  <?php if (!empty($successMessage)): ?>
    <div class="alert alert-success" style="background-color: #d4edda; color: #155724; padding: 15px; margin: 20px; border: 1px solid #c3e6cb; border-radius: 5px;">
        ✅ <?= htmlspecialchars($successMessage) ?>
    </div>
  <?php endif; ?>
  
  <?php if (!empty($errorMessage)): ?>
    <div class="alert alert-error" style="background-color: #f8d7da; color: #721c24; padding: 15px; margin: 20px; border: 1px solid #f5c6cb; border-radius: 5px;">
        ❌ <?= htmlspecialchars($errorMessage) ?>
    </div>
  <?php endif; ?>

  <!-- Section Administrateur (role_id = 2) -->
  <?php 
  $isAdmin = false;
  
  if (isset($_SESSION['role_id']) && $_SESSION['role_id'] == 2) {
      $isAdmin = true;
  } elseif (isset($_COOKIE['role_id']) && $_COOKIE['role_id'] == 2) {
      $isAdmin = true;
  }
  ?>
  
  <?php if ($isAdmin): ?>
    <div class="admin-section" style="background-color: #f8f9fa; padding: 20px; margin: 20px; border-radius: 10px; border: 2px solid #007bff;">
        <h2 style="color: #007bff; margin-bottom: 20px;">🔧 Administration de la base de données</h2>
        
        <div style="display: flex; gap: 20px; flex-wrap: wrap;">
            <!-- Export de la base -->
            <div class="export-section" style="flex: 1; min-width: 400px; background: white; padding: 20px; border-radius: 5px; border: 1px solid #dee2e6;">
                <h3 style="color: #28a745; margin-bottom: 15px;">📥 Exporter la base de données</h3>
                <p style="color: #6c757d; margin-bottom: 15px;">Téléchargez une sauvegarde complète de la base de données PostgreSQL.</p>
                
                <form action="../controleurs/database_manager.php" method="post" style="margin-bottom: 10px;">
                    <div style="margin-bottom: 15px;">
                        <label style="display: block; margin-bottom: 8px; font-weight: bold;">
                            <input type="radio" name="export_type" value="pg_dump" checked style="margin-right: 8px;">
                            🔧 Export via pg_dump (recommandé)
                        </label>
                        <small style="display: block; margin-left: 25px; color: #6c757d; margin-bottom: 10px;">
                            Utilise l'outil officiel PostgreSQL pour un export complet et optimisé
                        </small>
                        
                        <label style="display: block;">
                            <input type="radio" name="export_type" value="sql" style="margin-right: 8px;">
                            � Export via requêtes SQL (alternatif)
                        </label>
                        <small style="display: block; margin-left: 25px; color: #6c757d;">
                            Export personnalisé via requêtes SQL si pg_dump n'est pas disponible
                        </small>
                    </div>
                    <button type="submit" name="export_db" class="btn-export" style="background-color: #28a745; color: white; padding: 12px 25px; border: none; border-radius: 5px; cursor: pointer; font-weight: bold; font-size: 16px; width: 100%;">
                        📥 Télécharger la sauvegarde
                    </button>
                </form>
                <div style="background-color: #f8f9fa; padding: 10px; border-radius: 3px; margin-top: 15px;">
                    <small style="color: #495057;">
                        <strong>Format:</strong> Fichier .sql compatible PostgreSQL<br>
                        <strong>Contenu:</strong> Structure + données + séquences
                    </small>
                </div>
            </div>
        </div>
        
        <div style="margin-top: 20px; padding: 20px; background-color: #e3f2fd; border: 1px solid #bbdefb; border-radius: 5px;">
            <strong>ℹ️ Informations sur l'export :</strong>
            <ul style="margin: 10px 0; padding-left: 20px;">
                <li><strong>Export complet :</strong> Toutes les tables, données, contraintes et séquences</li>
                <li><strong>Compatibilité :</strong> Fichier SQL strictement compatible PostgreSQL</li>
                <li><strong>Sécurité :</strong> Accessible uniquement aux administrateurs</li>
                <li><strong>Format :</strong> backup_site_jeux_YYYY-MM-DD_HH-MM-SS.sql</li>
                <li><strong>Fallback automatique :</strong> Si pg_dump échoue, utilisation de l'export SQL alternatif</li>
            </ul>
        </div>
    </div>
  <?php endif; ?>

  <!-- Section Gestionnaire/Admin (role_id = 2 ou 3) -->
  <?php 
  $canManageUsers = false;
  if (isset($_SESSION['role_id']) && ($_SESSION['role_id'] == 2 || $_SESSION['role_id'] == 3)) {
      $canManageUsers = true;
  } elseif (isset($_COOKIE['role_id']) && ($_COOKIE['role_id'] == 2 || $_COOKIE['role_id'] == 3)) {
      $canManageUsers = true;
  }
  
  if ($canManageUsers): 
  ?>
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