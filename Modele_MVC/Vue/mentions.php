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
    <title>Mentions légales - Sorbonne Paris Nord</title>
    <link rel="stylesheet" href="mentions_styles.css">
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

    <main class="main">
        <h1>Mentions légales</h1>
        <section>
            <h2>1. Éditeur du site</h2>
            <p>Le site <strong>Sorbonne Paris Nord</strong> est édité par :</p>
            <ul>
                <li><strong>Nom de l'établissement :</strong> Sorbonne Paris Nord</li>
                <li><strong>Statut juridique :</strong> Établissement public à caractère scientifique, culturel et professionnel (EPSCP)</li>
                <li><strong>Adresse :</strong> 99 Avenue Jean-Baptiste Clément, 93430 Villetaneuse, France</li>
            </ul>
        </section>

        <section>
            <h2>2. Hébergeur</h2>
            <p>Le site est hébergé par Hostinger:</p>
            <ul>
                <li><strong>Site internet :</strong> <a href="https://www.hostinger.com" target="_blank">https://www.hostinger.com</a></li>
            </ul>
        </section>

        <section>
            <h2>3. Propriété intellectuelle</h2>
            <p>Le contenu (textes, images, graphismes, logos, vidéos, etc.) présent sur le site de <strong>l'Université Sorbonne Paris Nord</strong> est la propriété exclusive de l'Université ou de ses partenaires. Toute reproduction, distribution, modification ou adaptation est interdite sans autorisation écrite préalable.</p>
        </section>

        <section>
            <h2>4. Limitation de responsabilité</h2>
            <p>Sorbonne Paris Nord s'efforce de fournir des informations exactes et à jour. Toutefois, l'Université ne peut garantir l'exhaustivité ou l'absence d'erreurs. Elle décline toute responsabilité en cas de dommages résultant de l'utilisation du site.</p>
        </section>

        <section>
            <h2>5. Liens hypertextes</h2>
            <p>Le site peut contenir des liens vers des sites externes. Sorbonne Paris Nord ne peut être tenue responsable du contenu de ces sites ni de leurs pratiques en matière de protection des données.</p>
        </section>

        <section>
            <h2>6. Contact</h2>
            <p>Pour toute question ou réclamation, vous pouvez contacter le service communication de la Sorbonne Paris Nord</p>
            <ul>
                <li><strong>Adresse e-mail :</strong> <a href="mailto:communication@sorbonne-paris-nord.fr">communication@sorbonne-paris-nord.fr</a></li>
                <li><strong>Adresse postale :</strong> 99 Avenue Jean-Baptiste Clément, 93430 Villetaneuse, France</li>
            </ul>
        </section>
    </main>

    <footer class="footer">
        <p>
            <a href="../Vue/politique.php">Politique de cookies</a> |
            <a href="../Vue/protection.php">Protection des données</a>
        </p>
    </footer>
    <script>
        // Ouvrir et fermer le pop-up de filtre
        function ouvrirFiltre() {
            document.getElementById('popupFiltre').style.display = 'block';
        }
        function fermerFiltre() {
            document.getElementById('popupFiltre').style.display = 'none';
        }

        // Ouvrir et fermer le pop-up pour ajouter un jeu
        function ouvrirAjoutJeu() {
            document.getElementById('popupAjoutJeu').style.display = 'block';
        }
        function fermerAjoutJeu() {
            document.getElementById('popupAjoutJeu').style.display = 'none';
        }

        // Basculer l'affichage du menu déroulant
        function basculerMenuDeroulant() {
            const menuDeroulant = document.getElementById('menuDeroulant');
            menuDeroulant.style.display = menuDeroulant.style.display === 'block' ? 'none' : 'block';
        }

        // Fermer le menu déroulant si clic en dehors
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
