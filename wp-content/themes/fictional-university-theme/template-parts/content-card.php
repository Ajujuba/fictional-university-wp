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
    }

    .page-button{
        padding: 12px;
        width: 40px;
        height: 40px;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .page-button:hover {
        background-color: #ddd;
    }

    .pagination .current {
        border: 2px solid black;
        color: black;
    }

</style>
<?php
    $thumbnail_url = get_the_post_thumbnail_url();

    $event_date_inicio = get_field('event_date_inicio');
    $formatted_date = date('d', strtotime($event_date_inicio));

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
                <span>Your addreess here...</span>
                    |
                <span> <?= $formatted_date ?> au <?= get_field('event_date_fim') ?> </span>
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
<div class="">
    <div class="row">
        <span class="title-filter">FILTRER PAR:</span>
    </div>
    <div class="boxFilter">
        <span class="label-filter">À VENIR</span>
        <label class="switch">
            <input id="filterCheckbox" type="checkbox">
            <span class="slider round"></span>
        </label>
        <span class="label-filter">PASSÉ</span>
    </div>
</div>
<hr>
<div id="events-results"></div>
<script>
 document.addEventListener('DOMContentLoaded', function() {
    var filterCheckbox = document.getElementById('filterCheckbox');
    var resultsDiv = document.getElementById('events-results');
    var nextPage = 1;
    var prevPage = null;
    var maxPages = 1;

    // Função para carregar eventos com base no filtro e página
    function loadEvents(filter, page) {
        var formData = new FormData();
        formData.append('filterCheck', filter);
        formData.append('page', page);

        fetch('/fictional-university-wp/wp-admin/admin-ajax.php?action=custom_event_filter', {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(data => {
            resultsDiv.innerHTML = data;
            updatePaginationButtons();
        });
    }

    // Adicionar uma chamada AJAX inicial quando a página for carregada
    loadEvents('venir', nextPage);

    filterCheckbox.addEventListener('change', function() {
        // Determinar o filtro com base no estado do switch
        var selectedFilter = this.checked ? 'passe' : 'venir';
        nextPage = 1; // Reinicia a página ao trocar o filtro
        prevPage = null; // Reseta a página anterior
        loadEvents(selectedFilter, nextPage);
    });

    // Adicionar um listener para os botões "Carregar Mais" e "Voltar"
    document.addEventListener('click', function(event) {
        if (event.target.classList.contains('load-more-button')) {
            prevPage = nextPage;
            nextPage++;
            var selectedFilter = filterCheckbox.checked ? 'passe' : 'venir';
            loadEvents(selectedFilter, nextPage);
        } else if (event.target.classList.contains('load-prev-button')) {
            nextPage = prevPage;
            prevPage = (nextPage > 1) ? nextPage - 1 : null;
            var selectedFilter = filterCheckbox.checked ? 'passe' : 'venir';
            loadEvents(selectedFilter, nextPage);
        } else if (event.target.classList.contains('pagination-buttons')) {
            // Se clicar em uma página numerada, atualiza a classe 'current'
            nextPage = parseInt(event.target.dataset.page);
            prevPage = (nextPage > 1) ? nextPage - 1 : null;
            var selectedFilter = filterCheckbox.checked ? 'passe' : 'venir';
            loadEvents(selectedFilter, nextPage);
        }
    });

    // Função para atualizar a visibilidade dos botões de paginação
    function updatePaginationButtons() {
        var paginationButtons = document.querySelectorAll('.pagination-buttons');
        var loadMoreButton = document.querySelector('.load-more-button');
        var loadPrevButton = document.querySelector('.load-prev-button');

        maxPages = paginationButtons.length;

        if (nextPage === 1) {
            loadPrevButton.style.visibility = 'hidden';
        } else {
            loadPrevButton.style.visibility = 'visible';
        }

        if (nextPage >= maxPages) {
            loadMoreButton.style.visibility = 'hidden';
        } else {
            loadMoreButton.style.visibility = 'visible';
        }

        paginationButtons.forEach(function(button) {
            var pageNumber = parseInt(button.dataset.page);
            var selectedFilter = filterCheckbox.checked ? 'passe' : 'venir';

            if (pageNumber === nextPage) {
                button.classList.add('current');
            } else {
                button.classList.remove('current');
            }
        });
    }

    // Adiciona a classe 'current' à página 1 inicialmente
    var page1Button = document.querySelector('.pagination-buttons[data-page="1"]');
    if (page1Button) {
        page1Button.classList.add('current');
    }
});

</script> -->