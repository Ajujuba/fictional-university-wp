<?php

#Here I'm including my Custom REST API for searching my custom posts type
require get_theme_file_path('/inc/search-route.php');

#This function customized my REST API return adding a property 'authorName'
function university_custom_rest(){
    register_rest_field('post', 'authorName', [
        'get_callback' => function(){ return get_the_author();}
    ]);
}
add_action('rest_api_init', 'university_custom_rest');

#Load my css e js when load my hook wp_enqueue_scripts
function university_files(){
    wp_enqueue_script('main-university-js', get_theme_file_uri('/build/index.js'), array('jquery'), '1.0', true);// here I say my js uses a jquey dependency, then the version of my js, and the last one says if I want to load before the body closes
    wp_enqueue_style('custom-google-fonts', '//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i');
    wp_enqueue_style('font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
    wp_enqueue_style('university_main_styles', get_theme_file_uri('/build/style-index.css'));
    wp_enqueue_style('university_extra_styles', get_theme_file_uri('/build/index.css'));
    
    //this function will create a js variavle with de value of my url site domain
    wp_localize_script('main-university-js', 'universityData', [
        'root_url' => get_site_url(),
        'nonce' => wp_create_nonce('wp_rest') //this line will create a nonce (like 'a secret key') for autorize my users in requests like delete or update
    ]);
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

    if(!is_admin() && is_post_type_archive('event') && $query->is_main_query()){
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

    if(!is_admin() && is_post_type_archive('program') && $query->is_main_query()){
        $query->set('orderby', 'title');
        $query->set('order', 'ASC');
        $query->set('posts_perpage', '-1');
    }


}
add_action('pre_get_posts', 'university_adjust_queries');

function pageBanner($args = NULL){

    if(!isset($args['title'])){
        $args['title'] = get_the_title();
    }

    if(!isset($args['subtitle'])){
        $args['subtitle'] = get_field('page_banner_subtitle');
    }

    if(!isset($args['photo'])){
        if(get_field('page_banner_background_image') && !is_archive() && !is_home() ){
            $args['photo'] = get_field('page_banner_background_image')['sizes']['pageBanner'];
        }  else {
            $args['photo'] = get_theme_file_uri('/images/ocean.jpg');
        }
    }
    ?>
    <div class="page-banner">
        <div class="page-banner__bg-image" style="background-image: url(<?= $args['photo'] ?>);"></div>
        <div class="page-banner__content container container--narrow">
            <h1 class="page-banner__title"><?= $args['title'] ?></h1>
            <div class="page-banner__intro">
                <p><?= $args['subtitle'] ?></p>
            </div>
        </div>
    </div>
    <?php
}

# AS I DID NOT ACTIVATE THE API, INSTEAD OF USING A CUSTOM FIELD FROM GOOGLE MAPS, I CREATED A WYSIWYG FIELD AND COPIED THE GOOGLE MAPS CODE TO INCORPORATE ON MY PAGE
// function universityMapKey($api){
//     $api['key'] = ''; // Here is your API if you have one configured with the 3 plugins necessary for the map to work, I didn't do it because it needs to associate the card
//     return $api;
// }
// add_filter('acf/fields/google_map/api', 'universityMapKey');

#Redirect subscriber accounts out of admin and onto homepage
function redirectSubsToFrontend(){
    $currentUser = wp_get_current_user();
    if(count($currentUser->roles) == 1 AND $currentUser->roles[0] == 'subscriber'){
        wp_redirect(site_url('/index.php'));
        exit;
    }
}
add_action('admin_init', 'redirectSubsToFrontend');

#Hide my admin toolbar when login subscriber
function noSubsAdminBar(){
    $currentUser = wp_get_current_user();
    if(count($currentUser->roles) == 1 AND $currentUser->roles[0] == 'subscriber'){
       show_admin_bar(false);
    }
}
add_action('wp_loaded', 'noSubsAdminBar');

#Customizing login screen
function ourHeaderUrl(){
    return esc_url(site_url('/')); // this line send us to home page when click in WP logo
}
add_filter('login_headerurl', 'ourHeaderUrl');

#Call our css in the login screen
function ourLoginCss(){
    wp_enqueue_style('custom-google-fonts', '//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i');
    wp_enqueue_style('font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
    wp_enqueue_style('university_main_styles', get_theme_file_uri('/build/style-index.css'));
    wp_enqueue_style('university_extra_styles', get_theme_file_uri('/build/index.css'));
}
add_filter('login_enqueue_scripts', 'ourLoginCss');

#Get my website title for my Login screen
function ourHeaderTitle() {
    return get_bloginfo('name');
}
add_filter('login_headertitle', 'ourHeaderTitle');
