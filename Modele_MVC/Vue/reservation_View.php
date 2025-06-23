<?php
require '../modele/GameModel.php'; // Inclure le modèle
session_start();
if(!isset($_SESSION['role_id']) ||($_SESSION['role_id'] != 2 && $_SESSION['role_id'] != 3 && $_SESSION['role_id'] != 1)) {
    header("Location: ../Vue/connexion.html");
    exit;
}
// Récupérer le nom du jeu depuis l'URL
$gameName = isset($_GET['game']) ? htmlspecialchars($_GET['game']) : '';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../Vue/reservation_styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.13/flatpickr.min.css">
    <title>Réservation - Sorbonne Paris Nord</title>
    
</head>
<body>

<!-- Header (avec le menu déroulant) -->
<header>
    <a href="accueil.html"><img src="../img/LogoUSPN.png" alt="Sorbonne Paris Nord"></a>
    <nav>
        <a href="documentation.html">Documentation</a>
        <a href="../controleurs/info.php">Collection</a>
        <a href="reservation_View.php">Réservation</a>
        <a href="https://cas.univ-paris13.fr/cas/login?service=https%3A%2F%2Fent.univ-paris13.fr">ENT</a>
    </nav>
    <div class="search-bar">
        <form action="../controleurs/index.php" method="get">
            <input type="hidden" name="action" value="searchGame">
            <input type="text" name="query" placeholder="Rechercher un jeu...">
            <button type="submit">🔍</button>
        </form>
    </div>
    <div class="zone-utilisateur">

        <a class="username" style="color: white;">Bonjour <?php echo isset($_COOKIE['username']) ? htmlspecialchars($_COOKIE['username']) : 'Utilisateur'; ?></a>


    <div class="profil-utilisateur" id="profilUtilisateur">
      <img src="../img/profile.png" alt="Icône Profil" class="icone-utilisateur" onclick="basculerMenuDeroulant()" />
      <div class="menu-deroulant" id="menuDeroulant">
        <a href="../Vue/compte.php">Gestion du profil</a>
        <?php if (isset($_COOKIE['role_id']) && ($_COOKIE['role_id'] == 2 || $_COOKIE['role_id'] == 3)): ?>
            <a href="../Vue/gestion.php">Gestion des utilisateurs et des jeux</a>
        <?php endif; ?>
    
        <button class="bouton-deconnexion" onclick="window.location.href='../controleurs/deconnexion.php';">Déconnexion</button>
      </div>
    </div>
    </div>

</header>

<!-- Main content -->
<main>
    <div class="reservation-form">
        <h2>Réservation</h2>
        <form action="../controleurs/index.php" method="post">
            <!-- Numéro étudiant -->
            <label for="nom">Nom</label>
            <input type="text" id="nom" name="nom" placeholder="Nom" value="<?php echo isset($_COOKIE['username']) ? htmlspecialchars($_COOKIE['username']) : ''; ?>"  readonly required>

            <label for="email">Email</label>
            <input type="text" id="email" name="email" placeholder="Email" required>

            <label for="adresse">Adresse</label>
            <input type="text" id="adresse" name="adresse" placeholder="Adresse" required>

            <!-- Sélection du jeu -->
            <label for="game-select">Jeux de société...</label>
            <input type="text" id="game-select" name="game-select" value="<?= $gameName ?>" placeholder="Jeux de société" required>

            <!-- Sélecteur de date -->
            <label for="date-range">Début et fin d'emprunt</label>
            <input type="text" id="date-range" name="date-range" placeholder="Sélectionner vos dates d'emprunt" required>

            <!-- Bouton de soumission -->
            <button type="submit" name="reserverGame">Réserver</button>
        </form>
    </div>
</main>

<!-- Footer -->
<footer class="footer">
    <p>
        <a href="mentions.html">Mentions légales</a> |
        <a href="politique.html">Politique de cookies</a> |
        <a href="protection.html">Protection de données</a>
    </p>
</footer>

<!-- Flatpickr JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.13/flatpickr.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/fr.js"></script>
<script>
    // Initialisation du calendrier avec localisation française
    flatpickr("#date-range", {
        mode: "range",
        dateFormat: "Y/m/d",
        locale: "fr",
        altInput: true,
        altFormat: "Y/m/j",
        weekNumbers: true,
        minDate: "today",
        onChange: function (Choixdate) {
            if (Choixdate.length === 2) {
                const Datedebut = Choixdate[0];
                const Datefin = Choixdate[1];
                const diff = Math.abs(Datefin - Datedebut);
                const JMax = Math.ceil(diff / (1000 * 60 * 60 * 24));

                if (JMax > 7) {
                    alert("La durée maximale d'emprunt est d'une semaine !");
                    this.clear();
                }
            }
        }
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
</script>

</body>
</html>