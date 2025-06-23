<?php
// On s'assure d'inclure le modèle pour avoir accès aux données
require_once '../modele/GameModel.php';
// Vérifier si l'utilisateur est connecté (si nécessaire)
$isLoggedIn = isset($_COOKIE['username']);
$username = $isLoggedIn ? $_COOKIE['username'] : '';
$roleId = isset($_COOKIE['role_id']) ? $_COOKIE['role_id'] : 0;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Accueil - Sorbonne Paris Nord</title>
  <link rel="stylesheet" href="../Vue/accueil_styles.css" />
  <link rel="stylesheet" href="../Vue/podium.css" />
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
      <form action="../controleurs/info.php" method="get">
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
        <?php if ($roleId == 2 || $roleId == 3): ?>
            <a href="../Vue/gestion.php">Gestion des utilisateurs et des jeux</a>
        <?php endif; ?>
        <?php if (!isset($_COOKIE['role_id'])): ?>
            <a href="../Vue/connexion.html"> Se connecter</a>
        <?php endif; ?>
        <button class="bouton-deconnexion" onclick="window.location.href='../controleurs/deconnexion.php';">Déconnexion</button>
      </div>
    </div>
  </header>

  <section class="top-section">
    <div class="top-gauche">
      <img src="../img/monopoly.jpg" alt="Monopoly">
    </div>
    <div class="top-droite">
      <h1>JEUX DE SOCIÉTÉ</h1>
      <p>Découvrez et empruntez des jeux</p>
      <a href="../controleurs/info.php" class="btn-orange">Découvrir les jeux</a>
    </div>
  </section>

  <section class="bot-section">
    <div class="jeux">
      <?php
      // Créer le podium directement en PHP plutôt qu'en JavaScript
      $gameModel = new GameModel();
      $popularGames = $gameModel->getMostPopularGames(3);
      
      // Section du podium
      echo '<div class="top-jeux">';
      echo '<h2>Top 3 des jeux les plus réservés</h2>';
      
      // Podium container
      echo '<div id="podium-container" class="podium-container">';
      
      if (empty($popularGames)) {
          echo '<div class="no-games-message" style="text-align: center; padding: 20px; color: #6c757d;">Aucun jeu réservé pour le moment</div>';
      } else {
          $positions = [1, 2, 3];
          
          foreach ($positions as $position => $rank) {
              echo '<div class="podium-place">';
              
              // Informations du jeu
              $gameTitle = 'Pas de données';
              $reservationCount = '0';
              
              if (isset($popularGames[$position])) {
                  $game = $popularGames[$position];
                  $gameTitle = $game['titre'];
                  $reservationCount = $game['reservation_count'];
              }
              
              // Conteneur d'info
              echo '<div class="info-container">';
              
              // Numéro de position
              echo '<div class="podium-number">' . $rank . '</div>';
                // Titre du jeu
              echo '<div class="podium-title">';              if (isset($popularGames[$position])) {
                  // Utiliser urlencode pour garantir que les caractères spéciaux sont correctement gérés
                  $encodedTitle = urlencode($gameTitle);
                  echo '<a href="../controleurs/info.php?action=searchGame&query=' . $encodedTitle . '" style="color: #333; text-decoration: none; font-weight: bold;">' . htmlspecialchars($gameTitle) . '</a>';
              } else {
                  echo htmlspecialchars($gameTitle);
              }
              echo '</div>'; // fin podium-title
              echo '</div>'; // fin info-container
              
              // Nombre de réservations
              echo '<div class="podium-reservations">';
              echo $reservationCount . ' réservation' . ($reservationCount > 1 ? 's' : '');
              
              if (isset($popularGames[$position])) {
                  echo '<div class="click-hint" style="font-size: 0.75rem; color: #999; margin-top: 2px;">(cliquez pour voir)</div>';
              }
              echo '</div>'; // fin podium-reservations
              
              // Marche du podium
              echo '<div class="podium-block">';
              echo '<div class="podium-step">' . ($rank === 1 ? 'OR' : ($rank === 2 ? 'ARGENT' : 'BRONZE')) . '</div>';
              echo '</div>'; // fin podium-block
              
              echo '</div>'; // fin podium-place
          }
      }
      
      echo '</div>'; // fin podium-container
      echo '</div>'; // fin top-jeux
      ?>
      
      <h2>Jeux Populaires : </h2>
      <div class="ligne-jeux">
        <div class="jeu-card">
          <img src="../img/uno.jpg" alt="Uno">
          <span>Uno</span>
        </div>
        <div class="jeu-card">
          <img src="../img/TimesUp.png" alt="Time's Up">
          <span>Time's Up</span>
        </div>
      </div>
    </div>

    <div class="actualites">
      <h2>Actualités : </h2>

      <div class="actu-block">
        <p>🃏 Grand Tournoi de Poker Universitaire 🃏</p>
        <p>📍 Bureau des étudiants, IUT Villetaneuse </p>
        <p>📅 6 février – 14h à 16h</p>
        <p>📢 Venez nombreux ! 📢</p>
      </div>

      <div class="section-articles">
        <div class="article">
          <div class="fond-article"></div>
          <div class="wrap-cat">
            <span class="theme" data-hover="Tournoi">Élections</span>
          </div>
          <h1>
            <span class="cat-title"><a href="https://etd.univ-spn.fr/2025/03/25/elections-cneser-usagers-du-lundi-2-au-vendredi-6-juin-2025/">Elections CNESER usagers : du lundi 2 au vendredi 6 juin 2025</a></span>
          </h1>
        </div>
        <div class="article">
          <div class="fond-article"></div>
          <div class="wrap-cat">
            <span class="theme" data-hover="Clubs">Culture</span>
          </div>
          <h1>
            <span class="cat-title"><a href="https://etd.univ-spn.fr/2025/01/08/ouverture-des-inscriptions-aux-ateliers-de-pratique-artistique/">Ouverture des inscriptions aux ateliers de pratique artistique !</a></span>
          </h1>
        </div>
      </div>
    </div>
  </section>

  <footer class="footer">
    <p>
      <a>© 2025 Sorbonne Paris Nord | Tous droits réservés.</a>
      <a href="../Vue/mentions.html">Mentions légales</a> |
      <a href="../Vue/politique.html">Politique de cookies</a> |
      <a href="../Vue/protection.html">Protection de données</a>
    </p>
  </footer>

  <script src="accueil.js"></script>
  <!-- Nous n'avons plus besoin du script accueil_controller.php car nous générons le podium directement en PHP -->
</body>
</html>
