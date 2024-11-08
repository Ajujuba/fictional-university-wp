<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
<style>
    .content-card {
        display: flex;
        flex-direction: column;
    }

    .card-content{
        width: 335px;
        overflow: hidden;
    }

    .card-img-top{
        width: 335px;
        height: 300px;
        background-repeat: no-repeat;
        background-size: cover;
        background-position: center center;
    }

    span.tag-card {
        background-color: #D9D9D9;
        width: 200px;
        height: 45px;
        font-size: 20px;
        font-weight: 400;
        line-height: 30px;
        display: flex;
        justify-content: center;
        align-items: center;
        margin-top: 80px;
    }

    .card-title{
        font-size: 18px;
    }

    .btn-card {
        background-color: lightseagreen;
        color: white;
        width: 200px;
        height: 40px;
        text-transform: uppercase;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .pagination {
        display: flex;
        justify-content: center;
        margin-top: 20px;
    }

    .load-more-button, .load-prev-button {
        padding: 10px;
        margin: 0 5px;
        background-color: #f1f1f1;
        border: 1px solid #ddd;
        text-decoration: none;
        color: black;
        cursor: pointer;
    }

    .page-button{
        padding: 12px;
        width: 40px;
        height: 40px;
        display: flex;
        justify-content: center;
        align-items: center;
        cursor: pointer;
    }

    .page-button:hover {
        background-color: #ddd;
    }

    .pagination .current {
        border: 2px solid black;
        color: black;
    }

    /* Descomment this part if you will not use bootstrap
    @media (min-width: 1400px){
        .container, .container-lg, .container-md, .container-sm, .container-xl, .container-xxl {
            max-width: 1320px;
        }
    }
    @media (min-width: 768px){
        .row-cols-md-3>* {
            flex: 0 0 auto;
            width: 33.33333333%;
        }
    }
        
    .row {
        --bs-gutter-x: 1.5rem;
        --bs-gutter-y: 0;
        display: flex;
        flex-wrap: wrap;
        margin-top: calc(-1 * var(--bs-gutter-y));
        margin-right: calc(-.5 * var(--bs-gutter-x));
        margin-left: calc(-.5 * var(--bs-gutter-x));
    } 

    .load-more-button, .load-prev-button {
        display: flex;
        justify-content: center;
        align-items: center;
        width: 30px;
    } */
   

</style>
<?php

    $current_language = get_locale();
    $custom_translations = defined('CUSTOM_TRANSLATIONS') ? CUSTOM_TRANSLATIONS : array();
    $decouvrir_label = isset($custom_translations[$current_language]['decouvrir']) ? $custom_translations[$current_language]['decouvrir'] : 'Découvrir';
    $au_label = isset($custom_translations[$current_language]['au']) ? $custom_translations[$current_language]['au'] : ' au ';
    $thumbnail_url = get_the_post_thumbnail_url();

    $event_date_inicio = get_field('event_date_inicio');
    $event_date_fim = get_field('event_date_fim');

    //If my start and end dates are the same or if final_date is empty, I only show the start_date
    if($event_date_fim == $event_date_inicio || empty($event_date_fim) ){
        $day_event = date('d/m/Y', strtotime($event_date_inicio));
    }else{
        $day_event = date('d', strtotime($event_date_inicio)) . " $au_label " . date('d/m/Y', strtotime($event_date_fim));

    }

    // $filter = isset($args['filter']) ? $args['filter'] : 'venir';
    // if($filter == 'passe'){
    //     $tagCard = 'PASSÉ';
    // }else{
    //     $tagCard = 'À VENIR';
    // }
    $filter = isset($args['filter']) ? $args['filter'] : 'venir';
    $tagCard_key = ($filter == 'passe') ? 'passe' : 'venir';
    $tagCard = isset($custom_translations[$current_language][$tagCard_key]) ? $custom_translations[$current_language][$tagCard_key] : '';
?>

<div class="content-card">
    <span class="tag-card"><?= $tagCard; ?></span>
    <div class="card-content">
        <div style="background-image: url(<?php echo esc_url($thumbnail_url); ?>)" class="card-img-top"></div>
        <div class="card-body-content">
            <h5 class="card-title">
                <span><?= get_field('localization_competitions') ?></span>
                        &bull;
                <span> <?= $day_event ?> </span>
            </h5>
            <p class="card-text"><?php the_title() ?></p>
        </div>
    </div>
    <a href="<?php the_permalink() ?>" class="btn-card"><?php echo  $decouvrir_label ?></a>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>


<!-- SHORTCODE TO FILTER EVENTS TEMPLETE (CUSTOM-EVENT-FILTER.PHP) -->
