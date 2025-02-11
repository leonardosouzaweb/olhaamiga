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

    const btnCupons = document.getElementById("btnradio1");
    const btnOfertas = document.getElementById("btnradio2");
    const labelCupons = document.querySelector("label[for='btnradio1']");
    const labelOfertas = document.querySelector("label[for='btnradio2']");
    const cuponsSection = document.querySelector(".cuponsIntern");
    const ofertasSection = document.querySelector(".ofertasIntern");

    function atualizarContagens() {
        const totalCupons = document.querySelectorAll(".cuponsIntern .shadowCustom").length;
        const totalOfertas = document.querySelectorAll(".ofertasIntern .shadowCustom").length;

        labelCupons.innerText = `Cupons (${totalCupons})`;
        labelOfertas.innerText = `Ofertas (${totalOfertas})`;
    }

    function marcarEscrollar(botao, secao) {
        btnCupons.checked = false;
        btnOfertas.checked = false;

        botao.checked = true;

        if (secao) {
            secao.scrollIntoView({ behavior: "smooth", block: "start" });
        }
    }

    btnCupons.addEventListener("click", function () {
        marcarEscrollar(btnCupons, cuponsSection);
    });

    btnOfertas.addEventListener("click", function () {
        marcarEscrollar(btnOfertas, ofertasSection);
    });

    atualizarContagens();

    document.querySelectorAll(".description-container").forEach(container => {
        let description = container.querySelector(".description");
        let showMore = container.querySelector(".show-more");

        // Cria um clone para medir altura real
        let clone = description.cloneNode(true);
        clone.style.display = "block";
        clone.style.position = "absolute";
        clone.style.visibility = "hidden";
        clone.style.width = description.clientWidth + "px";
        clone.style.whiteSpace = "normal";
        document.body.appendChild(clone);

        if (clone.clientHeight > description.clientHeight) {
            showMore.style.display = "inline";
        }

        document.body.removeChild(clone);
    });
});

function toggleDescription(button) {
    let container = button.closest(".description-container");
    let description = container.querySelector(".description");

    if (description.style.display === "-webkit-box") {
        description.style.display = "block";
        button.innerText = "Menos informações";
    } else {
        description.style.display = "-webkit-box";
        button.innerText = "Mais informações";
    }
}

if (localStorage.getItem('cookieConsent') === 'accepted') {
    document.getElementById('cookieConsent').style.display = 'none';
}

document.getElementById('acceptCookies').addEventListener('click', function () {
    localStorage.setItem('cookieConsent', 'accepted');
    document.getElementById('cookieConsent').style.display = 'none';
});