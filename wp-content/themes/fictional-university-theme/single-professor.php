<?php 
    get_header(); 

    while(have_posts()){
        the_post();
        pageBanner();
    ?>

        <div class="container container--narrow page-section">
            <div class="generic-content">
                <div class="row group">
                    <div class="one-third">
                        <?php the_post_thumbnail('professorPortrait'); ?>
                    </div>
                    <two class="thirds">
                        <?php  the_content(); ?>
                    </two>
                </div>
            </div>

            <?php 
                $relatedPrograms = get_field('related_programs'); // retorna um bjeto do wordpress por isso posso usar dentro da get_the_title()

                if($relatedPrograms){
                    echo '<hr class="section-break">';
                    echo '<h3 class="headline headline--medium">Subjects(s) taught</h3>';
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
    }

    get_footer();
?>