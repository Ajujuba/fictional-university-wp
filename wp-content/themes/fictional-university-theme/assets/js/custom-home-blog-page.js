console.log(customScriptData.admin_ajax_url);

//BLOG PAGE
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
            url: customScriptData.admin_ajax_url ,
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

//FRONT-PAGE
function validateFormDonorbox() {
    var firstName = document.getElementById('first_name').value;
    var lastName = document.getElementById('last_name').value;
    var email = document.getElementById('email').value;
    var amount = document.getElementById('amount').value;

    var radioValue = document.querySelector('input[name="radioAmount"]:checked');
    // Verificar se o usuário escolheu uma opção de radio OU inseriu um valor no campo de texto
    if (!radioValue && amount.trim() === '') {
        alert('Por favor, escolha uma opção de doação ou insira um valor.');
        return false;
    }
    // Se o usuário escolheu uma opção de radio, usar o valor do radio, senão, usar o valor do campo de texto
    var selectedAmount = radioValue ? radioValue.value : amount;
    
    if (firstName.trim() === '' || lastName.trim() === '' || email.trim() === '') {
        alert('Por favor, preencha todos os campos.');
        return false;
    }

    var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
        alert('Por favor, insira um endereço de e-mail válido.');
        return false;
    }

    enviarParaDonorbox(selectedAmount);
}

function enviarParaDonorbox(amount) {
    // Obter os valores dos campos do formulário
    var firstName = document.getElementById('first_name').value;
    var lastName = document.getElementById('last_name').value;
    var email = document.getElementById('email').value;
    var language = document.getElementById('language').value;

    const possibleLanguages = ['fr', 'en'];

    if (!possibleLanguages.includes(language)) {
        console.error('Invalid Language');
        return;
    }

    // Construir a URL com os parâmetros preenchidos
    var donorboxURL = "https://donorbox.org/embed/donor-test-3?";
    donorboxURL += "first_name=" + encodeURIComponent(firstName);
    donorboxURL += "&last_name=" + encodeURIComponent(lastName);
    donorboxURL += "&email=" + encodeURIComponent(email);
    donorboxURL += "&amount=" + encodeURIComponent(amount);
    donorboxURL += "&language=" + encodeURIComponent(language);

    // Redirecionar para a URL da Donorbox
    window.location.href = donorboxURL;
}