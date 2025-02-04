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
    ? window.location.origin + "/olha"
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
