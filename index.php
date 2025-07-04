<?php
// index.php minimal pour thème WordPress valide

get_header();

if (have_posts()) :
    while (have_posts()) : the_post();
        the_content();
    endwhile;
else :
    echo '<p>Aucun contenu trouvé.</p>';
endif;

get_footer();
