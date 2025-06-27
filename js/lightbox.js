document.addEventListener('DOMContentLoaded', function () {
    // Si écran mobile (375px ou moins), on désactive complètement la lightbox
    if (window.innerWidth <= 375) return;

    const overlay = document.getElementById('lightbox-overlay');
    if (!overlay) return;

    // Récupération des éléments HTML à manipuler
    const imgEl = overlay.querySelector('.lightbox-image');
    const refEl = overlay.querySelector('.lightbox-reference');
    const catEl = overlay.querySelector('.lightbox-category');
    const closeBtn = overlay.querySelector('.lightbox-close');
    const prevBtn = overlay.querySelector('.lightbox-prev');
    const nextBtn = overlay.querySelector('.lightbox-next');

    let photoIds = []; // Tableau contenant les IDs des photos
    let currentIndex = -1; // Index de la photo actuellement affichée

    // Fonction d'ouverture de la lightbox à partir de l'index dans le tableau photoIds
    function openLightbox(index) {
        const photoId = photoIds[index];
        if (!photoId) return;

        currentIndex = index;

        // Récupère les données dynamiques de la photo (référence, catégorie, image)
        fetchPhotoData(photoId).then(data => {
            imgEl.src = data.url || '';
            refEl.textContent = data.reference || '';
            catEl.textContent = data.category || '';
            overlay.style.display = 'flex';
        }).catch(err => {
            console.error("Erreur récupération lightbox:", err);
        });
    }

    // Ferme la lightbox et réinitialise les champs
    function closeLightbox() {
        overlay.style.display = 'none';
        imgEl.src = '';
        refEl.textContent = '';
        catEl.textContent = '';
    }

    // Navigation vers la photo précédente
    function goToPrevious() {
        if (currentIndex > 0) openLightbox(currentIndex - 1);
    }

    // Navigation vers la photo suivante
    function goToNext() {
        if (currentIndex < photoIds.length - 1) openLightbox(currentIndex + 1);
    }

    // Récupère dynamiquement les données de la photo via une requête Ajax
    function fetchPhotoData(photoId) {
        return fetch(lightbox_ajax_object.ajax_url, {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: new URLSearchParams({
                action: 'get_photo_data',
                security: lightbox_ajax_object.security,
                photo_id: photoId
            })
        })
        .then(response => response.json())
        .then(data => {
            console.log('🧪 Données reçues pour la lightbox :', data);
            if (data.success) return data.data;
            throw new Error('Erreur serveur');
        });
    }

    // Attache les événements click aux icônes "plein écran"
    function bindLightboxEvents() {
        const buttons = document.querySelectorAll('.icon-fullscreen');
        photoIds = []; // Réinitialisation des IDs pour éviter les doublons

        buttons.forEach((btn) => {
            const article = btn.closest('article');
            const id = article?.dataset?.id;

            if (id && !photoIds.includes(parseInt(id))) {
                const photoId = parseInt(id);
                photoIds.push(photoId);

                // Lors du clic, on ouvre la lightbox avec la bonne photo
                btn.addEventListener('click', (e) => {
                    e.preventDefault();
                    const index = photoIds.indexOf(photoId);
                    openLightbox(index);
                });
            }
        });
    }

    // Fermeture de la lightbox (clic croix ou clic en dehors du contenu)
    if (closeBtn) closeBtn.addEventListener('click', closeLightbox);
    if (overlay) overlay.addEventListener('click', closeLightbox);
    const content = overlay.querySelector('.lightbox-content');
    if (content) content.addEventListener('click', e => e.stopPropagation()); // Pour ne pas fermer si on clique dans le contenu

    // Navigation avec les flèches
    if (prevBtn) prevBtn.addEventListener('click', goToPrevious);
    if (nextBtn) nextBtn.addEventListener('click', goToNext);

    // Initialisation des événements
    bindLightboxEvents();

    // Lorsqu’on recharge des images dynamiquement (ex. via "Charger plus"), on réattache les événements
    document.addEventListener('lightboxReload', bindLightboxEvents);
});
