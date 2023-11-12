<?php
#the param of the function get_page_by_path() is the slug of the page, the last part of my link
$page1 = get_page_by_path('content-1');
$page2 = get_page_by_path('content-2');

?>
<div class="content-homepage">
    <div class=row-homepage>
        <div class="col-homepage col-left-homepage">
            <div class="title-card-homepage">
                <?= esc_html($page1->post_title)?>
                <div class="border-title-card-homepage"></div>
            </div>
            <div class="content-card-homepage text-left-homepage">
                <p> <?= esc_html(wp_trim_words($page1->post_content, 50, '...'))?></p>
            </div>
            <div class="t-center no-margin">
                <a class="animation-btn-homepage btn btn--blue" href="<?= $page1->guid ?>">See more</a>
            </div>
        </div>
        <div class="col-homepage col-right-homepage">
            <div class="title-card-homepage">
                <?= esc_html($page2->post_title)?>
                <div class="border-title-card-right-homepage"></div>
            </div>
            <div class="content-card-homepage text-right-homepage">
                <p> <?= esc_html(wp_trim_words($page2->post_content, 50, '...'))?></p>
            </div>
            <div class="t-center no-margin">
                <a class="animation-btn-homepage btn btn--yellow" href="<?= $page2->guid ?>">Learn more</a>
            </div>
        </div>
    </div>
</div>