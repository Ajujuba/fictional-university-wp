<?php

pageBanner([
    'title' => 'All Events',
    'subtitle' => 'See what is going on in our world'
]);
?>

<div class="container container--narrow page-section">
    <?php
        while(have_posts()){
            the_post(); 
            get_template_part('template-parts/content-event');
        }
        echo paginate_links();
    ?>
    <hr class="section-break">
    <p>Looking for a recap of past events?<a href="<?= site_url('index.php/past-events')?>"> Chech out our past events here </a> </p>
</div>