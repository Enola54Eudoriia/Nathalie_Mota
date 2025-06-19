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
        <section class="photo-apparentees">
            <h3 class="title-apparentees">Vous aimerez aussi</h3>
            <div class="apparentees-list">
                <?php while ($related_query->have_posts()) : $related_query->the_post(); ?>
                    <article class="photo-block">
                        <a href="<?php the_permalink(); ?>" class="photo-thumbnail-link">
                            <?php the_post_thumbnail('large'); ?>
                            <div class="hover-icons">
                                <span class="icon-eye" title="Voir détails">👁️</span>
                                <span class="icon-fullscreen" title="Voir en grand">🔍</span>
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
