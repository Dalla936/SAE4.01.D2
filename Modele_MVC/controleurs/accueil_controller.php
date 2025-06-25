<?php
header('Content-Type: application/javascript');

require_once '../modele/GameModel.php';

// Instancier le modèle
$gameModel = new GameModel();

// Récupérer les jeux les plus populaires
$popularGames = $gameModel->getMostPopularGames(3);

// Convertir les données en JSON pour les utiliser en JavaScript
echo "// Données des jeux populaires générées dynamiquement\n";
echo "const popularGames = " . json_encode($popularGames) . ";\n\n";
echo "// Fonction pour créer le podium des jeux populaires\n";
echo "document.addEventListener('DOMContentLoaded', function() {
    // Vérifier si l'élément pour le podium existe déjà
    let podiumContainer = document.getElementById('podium-container');
    
    // Si le podium n'existe pas encore, on le crée
    if (!podiumContainer) {
        // Créer le conteneur principal pour le podium
        const jeuxSection = document.querySelector('.jeux');
        const h2Element = jeuxSection.querySelector('h2');
          // Créer la div pour le podium
        const topJeuxDiv = document.createElement('div');
        topJeuxDiv.className = 'top-jeux';
        topJeuxDiv.innerHTML = '<h2>Top 3 des jeux les plus réservés</h2>';
        
        // Insérer le podium avant le titre 'Jeux Populaires'
        jeuxSection.insertBefore(topJeuxDiv, h2Element);
        
        // Créer le conteneur du podium
        podiumContainer = document.createElement('div');
        podiumContainer.id = 'podium-container';
        podiumContainer.className = 'podium-container';
        topJeuxDiv.appendChild(podiumContainer);
    }
      // Positions pour le podium
    const positions = [1, 2, 3];
    
    // Vider le podium avant de le remplir
    podiumContainer.innerHTML = '';
    
    // S'assurer qu'on a des jeux à afficher
    if (popularGames.length === 0) {
        const noGamesMsg = document.createElement('div');
        noGamesMsg.className = 'no-games-message';
        noGamesMsg.textContent = 'Aucun jeu réservé pour le moment';
        noGamesMsg.style.textAlign = 'center';
        noGamesMsg.style.padding = '20px';
        noGamesMsg.style.color = '#6c757d';
        podiumContainer.appendChild(noGamesMsg);
        return;
    }
    
    // Remplir le podium
    positions.forEach((position, index) => {        // Créer l'élément de place du podium
        const podiumPlace = document.createElement('div');
        podiumPlace.className = 'podium-place';
        
        // Bloc du podium pour la structure
        const podiumBlock = document.createElement('div');
        podiumBlock.className = 'podium-block';
        
        // Informations du jeu
        let gameTitle = 'Pas de données';
        let reservationCount = '0';
        
        // Si nous avons un jeu à cette position
        if (popularGames[index]) {
            const game = popularGames[index];
            gameTitle = game.titre;
            reservationCount = game.reservation_count;
        }
        
        // Créer un conteneur pour le numéro et le titre
        const infoContainer = document.createElement('div');
        infoContainer.className = 'info-container';
        
        // Numéro de place (avant le titre)
        const podiumNumber = document.createElement('div');
        podiumNumber.className = 'podium-number';
        podiumNumber.textContent = position;
        infoContainer.appendChild(podiumNumber);
        
        // Titre du jeu (après le numéro)
        const podiumTitle = document.createElement('div');
        podiumTitle.className = 'podium-title';
        
        if (popularGames[index]) {            // Lien vers la page de recherche du jeu avec le nom exact
            const titleLink = document.createElement('a');
            titleLink.href = '../controleurs/info.php?query=' + encodeURIComponent(gameTitle) + '&action=searchGame';
            titleLink.textContent = gameTitle;
            titleLink.style.color = '#333';
            titleLink.style.textDecoration = 'none';
            titleLink.style.fontWeight = 'bold';
            
            podiumTitle.appendChild(titleLink);
        } else {
            podiumTitle.textContent = gameTitle;
        }
        infoContainer.appendChild(podiumTitle);
          // Ajouter le conteneur d'infos avant le bloc du podium
        podiumPlace.appendChild(infoContainer);
        
        // Nombre de réservations
        const podiumReservations = document.createElement('div');
        podiumReservations.className = 'podium-reservations';
        podiumReservations.textContent = reservationCount + ' réservation' + (reservationCount > 1 ? 's' : '');
        
        // Si le jeu a des réservations, on ajoute un petit indicateur visuel pour montrer que c'est cliquable
        if (popularGames[index]) {
            const clickHint = document.createElement('div');
            clickHint.className = 'click-hint';
            clickHint.textContent = '(cliquez pour voir)';
            clickHint.style.fontSize = '0.75rem';
            clickHint.style.color = '#999';
            clickHint.style.marginTop = '2px';
            podiumReservations.appendChild(clickHint);
        }
        podiumPlace.appendChild(podiumReservations);
          // Marche du podium
        const podiumStep = document.createElement('div');
        podiumStep.className = 'podium-step';
        podiumStep.textContent = position === 1 ? 'OR' : (position === 2 ? 'ARGENT' : 'BRONZE');
        podiumBlock.appendChild(podiumStep);
        
        // Ajouter le bloc à la place (après le titre et le nombre de réservations)
        podiumPlace.appendChild(podiumBlock);
        
        // Ajouter la place au podium
        podiumContainer.appendChild(podiumPlace);
    });
});";
?>
