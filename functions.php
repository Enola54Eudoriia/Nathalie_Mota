<?php
// Chargement CSS/JS
function nathalie_mota_enqueue_assets() {
    $theme_version = wp_get_theme()->get('Version');
    wp_enqueue_style('theme-style', get_stylesheet_uri(), [], $theme_version);
    wp_enqueue_script('theme-scripts', get_template_directory_uri() . '/js/scripts.js', [], null, true);
}
add_action('wp_enqueue_scripts', 'nathalie_mota_enqueue_assets');

// Support d’images
add_theme_support('post-thumbnails');

// Menus
register_nav_menus([
    'main_menu' => 'Menu principal'
]);

add_theme_support('title-tag');
