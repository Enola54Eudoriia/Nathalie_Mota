<footer class="site-footer">
  <div>
    <nav class="footer-nav">
      <ul>
        <li><a href="#">MENTIONS LÉGALES</a></li>
        <li><a href="#">VIE PRIVÉE</a></li>
        <li><a href="#">TOUS DROITS RÉSERVÉS</a></li>
      </ul>
    </nav>
  </div>

  <?php 
  // Inclusion du template de la modale de contact, utilisée globalement dans le site
  get_template_part('template_parts/contact-modal'); 
  ?>

  <?php 
  // Fonction WordPress obligatoire qui insère tous les scripts et actions en footer (ex: JS)
  wp_footer(); 
  ?>
</footer>

<!-- Lightbox : structure HTML cachée par défaut, affichée via JS -->
<div id="lightbox-overlay" class="lightbox-overlay" style="display: none;">
  <div class="lightbox-content">

    <!-- Bouton fermeture de la lightbox avec attributs d'accessibilité -->
    <button class="lightbox-close" aria-label="Fermer la lightbox">
      <img src="<?php echo get_template_directory_uri(); ?>/assets/img/close.svg" alt="Fermer la lightbox">
    </button>

    <!-- Bouton navigation image précédente -->
    <button class="lightbox-nav lightbox-prev" aria-label="Image précédente">
      <img src="<?php echo get_template_directory_uri(); ?>/assets/img/arrow-left.svg" alt="Précédente">
    </button>

    <!-- Conteneur image principale affichée dans la lightbox -->
    <div class="lightbox-image-container">
      <img src="" alt="" class="lightbox-image">
    </div>

    <!-- Bouton navigation image suivante -->
    <button class="lightbox-nav lightbox-next" aria-label="Image suivante">
      <img src="<?php echo get_template_directory_uri(); ?>/assets/img/arrow-right.svg" alt="Suivante">
    </button>

    <!-- Informations complémentaires affichées dans la lightbox -->
    <div class="lightbox-info">
      <span class="lightbox-reference"></span>
      <span class="lightbox-category"></span>
    </div>

  </div>
</div>

</body>
</html>
