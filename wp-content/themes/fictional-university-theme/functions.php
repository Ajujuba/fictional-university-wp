<?php

#Load my css e js when load my hook wp_enqueue_scripts
function university_files(){
    wp_enqueue_script('main-university-js', get_theme_file_uri('/build/index.js'), array('jquery'), '1.0', true); // aqui digo meu js usa dependencia do jquey , depois a versão do meu js, e o ultimo diz se quero carregar antes do fechamento do body
    wp_enqueue_style('custom-google-fonts', '//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i');
    wp_enqueue_style('font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
    wp_enqueue_style('university_main_styles', get_theme_file_uri('/build/style-index.css'));
    wp_enqueue_style('university_extra_styles', get_theme_file_uri('/build/index.css'));

}

add_action('wp_enqueue_scripts', 'university_files');

#Get page title for my browser  when load my hook after_setup_theme
function university_features(){
    register_nav_menu('headerMenuLocation', 'Header Menu Location');
    register_nav_menu('footerLocation1', 'Footer Location 1');
    register_nav_menu('footerLocation2', 'Footer Location 2');
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_image_size('professorLandscape', 400, 260, false);
    add_image_size('professorPortrait', 480, 660, true);
    add_image_size('pageBanner', 1500, 350, true);

}

add_action('after_setup_theme', 'university_features');

# Remove The [...] is added by the_excerpt().
function new_excerpt_more( $more ) {
    return '';
}
add_filter('excerpt_more', 'new_excerpt_more');

#adjust an exting query
function university_adjust_queries($query){
    $today = date('Ymd');

    if(!is_admin() AND is_post_type_archive('event') AND $query->is_main_query()){
        $query->set('meta_key','event_date');
        $query->set('orderby', 'event_date');
        $query->set('orderby', 'meta_value_num');
        $query->set('order', 'ASC');
        $query->set('meta_query', 
            [
                [
                    'key' => 'event_date',
                    'compare' => '>=',
                    'value' => $today,
                    'type' => 'numeric'
                ]
            ]
        );
    }

    if(!is_admin() AND is_post_type_archive('program') AND $query->is_main_query()){
        $query->set('orderby', 'title');
        $query->set('order', 'ASC');
        $query->set('posts_perpage', '-1');
    }


}
add_action('pre_get_posts', 'university_adjust_queries');