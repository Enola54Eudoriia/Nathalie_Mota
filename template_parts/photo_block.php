<?php
$current_id = get_the_ID();
// Récupère la catégorie principale de la photo
$categories = get_the_terms($current_id, 'categorie_photo');
$category_name = $categories && !is_wp_error($categories) ? $categories[0]->name : '';

// Récupère la référence personnalisée de la photo
$reference = get_post_meta($current_id, 'reference', true);
?>

<article class="photo-card" data-id="<?php echo $current_id; ?>">
    <a href="<?php the_permalink(); ?>"
       class="photo-thumbnail-link"
       data-ref="<?php echo esc_attr($reference); ?>"
       data-cat="<?php echo esc_attr($category_name); ?>">
       
        <?php the_post_thumbnail('medium-large'); ?>

        <div class="hover-icons">
            <img src="<?php echo get_template_directory_uri(); ?>/assets/img/Icon_eye.svg" alt="voir détails" class="icon-eye" />
            <span class="photo-reference"><?php echo esc_html($reference); ?></span>
            <span class="photo-category"><?php echo esc_html($category_name); ?></span>
        </div>
    </a>

    <img src="<?php echo get_template_directory_uri(); ?>/assets/img/Icon_fullscreen.svg"
         alt="plein écran"
         class="icon-fullscreen"
         data-photo-id="<?php echo $current_id; ?>" />
</article>
