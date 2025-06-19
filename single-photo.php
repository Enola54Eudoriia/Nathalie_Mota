<?php get_header(); ?>

<main id="single-photo">

    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>

            <?php
            // Champs personnalisés
            $type = SCF::get('type');
            $reference = SCF::get('reference');

            // Taxonomies
            $categories = get_the_terms(get_the_ID(), 'categorie');
            $formats = get_the_terms(get_the_ID(), 'format');

            // Date
            $annee = get_the_date('Y');
            ?>

            <section class="photo-main">
                <!-- TITRE DE LA PHOTO -->

                <div class="photo-details">
                    <h2 class="photo-title"><?php the_title(); ?></h2>
                    <!-- Référence -->
                    <p class="photo-description">
                        <span>Référence : </span> <?php echo esc_html(SCF::get('reference')); ?>
                    </p>

                    <!-- Catégorie -->
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

                    <!-- Format -->
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

                    <!-- Type (argentique / numérique) -->
                    <p class="photo-description">
                        <span>Type : </span> <?php echo esc_html(SCF::get('type')); ?>
                    </p>

                    <!-- Année (date de prise de vue) -->
                    <p class="photo-description">
                        <span>Année : </span> <?php echo get_the_date('Y'); ?>
                    </p>
                </div>
                <div class="photo-image">
                    <?php the_post_thumbnail('large'); ?>
                </div>

                <!-- BOUTON CONTACT + NAVIGATION ENTRE LES PHOTOS EN BOUCLE -->
                <div class="photo-contact-block">
                    <div class="photo-contact-wrapper">
                        <p class="photo-contact-text">
                            Cette photo vous intéresse ?
                        </p>
                        <div class="photo-contact-button">
                            <button id="contact-button" class="open-contact-modal" data-reference="<?php echo esc_attr($reference); ?>">
                                CONTACT
                            </button>
                        </div>
                    </div>


                    <?php get_template_part('template_parts/contact-modal'); ?>

                    <?php
                    $current_post = get_post();

                    // Suivant
                    $next_post = get_adjacent_post(false, '', false);
                    if (!$next_post) {
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

                    // Précédent
                    $prev_post = get_adjacent_post(false, '', true);
                    if (!$prev_post) {
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

                    <nav class="photo-navigation">
                        <?php if ($next_post) : ?>
                            <div class="photo-preview-thumbnail">
                                <a href="<?php echo get_permalink($next_post->ID); ?>">
                                    <?php echo get_the_post_thumbnail($next_post->ID, 'thumbnail'); ?>
                                </a>
                            </div>
                        <?php endif; ?>

                        <div class="photo-arrows">
                            <div class="prev-photo">
                                <?php if ($prev_post) : ?>
                                    <a href="<?php echo get_permalink($prev_post->ID); ?>">←</a>
                                <?php endif; ?>
                            </div>
                            <div class="next-photo">
                                <?php if ($next_post) : ?>
                                    <a href="<?php echo get_permalink($next_post->ID); ?>">→</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </nav>
                </div>
            </section>


            <!-- ZONE DES PHOTOS APPARENTÉES -->
            <?php get_template_part('template_parts/photo_block'); ?>

    <?php endwhile;
    endif; ?>

</main>

<?php get_footer(); ?>