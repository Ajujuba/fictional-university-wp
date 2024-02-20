<?php 

    #As of WP 6.4 we have block-themes within the singular content, WP already executes the loop itself, so for page.php, singlepost.php or singleQualquerCoisa.php I can remove the while() and the the_post() call
    while(have_posts()){ //comment if you update to WP 6.4
        the_post(); //comment if you update to WP 6.4
        pageBanner() ?>

        <div class="container container--narrow page-section">
            <div class="metabox metabox--position-up metabox--with-home-link">
                <p><a class="metabox__blog-home-link" href="<?= site_url('index.php/blog')?>"><i class="fa fa-home" aria-hidden="true"></i> Blog Home </a> <span class="metabox__main"> Posted by: <?php the_author_posts_link() ?> on <?php the_time('n/j/Y')?> in <?= get_the_category_list(', ') ?> </span></p>
            </div>
            <div class="generic-content">
                <?php the_content(); ?>
            </div>
        </div>
    <?php
    } //comment if you update to WP 6.4