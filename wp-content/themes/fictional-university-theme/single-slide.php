<?php
    get_header();

    while(have_posts()){
        the_post();
        pageBanner();
    ?>

        <div class="container container--narrow page-section">
            <div class="generic-content">
            <?php the_content(); ?>
            <img src="<?= get_field('background_image_slide') ?>" alt="bg-slide">
            </div>
        </div>
    <?php
    }

    get_footer();
?>
