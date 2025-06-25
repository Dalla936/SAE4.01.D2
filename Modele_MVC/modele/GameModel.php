<?php

class GameModel {
    private $connection;

    public function __construct() {
        require '../modele/nbpn.php';
        $this->connection = $connection;
        $this->connection->query("SET NAMES 'utf8'");
        $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); //error mode 
    }

    // Méthode pour récupérer tous les jeux
    public function getAllGames() {
        $sql = "
        SELECT 
            jeux.id_jeu,
            jeux.titre,
            jeux.date_parution_debut,
            jeux.date_parution_fin,
            jeux.information_date,
            jeux.version,
            COALESCE(jeux.nombre_de_joueurs, 'Nombre inconnu') AS nombre_de_joueurs,
            jeux.age_indique,
            COALESCE(jeux.mots_cles, 'Pas de mots clés') AS mots_cles,
            COALESCE(mecanisme.nom, 'Type inconnu') AS mecanisme,
            -- Récupérer les noms des auteurs associés au jeu
COALESCE(string_agg(DISTINCT COALESCE(auteur.nom, 'Auteur inconnu'), ', '), 'Aucun auteur disponible') AS auteurs,
            -- Récupérer les noms des éditeurs associés au jeu
            string_agg(DISTINCT COALESCE (editeur.nom, 'Editeur inconnu'),', ') AS editeurs
        FROM jeux
        -- Association avec les mécanismes
        LEFT JOIN mecanisme ON mecanisme.mecanisme_id = jeux.mecanisme_id
        -- Associations avec les auteurs via jeu_auteur
        LEFT JOIN jeuauteur ON jeuauteur.jeu_id = jeux.id_jeu
        LEFT JOIN auteur ON auteur.auteur_id = jeuauteur.auteur_id
        -- Associations avec les éditeurs via jeu_editeur
        LEFT JOIN jeuediteur ON jeuediteur.jeu_id = jeux.id_jeu
        LEFT JOIN editeur ON editeur.editeur_id = jeuediteur.editeur_id
        GROUP BY 
            jeux.id_jeu, jeux.titre, jeux.date_parution_debut, jeux.date_parution_fin,
            jeux.information_date, jeux.version, jeux.nombre_de_joueurs, jeux.age_indique, 
            jeux.mots_cles, mecanisme.nom
    ";
    return $this->connection->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    // Méthode pour rechercher un jeu par nom
   // Méthode pour rechercher un jeu par nom  : On aura donc comme résultat un  tableau décerner a un jeux ou il y aura toute les infos (i=>1,... )
   public function getGameByName($name) {
    $sql = "SELECT 
                jeux.id_jeu, 
                jeux.titre, 
                jeux.date_parution_debut, 
                jeux.date_parution_fin, 
                jeux.information_date, 
                jeux.version, 
                COALESCE(jeux.nombre_de_joueurs, 'Nombre de joueurs inconnu') AS nombre_de_joueurs,
                jeux.age_indique, 
                jeux.mots_cles, 
                COALESCE(mecanisme.nom, 'Type inconnu') AS mecanisme,
                string_agg(DISTINCT auteur.nom, ', ') AS auteurs,
                string_agg(DISTINCT editeur.nom, ', ') AS editeurs
            FROM jeux
            LEFT JOIN mecanisme ON mecanisme.mecanisme_id = jeux.mecanisme_id
            LEFT JOIN jeuauteur ON jeuauteur.jeu_id = jeux.id_jeu
            LEFT JOIN auteur ON auteur.auteur_id = jeuauteur.auteur_id
            LEFT JOIN jeuediteur ON jeuediteur.jeu_id = jeux.id_jeu
            LEFT JOIN editeur ON editeur.editeur_id = jeuediteur.editeur_id
            WHERE jeux.titre ILIKE :name
            GROUP BY jeux.id_jeu, jeux.titre, jeux.date_parution_debut, jeux.date_parution_fin, 
                     jeux.information_date, jeux.version, jeux.nombre_de_joueurs, 
                     jeux.age_indique, jeux.mots_cles, mecanisme.nom";    $stmt = $this->connection->prepare($sql);
    $searchTerm = '%' . $name . '%'; // Rechercher les jeux qui contiennent $name (plus flexible)
    $stmt->bindParam(':name', $searchTerm);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC); // Récupérer tous les jeux correspondant de la bd  grace au fetchAll

}
public function CreerUtilisateur($nom, $prenom,$numero_etu,$mot_de_passe,$mot_de_passe_final,$mot_de_passe_hache, $regex){
    
        $role_id =1;
        // Préparer et exécuter la requête d'insertion
        if(preg_match($regex,$mot_de_passe_final) && $mot_de_passe_final == $mot_de_passe){
        $stmt = $this->connection->prepare('INSERT INTO utilisateurs (numero_etu ,nom, prenom, mot_de_passe,mot_de_passe_nonh,role_id) VALUES (:numero_etu, :nom ,:prenom, :mot_de_passe,:mot_de_passe_nonh,:role_id)');
        $stmt->execute([
            ':numero_etu'=>$numero_etu,
            ':nom' => $nom,
            ':prenom'=> $prenom,
            ':mot_de_passe' => $mot_de_passe_hache,
            ':mot_de_passe_nonh'=> $mot_de_passe,
            ':role_id'=>$role_id       ]);
        
    }
    else{
        echo "insérer un mot de passe d'une longueur de 8 avec une majuscule au moins un chiffre et un caractère spécial";
    }
    }
    public function getOrCreateEmprunteur($nom, $email, $adresse) {
        // Vérifier si l'emprunteur existe déjà
        $sqlCheck = "SELECT emprunteur_id FROM Emprunteur WHERE nom = :nom AND email = :email";
        $stmtCheck = $this->connection->prepare($sqlCheck);
        $stmtCheck->execute([
            ':nom' => $nom,
            ':email' => $email
        ]);
        $emprunteur = $stmtCheck->fetch(PDO::FETCH_ASSOC);

        if ($emprunteur) {
            // Si l'emprunteur existe, retourner son ID
            return $emprunteur['emprunteur_id'];
        }

        // Sinon, insérer un nouvel emprunteur
        $sqlInsert = "INSERT INTO Emprunteur (nom, email, adresse) VALUES (:nom, :email, :adresse)";
        $stmtInsert = $this->connection->prepare($sqlInsert);
        $stmtInsert->execute([
            ':nom' => $nom,
            ':email' => $email,
            ':adresse' => $adresse
        ]);

        // Retourner l'ID du nouvel emprunteur
        return $this->connection->lastInsertId();
    }
    public function createPret($boiteId, $emprunteurId, $startDate, $endDate) {
        $db = $this->connection;
        $sql = "INSERT INTO pret (boite_id, emprunteur_id, date_emprunt, date_retour)
                VALUES (:boiteId, :emprunteurId, :startDate, :endDate)";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':boiteId', $boiteId);
        $stmt->bindParam(':emprunteurId', $emprunteurId);
        $stmt->bindParam(':startDate', $startDate);
        $stmt->bindParam(':endDate', $endDate);
        $stmt->execute();
    }
    public function getBoiteIdByGameId($jeuId) {
    $sql = "SELECT boite_id FROM boites WHERE jeu_id = :jeuId LIMIT 1";
    $stmt = $this->connection->prepare($sql);
    $stmt->bindParam(':jeuId', $jeuId, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC); // Retourne un tableau associatif contenant boite_id
}

