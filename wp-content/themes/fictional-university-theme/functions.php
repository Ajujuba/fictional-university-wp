<?php

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

function load_jquery_ui() {
    // wp_enqueue_style( 'jquery-ui-css', 'https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css', array(), '1.12.1' );
    // wp_enqueue_script( 'jquery', 'https://code.jquery.com/jquery-3.6.0.min.js', array(), '3.6.0', true );
    // wp_enqueue_script( 'jquery-ui-js', 'https://code.jquery.com/ui/1.12.1/jquery-ui.min.js', array('jquery'), '1.12.1', true );

    // Register jQuery and DatePickerRange
    wp_enqueue_script( 'jquery', 'https://cdn.jsdelivr.net/jquery/latest/jquery.min.js', array(), null, true );
    wp_enqueue_script( 'moment-js', 'https://cdn.jsdelivr.net/momentjs/latest/moment.min.js', array('jquery'), null, true );
    wp_enqueue_script( 'daterangepicker-js', 'https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js', array('jquery', 'moment-js'), null, true );
    wp_enqueue_style( 'daterangepicker-css', 'https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css', array(), null );
}
add_action( 'wp_enqueue_scripts', 'load_jquery_ui' );

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
    $exclude_filters[] = 'twentytwentythree';
    $exclude_filters[] = 'fictional-university-block-theme';
    return $exclude_filters;
}
add_filter('ai1wm_exclude_themes_from_export', 'ignoreCertainFiles');

#register my script to use in Events card & Map
function enqueue_custom_script_events() {
    //Load my script only if the page is page-map-test or events-card-test
    if (is_page(array('page-map-test', 'events-card-test', 'map-test'))) {
        wp_enqueue_script('custom-script', get_template_directory_uri() . '/assets/js/custom-script.js', array(), null, true);

        // Define the data you want to send to your JS script
        $script_data = array(
            'admin_ajax_url' => esc_url(admin_url('admin-ajax.php')),
            'theme_path' => get_template_directory_uri(),
        );

        // Finds the script and sends the data
        wp_localize_script('custom-script', 'customScriptData', $script_data);
    }

    if(is_front_page() || is_home()){
        wp_enqueue_script('custom-home-blog-page', get_template_directory_uri() . '/assets/js/custom-home-blog-page.js', array(), null, true);

        // Define the data you want to send to your JS script
        $script_data = array(
            'admin_ajax_url' => esc_url(admin_url('admin-ajax.php')),
            'theme_path' => get_template_directory_uri(),
        );

        // Finds the script and sends the data
        wp_localize_script('custom-home-blog-page', 'customScriptData', $script_data);
    }
}
add_action('wp_enqueue_scripts', 'enqueue_custom_script_events');

function save_month_acf($post_id) {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE || !current_user_can('edit_post', $post_id)) {
        return;
    }

    if (get_post_type($post_id) === 'post') {
        $start_date = get_field('event_date_inicio', $post_id);

        if ($start_date) {
            $month = date('m', strtotime($start_date));

            update_field('month_hidden', $month, $post_id);
        }
    }
}
add_action('save_post', 'save_month_acf');

function custom_event_filter_shortcode_output() {
    ob_start();
    get_template_part('template-parts/custom-event-filter');
    $content = ob_get_clean();
    return $content;
  }
  add_shortcode('custom_event_filter_shortcode', 'custom_event_filter_shortcode_output');
  
