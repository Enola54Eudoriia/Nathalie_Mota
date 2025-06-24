<?php
$current_id = get_the_ID();
$categories = get_the_terms($current_id, 'categorie_photo');
$category_name = $categories && !is_wp_error($categories) ? $categories[0]->name : '';
$reference = get_post_meta($current_id, 'reference', true);
?>

<article class="photo-card">
    <a href="<?php the_permalink(); ?>" class="photo-thumbnail-link">
        <?php the_post_thumbnail('large'); ?>
        <div class="hover-icons">
            <img src="<?php echo get_template_directory_uri(); ?>/assets/img/Icon_fullscreen.svg" alt="plein écran" class="icon-fullscreen" />
            <img src="<?php echo get_template_directory_uri(); ?>/assets/img/Icon_eye.svg" alt="voir détails" class="icon-eye" />
            <span class="photo-reference"><?php echo esc_html($reference); ?></span>
            <span class="photo-category"><?php echo esc_html($category_name); ?></span>
        </div>
    </a>
</article>
