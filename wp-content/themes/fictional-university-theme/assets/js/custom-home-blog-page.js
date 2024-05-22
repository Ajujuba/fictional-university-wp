console.log(customScriptData.admin_ajax_url);

//FRONT-PAGE
function validarFormulario() {
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