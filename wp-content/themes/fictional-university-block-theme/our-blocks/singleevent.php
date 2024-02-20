<?php 

    while(have_posts()){ //comment if you update to WP 6.4
        the_post(); //comment if you update to WP 6.4
        pageBanner([   
        ]);
        ?>

        <div class="container container--narrow page-section">
            <div class="metabox metabox--position-up metabox--with-home-link">
                <p><a class="metabox__blog-home-link" href="<?= get_post_type_archive_link('event')?>"><i class="fa fa-home" aria-hidden="true"></i> Events Home </a> <span class="metabox__main"><?php the_title() ?></span></p>
            </div>
            <div class="generic-content">
                <?php the_content(); ?>
            </div>

            <?php 
                $relatedPrograms = get_field('related_programs'); // retorna um bjeto do wordpress por isso posso usar dentro da get_the_title()

                if($relatedPrograms){
                    echo '<hr class="section-break">';
                    echo '<h3 class="headline headline--medium">Related Program(s)</h3>';
                    echo '<ul class="link-list min-list">';
                    foreach($relatedPrograms as $program){ ?>
                        <li>
                            <a href="<?= get_the_permalink($program)?>">
                                <?= get_the_title($program);?>
                            </a>
                        </li>
                    <?php }
                    echo '</ul>';
                }
            ?>
        </div>
    <?php
    } //comment if you update to WP 6.4

?>