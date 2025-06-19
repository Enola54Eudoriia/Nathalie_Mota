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
                <div class="photo-image">
                    <?php the_post_thumbnail('large'); ?>
                </div>

                <div class="photo-details">

                    <!-- Référence -->
                    <p class="photo-reference">
                        <strong>Référence : </strong> <?php echo esc_html(SCF::get('reference')); ?>
                    </p>

                    <!-- Catégorie -->
                    <p class="photo-category">
                        <strong>Catégorie : </strong>
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
                    <p class="photo-format">
                        <strong>Format : </strong>
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
                    <p class="photo-type">
                        <strong>Type : </strong> <?php echo esc_html(SCF::get('type')); ?>
                    </p>

                    <!-- Année (date de prise de vue) -->
                    <p class="photo-year">
                        <strong>Année : </strong> <?php echo get_the_date('Y'); ?>
                    </p>



                    <!-- BOUTON CONTACT -->
                    <button id="contact-button" class="open-contact-modal" data-reference="<?php echo esc_attr($reference); ?>">
                        CONTACT
                    </button>
                    <?php get_template_part('template_parts/contact-modal'); ?>
                </div>
            </section>

            <!-- NAVIGATION ENTRE LES PHOTOS -->
            <nav class="photo-navigation">
                <div class="prev-photo"><?php previous_post_link('%link', '←'); ?></div>
                <div class="next-photo"><?php next_post_link('%link', '→'); ?></div>
            </nav>

            <!-- ZONE DES PHOTOS APPARENTÉES -->
            <?php get_template_part('template_parts/photo_block'); ?>

    <?php endwhile;
    endif; ?>

</main>

<?php get_footer(); ?>