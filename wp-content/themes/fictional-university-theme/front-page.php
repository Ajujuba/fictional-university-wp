<?php
get_header();

?>
<div class="page-banner">
    <div class="page-banner__bg-image" style="background-image: url(<?php echo get_theme_file_uri('/images/library-hero.jpg') ?>);"></div>
    <div class="page-banner__content container t-center c-white">
        <h1 class="headline headline--large">Welcome!</h1>
        <h2 class="headline headline--medium">We think you&rsquo;ll like it here.</h2>
        <h3 class="headline headline--small">Why don&rsquo;t you check out the <strong>major</strong> you&rsquo;re interested in?</h3>
        <a href="<?= get_post_type_archive_link('program')?>" class="btn btn--large btn--blue">Find Your Major</a>
    </div>
</div>

<div class="full-width-split group">
    <div class="full-width-split__one">
        <div class="full-width-split__inner">
            <h2 class="headline headline--small-plus t-center">Upcoming Events</h2>

            <?php 
                $today = date('Ymd');
                $homePageEvents = new WP_Query([
                    'posts_per_page' => 2,
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
                        ]
                    ]
                    #Se quisesse ordenar por ordem alfabetica:
                    // 'orderby' => 'title',
                    // 'order' => 'ASC'

                ]);

                    while($homePageEvents->have_posts()){
                        $homePageEvents->the_post(); 
                        get_template_part('template-parts/content', 'event');
                    } 
                    wp_reset_postdata();
            ?>

            <p class="t-center no-margin"><a href="<?= get_post_type_archive_link('event') ?>" class="btn btn--blue">View All Events</a></p>
        </div>
    </div>
    <div class="full-width-split__two">
        <div class="full-width-split__inner">
            <h2 class="headline headline--small-plus t-center">From Our Blogs</h2>

            <?php 

            $homePagePosts = new WP_Query([
                'posts_per_page' => 2,
                ''
            ]);

                while($homePagePosts->have_posts()){
                    $homePagePosts->the_post(); ?>
                    <div class="event-summary">
                        <a class="event-summary__date event-summary__date--beige t-center" href="<?php the_permalink() ?>">
                            <span class="event-summary__month"><?php the_time('M') ?></span>
                            <span class="event-summary__day"><?php the_time('d') ?></span>
                        </a>
                        <div class="event-summary__content">
                            <h5 class="event-summary__title headline headline--tiny"><a href="<?php the_permalink() ?>"><?php the_title() ?></a></h5>
                            <p><?php
                                    if(has_excerpt()){
                                        echo get_the_excerpt();
                                    }else{
                                        echo wp_trim_words(get_the_content(), 18);
                                    }
                                ?> 
                                <a href="<?php the_permalink() ?>" class="nu gray">Read more</a>
                            </p>
                        </div>
                    </div>
               <?php } wp_reset_postdata();
            ?>

            <p class="t-center no-margin"><a href="<?= site_url('index.php/blog')?>" class="btn btn--yellow">View All Blog Posts</a></p>
        </div>
    </div>
</div>

<div class="hero-slider">
    <div data-glide-el="track" class="glide__track">
        <div class="glide__slides">
            <?php
                $homepageSlideshow = new WP_Query(array(
                'posts_per_page' => 10,
                'post_type' => 'slide'
                )); 
                
                while($homepageSlideshow->have_posts()){
                    $homepageSlideshow->the_post(); ?>

                    <div class="hero-slider__slide" style="background-image: url(<?= get_field('background_image_slide') ?>);">
                        <div class="hero-slider__interior container">
                            <div class="hero-slider__overlay">
                                <h2 class="headline headline--medium t-center"><?php the_title()?></h2>
                                <p class="t-center"><?php echo wp_trim_words(get_the_content(), 10, '...')?></p>
                                <p class="t-center no-margin"><a href="<?php the_permalink()?>" class="btn btn--blue"><?= get_field('button_title_slide') ?></a></p>
                            </div>
                        </div>
                    </div>
                    
                <?php }
            ?>
        </div>
        <div class="slider__bullets glide__bullets" data-glide-el="controls[nav]"></div>
    </div>
</div>

<?php
//the param of the function get_page_by_path() is the slug of the page, the last part of my link
$page1 = get_page_by_path('content-1');
$page2 = get_page_by_path('content-2');

?>
<div class="content-homepage">
    <div class=row-homepage>
        <div class="col-homepage col-left-homepage">
            <div class="title-card-homepage">
                <?= esc_html($page1->post_title)?>
                <div class="border-title-card-homepage"></div>
            </div>
            <div class="content-card-homepage text-left-homepage">
                <p> <?= esc_html(wp_trim_words($page1->post_content, 50, '...'))?></p>
            </div>
            <div class="t-center no-margin">
                <a class="btn btn--blue" href="<?= $page1->guid ?>">See more</a>
            </div>
        </div>
        <div class="col-homepage col-right-homepage">
            <div class="title-card-homepage">
                <?= esc_html($page2->post_title)?>
                <div class="border-title-card-right-homepage"></div>
            </div>
            <div class="content-card-homepage text-right-homepage">
                <p> <?= esc_html(wp_trim_words($page2->post_content, 50, '...'))?></p>
            </div>
            <div class="t-center no-margin">
                <a class="btn btn--yellow" href="<?= $page2->guid ?>">Learn more</a>
            </div>
        </div>
    </div>
</div>

<?php
get_footer();
?>