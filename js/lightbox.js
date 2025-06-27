document.addEventListener('DOMContentLoaded', function () {
    const overlay = document.getElementById('lightbox-overlay');
    if (!overlay) return;

    const imgEl = overlay.querySelector('.lightbox-image');
    const refEl = overlay.querySelector('.lightbox-reference');
    const catEl = overlay.querySelector('.lightbox-category');
    const closeBtn = overlay.querySelector('.lightbox-close');
    const prevBtn = overlay.querySelector('.lightbox-prev');
    const nextBtn = overlay.querySelector('.lightbox-next');

    let photoIds = [];
    let currentIndex = -1;

    function openLightbox(index) {
        const photoId = photoIds[index];
        if (!photoId) return;

        currentIndex = index;

        fetchPhotoData(photoId).then(data => {
            imgEl.src = data.url || '';
            refEl.textContent = data.reference || '';
            catEl.textContent = data.category || '';
            overlay.style.display = 'flex';
        }).catch(err => {
            console.error("Erreur récupération lightbox:", err);
        });
    }

    function closeLightbox() {
        overlay.style.display = 'none';
        imgEl.src = '';
        refEl.textContent = '';
        catEl.textContent = '';
    }

    function goToPrevious() {
        if (currentIndex > 0) openLightbox(currentIndex - 1);
    }

    function goToNext() {
        if (currentIndex < photoIds.length - 1) openLightbox(currentIndex + 1);
    }

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

    function bindLightboxEvents() {
        const buttons = document.querySelectorAll('.icon-fullscreen');
        photoIds = [];

        buttons.forEach((btn) => {
            const article = btn.closest('article');
            const id = article?.dataset?.id;

            if (id && !photoIds.includes(parseInt(id))) {
                const photoId = parseInt(id);
                photoIds.push(photoId);

                btn.addEventListener('click', (e) => {
                    e.preventDefault();
                    const index = photoIds.indexOf(photoId);
                    openLightbox(index);
                });
            }
        });
    }

    // Navigation et fermeture
    if (closeBtn) closeBtn.addEventListener('click', closeLightbox);
    if (overlay) overlay.addEventListener('click', closeLightbox);
    const content = overlay.querySelector('.lightbox-content');
    if (content) content.addEventListener('click', e => e.stopPropagation());
    if (prevBtn) prevBtn.addEventListener('click', goToPrevious);
    if (nextBtn) nextBtn.addEventListener('click', goToNext);

    // Initial bind + rebind après ajax load
    bindLightboxEvents();
    document.addEventListener('lightboxReload', bindLightboxEvents);
});
