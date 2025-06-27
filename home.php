<?php get_header(); ?>

<main id="home-hero">

    <?php
    // Requête pour récupérer une photo aléatoire (post_type = 'photo')
    $random_photo = new WP_Query([
        'post_type' => 'photo',
        'posts_per_page' => 1,
        'orderby' => 'rand',
    ]);

    if ($random_photo->have_posts()) :
        while ($random_photo->have_posts()) : $random_photo->the_post();
            // Récupère l'URL de la miniature en taille 'full'
            $image_url = get_the_post_thumbnail_url(get_the_ID(), 'full');
        endwhile;
        wp_reset_postdata();  // Réinitialise la requête principale après boucle personnalisée
    endif;
    ?>

    <!-- Section Hero avec image de fond dynamique -->
    <section class="hero" style="background-image: url('<?php echo esc_url($image_url); ?>')">
        <h1>PHOTOGRAPHE EVENT</h1>
    </section>

    <!-- Section des filtres personnalisés pour les photos -->
    <section id="photo-filters">
        <form id="filters-form">

            <!-- Filtres catégorie et format -->
            <div class="filters-left">

                <!-- Filtre catégories -->
                <div class="custom-select" data-filter-type="category">
                    <div class="selected" data-value="">Catégories</div>
                    <ul class="options-list">
                        <li data-value="">Catégories</li> <!-- Option "Tous" pour réinitialiser le filtre -->
                        <?php
                        // Récupère toutes les catégories (taxonomy 'categorie_photo'), même vides
                        $categories = get_terms([
                            'taxonomy' => 'categorie_photo',
                            'hide_empty' => false,
                        ]);
                        foreach ($categories as $category) {
                            echo '<li data-value="' . esc_attr($category->slug) . '">' . esc_html($category->name) . '</li>';
                        }
                        ?>
                    </ul>
                </div>

                <!-- Filtre formats -->
                <div class="custom-select" data-filter-type="format">
                    <div class="selected" data-value="">Formats</div>
                    <ul class="options-list">
                        <li data-value="">Formats</li> <!-- Option "Tous" pour réinitialiser -->
                        <?php
                        // Récupère tous les formats (taxonomy 'format_photo'), même vides
                        $formats = get_terms([
                            'taxonomy' => 'format_photo',
                            'hide_empty' => false,
                        ]);
                        foreach ($formats as $format) {
                            echo '<li data-value="' . esc_attr($format->slug) . '">' . esc_html($format->name) . '</li>';
                        }
                        ?>
                    </ul>
                </div>
            </div>

            <!-- Filtre tri -->
            <div class="custom-select" data-filter-type="sort">
                <div class="selected" data-value="">Trier par</div>
                <ul class="options-list">
                    <li data-value="">Trier par</li> <!-- Option par défaut, ne filtre pas -->
                    <li data-value="DESC">Plus récentes</li>
                    <li data-value="ASC">Plus anciennes</li>
                </ul>
            </div>
        </form>
    </section>

    <!-- Section galerie photos -->
    <section id="photo-gallery">
        <div class="gallery-grid">
            <?php
            // Requête pour afficher les 8 premières photos
            $photo_query = new WP_Query([
                'post_type' => 'photo',
                'posts_per_page' => 8,
            ]);

            if ($photo_query->have_posts()) :
                while ($photo_query->have_posts()) : $photo_query->the_post();
                    // Inclut le template part photo_block pour afficher chaque photo
                    get_template_part('template_parts/photo_block');
                endwhile;
                wp_reset_postdata();
            else :
                echo '<p>Aucune photo trouvée pour le moment.</p>';
            endif;
            ?>
        </div>

        <!-- Bouton "Charger plus" pour pagination infinie AJAX -->
        <div class="load-more-wrapper">
            <button class="load-more-button" 
                    data-url="<?php echo admin_url('admin-ajax.php'); ?>" 
                    data-nonce="<?php echo wp_create_nonce('load_more_nonce'); ?>">
                Charger plus
            </button>
        </div>
    </section>

</main>

<?php get_footer(); ?>