public function connecterUtilisateur($identifiant, $mot_de_passe) {
    try {
        $stmt = $this->connection->prepare('SELECT numero_etu, mot_de_passe_nonh FROM utilisateurs WHERE numero_etu = :numero_etu');
        $stmt->execute([':numero_etu' => $identifiant]);
        
        $utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($utilisateur) {
            if ($mot_de_passe === $utilisateur['mot_de_passe_nonh']) {
                return true; // Connexion réussie
            } else {
                throw new Exception("Le mot de passe est incorrect.");
            }
        } else {
            throw new Exception("Aucun utilisateur trouvé avec cet identifiant.");
        }
    } catch (PDOException $e) {
        throw new Exception("Erreur SQL : " . $e->getMessage());
    }
}
    
public function updateUserProfile($userId,$regex,$hashedPassword = null, $password = null)
{
    try {
        // Vérifier si le mot de passe suit la regex
        if (!preg_match($regex, $password)) {
            throw new Exception("Le mot de passe doit contenir au moins 8 caractères, une majuscule, un chiffre et un caractère spécial.");
        }

        // Construire la requête de mise à jour
        $query = "UPDATE utilisateurs SET mot_de_passe_nonh = :password, mot_de_passe = :password_h WHERE id = :user_id";

        // Préparer la requête
        $stmt = $this->connection->prepare($query);

        // Lier les paramètres
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':password_h', $hashedPassword);
        $stmt->bindParam(':user_id', $userId);

        // Exécuter la requête
        $stmt->execute();

        return "Mot de passe mis à jour avec succès.";
    } catch (PDOException $e) {
        // Gérer les erreurs
        return "Erreur lors de la mise à jour du mot de passe : " . $e->getMessage();
    } catch (Exception $e) {
        // Gérer les erreurs de validation
        return $e->getMessage();
    }
}

