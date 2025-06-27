<?php
// Chargement CSS/JS
function nathalie_mota_enqueue_assets()
{
    $theme_version = wp_get_theme()->get('Version');
    wp_enqueue_style('theme-style', get_stylesheet_uri(), [], $theme_version);

    function enqueue_fontawesome()
    {
        wp_enqueue_style('fontawesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css');
    }
    add_action('wp_enqueue_scripts', 'enqueue_fontawesome');

    // On charge jQuery via WordPress
    wp_enqueue_script('jquery');

    // Ton script qui dépend de jQuery (le tableau ['jquery'])
    wp_enqueue_script('theme-scripts', get_template_directory_uri() . '/js/scripts.js', ['jquery'], null, true);
}
add_action('wp_enqueue_scripts', 'nathalie_mota_enqueue_assets');


// Support d’images
add_theme_support('post-thumbnails');

// Menus
register_nav_menus([
    'main_menu' => 'Menu principal'
]);

add_theme_support('title-tag');

// Pagination infinie 
function enqueue_infinite_scroll_script()
{
    wp_enqueue_script('infinite-scroll', get_template_directory_uri() . '/js/infinite-scroll.js', array(), null, true);

    wp_localize_script('infinite-scroll', 'loadMoreParams', array(
        'url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('load_more_nonce'),
    ));
}
add_action('wp_enqueue_scripts', 'enqueue_infinite_scroll_script');

function load_more_photos()
{
    if (!isset($_POST['security']) || !wp_verify_nonce($_POST['security'], 'load_more_nonce')) {
        wp_send_json_error('Permission non accordée');
    }

    $paged = isset($_POST['page']) ? intval($_POST['page']) : 1;

    $args = [
        'post_type' => 'photo',
        'posts_per_page' => 8,
        'paged' => $paged,
    ];

    $query = new WP_Query($args);

    ob_start();

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            get_template_part('template_parts/photo_block');
        }
    }

    $html = ob_get_clean();
    wp_reset_postdata();

    // Vérifie s’il reste encore des pages
    $has_more = $paged < $query->max_num_pages;

    wp_send_json_success([
        'html' => $html,
        'has_more' => $has_more,
    ]);
}

add_action('wp_ajax_load_more_photos', 'load_more_photos');
add_action('wp_ajax_nopriv_load_more_photos', 'load_more_photos');

// FILTRE ET TRI
function filter_photos_ajax()
{
    // Vérification nonce pour la sécurité
    check_ajax_referer('load_more_nonce', 'security');

    $tax_query = [];

    // Si catégorie sélectionnée
    if (!empty($_POST['category'])) {
        $tax_query[] = [
            'taxonomy' => 'categorie_photo',
            'field'    => 'slug',
            'terms'    => sanitize_text_field($_POST['category']),
        ];
    }

    // Si format sélectionné
    if (!empty($_POST['format'])) {
        $tax_query[] = [
            'taxonomy' => 'format_photo',
            'field'    => 'slug',
            'terms'    => sanitize_text_field($_POST['format']),
        ];
    }

    // Ordre
    $order = (!empty($_POST['sort']) && in_array($_POST['sort'], ['ASC', 'DESC'])) ? $_POST['sort'] : 'DESC';

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

    if ($query->have_posts()) :
        while ($query->have_posts()) : $query->the_post();
            get_template_part('template_parts/photo_block');
        endwhile;
    else :
        echo '<p>Aucune photo ne correspond aux filtres.</p>';
    endif;

    wp_reset_postdata();
    wp_die();
}
add_action('wp_ajax_filter_photos', 'filter_photos_ajax');
add_action('wp_ajax_nopriv_filter_photos', 'filter_photos_ajax');


// Lightbox enqueue + ajax setup
function enqueue_lightbox_script()
{
    wp_enqueue_script('lightbox', get_template_directory_uri() . '/js/lightbox.js', [], null, true);

    wp_localize_script('lightbox', 'lightbox_ajax_object', [
        'ajax_url' => admin_url('admin-ajax.php'),
        'security' => wp_create_nonce('lightbox_nonce')
    ]);
}
add_action('wp_enqueue_scripts', 'enqueue_lightbox_script');

function get_photo_data_callback()
{
    check_ajax_referer('lightbox_nonce', 'security');

    $photo_id = intval($_POST['photo_id']);

    if (!$photo_id || get_post_type($photo_id) !== 'photo') {
        wp_send_json_error('Photo non trouvée');
    }

    $url = get_the_post_thumbnail_url($photo_id, 'full');

    // SCF ou ACF : récupère la référence
    $reference = function_exists('SCF::get')
        ? SCF::get('reference', $photo_id)
        : (function_exists('get_field')
            ? get_field('reference', $photo_id)
            : get_post_meta($photo_id, 'reference', true));


    // Catégorie (taxonomie 'categorie_photo')
    $terms = get_the_terms($photo_id, 'categorie_photo');
    $category = (!empty($terms) && !is_wp_error($terms)) ? $terms[0]->name : '';

    wp_send_json_success([
        'id'        => $photo_id,
        'url'       => esc_url($url),
        'reference' => esc_html($reference),
        'category'  => esc_html($category),
    ]);
}
add_action('wp_ajax_get_photo_data', 'get_photo_data_callback');
add_action('wp_ajax_nopriv_get_photo_data', 'get_photo_data_callback');
