document.addEventListener('DOMContentLoaded', function () {
    // Gestion du premier bouton (btn-search)
    const searchButton = document.getElementById('btn-search');
    const closeButton = document.getElementById('btn-close');
    const searchBlock = document.getElementById('search-panel');

    // Fonction pour afficher/masquer le panneau de recherche
    function toggleSearchPanel() {
        searchBlock.classList.toggle('hidden');
    }

    // Vérification de la présence des éléments dans le DOM avant d'ajouter les événements
    if (searchButton && searchBlock && closeButton) {
        searchButton.addEventListener('click', toggleSearchPanel);    
        closeButton.addEventListener('click', toggleSearchPanel);  

        window.addEventListener('scroll', function() {
            if (window.scrollY > 0) {
                searchBlock.classList.add('hidden');
            }
        });

        window.addEventListener('click', (event) => {
            if (!searchBlock.contains(event.target) &&
                !searchButton.contains(event.target) && 
                !closeButton.contains(event.target)) {
                searchBlock.classList.add('hidden');
            }
        });
    } else {
        console.warn('Les éléments nécessaires pour le premier panneau de recherche ne sont pas présents dans le DOM.');
    }

    // Gestion du deuxième bouton (btn-search-objects)
    const searchButtonObject = document.getElementById('btn-search-objects');
    const closeButtonObject = document.getElementById('btn-close-objects');
    const searchBlockObject = document.getElementById('search-panel-objects');

    // Fonction pour afficher/masquer le panneau de recherche des objets
    function toggleSearchPanelObject() {
        searchBlockObject.classList.toggle('hidden');
    }

    // Vérification de la présence des éléments dans le DOM avant d'ajouter les événements
    if (searchButtonObject && searchBlockObject && closeButtonObject) {
        searchButtonObject.addEventListener('click', toggleSearchPanelObject);    
        closeButtonObject.addEventListener('click', toggleSearchPanelObject);

        window.addEventListener('scroll', function() {
            if (window.scrollY > 0) {
                searchBlockObject.classList.add('hidden');
            }
        });

        window.addEventListener('click', (event) => {
            if (!searchBlockObject.contains(event.target) &&
                !searchButtonObject.contains(event.target) && 
                !closeButtonObject.contains(event.target)) {
                searchBlockObject.classList.add('hidden');
            }
        });
    } else {
        console.warn('Les éléments nécessaires pour le panneau de recherche des objets ne sont pas présents dans le DOM.');
    }
});
