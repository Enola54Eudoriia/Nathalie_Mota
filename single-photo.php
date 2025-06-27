<?php get_header(); ?>

<main id="single-photo" class="container">

    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>

            <?php
            // Récupération des champs personnalisés via SCF (Smart Custom Fields)
            $type = SCF::get('type');
            $reference = SCF::get('reference');

            // Récupération des taxonomies 'categorie' et 'format' associées à la photo
            $categories = get_the_terms(get_the_ID(), 'categorie');
            $formats = get_the_terms(get_the_ID(), 'format');

            // Récupération de l'année de publication
            $annee = get_the_date('Y');
            ?>

            <section class="photo-main">

                <!-- Détails de la photo -->

                <div class="photo-details">
                    <!-- Titre -->
                    <h2 class="photo-title"><?php the_title(); ?></h2>

                    <!-- Référence -->
                    <p class="photo-description">
                        <span>Référence : </span> <?php echo esc_html(SCF::get('reference')); ?>
                    </p>

                    <!-- Catégorie : affiche la première catégorie ou "Non définie" -->
                    <p class="photo-description">
                        <span>Catégorie : </span>
                        <?php
                        $categories = get_the_terms(get_the_ID(), 'categorie_photo');
                        if ($categories && !is_wp_error($categories)) {
                            echo esc_html($categories[0]->name);
                        } else {
                            echo 'Non définie';
                        }
                        ?>
                    </p>

                    <!-- Format : affiche le premier format ou "Non défini" -->
                    <p class="photo-description">
                        <span>Format : </span>
                        <?php
                        $formats = get_the_terms(get_the_ID(), 'format_photo');
                        if ($formats && !is_wp_error($formats)) {
                            echo esc_html($formats[0]->name);
                        } else {
                            echo 'Non défini';
                        }
                        ?>
                    </p>

                    <!-- Type (ex: argentique ou numérique) -->
                    <p class="photo-description">
                        <span>Type : </span> <?php echo esc_html(SCF::get('type')); ?>
                    </p>

                    <!-- Année de prise de vue -->
                    <p class="photo-description">
                        <span>Année : </span> <?php echo get_the_date('Y'); ?>
                    </p>
                </div>

                <!-- Affichage de la photo en grand -->
                <div class="photo-image">
                    <?php the_post_thumbnail('large'); ?>
                </div>

                <!-- Bloc contact + navigation entre photos -->

                <div class="photo-contact-block">
                    <div class="photo-contact-wrapper">
                        <p class="photo-contact-text">
                            Cette photo vous intéresse ?
                        </p>

                        <!-- Bouton qui ouvre la modale contact, avec référence en data -->
                        <div class="photo-contact-button">
                            <button id="contact-button" class="open-contact-modal" data-reference="<?php echo esc_attr($reference); ?>">
                                CONTACT
                            </button>
                        </div>
                    </div>

                    <!-- Inclusion de la modale de contact -->
                    <?php get_template_part('template_parts/contact-modal'); ?>

                    <?php
                    // Navigation entre photos (en boucle)

                    $current_post = get_post();

                    // Récupère la photo suivante (date croissante)
                    $next_post = get_adjacent_post(false, '', false);
                    if (!$next_post) {
                        // S'il n'y en a pas, récupère la première photo la plus ancienne (boucle)
                        $args = [
                            'post_type' => 'photo',
                            'posts_per_page' => 1,
                            'order' => 'ASC',
                            'orderby' => 'date',
                        ];
                        $loop = new WP_Query($args);
                        if ($loop->have_posts()) {
                            $loop->the_post();
                            $next_post = get_post();
                            wp_reset_postdata();
                        }
                    }

                    // Récupère la photo précédente (date décroissante)
                    $prev_post = get_adjacent_post(false, '', true);
                    if (!$prev_post) {
                        // S'il n'y en a pas, récupère la photo la plus récente (boucle)
                        $args = [
                            'post_type' => 'photo',
                            'posts_per_page' => 1,
                            'order' => 'DESC',
                            'orderby' => 'date',
                        ];
                        $loop = new WP_Query($args);
                        if ($loop->have_posts()) {
                            $loop->the_post();
                            $prev_post = get_post();
                            wp_reset_postdata();
                        }
                    }
                    ?>

                    <!-- Navigation visuelle entre photos -->
                    <nav class="photo-navigation">

                        <!-- Aperçu miniature photo suivante -->
                        <?php if ($next_post) : ?>
                            <div class="photo-preview-thumbnail">
                                <a href="<?php echo get_permalink($next_post->ID); ?>">
                                    <?php echo get_the_post_thumbnail($next_post->ID, 'thumbnail'); ?>
                                </a>
                            </div>
                        <?php endif; ?>

                        <div class="photo-arrows">
                            <!-- Flèche précédente -->
                            <div class="prev-photo">
                                <?php if ($prev_post) : ?>
                                    <a href="<?php echo get_permalink($prev_post->ID); ?>">←</a>
                                <?php endif; ?>
                            </div>

                            <!-- Flèche suivante -->
                            <div class="next-photo">
                                <?php if ($next_post) : ?>
                                    <a href="<?php echo get_permalink($next_post->ID); ?>">→</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </nav>
                </div>
            </section>

            <!-- Section des photos apparentées -->

            <?php
            $current_id = get_the_ID();
            $categories = get_the_terms($current_id, 'categorie_photo');

            if ($categories && !is_wp_error($categories)) {
                $category_id = $categories[0]->term_id;

                // Requête pour récupérer 2 photos aléatoires de la même catégorie, excluant la photo actuelle
                $related_query = new WP_Query([
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
                ]);

                if ($related_query->have_posts()) : ?>
                    <section class="photo-apparentees container">
                        <h3 class="title-apparentees">Vous aimerez aussi</h3>
                        <div class="apparentees-list">
                            <?php while ($related_query->have_posts()) : $related_query->the_post();
                                get_template_part('template_parts/photo_block');
                            endwhile; ?>
                        </div>
                    </section>
            <?php
                endif;
                wp_reset_postdata();
            }
            ?>

    <?php endwhile;
    endif; ?>

</main>

<?php get_footer(); ?>
