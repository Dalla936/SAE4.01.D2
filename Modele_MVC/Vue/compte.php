<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion de Compte - Université Sorbonne Paris Nord</title>
    <link rel="stylesheet" href="../Vue/compte.css">
</head>
<body>
    <div class="flou"></div>
    <header>
        <a href="../Vue/accueil.php"><img src="../img/LogoUSPN.png" alt="Sorbonne Paris Nord"></a>
        <nav>
            <a href="../Vue/documentation.html">Documentation</a>
            <a href="../controleurs/info.php ">Collection</a>
            <a href="../Vue/reservation_View.php">Réservation</a>

            <a href="https://cas.univ-paris13.fr/cas/login?service=https%3A%2F%2Fent.univ-paris13.fr">ENT</a>
        </nav>
        <div class="search-bar">
                <form action="../controleurs/index.php" method="get">
                <input type="hidden" name="action" value="searchGame">
                <input type="text" name="query" placeholder="Rechercher un jeu..." required>
                <button type="submit">🔍</button>
        </div>
        <div class="profil-utilisateur" id="profilUtilisateur">
        <a class="username" style="color: white;">Bonjour <?php echo isset($_COOKIE['username']) ? htmlspecialchars($_COOKIE['username']) : 'Utilisateur'; ?></a>

            <img src="../img/profile.png" alt="Icône Profil" class="icone-utilisateur" onclick="basculerMenuDeroulant()">

            <div class="menu-deroulant" id="menuDeroulant">
                <a href="compte.php">Gestion du profil</a>
                <?php if (isset($_COOKIE['role_id']) && ($_COOKIE['role_id'] == 2 || $_COOKIE['role_id'] == 3)): ?>
            <a href="../Vue/gestion.php">Gestion des utilisateurs et des jeux</a>
        <?php endif; ?>
        <button class="bouton-deconnexion" onclick="window.location.href='../controleurs/deconnexion.php';">Déconnexion</button>
            </div>
        </div>
    </header>

    <div class="container">
        <h1 style="color :white;">Gestion de Compte</h1>
        <section class="profil-section">
            <div class="photo-profil">
                <form action="" method="post" enctype="multipart/form-data" class="photo-profil">
                    <img src="../img/profile.png" alt="Photo de profil actuelle" class="photo-actuelle" id="photoPreview">
                    <label for="photo">Changer la photo de profil :</label>
                    <input type="file" id="photo" name="photo" accept="image/*" onchange="previewPhoto(event)">
                    <button type="submit" class="btn-submit">Mettre à jour la photo</button>
                </form>
                
                <form action="../controleurs/update_profile.php" method="post" class="form-gestion-compte">
                
                <label for="numero"> Numéro étudiant :</label>

                <input type="text" id="numero" name="numero" placeholder="Entrez votre numéro étudiant"
                value="<?php echo isset($_COOKIE['numero_etu']) ? htmlspecialchars($_COOKIE['numero_etu']) : ''; ?>" readonly required>

                    <label for="username"> Nom :</label>
                    <input type="text" id="username" name="username" placeholder="Entrez votre nom" value="<?php echo isset($_COOKIE['username']) ? htmlspecialchars($_COOKIE['username']) : ''; ?>" readonly required>
                
                    <label for="password">Mot de passe :</label>
                    <input type="password" id="password" name="password" placeholder="Entrez un nouveau mot de passe" required>
                
                    <label for="confirm_password">Confirmer le mot de passe :</label>
                    <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirmez votre mot de passe" required>
                
                    <button type="submit" class="btn-submit" href="../Vue/accueil.html">Mettre à jour</button>
                </form>
                
        </section>
    </div>

    <script>
        function basculerMenuDeroulant() {
            const menuDeroulant = document.getElementById('menuDeroulant');
            menuDeroulant.style.display = menuDeroulant.style.display === 'block' ? 'none' : 'block';
        }

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

<script>
    function previewPhoto(event) {
        const reader = new FileReader();
        const photoPreview = document.getElementById('photoPreview');

        reader.onload = function () {
            photoPreview.src = reader.result;
        };

        if (event.target.files[0]) {
            reader.readAsDataURL(event.target.files[0]);
        }
    }
</script>

</body>
</html>