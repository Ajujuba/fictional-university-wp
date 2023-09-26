<?php 

# Create new post type when load my hook init
function university_post_types(){
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
}
add_action('init', 'university_post_types');