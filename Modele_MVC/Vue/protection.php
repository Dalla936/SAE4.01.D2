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
    <title>Politique de Protection des Données - Sorbonne Paris Nord</title>
    <link rel="stylesheet" href="../Vue/accueil_styles.css">
    <link rel="stylesheet" href="protection_styles.css">
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
        <h1>Protection des Données</h1>

        <section>
            <h2>1. Introduction</h2>
            <p>La protection de vos données personnelles est une priorité pour l'Université Sorbonne Paris Nord. Nous nous engageons à respecter la confidentialité et la sécurité de vos informations personnelles. Cette politique de protection des données décrit comment nous collectons, utilisons et protégeons vos données personnelles.</p>
        </section>

        <section>
            <h2>2. Informations collectées</h2>
            <p>Nous collectons plusieurs types de données pour vous offrir une expérience optimale sur notre site :</p>
            <ul>
                <li><strong>Données d'identification :</strong> Nous collectons des informations personnelles telles que votre nom, adresse e-mail, numéro de téléphone, etc., lors de votre inscription ou d'autres interactions avec nos services.</li>
                <li><strong>Données de navigation :</strong> Nous recueillons également des informations concernant vos interactions avec notre site, telles que votre adresse IP, votre type de navigateur, vos préférences de langue, etc.</li>
            </ul>
        </section>

        <section>
            <h2>3. Utilisation des données</h2>
            <p>Les données que nous collectons sont utilisées dans les buts suivants :</p>
            <ul>
                <li>Fournir les services demandés par les utilisateurs.</li>
                <li>Améliorer notre site et nos services en fonction des préférences et des besoins des utilisateurs.</li>
                <li>Respecter les obligations légales, notamment en matière de sécurité des données et de conformité aux réglementations sur la protection des données.</li>
            </ul>
        </section>

        <section>
            <h2>4. Partage des données</h2>
            <p>Les données personnelles que nous collectons ne seront pas partagées avec des tiers sans votre consentement, sauf dans les cas suivants :</p>
            <ul>
                <li>Lorsque la loi nous oblige à partager des informations dans le cadre de demandes légales ou judiciaires.</li>
                <li>Lorsque cela est nécessaire pour protéger les droits, la sécurité ou la propriété de l'Université Sorbonne Paris Nord ou de ses utilisateurs.</li>
            </ul>
        </section>

        <section>
            <h2>5. Sécurité des données</h2>
            <p>Nous mettons en place des mesures techniques et organisationnelles appropriées pour protéger vos données personnelles contre tout accès non autorisé, divulgation, altération ou destruction. Cependant, aucune méthode de transmission sur Internet ou de stockage électronique n'est totalement sécurisée, et nous ne pouvons garantir une sécurité absolue.</p>
        </section>

        <section>
            <h2>6. Vos droits</h2>
            <p>Conformément à la législation sur la protection des données, vous disposez des droits suivants concernant vos données personnelles :</p>
            <ul>
                <li><strong>Droit d'accès :</strong> Vous avez le droit d'accéder aux données personnelles que nous détenons à votre sujet.</li>
                <li><strong>Droit de rectification :</strong> Vous pouvez demander la correction de toute donnée incorrecte ou incomplète.</li>
                <li><strong>Droit de suppression :</strong> Vous pouvez demander la suppression de vos données personnelles dans certaines situations.</li>
                <li><strong>Droit à la portabilité :</strong> Vous avez le droit de recevoir vos données personnelles dans un format structuré, couramment utilisé, et de les transmettre à un autre responsable du traitement.</li>
            </ul>
            <p>Pour exercer ces droits, veuillez nous contacter à l'adresse indiquée ci-dessous.</p>
        </section>

        <section>
            <h2>7. Conservation des données</h2>
            <p>Nous conservons vos données personnelles aussi longtemps que nécessaire pour répondre aux finalités pour lesquelles elles ont été collectées, et ce, conformément à la législation en vigueur.</p>
        </section>

        <section>
            <h2>8. Modifications de cette politique</h2>
            <p>Nous nous réservons le droit de modifier cette politique de protection des données. Toute mise à jour sera publiée sur cette page avec une nouvelle date de révision. Nous vous encourageons à consulter régulièrement cette politique pour être informé de toute modification.</p>
        </section>

        <section>
            <h2>9. Contact</h2>
            <p>Pour toute question concernant la protection de vos données personnelles ou pour exercer vos droits, vous pouvez nous contacter à :</p>
            <ul>
                <li><strong>Adresse e-mail :</strong> <a href="mailto:communication@sorbonne-paris-nord.fr">communication@sorbonne-paris-nord.fr</a></li>
                <li><strong>Adresse postale :</strong> 99 Avenue Jean-Baptiste Clément, 93430 Villetaneuse, France</li>
            </ul>
        </section>
    </main>

    <footer class="footer">
        <p>
            <a href="../Vue/mentions.php">Mentions légales</a> |
            <a href="../Vue/politique.php">Politique de Cookies</a>
        </p>
    </footer>
    <script>
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
