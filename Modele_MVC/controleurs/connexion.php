<?php
require '../modele/GameModel.php';
$gamemodel = new GameModel();

session_start();

/**
 * Génère un token de sécurité aléatoire et le stocke dans la session
 * @param int $length Longueur du token (par défaut 32 caractères)
 * @return string Le token généré
 */
function generateSecurityToken($length = 32) {
    // Génération d'un token aléatoire en utilisant random_bytes (PHP 7+)
    if (function_exists('random_bytes')) {
        $token = bin2hex(random_bytes($length / 2));
    } 
    // Fallback sur openssl si random_bytes n'est pas disponible
    elseif (function_exists('openssl_random_pseudo_bytes')) {
        $token = bin2hex(openssl_random_pseudo_bytes($length / 2));
    } 
    // Méthode alternative si aucune des méthodes sécurisées n'est disponible
    else {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $token = '';
        for ($i = 0; $i < $length; $i++) {
            $token .= $characters[rand(0, strlen($characters) - 1)];
        }
    }
    
    // Stockage du token dans la session
    $_SESSION['token'] = $token;
    return $token;
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $numero_etu = trim($_POST['numero_etu']);
    $mot_de_passe = trim($_POST['mdp']);

    // Vérifier les informations de l'utilisateur
    $user = $gamemodel->getUserByNumeroEtu($numero_etu); // Adapte le nom de la méthode si besoin

    if ($user && $mot_de_passe === $user['mot_de_passe_nonh']) { // ou password_verify si hashé
        $_SESSION['numero_etu'] = $user['numero_etu'];
        $_SESSION['username'] = $user['nom'];
        $_SESSION['role_id'] = $user['role_id'];
        
        // Génération d'un token de sécurité lors de la connexion réussie
        $token = generateSecurityToken();
        $_SESSION['token'] = $token;
        echo "<script>console.log('Token de sécurité généré : $token');</script>";
        
        setcookie('numero_etu', $user['numero_etu'], time() + 7246060, "/");
        setcookie('username', $user['nom'], time() + 7246060, "/");
        setcookie('role_id', $user['role_id'], time() + 72460*60, "/");

        header("Location: ../Vue/accueil.html");
        exit;
    } else {
        header("Location: ../Vue/mdp_refus.html");
        exit;
    }
}
?>