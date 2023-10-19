<?php


function universityLikeRoutes(){
    //insert my like
    register_rest_route('university/v1', 'manageLike', [
        'methods' => 'POST',
        'callback' => 'createLike'
    ]);

    //remove my like
    register_rest_route('university/v1', 'manageLike', [
        'methods' => 'DELETE' ,
        'callback' => 'deleteLike'
    ]);
}
add_action('rest_api_init', 'universityLikeRoutes');

function createLike($data){

    //verify if my user is logged in or not
    if(is_user_logged_in()){ //if I don't use 'nonce' WP returns false in is_user_logged_in() for security
        $professor = sanitize_text_field($data['professorId']); //get my professorId of my request sent for my js

        $existQuery = new WP_Query([
            'post_type' => 'like',
            'author' => get_current_user_id(),
            'meta_query' => [
                [
                    'key' => 'liked_professor_id',
                    'compare' => '=' ,
                    'value' => $professor
                ]
            ]
        ]);
        //if 'like' doesn't already exists and the id is of a professor, then I'll create a new like post. Is important to each user like a professor only once
        if($existQuery->found_posts == 0 && get_post_type($professor) == 'professor'){
            //create my new post type: 'like'
            return wp_insert_post([
                'post_type' => 'like',
                'post_status' => 'publish', //define my status like publish, a complet post
                'post_title' => 'Our php create post test',
                'meta_input' => [
                    'liked_professor_id' => $professor //this key is the same name of my custom field that I created with ACF
                ]
            ]);
        }else{
            die('Invalid professor id');
        }
    }else{
        die('Only logged in users can create a like.');
    }   
}

function deleteLike($data){
    $likeId = sanitize_text_field($data['like']); //get my like of my request sent for my js

    //I'll delete if was my own like and the post type was 'like'
    if(get_current_user_id() == get_post_field('post_author', $likeId) && get_post_type($likeId) == 'like'){
        wp_delete_post($likeId, true);
        return 'Congrats, like deleted';
    }else{
        die("You don't have permission to delete that.");
    }
}