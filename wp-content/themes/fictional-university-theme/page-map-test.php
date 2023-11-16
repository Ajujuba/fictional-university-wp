<?php

    get_header();
    pageBanner();

    $custom_posts = get_posts(array(
        'post_type' => 'campus',
        'posts_per_page' => -1,
    ));

    $locations = array();

    foreach ($custom_posts as $post) {
        $location_data = array(
            'id'    => $post->ID,
            'title' => get_the_title($post->ID),
            'lat'   => get_field('latitude', $post->ID),
            'lon'   => get_field('longitude', $post->ID),
            'link'  => get_permalink($post->ID)
        );

        array_push($locations, $location_data);
       
    }
?>
    <script>
        //Send my php values to JS
        const locations = <?php echo json_encode($locations); ?>;
    </script>

    <div class="map-container">
        <div style="height:700px;" id="map"></div>
        <ul id="map-list" class="map-list scrollbar-hide"></ul>
    </div>

<?php 
    get_footer();
?>