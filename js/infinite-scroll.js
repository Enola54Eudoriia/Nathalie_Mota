document.addEventListener('DOMContentLoaded', function () {
    // Numéro de la page à charger (la page 1 est déjà affichée)
    let page = 2;

    const loadMoreButton = document.querySelector('.load-more-button');
    const galleryGrid = document.querySelector('.gallery-grid');

    // Vérifie que le bouton existe avant d'ajouter un écouteur
    if (loadMoreButton) {
        loadMoreButton.addEventListener('click', function () {
            loadMoreButton.classList.add('loading'); // Optionnel : pour montrer visuellement que ça charge
            fetchPosts(page);
        });
    }

    // Fonction pour récupérer les photos supplémentaires via AJAX
    function fetchPosts(pageNumber) {
        const data = {
            action: 'load_more_photos', // Action liée à notre fonction PHP
            page: pageNumber, // Numéro de page à charger
            security: loadMoreButton.dataset.nonce, // Nonce pour la sécurité WordPress
        };

        // Requête POST vers admin-ajax.php
        fetch(loadMoreButton.dataset.url, {
            method: 'POST',
            body: new URLSearchParams(data),
        })
            .then(response => response.json()) // Convertit la réponse JSON
            .then(result => {
                if (result.success) {
                    // Ajoute les nouvelles photos dans la grille
                    galleryGrid.insertAdjacentHTML('beforeend', result.data.html);

                    // Redéclenche l’event custom pour rebrancher la lightbox sur les nouvelles images
                    document.dispatchEvent(new Event('lightboxReload'));

                    page++; // Incrémente la page pour le prochain clic

                    // Si plus de photos à charger, on masque le bouton
                    if (!result.data.has_more) {
                        loadMoreButton.style.display = 'none';
                    }
                } else {
                    console.error('Erreur serveur :', result.data);
                }

                loadMoreButton.classList.remove('loading');
            })
            .catch(error => {
                console.error('Erreur AJAX :', error);
                loadMoreButton.classList.remove('loading');
            });
    }
});
