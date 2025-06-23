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

</main>

<?php get_footer(); ?>
