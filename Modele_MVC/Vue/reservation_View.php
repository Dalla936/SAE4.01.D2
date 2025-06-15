<?php
// Récupérer le nom du jeu depuis l'URL
$gameName = isset($_GET['game']) ? htmlspecialchars($_GET['game']) : '';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="reservation_styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.13/flatpickr.min.css">
    <title>Réservation - Sorbonne Paris Nord</title>
    <style>
        /* Styles pour le menu déroulant */
        .dropdown {
            position: relative;
            display: inline-block;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            right: 0;
            background-color: #fff;
            min-width: 160px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            z-index: 1;
        }

        .dropdown-content a, .dropdown-content button {
            color: black;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
            background: none;
            border: none;
            text-align: left;
            width: 100%;
            cursor: pointer;
        }

        .dropdown-content a:hover,
        .dropdown-content button:hover {
            background-color: #f1f1f1;
        }

        .dropdown:hover .dropdown-content {
            display: block;
        }

        .dropdown img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            cursor: pointer;
            border: 2px solid #ddd;
        }
    </style>
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
    <div class="dropdown">
        <img src="../img/profile.png" alt="Icône Profil">
        <div class="dropdown-content">
            <a href="compte.php">Gestion du compte</a>
            <button class="bouton-deconnexion">Déconnexion</button>
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
            <input type="text" id="nom" name="nom" placeholder="Nom" required>

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
</script>

</body>
</html>