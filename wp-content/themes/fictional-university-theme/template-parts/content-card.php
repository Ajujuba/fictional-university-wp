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
    $thumbnail_url = get_the_post_thumbnail_url();

    $event_date_inicio = get_field('event_date_inicio');
    $event_date_fim = get_field('event_date_fim');

    if($event_date_fim == $event_date_inicio || empty($event_date_fim) ){
        $day_event = date('d/m/Y', strtotime($event_date_inicio));
    }else{
        $day_event = date('d', strtotime($event_date_inicio)) . " au " . date('d/m/Y', strtotime($event_date_fim));

    }

    $filter = isset($args['filter']) ? $args['filter'] : 'venir';
    if($filter == 'passe'){
        $tagCard = 'PASSÉ';
    }else{
        $tagCard = 'À VENIR';
    }
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
    <a href="<?php the_permalink() ?>" class="btn-card">Découvrir</a>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>


<!-- This need be in your widget HTML on page:
<style>
    /* The switch - the box around the slider */
    .switch {
        position: relative;
        display: inline-block;
        width: 60px;
        height: 34px;
        margin: 10px;
    }

    /* Hide default HTML checkbox */
    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    /* The slider */
    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        -webkit-transition: .4s;
        transition: .4s;
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 26px;
        width: 26px;
        left: 4px;
        bottom: 4px;
        background-color: white;
        -webkit-transition: .4s;
        transition: .4s;
    }

    input:checked+.slider {
        background-color: #2196F3;
    }

    input:focus+.slider {
        box-shadow: 0 0 1px #2196F3;
    }

    input:checked+.slider:before {
        -webkit-transform: translateX(26px);
        -ms-transform: translateX(26px);
        transform: translateX(26px);
    }

    /* Rounded sliders */
    .slider.round {
        border-radius: 34px;
    }

    .slider.round:before {
        border-radius: 50%;
    }

    span.title-filter {
        font-size: 20px;
        text-transform: uppercase;
        line-height: 30px;
        font-weight: 800;
    }
    span.label-filter {
        font-weight: 600;
        font-size: 20px;
        line-height: 24px;
    }
</style>
<div class="container-filter">
    <div class="boxFilter">
        <span class="title-filter">Filtrer par:</span>
        <div class="options-filter">
            <span class="label-filter">À VENIR</span>
            <label class="switch">
                <input id="filterCheckbox" type="checkbox">
                <span class="slider round"></span>
            </label>
            <span class="label-filter">PASSÉS</span>
        </div>
    </div>
    <form class="filter-form" id="filter-form">
        <select class="select-competition" name="golf-location" id="golf-location">
            <option value="all">Golf</option>
            <option value="Casa Green Golf">Casa Green Golf</option>
            <option value="Golf Teelal">Golf Teelal</option>
            <option value="Golf des Lacs">Golf des Lacs</opetion>
            <option value="Oued Fès Golf">Oued Fès Golf</option>
            <option value="Royal Golf Fès">Royal Golf Fès</option>
            <option value="Noria Golf">Noria Golf</option>
            <option value="Golf Les Dunes">Golf Les Dunes</option>
            <option value="Tazegzout Golf">Tazegzout Golf</option>
            <option value="Royal Golf El Jadida">Royal Golf El Jadida</option>
        </select>
        <select class="select-competition" name="filter-month" id="filter-month">
            <option value="all">Mois</option>
            <option value="01">Janvier</option>
            <option value="02">Février</option>
            <option value="03">Mars</option>
            <option value="04">Avril</option>
            <option value="05">Mai</option>
            <option value="06">Juin</option>
            <option value="07">Juillet </option>
            <option value="08">Aout</option>
            <option value="09">Septembre </option>
            <option value="10">Octobre</option>
            <option value="11">Novembre </option>
            <option value="12">Décembre</option>
        </select>

        <button type="submit">Rechercher</button>
    </form>
</div>
<hr>
<div id="events-results"></div> 
-->