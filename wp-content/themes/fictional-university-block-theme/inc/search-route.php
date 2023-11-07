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
    #Get my posts according of my GET parameter 'term' -> this search is only in key fields, like title and content
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

    while($mainQuery->have_posts()){
        $mainQuery->the_post();
        // Get the post details I want from the post:
       
        if (get_post_type() == 'post' OR get_post_type() == 'page') {
            array_push($results['generalInfo'], array(
                'title' => get_the_title(),
                'permalink' => get_the_permalink(),
                'postType' => get_post_type(),
                'authorName' => get_the_author()
            ));
        }
    
        if (get_post_type() == 'professor') {
            array_push($results['professors'], array(
                'title' => get_the_title(),
                'permalink' => get_the_permalink(),
                'image' => get_the_post_thumbnail_url(0, 'professorLandscape')
            ));
        }
    
        if (get_post_type() == 'program') {

            #get related campuses
            $relatedCampuses = get_field('related_campus');
            if($relatedCampuses){
                foreach($relatedCampuses as $campus){
                    array_push($results['campuses'], [
                        'title' => get_the_title($campus),
                        'permalink' => get_the_permalink($campus),
                    ]);
                }
            }

            array_push($results['programs'], array(
                'title' => get_the_title(),
                'permalink' => get_the_permalink(),
                'id' => get_the_id()
            ));
        }
    
        if (get_post_type() == 'campus') {
            array_push($results['campuses'], array(
                'title' => get_the_title(),
                'permalink' => get_the_permalink()
            ));
        }
    
        if (get_post_type() == 'event') {
            $eventDate = new DateTime(get_field('event_date'));
            $description = null;
            if (has_excerpt()) {
                $description = get_the_excerpt();
            } else {
                $description = wp_trim_words(get_the_content(), 18);
            }
    
            array_push($results['events'], array(
                'title' => get_the_title(),
                'permalink' => get_the_permalink(),
                'month' => $eventDate->format('M'),
                'day' => $eventDate->format('d'),
                'description' => $description
            ));
        }
    }

    #Get the relashionship between programs, professors and events if exist programs
    if($results['programs']){
        $programsMetaQuery = ['relation' => 'OR'];

        foreach($results['programs'] as $item){
            array_push($programsMetaQuery, [
                    'key' => 'related_programs',
                    'compare' => 'LIKE',
                    'value' => '"' . $item['id'] . '"'
                ],
            );
        }
        $programsRelationshipQuery = new WP_Query([
            'post_type' => ['professor', 'event'],
            'meta_query' => $programsMetaQuery
        ]);

        while($programsRelationshipQuery->have_posts()){
            $programsRelationshipQuery->the_post();

            #Get related professors
            if(get_post_type() == 'professor'){
                array_push($results['professors'], [
                    'title' => get_the_title(),
                    'permalink' => get_the_permalink(),
                    'image' => get_the_post_thumbnail_url(0,'professorLandscape'),

                ]);
            }

            #Get related events
            if (get_post_type() == 'event') {
                $eventDate = new DateTime(get_field('event_date'));
                $description = null;
                if (has_excerpt()) {
                    $description = get_the_excerpt();
                } else {
                    $description = wp_trim_words(get_the_content(), 18);
                }
        
                array_push($results['events'], array(
                    'title' => get_the_title(),
                    'permalink' => get_the_permalink(),
                    'month' => $eventDate->format('M'),
                    'day' => $eventDate->format('d'),
                    'description' => $description
                ));
            }
        }

        #This line remove duplicated results of my array, for each professors be unique
    $results['professors'] = array_values(array_unique($results['professors'], SORT_REGULAR));
    $results['events'] = array_values(array_unique($results['events'], SORT_REGULAR));

    }

    return $results;
}