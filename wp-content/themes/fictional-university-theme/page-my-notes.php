<?php

#Redirect my user if is not logged in
if(!is_user_logged_in()){
    wp_redirect(esc_url(site_url('/')));
    exit;
}

get_header();

while (have_posts()) {
    the_post();
    pageBanner(); ?>

    <div class="container container--narrow page-section">
        <div class="create-note">
            <h2 class="headline headline--medium">Create a new Note</h2>
            <input class="new-note-title" placeholder="Your title">
            <textarea class="new-note-body" placeholder="Write here your note..."></textarea>
            <span class="submit-note">Create Note</span>
            <span class="note-limit-message">Note limit reached: Delete an existing note to make room for a new one</span>
        </div>
        <ul class="min-list link-list" id="my-notes">
            <?php
                $userNotes = new WP_Query([
                    'post_type' => 'note',
                    'posts_per_page' => -1,
                    'author' => get_current_user_id(), //this line will get my notes only for my current logged in user
                ]);

                while($userNotes->have_posts()){
                    $userNotes->the_post(); ?>

                    <li data-id="<?php the_ID() ?>">
                        <input readonly class="note-title-field" value="<?= str_replace('Private: ','',esc_attr(get_the_title())) //this will show mt title without 'Private: '?>">
                        <span class="edit-note"><i class="fa fa-pencil" area-hidden="true"></i>Edit</span>
                        <span class="delete-note"><i class="fa fa-trash-o" area-hidden="true"></i>Delete</span>
                        <textarea readonly class="note-body-field"><?= esc_textarea(wp_strip_all_tags(get_the_content()))?></textarea>
                        <span class="update-note btn btn--blue btn--small"><i class="fa fa-arrow-right" area-hidden="true"></i>Save</span>
                    </li>

                <?php }
            ?>
        </ul>

    </div>

<?php }

get_footer();

?>
