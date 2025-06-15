<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>

<header class="site-header">
  <div class="container">
    <a href="<?= home_url(); ?>" class="logo">
      <img src="<?= get_template_directory_uri(); ?>/assets/img/Logo.svg" alt="Logo Nathalie Mota">
    </a>

    <nav class="main-nav">
      <?php
      wp_nav_menu([
        'theme_location' => 'main_menu',
        'menu_class'     => 'menu-desktop',
      ]);
      ?>
    </nav>

    <button class="burger" aria-label="Ouvrir le menu">
      <span></span>
      <span></span>
      <span></span>
    </button>

    <nav class="menu-mobile">
      <?php
      wp_nav_menu([
        'theme_location' => 'main_menu',
        'menu_class'     => 'menu-mobile-links',
        'container'      => false,
      ]);
      ?>
    </nav>
  </div>
</header>
