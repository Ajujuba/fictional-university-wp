<?php

# My REST API URL: https://localhost/fictional-university-wp/index.php/wp-json/university/v1/search

function universityRegisterFunction(){
    register_rest_route('university/v1', 'search',  [
        'methods' => WP_REST_SERVER::READABLE, //Here I can write 'GET', but this way is better and safesty
        'callback' => 'universitySearchResults' //here when called this function we passed many informations too, so in the function we will get this information in $data
    ]);
}
add_action('rest_api_init', 'universityRegisterFunction');


function universitySearchResults($data) {
    $mainQuery = new WP_Query([
        'post_type' => ['post', 'page', 'professor', 'event', 'campus', 'program'],
        's' => sanitize_text_field($data['term']) //The name here is a name that I put in my url EX: ...search?term=mylena

    ]);
    $results = [
        'generalInfo' => [],
        'professors' => [],
        'programs' => [],
        'events' => [],
        'campuses' => []
    ];

    /** Keys: slug of each post type
    *  Values: the desired label for the key to output the JSON
    */
    $labels = array(
        'post' => 'generalInfo',
        'page' => 'generalInfo',
        'professor' => 'professors',
        'program' => 'programs',
        'campus' => 'campuses',
        'event' => 'events'
    );

    while($mainQuery->have_posts()){
        $mainQuery->the_post();
        // Get the post details I want from the post:
        // if(get_post_type() == 'post' || get_post_type() == 'page'){
        //     array_push($results['generalInfo'], [
        //         'title' => get_the_title(),
        //         'permalink' => get_the_permalink()
        //     ]);
        // }
        // if(get_post_type() == 'professor'){
        //     array_push($results['professors'], [
        //         'title' => get_the_title(),
        //         'permalink' => get_the_permalink()
        //     ]);
        // }
        // if(get_post_type() == 'event'){
        //     array_push($results['events'], [
        //         'title' => get_the_title(),
        //         'permalink' => get_the_permalink()
        //     ]);
        // }
        // if(get_post_type() == 'campus'){
        //     array_push($results['campuses'], [
        //         'title' => get_the_title(),
        //         'permalink' => get_the_permalink()
        //     ]);
        // }
        // if(get_post_type() == 'program'){
        //     array_push($results['programs'], [
        //         'title' => get_the_title(),
        //         'permalink' => get_the_permalink()
        //     ]);
        // }

        //get date for my event
        $eventDate = new DateTime(get_field('event_date'));
        $description = null;
        //get if exists excerpt or not
        if(has_excerpt()){
            $description = get_the_excerpt();
        }else{
            $description = wp_trim_words(get_the_content(), 18);
        }

        # I could use the ifs above, but to clean the code I can use:
        $post_type_group = $labels[get_post_type()]; // This line determines the category in $results that the current post belongs to, based on the post type. It uses the $labels array to make this match.
        array_push($results[$post_type_group], array(
            'title' => get_the_title(),
            'permalink' => get_the_permalink(),
            'postType' => get_post_type(),
            'authorName' => get_the_author(),
            'image' => get_the_post_thumbnail_url(0,'professorLandscape'),
            'month' => $eventDate->format('M'),
            'day' => $eventDate->format('d'),
            'description' => $description
            

        )); //The current post details, such as the title and permalink, are added to the corresponding associative array in $results. The key is determined based on the post type and is stored in $post_type_group.
        
    }

    return $results;
}