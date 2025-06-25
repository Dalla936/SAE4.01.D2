<?php
// Vérifier si l'utilisateur est connecté
$isLoggedIn = isset($_COOKIE['username']);
$username = $isLoggedIn ? $_COOKIE['username'] : '';
$roleId = isset($_COOKIE['role_id']) ? $_COOKIE['role_id'] : 0;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Documentation - Sorbonne Paris Nord</title>
    <link rel="stylesheet" href="../Vue/documentation_style.css">
</head>
<body>
    <!-- Header -->
   <header>
    <a href="../Vue/accueil.php"><img src="../img/LogoUSPN.png" alt="Sorbonne Paris Nord" /></a>
    <nav>
      <a href="../Vue/documentation.html">Documentation</a>
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

  

    <!-- Main content -->
    <main>
        <section class="documentation">
            <h1>Documentation</h1>
            <p><strong>Explication :</strong></p>
            <p>Bienvenue sur notre site de réservation de jeux de société de l'Université Sorbonne Paris Nord. Ce service permet aux étudiants de découvrir et réserver une large collection de jeux. 
            Les réservations peuvent être faites directement en ligne ou sur place à la bibliothèque. Chaque réservation est valable pour une durée d'une semaine maximum. 
            Nous vous invitons à consulter les règles ci-dessous pour garantir une utilisation optimale du service. Respectez les conditions d'utilisation afin de profiter pleinement de l'expérience ludique proposée.</p>
            <h2>Règle du site</h2>
            <div class="rule-card"> <img src="../img/etudiant.jpg">Être étudiant</div>
            <div class="rule-card"> <img src="../img/card.jpg"> Avoir un numéro étudiant</div>
            <div class="rule-card"><img src="../img/calendrier.jpg">Réservation de 1 semaine maximum</div>
            <div class="rule-card"><img src="../img/réservation.gif">Une seule réservation autorisée à la fois</div>
            <div class="rule-card"><img src="../img/biblio.jpg">Venir chercher le jeu à la bibliothèque directement</div>
            <div class="rule-card"><img src="../img/dette.jpg"> Pas de dégradation du jeu sous peine de payer le montant du jeu</div>
            <div class="rule-card"><img src="../img/retard.jpg"> Rendre le jeu en retard ou ne pas le rendre peut engager des sanctions</div>
            <div class="rule-card"><img src="../img/pret.jpg"> Il est strictement interdit de prêter le jeu</div>
            <div class="rule-card"><img src="../img/order.jpeg"> Vous pouvez réserver sur place ou à distance sur le site</div>
            <div class="rule-card"><img src="../img/inspection.jpg">  Pour rendre le jeu, vous devez apporter votre carte étudiant et une vérification sera faite sur le jeu</div>
        </section>
    </main>

    <script>
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

    <!-- Footer -->
    <footer class="footer">
        <p>
            <a href="../Vue/mentions.php">Mentions légales</a> |
            <a href="../Vue/politique.php">Politique de cookies</a> |
            <a href="../Vue/protection.php">Protection de données</a>
        </p>
    </footer>
</body>
</html>
