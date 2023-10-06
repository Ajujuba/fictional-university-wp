<?php 
    get_header(); 

    while(have_posts()){
        the_post();
        pageBanner() ?>

        <div class="container container--narrow page-section">
            <div class="metabox metabox--position-up metabox--with-home-link">
                <p><a class="metabox__blog-home-link" href="<?= get_post_type_archive_link('campus')?>"><i class="fa fa-home" aria-hidden="true"></i> All Campuses </a> <span class="metabox__main"><?php the_title() ?></span></p>
            </div>
            <div class="generic-content">
                <?php the_content(); the_field('map_location') ?>
            </div>

            <?php 
                #search Programs
                $relatedPrograms = new WP_Query([
                    'posts_per_page' => -1,
                    'post_type' => 'program',
                    'orderby' => 'title',
                    'order' => 'ASC',
                    'meta_query' => [
                        #vai buscar os dados que tem event relacionado ao id da postagem atual
                        [
                            'key' => 'related_campus',
                            'compare' => 'LIKE',
                            'value' => '"'. get_the_ID() . '"', //precisa retornar entre aspas pra funcionar corretamente o retorno e ele pegar só oq corresponde exatamente a string
                        ]
                    ]

                ]);

                if($relatedPrograms->have_posts()){
                    echo '<hr class="section-break">';
                    echo '<h2 class="headline headline--medium">Programs Availables at This Campus</h2>';

                    echo '<ul class="min-list link-list">';
                    while($relatedPrograms->have_posts()){
                        $relatedPrograms->the_post(); ?>
                        <li class="">
                            <a class="" href="<?php the_permalink() ?>" >
                                <?php the_title() ?>
                            </a>
                        </li>
                    <?php } 
                    echo '</ul>';
                    wp_reset_postdata();
                }
               
            ?>
        </div>
    <?php
    }

    get_footer();
?>