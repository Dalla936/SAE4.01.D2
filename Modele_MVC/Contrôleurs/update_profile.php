<?php
require_once '../Modèle/GameModel.php'; // Inclure le modèle

// Démarrer la session pour accéder à l'utilisateur connecté
session_start();

// Vérifier si un numéro étudiant a été saisi
if (!isset($_POST['numero']) || empty(trim($_POST['numero']))) {
    die("Le numéro étudiant est requis.");
}

// Récupérer les données du formulaire
$username = trim($_POST['username']);
$numeroEtu = trim($_POST['numero']);
$password = trim($_POST['password']);
$confirmPassword = trim($_POST['confirm_password']);

// Vérifier que les mots de passe correspondent
if ($password && $password !== $confirmPassword) {
    die("Les mots de passe ne correspondent pas.");
}

// Hacher le mot de passe si fourni
$hashedPassword = null;
if ($password) {
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
}

try {
    // Initialiser le modèle
    $gameModel = new GameModel();

    // Vérifier si le numéro étudiant existe dans la base de données
    $userId = $gameModel->getUserIdByNumeroEtu($numeroEtu); // Appeler la méthode pour obtenir l'ID utilisateur
    if (!$userId) { //fetch qui retourne false ou true si il a trouvé un userid
        die("Aucun utilisateur trouvé avec ce numéro étudiant.");
    }

    // Mettre à jour le mot de passe si un nouveau a été fourni
    if ($hashedPassword) {
        $result = $gameModel->updateUserProfile($userId, $username, $numeroEtu, $hashedPassword,$password);

        if ($result) {
            echo "Mot de passe mis à jour avec succès.";
        } else {
            echo "Erreur lors de la mise à jour du mot de passe.";
        }
    } else {
        echo "Aucun mot de passe fourni à mettre à jour.";
    }
} catch (Exception $e) {
    echo "Erreur : " . $e->getMessage();
}
?>