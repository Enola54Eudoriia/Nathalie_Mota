<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">  <!-- Encodage du site -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Responsive -->
    <?php wp_head(); ?>  <!-- Hook WordPress pour charger scripts, styles, métadonnées -->
</head>
<body <?php body_class(); ?>>  <!-- Ajoute automatiquement des classes au body -->

<header class="site-header">
  <div class="container">
    <!-- Logo cliquable renvoyant vers la page d'accueil -->
    <a href="<?= home_url(); ?>" class="logo">
      <img src="<?= get_template_directory_uri(); ?>/assets/img/Logo.svg" alt="Logo Nathalie Mota">
    </a>

    <!-- Menu principal desktop -->
    <nav class="main-nav">
      <?php
      wp_nav_menu([
        'theme_location' => 'main_menu',   // Emplacement menu enregistré
        'menu_class'     => 'menu-desktop', // Classe CSS appliquée à <ul>
      ]);
      ?>
    </nav>

    <!-- Bouton burger pour menu mobile -->
    <button class="burger" aria-label="Ouvrir le menu">
      <span></span> <!-- 3 barres du burger -->
      <span></span>
      <span></span>
    </button>

    <!-- Menu mobile (menu déroulant caché par défaut) -->
    <nav class="menu-mobile">
      <?php
      wp_nav_menu([
        'theme_location' => 'main_menu',
        'menu_class'     => 'menu-mobile-links', // Classe pour styliser menu mobile
        'container'      => false,               // Pas de <div> supplémentaire
      ]);
      ?>
    </nav>
  </div>
</header>
