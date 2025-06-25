<?php
session_start();

// Détruire toutes les variables de session
session_unset();

// Détruire la session
session_destroy();

// Supprimer les cookies associés (si vous utilisez des cookies pour stocker des informations utilisateur)
if (isset($_COOKIE['role_id'])) {
    setcookie('role_id', '', time() - 3600, '/'); // Expire immédiatement
}
if (isset($_COOKIE['username'])) {
    setcookie('username', '', time() - 3600, '/'); // Expire immédiatement
}
if (isset($_COOKIE['numero_etu'])) {
    setcookie('numero_etu', '', time() - 3600, '/'); // Expire immédiatement
}
// Rediriger vers la page de connexion ou d'accueil
header("Location: ../Vue/connexion.html");
exit();