#Search my events filtered for cards
function custom_event_filter_shortcode() {
    ob_start();
    $today = date('Ymd');

    $filter = isset($_POST['filterCheck']) ? sanitize_text_field($_POST['filterCheck']) : 'venir'; // Current filter
    $location_filter = isset($_POST['golf-location']) ? sanitize_text_field($_POST['golf-location']) : 'all';
    $month_filter = isset($_POST['filter-month']) ? sanitize_text_field($_POST['filter-month']) : 'all';
    $page = isset($_POST['page']) ? intval($_POST['page']) : 1; // Current Page

    $args = array(
        'post_type' => 'post',
        'meta_key' => 'event_date_inicio',
        'posts_per_page' => 3,
        'paged' => $page,
    );

    if ($filter === 'venir') {
        $args['meta_query'] = array(
            array(
                'key' => 'event_date_inicio',
                'compare' => '>=',
                'value' => $today,
                'type' => 'numeric'
            )
        );
        $args['orderby'] = 'event_date_inicio';
		$args['order'] = 'ASC';
    } elseif ($filter === 'passe') {
        $args['meta_query'] = array(
            array(
                'key' => 'event_date_inicio',
                'compare' => '<',
                'value' => $today,
                'type' => 'numeric'
            )
        );
        $args['orderby'] = 'event_date_inicio';
		$args['order'] = 'DESC';
    }

    if ($location_filter !== 'all') {
        $args['meta_query'][] = array(
            'key' => 'localization_competitions',
            'value' => $location_filter,
            'compare' => '=',
        );
    }

	if ($month_filter !== 'all') {
		$args['meta_query'][] = array(
			'key' => 'month_hidden',
			'value' => $month_filter,
			'compare' => '=',
		);

	}

    $query = new WP_Query($args);

   if ($query->have_posts()) {
        echo '<div class="row row-cols-1 row-cols-md-3">'; // Start Bootstrap row div
        $count = 0; // Start counting cards per line
        while ($query->have_posts()) : $query->the_post();
            get_template_part('template-parts/content', 'card', array('filter' => $filter));
            $count++;
        endwhile;
        echo '</div>'; // Close the last line

		$total_posts = $query->found_posts;
		$posts_per_page = $args['posts_per_page'];
		$max_pages = ceil($total_posts / $posts_per_page);
	
		echo '<div class="pagination" data-max-pages="' . $max_pages . '">';

		// Add a button to the previous page
		$prev_page = $page - 1;
		echo '<div class="load-prev-button" data-prev-page="' . $prev_page . '"> <- </div>';

		// Always show the first page
		echo '<div class="pagination-buttons page-button';
		if ($page === 1) {
			echo ' current-page';
		}
		if($page !== 1){
			echo '" data-page="1">1</div>';
		}

		//Show dots if there are more than 2 pages
		if ($max_pages > 3 && $page !== 1 && $page !== 2 && $page !== 3) {
			echo '<div class="pagination-buttons-dots">...</div>';
		}

		// Show the previous page if it's within the visible range
		if ($page > 2) {
			echo '<div class="pagination-buttons page-button';
			if ($page - 1 === $page) {
				echo ' current-page';
			}
			echo '" data-page="' . ($page - 1) . '">' . ($page - 1) . '</div>';
		}

		// Show the current page
		echo '<div class="pagination-buttons page-button current-page" data-page="' . $page . '">' . $page . '</div>';

		// Show the next page if it's within the visible range
		if ($page < $max_pages - 1) {
			echo '<div class="pagination-buttons page-button';
			if ($page + 1 === $page) {
				echo ' current-page';
			}
			echo '" data-page="' . ($page + 1) . '">' . ($page + 1) . '</div>';
		}
		
		// Show dots if there are more than 3 pages
		if ($max_pages > 3 && $page != $max_pages && $page != $max_pages - 1  && $page != $max_pages - 2) {
			echo '<div class="pagination-buttons-dots">...</div>';
		}

		// Show the last page
		if($page != $max_pages){
			echo '<div class="pagination-buttons page-button';
			if ($page === $max_pages) {
				echo ' current-page';
			}
			echo '" data-page="' . $max_pages . '">' . $max_pages . '</div>';
		}
		
		// Add a button for the next page
		$next_page = $page + 1;
		echo '<div class="load-more-button" data-next-page="' . $next_page . '"> -> </div>';
		echo '</div>';

        wp_reset_postdata();
    } else {
        //$current_language = pll_current_language();
        $current_language = get_locale(); //Get the WP language
        $custom_translations = defined('CUSTOM_TRANSLATIONS') ? CUSTOM_TRANSLATIONS : array();
        $pasTrouve_label = isset($custom_translations[$current_language]['pasTrouve']) ? $custom_translations[$current_language]['pasTrouve'] : "Pas d'événements trouvés pour votre recherche.";
        echo $pasTrouve_label;
    }

	$content = ob_get_clean();
    echo $content;
    exit;
}
add_action('wp_ajax_custom_event_filter', 'custom_event_filter_shortcode');
add_action('wp_ajax_nopriv_custom_event_filter', 'custom_event_filter_shortcode');

