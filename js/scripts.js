// Gestion du menu burger en mode mobile
document.addEventListener("DOMContentLoaded", function () {
    const burger = document.querySelector('.burger'); // bouton burger
    const menu = document.querySelector('.menu-mobile'); // menu mobile
    if (burger && menu) {
        // Au clic sur le burger, toggle la classe "active" pour ouvrir/fermer le menu
        burger.addEventListener('click', function () {
            burger.classList.toggle('active');
            menu.classList.toggle('active');
        });
    }
});

// Gestion de la modale de contact avec jQuery
jQuery(document).ready(function ($) {
    // Ouverture de la modale au clic sur un bouton ayant la classe 'open-contact-modal'
    $('.open-contact-modal').on('click', function (e) {
        e.preventDefault();

        var ref = $(this).data('reference'); // récupère la référence liée au bouton
        $('#contact-modal').removeClass('hidden'); // affiche la modale

        var refField = $('#wpforms-18-field_4'); // champ du formulaire pour insérer la référence
        if (refField.length) {
            refField.val(ref || ''); // insère la référence ou une chaîne vide si pas de donnée
        }
    });

    // Ferme la modale si clic à l'extérieur du contenu (sur le fond)
    $('#contact-modal').on('click', function (e) {
        if (e.target === this) {
            $(this).addClass('hidden');
        }
    });
});

// FILTRE ET TRI DES PHOTOS
document.addEventListener('DOMContentLoaded', () => {
    // Ouvre/ferme la liste d'options des filtres personnalisés
    document.querySelectorAll('.custom-select .selected').forEach(select => {
        select.addEventListener('click', () => {
            const parent = select.parentElement;
            parent.classList.toggle('open'); // ouvre/ferme le dropdown
            select.classList.toggle('active'); // active l'effet visuel
        });
    });

    // Au clic sur une option, met à jour le filtre affiché, ferme la liste et déclenche la mise à jour
    document.querySelectorAll('.custom-select .options-list li').forEach(option => {
        option.addEventListener('click', () => {
            const parent = option.closest('.custom-select');
            const selected = parent.querySelector('.selected');

            // Mise à jour du texte et de la valeur sélectionnée
            selected.textContent = option.textContent;
            selected.dataset.value = option.dataset.value;

            // Fermeture du dropdown
            selected.classList.remove('active');
            parent.classList.remove('open');

            // Appel à la fonction de mise à jour des filtres
            updateFilters();
        });
    });

    // Fonction qui envoie les filtres au serveur via Ajax pour filtrer les photos
    function updateFilters() {
        // Récupère les valeurs des filtres sélectionnés
        const category = document.querySelector('[data-filter-type="category"] .selected').dataset.value || '';
        const format = document.querySelector('[data-filter-type="format"] .selected').dataset.value || '';
        const sort = document.querySelector('[data-filter-type="sort"] .selected').dataset.value || '';

        // Prépare les données à envoyer
        const data = {
            action: 'filter_photos', 
            category: category,
            format: format,
            sort: sort,
            security: loadMoreParams.nonce, // nonce pour la sécurité WordPress
        };

        // Envoi via fetch POST à admin-ajax.php
        fetch(loadMoreParams.url, {
            method: 'POST',
            body: new URLSearchParams(data),
        })
        .then(response => response.text())
        .then(html => {
            // Remplace le contenu de la grille photo avec le HTML reçu
            const galleryGrid = document.querySelector('.gallery-grid');
            galleryGrid.innerHTML = html;
        })
        .catch(error => {
            console.error('Erreur lors du filtre des photos :', error);
        });
    }

    // Ferme les dropdowns si on clique en dehors d'eux
    document.addEventListener('click', e => {
        document.querySelectorAll('.custom-select').forEach(select => {
            if (!select.contains(e.target)) {
                select.classList.remove('open');
                select.querySelector('.selected').classList.remove('active');
            }
        });
    });
});

// Envoie un événement personnalisé pour recharger la lightbox après modifications du DOM
document.dispatchEvent(new Event('lightboxReload'));
