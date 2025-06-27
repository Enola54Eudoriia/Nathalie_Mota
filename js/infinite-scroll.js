document.addEventListener('DOMContentLoaded', function () {
    let page = 2;
    const loadMoreButton = document.querySelector('.load-more-button');
    const galleryGrid = document.querySelector('.gallery-grid');

    if (loadMoreButton) {
        loadMoreButton.addEventListener('click', function () {
            loadMoreButton.classList.add('loading');
            fetchPosts(page);
        });
    }

    function fetchPosts(pageNumber) {
        const data = {
            action: 'load_more_photos',
            page: pageNumber,
            security: loadMoreButton.dataset.nonce,
        };

        fetch(loadMoreButton.dataset.url, {
            method: 'POST',
            body: new URLSearchParams(data),
        })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    galleryGrid.insertAdjacentHTML('beforeend', result.data.html);
                    document.dispatchEvent(new Event('lightboxReload'));
                    page++;

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