function load_custom_script_in_admin() {
    wp_enqueue_script('custom-script-automatic-coord', get_theme_file_uri('/assets/js/custom-script-automatic-coord.js'), array(), null, true);
}
add_action('admin_enqueue_scripts', 'load_custom_script_in_admin');

function set_custom_translations() {
    $translations = array(
        'en_US' => array(
            'filtrer' => 'Filter by',
            'venir' => 'UPCOMING',
            'passes' => 'PAST',
            'rechercher' => 'Search',
            'decouvrir' => 'Discover',
            'passe' => 'PAST',
            "mois" => "Month",
            "jan" => "January",
            "feb" => "February",
            "mar" => "March",
            "may" => "May",
            "apr" => "April",
            "jun" => "June",
            "jul" => "July ",
            "aug" => "August",
            "sep" => "September ",
            "oct" => "October",
            "nov" => "November ",
            "dec" => "December",
            "au"  => "to",
            "pasTrouve" => "No events found for your search.",
        ),
        'fr_FR' => array(
            'filtrer' => 'Filtrer par',
            'venir' => 'À VENIR',
            'passes' => 'PASSÉS',
            'rechercher' => 'Rechercher',
            'decouvrir' => 'Découvrir',
            'passe' => 'PASSÉ',
            "mois" => "Mois",
            "jan" => "Janvier",
            "feb" => "Février",
            "mar" => "Mars",
            "may" => "Avril",
            "apr" => "Mai",
            "jun" => "Juin",
            "jul" => "Juillet ",
            "aug" => "Aout",
            "sep" => "Septembre ",
            "oct" => "Octobre",
            "nov" => "Novembre ",
            "dec" => "Décembre",
            "au"  => "au",
            "pasTrouve" => "Pas d'événements trouvés pour votre recherche.",
        ),
    );
  
    return $translations;
}

define('CUSTOM_TRANSLATIONS', set_custom_translations());


add_action('wp_ajax_filter_posts', 'filter_posts');
add_action('wp_ajax_nopriv_filter_posts', 'filter_posts');

function filter_posts() {
    $postName = isset($_GET['post_name']) ? $_GET['post_name'] : '';
    $postDate = isset($_GET['post_date']) ? $_GET['post_date'] : '';
    
    $dates = explode(' - ', $postDate);
    $startDate = isset($dates[0]) ? date('Y-m-d', strtotime($dates[0])) : '';
    $endDate = isset($dates[1]) ? date('Y-m-d', strtotime($dates[1])) : '';

    $args = array(
        'post_type' => 'post',
        'posts_per_page' => -1,
        's' => $postName,
        'date_query' => array(
            array(
                'after' => $startDate,
                'before' => $endDate,
                'inclusive' => true
            )
        )
    );

    $query = new WP_Query($args);

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            ?>
            <div class="post-item">
                <h2 class="headline headline--medium headline--post-title"><a href="<?php the_permalink()?>"><?php the_title() ?></a></h2>
                <div class="metabox">
                    <p>Posted by: <?php the_author_posts_link() ?> on <?php the_time('j/n/Y')?> in <?= get_the_category_list(', ') ?> </p>
                </div>
                <div class="generic-content">
                    <?php the_excerpt()?>
                    <p><a class="btn btn--blue" href="<?php the_permalink() ?>">Continnue Reading &raquo;</a></p>
                </div>
            </div>
            <?php
        }
        wp_reset_postdata();
    } else {
        echo '<p>Not found. Please search other thing.</p>';
    }

    wp_die();
}
