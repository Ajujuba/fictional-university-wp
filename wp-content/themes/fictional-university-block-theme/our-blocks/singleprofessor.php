<?php 
    while(have_posts()){ //comment if you update to WP 6.4
        the_post(); //comment if you update to WP 6.4
        pageBanner();
    ?>

        <div class="container container--narrow page-section">
            <div class="generic-content">
                <div class="row group">
                    <div class="one-third">
                        <?php the_post_thumbnail('professorPortrait'); ?>
                    </div>
                    <div class="two-thirds">
                        <?php
                            // Count how many 'likes' the professor has
                            $likedCount = new WP_Query([
                                'post_type' => 'like',
                                'meta_query' => [
                                    [
                                        'key' => 'liked_professor_id',
                                        'compare' => '=' ,
                                        'value' => get_the_ID() 
                                    ]
                                ]
                            ]);

                            $existStatus = 'no';
                            //I'm making my query inside this if (user logged in) because this will fix the problem of full heart when my user is not logged in
                            if(is_user_logged_in()){
                                //check if my current user alredy liked this professor
                                $existQuery = new WP_Query([
                                    'post_type' => 'like',
                                    'author' => get_current_user_id(),
                                    'meta_query' => [
                                        [
                                            'key' => 'liked_professor_id',
                                            'compare' => '=' ,
                                            'value' => get_the_ID() 
                                        ]
                                    ]
                                ]);
                                if($existQuery->found_posts){
                                    //if there are 'likes' on this teacher, then we will change the 'data-exists' to =yes, and my css will try to change the classes to fill the heart. Otherwise, we will leave the 'data-exists' property = 'no' and the heart will be empty
                                    $existStatus = 'yes';
                                }
                            }
                        ?>
                        <span class="like-box" data-professor="<?php the_ID() ?>" data-exists="<?= $existStatus ?>" data-like="<?php if (isset($existQuery->posts[0]->ID)) echo $existQuery->posts[0]->ID; ?>">
                            <i class="fa fa-heart-o" aria-hidden="true"></i>
                            <i class="fa fa-heart" aria-hidden="true"></i>
                            <!-- found_post already counts the results of 'like' posts it found, and ignores pagination, so even if we have pagination it will count the total result -->
                            <sapn class="like-count"><?= $likedCount->found_posts?></span> 
                        </span>
                        <?php  the_content(); ?>
                    </div>
                </div>
            </div>

            <?php 
                $relatedPrograms = get_field('related_programs'); // retorna um bjeto do wordpress por isso posso usar dentro da get_the_title()

                if($relatedPrograms){
                    echo '<hr class="section-break">';
                    echo '<h3 class="headline headline--medium">Subjects(s) taught</h3>';
                    echo '<ul class="link-list min-list">';
                    foreach($relatedPrograms as $program){ ?>
                        <li>
                            <a href="<?= get_the_permalink($program)?>">
                                <?= get_the_title($program);?>
                            </a>
                        </li>
                    <?php }
                    echo '</ul>';
                }
            ?>
        </div>
    <?php
    } //comment if you update to WP 6.4

?>