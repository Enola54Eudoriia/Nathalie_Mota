<?php
$current_id = get_the_ID();
$categories = get_the_terms($current_id, 'categorie_photo');

if ($categories && !is_wp_error($categories)) {
    $category_id = $categories[0]->term_id;

    $args = [
        'post_type' => 'photo',
        'posts_per_page' => 2,
        'post__not_in' => [$current_id],
        'orderby' => 'rand',
        'tax_query' => [
            [
                'taxonomy' => 'categorie_photo',
                'field' => 'term_id',
                'terms' => $category_id,
            ],
        ],
    ];

    $related_query = new WP_Query($args);

    if ($related_query->have_posts()) : ?>
        <section class="photo-apparentees container">
            <h3 class="title-apparentees">Vous aimerez aussi</h3>
            <div class="apparentees-list">
                <?php while ($related_query->have_posts()) : $related_query->the_post(); ?>
                    <article class="photo-block">
                        <a href="<?php the_permalink(); ?>" class="photo-thumbnail-link">
                            <?php the_post_thumbnail('large'); ?>
                            <div class="hover-icons">
                                <!-- Plein écran en haut à droite -->
                                <img src="<?php echo get_template_directory_uri(); ?>/assets/img/Icon_fullscreen.svg" alt="plein écran" class="icon-fullscreen" />

                                <!-- Œil au centre -->
                                <img src="<?php echo get_template_directory_uri(); ?>/assets/img/Icon_eye.svg" alt="voir détails" class="icon-eye" />

                                <!-- Référence en bas à gauche -->
                                <span class="photo-reference">
                                    <?php echo esc_html(get_post_meta(get_the_ID(), 'reference', true)); ?>
                                </span>

                                <!-- Catégorie en bas à droite -->
                                <span class="photo-category">
                                    <?php echo esc_html($categories[0]->name ?? ''); ?>
                                </span>
                            </div>
                        </a>
                    </article>
                <?php endwhile; ?>
            </div>
        </section>
<?php endif;

    wp_reset_postdata();
}
?>
