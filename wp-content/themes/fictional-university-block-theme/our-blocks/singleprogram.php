<?php 

    while(have_posts()){ //comment if you update to WP 6.4
        the_post(); //comment if you update to WP 6.4
        pageBanner() ?>

        <div class="container container--narrow page-section">
            <div class="metabox metabox--position-up metabox--with-home-link">
                <p><a class="metabox__blog-home-link" href="<?= get_post_type_archive_link('program')?>"><i class="fa fa-home" aria-hidden="true"></i> All Programs </a> <span class="metabox__main"><?php the_title() ?></span></p>
            </div>
            <div class="generic-content">
                <?php the_field('main_body_content'); //I'm calling the custom field because of the search, if I leave my 'programs' content in the default content, WP searches for this text, but to avoid bringing incorrect data I put my 'program' text in an ACF ?>
            </div>

            <?php 
                #search professors
                $relatedProfessors = new WP_Query([
                    'posts_per_page' => -1,
                    'post_type' => 'professor',
                    'orderby' => 'title',
                    'order' => 'ASC',
                    #As linhas a seguir garantem que nao pegarei eventos que já passaram
                    'meta_query' => [
                        #vai buscar os dados que tem event relacionado ao id da postagem atual
                        [
                            'key' => 'related_programs',
                            'compare' => 'LIKE',
                            'value' => '"'. get_the_ID() . '"', //precisa retornar entre aspas pra funcionar corretamente o retorno e ele pegar só oq corresponde exatamente a string
                        ]
                    ]
                    #alphabetical order
                    // 'orderby' => 'title',
                    // 'order' => 'ASC'

                ]);

                if($relatedProfessors->have_posts()){
                    echo '<hr class="section-break">';
                    echo '<h2 class="headline headline--medium"> ' . get_the_title(). ' Professors</h2>';

                    echo '<ul class="professor-cards">';
                    while($relatedProfessors->have_posts()){
                        $relatedProfessors->the_post(); ?>
                        <li class="professor-card__list-item">
                            <a class="professor-card" href="<?php the_permalink() ?>" >
                                <img class="professor-card__image" src="<?php the_post_thumbnail_url('professorLandscape') ?>">
                                <span class="professor-card__name"> <?php the_title() ?></span>
                            </a>
                        </li>
                    <?php } 
                    echo '</ul>';
                    wp_reset_postdata();
                }

                #search events
                $today = date('Ymd');
                $homePageEvents = new WP_Query([
                    'posts_per_page' => -1,
                    'post_type' => 'event',
                    #as 3 linhas a seguir garantem que exibo os eventos com a 'menor' data, mais proxima
                    'meta_key' => 'event_date',
                    'orderby' => 'meta_value_num',
                    'order' => 'ASC',
                    #As linhas a seguir garantem que nao pegarei eventos que já passaram
                    'meta_query' => [
                        [
                            'key' => 'event_date',
                            'compare' => '>=',
                            'value' => $today,
                            'type' => 'numeric'
                        ],
                        #vai buscar os dados que tem event relacionado ao id da postagem atual
                        [
                            'key' => 'related_programs',
                            'compare' => 'LIKE',
                            'value' => '"'. get_the_ID() . '"', //precisa retornar entre aspas pra funcionar corretamente o retorno e ele pegar só oq corresponde exatamente a string
                        ]
                    ]
                    #Se quisesse ordenar por ordem alfabetica:
                    // 'orderby' => 'title',
                    // 'order' => 'ASC'

                ]);

                if($homePageEvents->have_posts()){
                    echo '<hr class="section-break">';
                    echo '<h2 class="headline headline--medium">Upcoming ' . get_the_title(). ' Events</h2>';
                    while($homePageEvents->have_posts()){
                        $homePageEvents->the_post(); 
                        get_template_part('template-parts/content-event');
                    } 
                    wp_reset_postdata();
                }

                $relatedCampuses = get_field('related_campus'); // retorna um bjeto do wordpress por isso posso usar dentro da get_the_title()

                if($relatedCampuses){
                    echo '<hr class="section-break">';
                    echo '<h3 class="headline headline--medium">' . get_the_title() . ' is Available  at these Capus(es)</h3>';
                    echo '<ul class="link-list min-list">';
                    foreach($relatedCampuses as $campus){ ?>
                        <li>
                            <a href="<?= get_the_permalink($campus)?>">
                                <?= get_the_title($campus);?>
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