public function updateUserRole($userId, $newRoleId)
{
    $sql = "UPDATE utilisateurs SET role_id = :role_id WHERE id_utilisateur = :user_id";
    $stmt = $this->connection->prepare($sql);
    $stmt->execute([
        'role_id' => $newRoleId,
        'user_id' => $userId
    ]);
}
public function getRoleIdByName($roleName)
{
    $sql = "SELECT role_id FROM roles WHERE role_name = :role_name";
    $stmt = $this->connection->prepare($sql);
    $stmt->execute(['role_name' => $roleName]);
    return $stmt->fetchColumn();
}
public function getUserById($userId)
{
    $sql = "
        SELECT utilisateurs.id, utilisateurs.nom, roles.role_name
        FROM utilisateurs
        LEFT JOIN roles ON utilisateurs.role_id = roles.role_id
        WHERE utilisateurs.id = :user_id
    ";
    $stmt = $this->connection->prepare($sql);
    $stmt->execute(['user_id' => $userId]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
    
}
public function getUserIdByNumeroEtu($numeroEtu) {
    $query = "SELECT id FROM utilisateurs WHERE numero_etu = :numeroEtu";
    $stmt = $this->connection->prepare($query);
    $stmt->bindParam(':numeroEtu', $numeroEtu, PDO::PARAM_STR);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    return $result ? $result['id'] : null;
}
public function getGamesOrderedByDate($order) {
    // Validation de la direction pour éviter des injections SQL
    if (!in_array($order, ['ASC', 'DESC'], true)) {
        throw new InvalidArgumentException('La direction de tri doit être ASC ou DESC.');
    }

    // Construction dynamique de la requête SQL
    $sql = "
        SELECT 
            jeux.id_jeu,
            jeux.titre,
            jeux.date_parution_debut,
            jeux.date_parution_fin,
            jeux.information_date,
            jeux.version,
            COALESCE(jeux.nombre_de_joueurs, 'Nombre inconnu') AS nombre_de_joueurs,
            jeux.age_indique,
            jeux.mots_cles,
            COALESCE(mecanisme.nom, 'Type inconnu') AS mecanisme,
            -- Récupérer les noms des auteurs associés au jeu
            string_agg(DISTINCT COALESCE (auteur.nom, 'Auteur inconnu'),', ') AS auteurs,
            -- Récupérer les noms des éditeurs associés au jeu
            string_agg(DISTINCT COALESCE (editeur.nom, 'Editeur inconnu'),', ') AS editeurs
        FROM jeux
        -- Association avec les mécanismes
        LEFT JOIN mecanisme ON mecanisme.mecanisme_id = jeux.mecanisme_id
        -- Associations avec les auteurs via jeu_auteur
        LEFT JOIN jeuauteur ON jeuauteur.jeu_id = jeux.id_jeu
        LEFT JOIN auteur ON auteur.auteur_id = jeuauteur.auteur_id
        -- Associations avec les éditeurs via jeu_editeur
        LEFT JOIN jeuediteur ON jeuediteur.jeu_id = jeux.id_jeu
        LEFT JOIN editeur ON editeur.editeur_id = jeuediteur.editeur_id
        WHERE jeux.date_parution_debut IS NOT NULL
        AND jeux.date_parution_debut <> 'inconnue'
        
        GROUP BY 
            jeux.id_jeu, jeux.titre, jeux.date_parution_debut, jeux.date_parution_fin,
            jeux.information_date, jeux.version, jeux.nombre_de_joueurs, jeux.age_indique, 
            jeux.mots_cles, mecanisme.nom
            ORDER BY date_parution_debut $order
    ";
    $stmt = $this->connection->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function addGames($titreJeu, $dateParutionDebut, $dateParutionFin, $Nbjoueurs, $Age) {
    // Validation des entrées
    if (empty($titreJeu) || empty($dateParutionDebut) || empty($dateParutionFin) || empty($Nbjoueurs) || empty($Age)) {
        return "Erreur : Tous les champs sont obligatoires.";
    }

    // Tentative d'insertion dans la base de données
    try {
        $query = "INSERT INTO Jeux (titre,date_parution_debut, date_parution_fin, nombre_de_joueurs, age_indique) 
                  VALUES (:titreJeu, :dateParutionDebut, :dateParutionFin, :Nbjoueurs, :Age)";

        $stmt = $this->connection->prepare($query);
        $stmt->bindParam(':titreJeu', $titreJeu);
        $stmt->bindParam(':dateParutionDebut', $dateParutionDebut);
        $stmt->bindParam(':dateParutionFin', $dateParutionFin);
        $stmt->bindParam(':Nbjoueurs', $Nbjoueurs);
        $stmt->bindParam(':Age', $Age);

        $stmt->execute();
        return "Jeu ajouté avec succès.";
    } catch (PDOException $e) {
        return "Erreur : " . $e->getMessage();
    }

 }
 public function getUserByNumeroEtu($numero_etu) {
    $sql = "SELECT * FROM utilisateurs WHERE numero_etu = :numero_etu";
    $stmt = $this->connection->prepare($sql);
    $stmt->execute([':numero_etu' => $numero_etu]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}
    public function getGamesByPage($offset, $limit) {
    $sql = "SELECT * FROM jeux LIMIT :limit OFFSET :offset";
    $stmt = $this->connection->prepare($sql);
    $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}



public static function getGamesPaginated($connection, $offset, $limit, $order = 'ASC') {
    // Sécurisation de l'ordre (éviter injection SQL)
    $order = strtoupper($order) === 'DESC' ? 'DESC' : 'ASC';

    $sql = "
        SELECT 
    j.id_jeu,
    j.titre,
    j.date_parution_debut,
    j.date_parution_fin,
    j.information_date,
    j.version,
    COALESCE(j.nombre_de_joueurs::text, 'Nombre inconnu') AS nombre_de_joueurs,
    j.age_indique,
    j.mots_cles,
    COALESCE(m.nom, 'Type inconnu') AS mecanisme,
    STRING_AGG(DISTINCT COALESCE(a.nom, 'Auteur inconnu'), ', ') AS auteurs,
    STRING_AGG(DISTINCT COALESCE(e.nom, 'Editeur inconnu'), ', ') AS editeurs
FROM jeux j
LEFT JOIN mecanisme m ON m.mecanisme_id = j.mecanisme_id
LEFT JOIN jeuauteur ja ON ja.jeu_id = j.id_jeu
LEFT JOIN auteur a ON a.auteur_id = ja.auteur_id
LEFT JOIN jeuediteur je ON je.jeu_id = j.id_jeu
LEFT JOIN editeur e ON e.editeur_id = je.editeur_id
WHERE j.date_parution_debut IS NOT NULL
AND j.date_parution_debut <> 'inconnue'
GROUP BY 
    j.id_jeu, j.titre, j.date_parution_debut, j.date_parution_fin,
    j.information_date, j.version, j.nombre_de_joueurs, j.age_indique, 
    j.mots_cles, m.nom
ORDER BY j.date_parution_debut $order
LIMIT :limit OFFSET :offset
";

    $requete = $connection->prepare($sql);
    $requete->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
    $requete->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
    $requete->execute();

    return $requete->fetchAll(PDO::FETCH_ASSOC);
}


public static function countGames($connection) {
    $requete = $connection->prepare("SELECT COUNT(*) AS total
FROM (
    SELECT j.id_jeu
    FROM jeux j
    WHERE j.date_parution_debut IS NOT NULL
    AND j.date_parution_debut <> 'inconnue'
) AS subquery
");
    $requete->execute();
    return $requete->fetchColumn();
    return (int)$result['total'];

}
public static function getGamesByNamePaginated($connection, $query, $offset, $limit) {
    $sql = "
        SELECT 
            j.id_jeu,
            j.titre,
            j.date_parution_debut,
            j.date_parution_fin,
            j.information_date,
            j.version,
            COALESCE(j.nombre_de_joueurs::text, 'Nombre inconnu') AS nombre_de_joueurs,
            j.age_indique,
            j.mots_cles,
            COALESCE(m.nom, 'Type inconnu') AS mecanisme,
            STRING_AGG(DISTINCT COALESCE(a.nom, 'Auteur inconnu'), ', ') AS auteurs,
            STRING_AGG(DISTINCT COALESCE(e.nom, 'Editeur inconnu'), ', ') AS editeurs
        FROM jeux j
        LEFT JOIN mecanisme m ON m.mecanisme_id = j.mecanisme_id
        LEFT JOIN jeuauteur ja ON ja.jeu_id = j.id_jeu
        LEFT JOIN auteur a ON a.auteur_id = ja.auteur_id
        LEFT JOIN jeuediteur je ON je.jeu_id = j.id_jeu
        LEFT JOIN editeur e ON e.editeur_id = je.editeur_id
        WHERE j.titre ILIKE :query
        AND j.date_parution_debut IS NOT NULL
        AND j.date_parution_debut <> 'inconnue'
        GROUP BY 
            j.id_jeu, j.titre, j.date_parution_debut, j.date_parution_fin,
            j.information_date, j.version, j.nombre_de_joueurs, j.age_indique, 
            j.mots_cles, m.nom
        ORDER BY j.date_parution_debut ASC
        LIMIT :limit OFFSET :offset
    ";

    $requete = $connection->prepare($sql);
    $requete->bindValue(':query', "%$query%", PDO::PARAM_STR);
    $requete->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
    $requete->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
    $requete->execute();

    return $requete->fetchAll(PDO::FETCH_ASSOC);
}
public function getAllUsers() {
    $sql = "SELECT * FROM utilisateurs";
    $stmt = $this->connection->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
    
}   
public function updateUserRoleId($userId, $roleId) {
    try {
        $sql = "UPDATE utilisateurs SET role_id = :role_id WHERE id = :user_id";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam(':role_id', $roleId, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        return "Rôle mis à jour avec succès.";
    } catch (PDOException $e) {
        return "Erreur lors de la mise à jour du rôle : " . $e->getMessage();
    }


}
public function updateGameTitle($gameId, $newTitle) {
    try {
        $sql = "UPDATE jeux SET titre = :newTitle WHERE id_jeu = :gameId";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam(':newTitle', $newTitle, PDO::PARAM_STR);
        $stmt->bindParam(':gameId', $gameId, PDO::PARAM_INT);
        $stmt->execute();
        return "Titre du jeu mis à jour avec succès.";
    } catch (PDOException $e) {
        return "Erreur lors de la mise à jour du titre du jeu : " . $e->getMessage();
    }
}

public function deleteUser($userId) {
    try {
        // Préparer la requête pour supprimer l'utilisateur
        $query = "DELETE FROM utilisateurs WHERE id = :id";
        $stmt = $this->connection->prepare($query);
        $stmt->bindValue(':id', $userId, PDO::PARAM_INT);

        // Exécuter la requête
        $stmt->execute();
    } catch (PDOException $e) {
        // Gérer les erreurs
        throw new Exception("Erreur lors de la suppression de l'utilisateur : " . $e->getMessage());
    }
}

public function isGameAvailable($boiteId, $dateEmprunt, $dateRetour) {
    $query = "SELECT COUNT(*) FROM pret 
              WHERE boite_id = :boite_id 
              AND (
                  (date_emprunt <= :date_retour AND date_retour >= :date_emprunt)
              )";
    $stmt = $this->connection->prepare($query);
    $stmt->bindValue(':boite_id', $boiteId, PDO::PARAM_INT);
    $stmt->bindValue(':date_emprunt', $dateEmprunt, PDO::PARAM_STR);
    $stmt->bindValue(':date_retour', $dateRetour, PDO::PARAM_STR);
    $stmt->execute();
    return $stmt->fetchColumn() == 0; // Retourne true si la boite est disponible
}

public function getGameReservationCount($jeuId) {
    try {
        // Récupère d'abord les boîtes correspondant à ce jeu
        $query = "SELECT boite_id FROM boites WHERE jeu_id = :jeu_id";
        $stmt = $this->connection->prepare($query);
        $stmt->bindValue(':jeu_id', $jeuId, PDO::PARAM_INT);
        $stmt->execute();
        $boites = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        if (empty($boites)) {
            return 0; // Pas de boîte pour ce jeu, donc pas de réservation
        }
          // Compte le nombre total de réservations pour ces boîtes (historique complet)
        $placeholders = implode(',', array_fill(0, count($boites), '?'));
        $query = "SELECT COUNT(*) FROM pret 
                 WHERE boite_id IN ($placeholders)";
        $stmt = $this->connection->prepare($query);
        
        // Bind des valeurs
        foreach ($boites as $index => $boiteId) {
            $stmt->bindValue($index + 1, $boiteId, PDO::PARAM_INT);
        }
        
        $stmt->execute();        return $stmt->fetchColumn(); // Retourne le nombre de réservations
    } catch (PDOException $e) {
        // Gérer l'erreur
        return 0;
    }
}

/**
 * Retourne les jeux les plus populaires basés sur le nombre total de réservations (historique complet)
 * Cette méthode est utilisée pour générer le podium des jeux populaires
 */
public function getMostPopularGames($limit = 3) {
    try {
        // Récupérer tous les jeux avec leur nombre total de réservations (historique complet)
        $query = "
            SELECT 
                j.id_jeu, 
                j.titre, 
                COUNT(p.pret_id) AS reservation_count
            FROM 
                jeux j
                JOIN boites b ON j.id_jeu = b.jeu_id
                LEFT JOIN pret p ON b.boite_id = p.boite_id
            GROUP BY 
                j.id_jeu, j.titre
            ORDER BY 
                reservation_count DESC
            LIMIT :limit";
        
        $stmt = $this->connection->prepare($query);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        // Gérer l'erreur
        return [];
    }
}
public static function countGamesByName($connection, $query) {
    $sql = "
        SELECT COUNT(*) FROM (
            SELECT 
                j.id_jeu
            FROM jeux j
            LEFT JOIN mecanisme m ON m.mecanisme_id = j.mecanisme_id
            LEFT JOIN jeuauteur ja ON ja.jeu_id = j.id_jeu
            LEFT JOIN auteur a ON a.auteur_id = ja.auteur_id
            LEFT JOIN jeuediteur je ON je.jeu_id = j.id_jeu
            LEFT JOIN editeur e ON e.editeur_id = je.editeur_id
            WHERE j.titre ILIKE :query
            AND j.date_parution_debut IS NOT NULL
            AND j.date_parution_debut <> 'inconnue'
            GROUP BY 
                j.id_jeu
        ) AS subquery
    ";

    $requete = $connection->prepare($sql);
    $requete->bindValue(':query', "%$query%", PDO::PARAM_STR);
    $requete->execute();
    return $requete->fetchColumn();
}

/**
 * Vérifie si l'utilisateur actuel est administrateur
 */
private function isAdmin() {
    // Vérifier d'abord les sessions, puis les cookies en fallback
    if (isset($_SESSION['role_id'])) {
        return $_SESSION['role_id'] == 2;
    }
    return isset($_COOKIE['role_id']) && $_COOKIE['role_id'] == 2;
}

/**
 * Exporte la base de données PostgreSQL (admin seulement)
 */
public function exportDatabase() {
    if (!$this->isAdmin()) {
        throw new Exception("Accès refusé - droits administrateur requis");
    }
    
    // Paramètres de connexion depuis nbpn.php
    $dbHost = 'localhost';
    $dbName = 'site_jeux';
    $dbUser = 'postgres';
    $dbPassword = 'postgres';
    
    $filename = 'backup_site_jeux_' . date('Y-m-d_H-i-s') . '.sql';
    
    // Créer un fichier temporaire
    $tempFile = tempnam(sys_get_temp_dir(), 'pg_dump_');
    
    // Commande pg_dump adaptée à Windows
    $command = "pg_dump -h $dbHost -U $dbUser -d $dbName > \"$tempFile\" 2>&1";
    
    // Définir le mot de passe via variable d'environnement
    putenv("PGPASSWORD=$dbPassword");
    
    exec($command, $output, $return_code);
    
    if ($return_code === 0 && file_exists($tempFile) && filesize($tempFile) > 0) {
        // Forcer le téléchargement
        header('Content-Type: application/sql');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Content-Length: ' . filesize($tempFile));
        readfile($tempFile);
        unlink($tempFile); // Supprimer le fichier temporaire
        exit;
    } else {
        // Nettoyer le fichier temporaire en cas d'erreur
        if (file_exists($tempFile)) {
            unlink($tempFile);
        }
        throw new Exception("Erreur lors de l'export de la base de données. Code retour: $return_code. Output: " . implode("\n", $output));
    }
}

/**
 * Exporte les données via requêtes SQL (alternative si pg_dump n'est pas disponible)
 */
public function exportDatabaseSQL() {
    if (!$this->isAdmin()) {
        throw new Exception("Accès refusé - droits administrateur requis");
    }
    
    $tables = ['utilisateurs', 'jeux', 'auteur', 'editeur', 'mecanisme', 'boites', 'emprunteur', 'pret', 'jeuauteur', 'jeuediteur'];
    $sql_content = "-- Export de la base de données site_jeux (PostgreSQL)\n";
    $sql_content .= "-- Date: " . date('Y-m-d H:i:s') . "\n\n";
    $sql_content .= "-- Désactiver les contraintes temporairement\n";
    $sql_content .= "SET session_replication_role = replica;\n\n";
    
    try {
        foreach ($tables as $table) {
            $sql_content .= "-- Table: \"$table\"\n";
            
            // Récupérer les métadonnées des colonnes pour les types
            $metaStmt = $this->connection->query("
                SELECT column_name, data_type, udt_name 
                FROM information_schema.columns 
                WHERE table_name = '$table' AND table_schema = 'public'
                ORDER BY ordinal_position
            ");
            $columnsMeta = $metaStmt->fetchAll(PDO::FETCH_ASSOC);
            $columnsTypes = [];
            foreach ($columnsMeta as $meta) {
                $columnsTypes[$meta['column_name']] = $meta['data_type'];
            }
            
            $stmt = $this->connection->query("SELECT * FROM \"$table\"");
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            if (!empty($rows)) {
                // Supprimer les données existantes
                $sql_content .= "DELETE FROM \"$table\";\n";
                
                foreach ($rows as $row) {
                    $columns = array_map(function($col) { return "\"$col\""; }, array_keys($row));
                    $columns_str = implode(', ', $columns);
                    
                    $values = [];
                    foreach ($row as $columnName => $value) {
                        if ($value === null) {
                            $values[] = 'NULL';
                        } elseif (is_bool($value)) {
                            $values[] = $value ? 'TRUE' : 'FALSE';
                        } elseif (isset($columnsTypes[$columnName])) {
                            $type = $columnsTypes[$columnName];
                            // Types numériques PostgreSQL
                            if (in_array($type, ['integer', 'bigint', 'smallint', 'numeric', 'decimal', 'real', 'double precision'])) {
                                $values[] = is_numeric($value) ? $value : 'NULL';
                            } else {
                                // Type texte
                                $values[] = "'" . str_replace("'", "''", $value) . "'";
                            }
                        } else {
                            // Par défaut, traiter comme string
                            $values[] = "'" . str_replace("'", "''", $value) . "'";
                        }
                    }
                    $values_str = implode(', ', $values);
                    
                    $sql_content .= "INSERT INTO \"$table\" ($columns_str) VALUES ($values_str);\n";
                }
            }
            $sql_content .= "\n";
        }
        
        // Réactiver les contraintes et remettre à jour les séquences
        $sql_content .= "-- Réactiver les contraintes\n";
        $sql_content .= "SET session_replication_role = DEFAULT;\n\n";
        
        // Remettre à jour les séquences PostgreSQL
        $sql_content .= "-- Mise à jour des séquences\n";
        $sequences_query = "SELECT schemaname, sequencename, last_value FROM pg_sequences WHERE schemaname = 'public'";
        $sequences_stmt = $this->connection->query($sequences_query);
        $sequences = $sequences_stmt->fetchAll(PDO::FETCH_ASSOC);
        
        foreach ($sequences as $seq) {
            $sql_content .= "SELECT setval('\"" . $seq['sequencename'] . "\"', " . ($seq['last_value'] ?: 1) . ", true);\n";
        }
        
        $filename = 'backup_site_jeux_' . date('Y-m-d_H-i-s') . '.sql';
        
        // Envoyer le fichier en téléchargement
        header('Content-Type: application/sql');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Content-Length: ' . strlen($sql_content));
        echo $sql_content;
        exit;
        
    } catch (Exception $e) {
        throw new Exception("Erreur lors de l'export SQL: " . $e->getMessage());
    }
}
}

?>
