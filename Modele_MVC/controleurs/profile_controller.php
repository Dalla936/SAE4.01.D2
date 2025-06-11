<?php
session_start();
require_once '../modele/GameModel.php';

// Initialisation du modèle
$model = new GameModel();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $numero = trim($_POST['numero']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);
    $userId = $_SESSION['user_id'];

    if (!empty($password) && $password !== $confirm_password) {
        echo "Les mots de passe ne correspondent pas.";
        exit;
    }

    $hashedPassword = !empty($password) ? password_hash($password, PASSWORD_DEFAULT) : null;

    $result = $model->updateUserProfile($userId, $username, $numero, $hashedPassword);
    echo $result;
}