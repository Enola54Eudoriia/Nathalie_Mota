<?php
// =========================
// Chargement des styles et scripts du thème
// =========================
function nathalie_mota_enqueue_assets()
{
    // Récupère la version du thème pour forcer le cache navigateur à se rafraîchir si besoin
    $theme_version = wp_get_theme()->get('Version');

    // Chargement du fichier CSS principal du thème
    wp_enqueue_style('theme-style', get_stylesheet_uri(), [], $theme_version);

    // Fonction interne pour charger FontAwesome (icônes)
    function enqueue_fontawesome()
    {
        wp_enqueue_style('fontawesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css');
    }
    add_action('wp_enqueue_scripts', 'enqueue_fontawesome');

    // Chargement de jQuery natif WordPress
    wp_enqueue_script('jquery');

    // Chargement de notre script JS principal, dépendant de jQuery, en footer (true)
    wp_enqueue_script('theme-scripts', get_template_directory_uri() . '/js/scripts.js', ['jquery'], null, true);
}
add_action('wp_enqueue_scripts', 'nathalie_mota_enqueue_assets');


// =========================
// Support de fonctionnalités WordPress
// =========================
add_theme_support('post-thumbnails'); // Support des images à la une

// Enregistrement du menu principal du thème
register_nav_menus([
    'main_menu' => 'Menu principal'
]);

// Support de la balise <title> dynamique
add_theme_support('title-tag');


// =========================
// Pagination infinie (infinite scroll)
// =========================
function enqueue_infinite_scroll_script()
{
    // Charge le script infinite-scroll.js en footer
    wp_enqueue_script('infinite-scroll', get_template_directory_uri() . '/js/infinite-scroll.js', array(), null, true);

    // Passe des variables JS utiles (url admin-ajax et nonce de sécurité)
    wp_localize_script('infinite-scroll', 'loadMoreParams', array(
        'url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('load_more_nonce'),
    ));
}
add_action('wp_enqueue_scripts', 'enqueue_infinite_scroll_script');

// Fonction AJAX qui récupère plus de photos en fonction de la page demandée
function load_more_photos()
{
    // Vérification du nonce de sécurité
    if (!isset($_POST['security']) || !wp_verify_nonce($_POST['security'], 'load_more_nonce')) {
        wp_send_json_error('Permission non accordée');
    }

    // Récupère la page demandée (défaut 1)
    $paged = isset($_POST['page']) ? intval($_POST['page']) : 1;

    // Requête WP_Query sur le post_type 'photo', 8 par page, page demandée
    $args = [
        'post_type' => 'photo',
        'posts_per_page' => 8,
        'paged' => $paged,
    ];

    $query = new WP_Query($args);

    // Mise en tampon pour récupérer le HTML des photos
    ob_start();

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            // Appelle le template part photo_block pour afficher chaque photo
            get_template_part('template_parts/photo_block');
        }
    }

    $html = ob_get_clean();
    wp_reset_postdata();

    // Indique s'il reste encore des pages à charger
    $has_more = $paged < $query->max_num_pages;

    // Renvoie les données JSON (succès)
    wp_send_json_success([
        'html' => $html,
        'has_more' => $has_more,
    ]);
}
// Hooks AJAX pour utilisateurs connectés et visiteurs
add_action('wp_ajax_load_more_photos', 'load_more_photos');
add_action('wp_ajax_nopriv_load_more_photos', 'load_more_photos');


// =========================
// Filtrage et tri des photos via AJAX
// =========================
function filter_photos_ajax()
{
    // Vérification du nonce pour la sécurité
    check_ajax_referer('load_more_nonce', 'security');

    $tax_query = [];

    // Filtrage par catégorie si définie
    if (!empty($_POST['category'])) {
        $tax_query[] = [
            'taxonomy' => 'categorie_photo',
            'field'    => 'slug',
            'terms'    => sanitize_text_field($_POST['category']),
        ];
    }

    // Filtrage par format si défini
    if (!empty($_POST['format'])) {
        $tax_query[] = [
            'taxonomy' => 'format_photo',
            'field'    => 'slug',
            'terms'    => sanitize_text_field($_POST['format']),
        ];
    }

    // Tri par date ascendant ou descendant
    $order = (!empty($_POST['sort']) && in_array($_POST['sort'], ['ASC', 'DESC'])) ? $_POST['sort'] : 'DESC';

    // Prépare la requête
    $args = [
        'post_type'      => 'photo',
        'posts_per_page' => 8,
        'orderby'        => 'date',
        'order'          => $order,
    ];

    if (!empty($tax_query)) {
        $args['tax_query'] = $tax_query;
    }

    $query = new WP_Query($args);

    // Affiche les résultats ou un message d'absence de photos
    if ($query->have_posts()) :
        while ($query->have_posts()) : $query->the_post();
            get_template_part('template_parts/photo_block');
        endwhile;
    else :
        echo '<p>Aucune photo ne correspond aux filtres.</p>';
    endif;

    wp_reset_postdata();
    wp_die(); // Termine proprement la requête AJAX
}
add_action('wp_ajax_filter_photos', 'filter_photos_ajax');
add_action('wp_ajax_nopriv_filter_photos', 'filter_photos_ajax');


// =========================
// Enqueue script lightbox + AJAX
// =========================
function enqueue_lightbox_script()
{
    // Charge le script JS lightbox
    wp_enqueue_script('lightbox', get_template_directory_uri() . '/js/lightbox.js', [], null, true);

    // Passe les variables AJAX nécessaires (url + nonce sécurité)
    wp_localize_script('lightbox', 'lightbox_ajax_object', [
        'ajax_url' => admin_url('admin-ajax.php'),
        'security' => wp_create_nonce('lightbox_nonce')
    ]);
}
add_action('wp_enqueue_scripts', 'enqueue_lightbox_script');


// Fonction AJAX pour récupérer les données d’une photo (lightbox)
function get_photo_data_callback()
{
    // Vérifie la sécurité
    check_ajax_referer('lightbox_nonce', 'security');

    $photo_id = intval($_POST['photo_id']);

    // Validation de l’ID et du type post
    if (!$photo_id || get_post_type($photo_id) !== 'photo') {
        wp_send_json_error('Photo non trouvée');
    }

    // URL de l’image en taille full
    $url = get_the_post_thumbnail_url($photo_id, 'full');

    // Récupère la référence via SCF, ACF ou postmeta
    $reference = function_exists('SCF::get')
        ? SCF::get('reference', $photo_id)
        : (function_exists('get_field')
            ? get_field('reference', $photo_id)
            : get_post_meta($photo_id, 'reference', true));

    // Récupère la catégorie (taxonomy 'categorie_photo')
    $terms = get_the_terms($photo_id, 'categorie_photo');
    $category = (!empty($terms) && !is_wp_error($terms)) ? $terms[0]->name : '';

    // Renvoie les données JSON à la lightbox
    wp_send_json_success([
        'id'        => $photo_id,
        'url'       => esc_url($url),
        'reference' => esc_html($reference),
        'category'  => esc_html($category),
    ]);
}
add_action('wp_ajax_get_photo_data', 'get_photo_data_callback');
add_action('wp_ajax_nopriv_get_photo_data', 'get_photo_data_callback');
