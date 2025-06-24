<?php get_header(); ?>

<main id="home-hero">

    <?php
    // Requête d'une photo aléatoire
    $random_photo = new WP_Query([
        'post_type' => 'photo',
        'posts_per_page' => 1,
        'orderby' => 'rand',
    ]);

    if ($random_photo->have_posts()) :
        while ($random_photo->have_posts()) : $random_photo->the_post();
            $image_url = get_the_post_thumbnail_url(get_the_ID(), 'full');
        endwhile;
        wp_reset_postdata();
    endif;
    ?>

    <section class="hero" style="background-image: url('<?php echo esc_url($image_url); ?>')">
        <h1>PHOTOGRAPHE EVENT</h1>
    </section>

    <section id="photo-gallery">
        <div class="gallery-grid">
            <?php
            $photo_query = new WP_Query([
                'post_type' => 'photo',
                'posts_per_page' => 8,
            ]);

            if ($photo_query->have_posts()) :
                while ($photo_query->have_posts()) : $photo_query->the_post();
                    get_template_part('template_parts/photo_block');
                endwhile;
                wp_reset_postdata();
            else :
                echo '<p>Aucune photo trouvée pour le moment.</p>';
            endif;
            ?>
        </div>
    </section>

</main>

<?php get_footer(); ?>