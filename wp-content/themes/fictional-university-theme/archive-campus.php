<?php
get_header();
pageBanner([
    'title' => 'Our Campuses',
    'subtitle' => 'He have several convineniently located campuses'
])
?>

<div class="container container--narrow page-section">
    <ul class="link-list min-list">
        <?php
            while(have_posts()){
                the_post(); ?>
                <li>
                    <a href="<?php the_permalink() ?>" ><?php the_title(); the_field('map_location') ?></a>
                </li>
            <?php }
            echo paginate_links();
        ?>
    </ul>
</div>

<?php
get_footer();
?>
