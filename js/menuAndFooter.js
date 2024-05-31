document.addEventListener("DOMContentLoaded", function() {

let menuHtml=`<nav class="materialize-red lighten-4" role="navigation">
<div class="nav-wrapper container">
    <a id="logo-container" href="#" class="brand-logo"><i class="material-icons">all_inclusive</i>FastPages</a>
    <ul class="right hide-on-med-and-down"></ul>
</div>
</nav>`


let footerHtml = `<footer class="page-footer materialize-red lighten-4">
    <div class="container">
        <div class="row">
            <div class="col l6 s12">
                <h5 class="white-text">Sobre a Aplicação</h5>
                <p class="grey-text text-lighten-4">Esta aplicação é um gerador de páginas dinâmico que permite criar e gerenciar conteúdo web de forma rápida e eficiente. Desenvolvida para facilitar a criação de sites sem necessidade de conhecimento avançado em programação.</p>
            </div>
            <div class="col l3 s12">
                <h5 class="white-text">Conectar</h5>
                <ul>
                    <li>
                        <a class="white-text" href="https://www.linkedin.com/in/faustinopsy/" target="_blank">
                            <i class="fab fa-linkedin"></i> LinkedIn
                        </a>
                    </li>
                    <li>
                        <a class="white-text" href="https://github.com/faustinopsy" target="_blank">
                            <i class="fab fa-github"></i> GitHub
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="footer-copyright">
        <div class="container">
            Thema do <a class="orange-text text-lighten-3" href="http://materializecss.com">Materialize</a>
        </div>
    </div>
</footer>`;


document.getElementById("menu-container").innerHTML = menuHtml;
document.getElementById("footer-container").innerHTML = footerHtml;

});