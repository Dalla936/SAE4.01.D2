<?php
require_once '../modele/GameModel.php';

// Vérifier que l'utilisateur est connecté et est admin
session_start();

// Vérifier d'abord les sessions, puis les cookies en fallback
$roleId = null;
if (isset($_SESSION['role_id'])) {
    $roleId = $_SESSION['role_id'];
} elseif (isset($_COOKIE['role_id'])) {
    $roleId = $_COOKIE['role_id'];
}

if (!$roleId || $roleId != 2) {
    header("Location: ../Vue/accueil.php");
    exit;
}

$gameModel = new GameModel();
$message = "";
$error = "";

// Gestion de l'export uniquement
if (isset($_POST['export_db'])) {
    try {
        if (isset($_POST['export_type']) && $_POST['export_type'] === 'sql') {
            // Export via requêtes SQL
            $gameModel->exportDatabaseSQL();
        } else {
            // Export via pg_dump (par défaut)
            $gameModel->exportDatabase();
        }
    } catch (Exception $e) {
        $error = "Erreur lors de l'export : " . $e->getMessage();
    }
}

// Redirection vers la page de gestion avec message
$redirectUrl = "../Vue/gestion.php";
if (!empty($message)) {
    $redirectUrl .= "?success=" . urlencode($message);
} elseif (!empty($error)) {
    $redirectUrl .= "?error=" . urlencode($error);
}

header("Location: $redirectUrl");
exit;
?>
