// BURGER
document.addEventListener("DOMContentLoaded", function () {
    const burger = document.querySelector('.burger');
    const menu = document.querySelector('.menu-mobile');
    if (burger && menu) {
        burger.addEventListener('click', function () {
            burger.classList.toggle('active');
            menu.classList.toggle('active');
        });
    }
});

jQuery(document).ready(function ($) {
    $('.open-contact-modal').on('click', function (e) {
        e.preventDefault();

        var ref = $(this).data('reference');
        $('#contact-modal').removeClass('hidden');

        var refField = $('#wpforms-18-field_4');
        if (refField.length) {
            refField.val(ref || '');
        }
    });

    $('#contact-modal').on('click', function (e) {
        if (e.target === this) {
            $(this).addClass('hidden');
        }
    });
});

// FILTRE ET TRI
document.addEventListener('DOMContentLoaded', () => {
    // 1. Gérer l'ouverture/fermeture des menus déroulants
    document.querySelectorAll('.custom-select .selected').forEach(select => {
        select.addEventListener('click', () => {
            const parent = select.parentElement;
            parent.classList.toggle('open');
            select.classList.toggle('active');
        });
    });

    // 2. Lorsqu'on clique sur une option dans une liste
    document.querySelectorAll('.custom-select .options-list li').forEach(option => {
        option.addEventListener('click', () => {
            const parent = option.closest('.custom-select');
            const selected = parent.querySelector('.selected');
            
            // Met à jour le texte et la valeur
            selected.textContent = option.textContent;
            selected.dataset.value = option.dataset.value; // ✅ Ajouté !
            
            // Ferme le menu déroulant
            selected.classList.remove('active');
            parent.classList.remove('open');

            // Envoie des filtres via JS/Ajax
            updateFilters(); // ✅ Appelle la fonction complète
        });
    });

    // 3. Envoie la requête Ajax à chaque changement de filtre
    function updateFilters() {
        const category = document.querySelector('[data-filter-type="category"] .selected').dataset.value || '';
        const format = document.querySelector('[data-filter-type="format"] .selected').dataset.value || '';
        const sort = document.querySelector('[data-filter-type="sort"] .selected').dataset.value || '';

        const data = {
            action: 'filter_photos', // ⚠️ Doit correspondre au nom du hook PHP
            category: category,
            format: format,
            sort: sort,
            security: loadMoreParams.nonce, // ✅ Généré dans functions.php
        };

        fetch(loadMoreParams.url, {
            method: 'POST',
            body: new URLSearchParams(data),
        })
        .then(response => response.text())
        .then(html => {
            const galleryGrid = document.querySelector('.gallery-grid');
            galleryGrid.innerHTML = html;
        })
        .catch(error => {
            console.error('Erreur lors du filtre des photos :', error);
        });
    }

    // 4. Ferme tous les menus si on clique ailleurs
    document.addEventListener('click', e => {
        document.querySelectorAll('.custom-select').forEach(select => {
            if (!select.contains(e.target)) {
                select.classList.remove('open');
                select.querySelector('.selected').classList.remove('active');
            }
        });
    });
});

document.dispatchEvent(new Event('lightboxReload'));




