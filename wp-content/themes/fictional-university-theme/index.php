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
        <form id="post-filter" action="" method="get">
            <label for="post-name">Filter by Post Name:</label>
            <input type="text" id="post-name" name="post_name" placeholder="Enter post name">
            
            <label for="post-date">Filter by Post Date:</label>
            <input type="text" id="post-date" name="post_date" placeholder="Select date range">
            
            <input type="submit" value="Apply Filters">
            <input type="button" id="clear-filters" value="Clear Filters">

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

<script>

jQuery(document).ready(function() {
    // Load datepicker with range dates
    jQuery('#post-date').daterangepicker({
        opens: 'left'
    }, function(start, end, label) {
        console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
    });
});

jQuery(document).ready(function($) {

    $('#clear-filters').click(function() {
        $('#post-name').val('');
        $('#post-date').val('');

        window.location.reload();
    });

    $('#post-filter').submit(function(event) {
        event.preventDefault();
        
        var postName = $('#post-name').val();
        var postDate = $('#post-date').val();
        console.log(postDate)
        // Enviar solicitação AJAX para buscar os posts filtrados
        $.ajax({
            url: '<?php echo admin_url('admin-ajax.php'); ?>',
            type: 'GET',
            data: {
                action: 'filter_posts',
                post_name: postName,
                post_date: postDate
            },
            success: function(response) {
                $('.resultsSearchBlog').empty();
                //console.log(response);
                $('.resultsSearchBlog').html(response);
            },
            error: function(xhr, status, error) {
                console.log(xhr.responseText);
            }
        });
    });
});

</script>

<?php
get_footer();
?>