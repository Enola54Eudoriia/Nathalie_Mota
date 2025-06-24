<?php
// Chargement CSS/JS
function nathalie_mota_enqueue_assets() {
    $theme_version = wp_get_theme()->get('Version');
    wp_enqueue_style('theme-style', get_stylesheet_uri(), [], $theme_version);

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
function enqueue_infinite_scroll_script() {
    wp_enqueue_script('infinite-scroll', get_template_directory_uri() . '/js/infinite-scroll.js', array(), null, true);

    wp_localize_script('infinite-scroll', 'loadMoreParams', array(
        'url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('load_more_nonce'),
    ));
}
add_action('wp_enqueue_scripts', 'enqueue_infinite_scroll_script');

function load_more_photos() {
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



