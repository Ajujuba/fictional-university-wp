<?php 
    get_header(); 

    while(have_posts()){
        the_post(); ?>

        <div class="page-banner">
            <div class="page-banner__bg-image" style="background-image: url(<?php echo get_theme_file_uri('/images/ocean.jpg') ?>);"></div>
            <div class="page-banner__content container container--narrow">
                <h1 class="page-banner__title"><?php the_title(); ?></h1>
                <div class="page-banner__intro">
                    <p>DONT FORGET TO REPLACE ME LATER</p>
                </div>
            </div>
        </div>

        <div class="container container--narrow page-section">
            <div class="metabox metabox--position-up metabox--with-home-link">
                <p><a class="metabox__blog-home-link" href="<?= get_post_type_archive_link('program')?>"><i class="fa fa-home" aria-hidden="true"></i> All Programs </a> <span class="metabox__main"><?php the_title() ?></span></p>
            </div>
            <div class="generic-content">
                <?php the_content(); ?>
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
                    #Se quisesse ordenar por ordem alfabetica:
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
                        $homePageEvents->the_post(); ?>
                        <div class="event-summary">
                            <a class="event-summary__date t-center" href="<?php the_permalink() ?>">
                                <span class="event-summary__month">
                                    <?php 
                                        //the_field('event_date') 
                                        $eventDate = new DateTime(get_field('event_date'));
                                        echo $eventDate->format('M');
                                    ?>
                                </span>
                                <span class="event-summary__day">
                                    <?php 
                                        //the_field('event_date') 
                                        $eventDate = new DateTime(get_field('event_date'));
                                        echo $eventDate->format('d');
                                    ?>
                                </span>
                            </a>
                            <div class="event-summary__content">
                                <h5 class="event-summary__title headline headline--tiny"><a href="<?php the_permalink() ?>"><?php the_title() ?></a></h5>
                                <p>
                                    <?php
                                        if(has_excerpt()){
                                            echo get_the_excerpt();
                                        }else{
                                            echo wp_trim_words(get_the_content(), 18);
                                        }
                                    ?>     
                                    <a href="<?php the_permalink() ?>" class="nu gray">Learn more</a>
                                </p>
                            </div>
                        </div>
                    <?php } wp_reset_postdata();
                }
               
            ?>
        </div>
    <?php
    }

    get_footer();
?>