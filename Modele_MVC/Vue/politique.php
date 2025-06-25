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
    <title>Politique de Cookies - Sorbonne Paris Nord</title>
    <link rel="stylesheet" href="../Vue/accueil_styles.css">
    <link rel="stylesheet" href="politique_styles.css">
</head>
<body>
    <header>
        <a href="../Vue/accueil.php"><img src="../img/LogoUSPN.png" alt="Sorbonne Paris Nord"></a>
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
        <h1>Politique de Cookies</h1>

        <section>
            <h2>1. Qu'est-ce qu'un cookie ?</h2>
            <p>Un cookie est un petit fichier texte qui est placé sur votre appareil lorsque vous consultez un site Web. Il permet au site de se souvenir de vos actions et préférences (par exemple, votre identifiant ou la langue que vous avez choisie) pendant un certain temps, afin que vous n'ayez pas à les ressaisir chaque fois que vous revenez sur le site ou naviguez d'une page à l'autre.</p>
        </section>

        <section>
            <h2>2. Types de cookies utilisés</h2>
            <p>Nous utilisons différents types de cookies pour améliorer votre expérience de navigation sur notre site :</p>
            <ul>
                <li><strong>Cookies nécessaires :</strong> Ces cookies sont essentiels pour naviguer sur le site et utiliser ses fonctionnalités, telles que l'accès aux zones sécurisées.</li>
                <li><strong>Cookies de performance :</strong> Ces cookies collectent des informations sur la manière dont les visiteurs utilisent notre site, par exemple les pages les plus visitées. Cela nous aide à améliorer les performances du site.</li>
                <li><strong>Cookies fonctionnels :</strong> Ces cookies permettent de mémoriser vos préférences, comme la langue ou la région, pour offrir une expérience personnalisée.</li>
                <li><strong>Cookies de publicité :</strong> Ces cookies sont utilisés pour vous fournir des publicités pertinentes selon vos intérêts. Ils peuvent être utilisés pour limiter le nombre de fois où vous voyez une annonce et pour mesurer l'efficacité des campagnes publicitaires.</li>
            </ul>
        </section>

        <section>
            <h2>3. Gestion des cookies</h2>
            <p>Vous pouvez gérer vos préférences en matière de cookies en modifiant les paramètres de votre navigateur. La plupart des navigateurs permettent de bloquer ou de supprimer les cookies. Cependant, veuillez noter que certaines fonctionnalités de notre site peuvent ne pas fonctionner correctement si vous choisissez de bloquer les cookies.</p>
        </section>

        <section>
            <h2>4. Consentement</h2>
            <p>En utilisant notre site, vous consentez à l'utilisation de cookies comme décrit dans cette politique. Vous pouvez à tout moment modifier vos préférences en matière de cookies en accédant aux paramètres de votre navigateur.</p>
        </section>

        <section>
            <h2>5. Modifications de cette politique</h2>
            <p>Nous nous réservons le droit de modifier cette politique de cookies. Toute mise à jour sera publiée sur cette page avec une nouvelle date de révision. Nous vous encourageons à consulter régulièrement cette politique pour être informé de toute modification.</p>
        </section>

        <section>
            <h2>6. Contact</h2>
            <p>Pour toute question concernant notre politique de cookies ou pour exercer vos droits, veuillez nous contacter à :</p>
            <ul>
                <li><strong>Adresse e-mail :</strong> <a href="mailto:communication@sorbonne-paris-nord.fr">communication@sorbonne-paris-nord.fr</a></li>
                <li><strong>Adresse postale :</strong> 99 Avenue Jean-Baptiste Clément, 93430 Villetaneuse, France</li>
            </ul>
        </section>
    </main>

    <footer class="footer">
        <p>
            <a href="../Vue/mentions.php">Mentions légales</a> |
            <a href="../Vue/protection.php">Protection des données</a>
        </p>
    </footer>

    <script>
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
