<?php

#This function with this hook will return all our querys and We can add new parameters to WP listen
function universityQueryVars($vars){
    $vars[] = 'skyColor'; //example how register my query var
    return $vars;
}
add_filter('query_vars', 'universityQueryVars');

#Here I'm including my Custom REST API for searching my custom posts type
require get_theme_file_path('/inc/search-route.php');

#Here I'm including my Custom REST API for like or deslike a professor
require get_theme_file_path('/inc/like-route.php');

#This function customized my REST API return adding a property 'authorName' and  'userNoteCount'
function university_custom_rest(){
    register_rest_field('post', 'authorName', [
        'get_callback' => function(){ return get_the_author();}
    ]);
    //check and returns in response how many notes my user already has
    register_rest_field('note', 'userNoteCount', [
        'get_callback' => function(){ return count_user_posts(get_current_user_id(), 'note');}
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

    #I need this line because I'm creating my block inside my theme and alredy have my css
    add_theme_support('editor-styles'); 
    add_editor_style(['htpps://fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i','build/style-index.css', 'build/index.css']);
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

# Force note posts to be private
function makeNotePrivate($data, $postarr){
    if($data['post_type'] == 'note'){
        //checks if my user has reached the posting limit and if this request is to create, in other words, if this post don't have id yet
        if(count_user_posts(get_current_user_id(), 'note') > 4 && !$postarr['ID']){
            die("You have reached your note limit.");
        }
        $data['post_content'] = sanitize_textarea_field($data['post_content']); //sanitize my textarea to my user can't use any html
        $data['post_title'] = sanitize_text_field($data['post_title']); //sanitize my title to my user can't use any html

    }
    if($data['post_type'] == 'note' && $data['post_status'] != 'trash'){
        $data['post_status'] = 'private'; //add my private note
    }
    return $data;
}
//This hook run when you want insert and update too
//this parameter '2' indicates that my 'makeNotePrivate' function will have 2 parameters, instead of 1 by default. 
//And the '10' is the priority when returning the function, this is a problem if you are going to call many functions for the same hook, in this case each call to the hook I need to define which fn I want to execute first (the smallest number executes first)
add_filter('wp_insert_post_data', 'makeNotePrivate', 10, 2);

#Ignore some files and folders in my export with my plugin all-in-one-wp-migration
#This not working very well... 
// function ignoreCertainFiles($exclude_filters){
//     $exclude_filters[] = 'themes/fictional-university-theme/node_modules';
//     return $exclude_filters;
// }
// add_filter('ai1wm_exclude_content_from_export', 'ignoreCertainFiles');

#I tried this new hook and works
function ignoreCertainFiles($exclude_filters){
    $exclude_filters[] = 'fictional-university-theme/node_modules';
    $exclude_filters[] = 'twentytwentyone';
    $exclude_filters[] = 'twentytwentytwo';
    return $exclude_filters;
}
add_filter('ai1wm_exclude_themes_from_export', 'ignoreCertainFiles');

#Add custom blocks without JSX, things more statics
class PlaceholderBlock{
    function __construct($name){
        $this->name = $name;
        add_action('init', [$this, 'onInit']);
    }

    //define our callback
    function ourRenderCallback(
        $attributes, //When WP call this fn, he will send to function any attributes from my block
        $content //In addition to the attributes we want the content because inside our block there are other nested blocks, so we have to highlight the content
    ){
        ob_start();
            require get_theme_file_path("/our-blocks/{$this->name}.php");
        return ob_get_clean();
    }

    function onInit(){
        wp_register_script($this->name, get_stylesheet_directory_uri() . "/our-blocks/{$this->name}.js", ['wp-blocks', 'wp-editor']);

        register_block_type("ourblocktheme/{$this->name}", [
            'editor_script' => $this->name, //The name of this property is a WP oficial, so needs to be exactly 'editor_script'
            'render_callback' => [$this, 'ourRenderCallback']
        ]);
    }
}
new PlaceholderBlock('eventsandblogs');
new PlaceholderBlock('header');
new PlaceholderBlock('footer');
new PlaceholderBlock('blocknumbers');
new PlaceholderBlock('blockboxes');
new PlaceholderBlock('singlepost');
new PlaceholderBlock('singlepage');
new PlaceholderBlock('blogindex');
new PlaceholderBlock('programarchive');
new PlaceholderBlock('singleprogram');
new PlaceholderBlock('singleprofessor');
new PlaceholderBlock('mynotes');
new PlaceholderBlock('eventarchive');
new PlaceholderBlock('singleevent');
new PlaceholderBlock('pastevents');
new PlaceholderBlock('campusarchive');
new PlaceholderBlock('singlecampus');
new PlaceholderBlock("search");
new PlaceholderBlock("searchresults");

# class to add custom blocks with JSX
class JSXBlock{
    function __construct(
        $name, 
        $renderCallback = null,//set to null so the parameter is optional 
        $data = null //set to null so the parameter is optional 
    ){
        $this->name = $name;
        $this->renderCallback = $renderCallback;
        $this->data = $data;
        add_action('init', [$this, 'onInit']);
    }

    //define our callback
    function ourRenderCallback(
        $attributes, //When WP call this fn, he will send to function any attributes from my block
        $content //In addition to the attributes we want the content because inside our block there are other nested blocks, so we have to highlight the content
    ){
        ob_start();

        //call a file with my HTML an variable with the content of my param $data
        require get_theme_file_path("/our-blocks/{$this->name}.php");

        return ob_get_clean();
    }

    function onInit(){
        #create my .js of my bannerblock
        wp_register_script($this->name, get_stylesheet_directory_uri() . "/build/{$this->name}.js", ['wp-blocks', 'wp-editor']);
        
        //this will send in my page an 
        if($this->data){
            wp_localize_script($this->name, $this->name, $this->data);
        }

        $ourArgs = [
            'editor_script' => $this->name //The name of this property is a WP oficial, so needs to be exactly 'editor_script'
        ];

        //Check if my property renderCallback is equals true
        if($this->renderCallback){
            //We'll call a function to set my render_callback. and the name of my property needs be exactly render_callback  is a WP official name
            $ourArgs['render_callback'] = [$this, 'ourRenderCallback'];
       }
        register_block_type("ourblocktheme/{$this->name}", $ourArgs);
    }
}
new JSXBlock('banner', true, ['fallbackimage' => get_theme_file_uri('/images/library-hero.jpg')]); //the optional 'true' indicate that we'll use PHP render callback, the 3 param will give to my banner.js an path to default image, because without this, when I create a new block in admin, my background is null
new JSXBlock('genericheading', true);
new JSXBlock('genericbutton', true);
new JSXBlock('slideshow', true); //it's my slide parent
new JSXBlock('slide', true, ['themeimagepath' => get_theme_file_uri('/images/') ]); // will appears inside my slideshow. I'm send the 3 param to my .js can see the currently theme value, so I'll define my images using a global path, that will work in all cases/configurations, even the user install WP in/out of root domain 

#this function manage which blocks you can use within your page or within full site editor
function myallowedblocks($allowed_block_types, $editor_context){
    return $allowed_block_types; //You can use all blocks
    #-----------------EXAMPLES:
    //return ["ourblocktheme/header", "ourblocktheme/footer"]; //return that you can use only this blocks in all environments
    // if(!empty($editor_context->post)){ 
    //     return $allowed_block_types; // if I'm working with a post/page editor screen, I can use all block types
    // }
    //return ["ourblocktheme/header", "ourblocktheme/footer", "core/paragraph"]; //if I am on the Full Site Editor (FSE) screen
    
    // if(!empty($editor_context->post->post_type == 'professor')){ 
    //     #... your condition
    // }
}
add_filter(
    'allowed_block_types_all', //name of WP filter hook
    'myallowedblocks', //your function
    10, //priority
    2 //the numbers of arguments the function will use
);