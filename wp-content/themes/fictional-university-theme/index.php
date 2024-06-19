<?php
get_header();
pageBanner([
    'title' => 'Welcome to our blog',
    'subtitle' => 'Keep up with out latest news '
])
?>

<div class="container container--narrow page-section">
    <!-- Filtros -->
    <div class="filters">
        <form id="post-filter" action="" method="get" class="form-filter-post">
            <div>
                <label for="post-name">Filter by Post Name:</label>
                <input type="text" id="post-name" name="post_name" placeholder="Enter post name">
            </div>
            <div>
                <label for="post-date">Filter by Post Date:</label>
                <input type="text" id="post-date" name="post_date" placeholder="Select date range">
            </div>
            <input type="submit" value="Apply Filters" class="btn btn--small btn--blue">
            <input type="button" id="clear-filters" value="Clear Filters" class="btn btn--small btn--dark-orange">

        </form>
    </div>
    <div class="resultsSearchBlog">
        <?php
            while(have_posts()){
                the_post(); ?>
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
            <?php }
            echo paginate_links();
        ?>
    </div>
</div>

<?php
get_footer();
?>