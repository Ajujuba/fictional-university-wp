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

<?php
    $current_language = get_locale();

    $custom_translations = defined('CUSTOM_TRANSLATIONS') ? CUSTOM_TRANSLATIONS : array();
    
    $filtrer_label = isset($custom_translations[$current_language]['filtrer']) ? $custom_translations[$current_language]['filtrer'] : 'Filtrer Par';
    $venir_label = isset($custom_translations[$current_language]['venir']) ? $custom_translations[$current_language]['venir'] : 'À VENIR';
    $passes_label = isset($custom_translations[$current_language]['passes']) ? $custom_translations[$current_language]['passes'] : 'PASSÉS';
    $rechercher_label = isset($custom_translations[$current_language]['rechercher']) ? $custom_translations[$current_language]['rechercher'] : 'Rechercher';
    $mois_option = isset($custom_translations[$current_language]['mois']) ? $custom_translations[$current_language]['mois'] : 'Mois';
    $jan_option = isset($custom_translations[$current_language]['jan']) ? $custom_translations[$current_language]['jan'] : 'Janvier';
    $feb_option = isset($custom_translations[$current_language]['feb']) ? $custom_translations[$current_language]['feb'] : 'Février';
    $mar_option = isset($custom_translations[$current_language]['mar']) ? $custom_translations[$current_language]['mar'] : 'Mars';
    $apr_option = isset($custom_translations[$current_language]['apr']) ? $custom_translations[$current_language]['apr'] : 'Avril';
    $may_option = isset($custom_translations[$current_language]['may']) ? $custom_translations[$current_language]['may'] : 'Mai';
    $jun_option = isset($custom_translations[$current_language]['jun']) ? $custom_translations[$current_language]['jun'] : 'Juin';
    $jul_option = isset($custom_translations[$current_language]['jul']) ? $custom_translations[$current_language]['jul'] : 'Juillet';
    $aug_option = isset($custom_translations[$current_language]['aug']) ? $custom_translations[$current_language]['aug'] : 'Aout';
    $sep_option = isset($custom_translations[$current_language]['sep']) ? $custom_translations[$current_language]['sep'] : 'Septembre';
    $oct_option = isset($custom_translations[$current_language]['oct']) ? $custom_translations[$current_language]['oct'] : 'Octobre';
    $nov_option = isset($custom_translations[$current_language]['nov']) ? $custom_translations[$current_language]['nov'] : 'Novembre';
    $dec_option = isset($custom_translations[$current_language]['dec']) ? $custom_translations[$current_language]['dec'] : 'Décembre';

?>
<div class="container-filter">
    <div class="boxFilter">
        <span class="title-filter"><?php echo  $filtrer_label ?>:</span>
        <div class="options-filter">
            <span class="label-filter"><?php echo $venir_label ?></span>
            <label class="switch">
                <input id="filterCheckbox" type="checkbox">
                <span class="slider round"></span>
            </label>
            <span class="label-filter"><?php echo  $passes_label ?></span>
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
            <option value="all"><?php echo  $mois_option ?></option>
            <option value="01"><?php echo  $jan_option ?></option>
            <option value="02"><?php echo  $feb_option ?></option>
            <option value="03"><?php echo  $mar_option ?></option>
            <option value="04"><?php echo  $apr_option ?></option>
            <option value="05"><?php echo  $may_option ?></option>
            <option value="06"><?php echo  $jun_option ?></option>
            <option value="07"><?php echo  $jul_option ?> </option>
            <option value="08"><?php echo  $aug_option ?></option>
            <option value="09"><?php echo  $sep_option ?> </option>
            <option value="10"><?php echo  $oct_option ?></option>
            <option value="11"><?php echo  $nov_option ?> </option>
            <option value="12"><?php echo  $dec_option ?></option>
        </select>

        <button type="submit"><?php echo  $rechercher_label ?></button>
    </form>
</div>
<hr>
<div id="events-results"></div>
