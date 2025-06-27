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
  <?php get_template_part('template_parts/contact-modal'); ?>

  <?php wp_footer(); ?>
</footer>
<div id="lightbox-overlay" class="lightbox-overlay" style="display: none;">
  <div class="lightbox-content">
    <button class="lightbox-close" aria-label="Fermer la lightbox">
      <img src="<?php echo get_template_directory_uri(); ?>/assets/img/close.svg" alt="Fermer la lightbox">
    </button>

    <button class="lightbox-nav lightbox-prev" aria-label="Image précédente">
      <img src="<?php echo get_template_directory_uri(); ?>./assets/img/arrow-left.svg" alt="Précédente">
    </button>

    <div class="lightbox-image-container">
      <img src="" alt="" class="lightbox-image">
    </div>

    <button class="lightbox-nav lightbox-next" aria-label="Image suivante">
      <img src="<?php echo get_template_directory_uri(); ?>./assets/img/arrow-right.svg" alt="Suivante">
    </button>

    <div class="lightbox-info">
      <span class="lightbox-reference"></span>
      <span class="lightbox-category"></span>
    </div>
  </div>
</div>
</body>

</html>