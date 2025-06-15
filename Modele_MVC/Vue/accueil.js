// Fonction pour basculer l'affichage du menu déroulant
    function basculerMenuDeroulant() {
        const menuDeroulant = document.getElementById('menuDeroulant');
        // Si le menu est affiché, le cacher, sinon l'afficher
        menuDeroulant.style.display = menuDeroulant.style.display === 'block' ? 'none' : 'block';
    }

    // Fermer le menu déroulant si l'utilisateur clique en dehors
    window.onclick = function(event) {
        // Vérifie si l'élément cliqué n'est pas l'icône de l'utilisateur
        if (!event.target.matches('.icone-utilisateur')) {
            const menusDeroulants = document.getElementsByClassName('menu-deroulant');
            // Parcourt tous les menus déroulants pour les cacher
            for (let i = 0; i < menusDeroulants.length; i++) {
                const menuOuvert = menusDeroulants[i];
                if (menuOuvert.style.display === 'block') {
                    menuOuvert.style.display = 'none';
                }
            }
        }
    }