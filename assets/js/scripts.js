const searchIcon = document.querySelector('.icSearch');
const searchInput = document.querySelector('.form-control');
const toggleIcon = document.querySelector('.icToggle');
const menuList = document.querySelector('.menu ul');

searchIcon.addEventListener('click', () => {
    searchInput.classList.toggle('active');
});

toggleIcon.addEventListener('click', () => {
    menuList.classList.toggle('active');
});

var baseUrl = window.location.origin.includes("localhost")
    ? window.location.origin + "/olhaamiga"
    : window.location.origin;

function abrirModal(button) {
    // Pegar os valores dos atributos do botão
    var titulo = button.getAttribute("data-titulo");
    var descricao = button.getAttribute("data-descricao");
    var codigo = button.getAttribute("data-codigo");
    var url = button.getAttribute("data-url");
    var logo = button.getAttribute("data-logo");

    // Debug para checar o caminho da imagem (remova depois de testar)
    console.log("Logo URL:", logo);

    // Preencher os elementos da modal com os dados corretos
    document.getElementById("modalTitulo").innerText = titulo;
    document.getElementById("modalDescricao").innerText = descricao;
    document.getElementById("modalCodigo").innerText = codigo;

    // Atualizar a imagem do logo corretamente
    var modalLogo = document.getElementById("modalLogo");
    if (logo && logo.trim() !== "") {
        modalLogo.src = baseUrl + "/" + logo; // Usa a variável global baseUrl
    } else {
        modalLogo.src = baseUrl + "/assets/images/uploads/lojas/default.svg";
    }
}

document.addEventListener("DOMContentLoaded", function () {
    const hiddenCodeDivs = document.querySelectorAll(".hiddenCode");

    hiddenCodeDivs.forEach(function (div) {
        div.addEventListener("click", function () {
            div.remove();
        });
    });

    document.querySelectorAll('a[target="_blank"]').forEach(link => {
        link.addEventListener("click", function (event) {
            event.preventDefault(); 

            const urlCupom = this.href; 
            const urlOrigem = window.location.href; 

            window.open(urlCupom, "_blank");
            window.location.href = urlOrigem;
        });
    });
});

if (localStorage.getItem('cookieConsent') === 'accepted') {
    document.getElementById('cookieConsent').style.display = 'none';
}

document.getElementById('acceptCookies').addEventListener('click', function () {
    localStorage.setItem('cookieConsent', 'accepted');
    document.getElementById('cookieConsent').style.display = 'none';
});