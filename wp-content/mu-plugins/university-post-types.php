<?php 

# Create new post type when load my hook init
function university_post_types(){
    #Event post type
    register_post_type('event', [
        'show_in_rest' => true, //isso permite o editor moderno do WP pq habilita o JS no meu tipo personalizado
        'supports' => ['title', 'editor', 'excerpt'], //se eu não especificar 'editor', vai carregar o editor antigo. Mas se eu tirar essa linha e deixar a de cima, funciona o editor novo
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
        'supports' => ['title', 'editor'],
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
        'show_in_rest' => true, 
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
    
}
add_action('init', 'university_post_types');