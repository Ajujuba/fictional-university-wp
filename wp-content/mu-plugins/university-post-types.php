<?php 

# Create new post type when load my hook init
function university_post_types(){
    #Event post type
    register_post_type('event', [
        'capability_type' => 'event', //This line allows us to create unique permissions for the type of event, without it the permissions remain generic and are the same as those in the post
        'map_meta_cap' => true, //complements of my permission for event
        'show_in_rest' => true, //This enables the WP modern editor because I enabled JS in my custom type
        'supports' => ['title', 'editor', 'excerpt'], //if I don't specify 'editor', it will load the old editor. But if I remove this line and leave the top one, the new editor works
        'rewrite' => ['slug'=>'events'],
        'has_archive' => true,
        'public' => true,
        'labels' => [
            'name' => 'Events',
            'show_in_rest' => true,
            'add_new_item' => 'Add New Event',
            'edit_item' => 'Edit Event',
            'all_items' => 'All Events',
            'singular_name' => 'Event'
        ],
        'menu_icon' => 'dashicons-calendar-alt'
    ]);

    #Program post type
    register_post_type('program', [
        'show_in_rest' => true, 
        'supports' => ['title'],
        'rewrite' => ['slug'=>'programs'],
        'has_archive' => true,
        'public' => true,
        'labels' => [
            'name' => 'Programs',
            'show_in_rest' => true,
            'add_new_item' => 'Add New Program',
            'edit_item' => 'Edit Program',
            'all_items' => 'All Programs',
            'singular_name' => 'Program'
        ],
        'menu_icon' => 'dashicons-awards'
    ]);

    #Professor post type
    register_post_type('professor', [
        'show_in_rest' => true, //this line allows me to see this type of post in the WP REST API 
        'supports' => ['title', 'editor', 'thumbnail'],
        'public' => true,
        'labels' => [
            'name' => 'Professors',
            'show_in_rest' => true,
            'add_new_item' => 'Add New Professor',
            'edit_item' => 'Edit Professor',
            'all_items' => 'All Professors',
            'singular_name' => 'Professor'
        ],
        'menu_icon' => 'dashicons-welcome-learn-more'
    ]);

    #Campus post type
    register_post_type('campus', [
        'capability_type' => 'campus', //This line allows us to create unique permissions for the type of event, without it the permissions remain generic and are the same as those in the post
        'map_meta_cap' => true,
        'show_in_rest' => true, //this line allows me to see this type of post in the WP REST API 
        'supports' => ['title', 'editor', 'excerpt'], //if I don't specify 'editor', it will load the old editor. But if I remove this line and leave the top one, the new editor works
        'rewrite' => ['slug'=>'campuses'],
        'has_archive' => true,
        'public' => true,
        'labels' => [
            'name' => 'Campuses',
            'show_in_rest' => true,
            'add_new_item' => 'Add New Campus',
            'edit_item' => 'Edit Campus',
            'all_items' => 'All Campuses',
            'singular_name' => 'Campus'
        ],
        'menu_icon' => 'dashicons-location-alt'
    ]);

    #Notes post type
    register_post_type('note', [
        'capability_type' => 'note',
        'map_meta_cap' => true,
        'show_in_rest' => true, //this line allows me to see this type of post in the WP REST API 
        'supports' => ['title', 'editor'],
        'public' => false, // we don't want that our notes appears in public searchs or to users disconected 
        'show_ui' => true, //this line will show this in my admin dashboard
        'labels' => [
            'name' => 'Notes',
            'add_new_item' => 'Add New Note',
            'edit_item' => 'Edit Note',
            'all_items' => 'All Notes',
            'singular_name' => 'Note'
        ],
        'menu_icon' => 'dashicons-welcome-write-blog'
    ]);
    
}
add_action('init', 'university_post_types');