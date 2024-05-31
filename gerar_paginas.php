<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $pagina = $_POST['pagina'];
    $uploads_dir = 'uploads/';
    $acesso = 0;

    if (!is_dir($uploads_dir)) {
        mkdir($uploads_dir, 0777, true);
    }

    function gerar_menu_script($pagina) {
        $numero = 0;
        $menu_script = 'document.addEventListener("DOMContentLoaded", function() {
            let menuHtml = `
                <nav class="light-blue lighten-1" role="navigation">
                    <div class="nav-wrapper container">
                        <a id="logo-container" href="#" class="brand-logo">Logo</a>
                        <ul class="right hide-on-med-and-down">';

        foreach ($pagina as $pagina_nome => $conteudo_pagina) {
            if (is_int($pagina_nome)) {
                continue;
            }
            $menu_script .= '<li><a href="' . htmlspecialchars($pagina_nome) . '.html">' . htmlspecialchars($pagina_nome) . '</a></li>';
        }

        $menu_script .= '</ul><ul id="nav-mobile" class="sidenav">';

        foreach ($pagina as $pagina_nome => $conteudo_pagina) {
            if (is_int($pagina_nome)) {
                continue;
            }
            $numero++;
            $menu_script .= '<li><a href="' . htmlspecialchars($pagina_nome) . '.html"><i class="material-icons">filter_' . $numero . '</i>' . htmlspecialchars($pagina_nome) . '</a></li>';
        }

        $menu_script .= '</ul>
                    <a href="#" data-target="nav-mobile" class="sidenav-trigger"><i class="material-icons">menu</i></a>
                </div>
            </nav>`;

        document.getElementById("menu-container").innerHTML = menuHtml;
        M.Sidenav.init(document.querySelectorAll(".sidenav"));

        let footerHtml = `
            <footer class="page-footer orange">
                <div class="container">
                    <div class="row">
                        <div class="col l6 s12">
                            <h5 class="white-text">Company Bio</h5>
                            <p class="grey-text text-lighten-4">We are a team of college students working on this project like its our full time job. Any amount would help support and continue development on this project and is greatly appreciated.</p>
                        </div>
                        <div class="col l3 s12">
                            <h5 class="white-text">Settings</h5>
                            <ul>
                                <li><a class="white-text" href="#!">Link 1</a></li>
                                <li><a class="white-text" href="#!">Link 2</a></li>
                                <li><a class="white-text" href="#!">Link 3</a></li>
                                <li><a class="white-text" href="#!">Link 4</a></li>
                            </ul>
                        </div>
                        <div class="col l3 s12">
                            <h5 class="white-text">Connect</h5>
                            <ul>
                                <li><a class="white-text" href="#!">Link 1</a></li>
                                <li><a class="white-text" href="#!">Link 2</a></li>
                                <li><a class="white-text" href="#!">Link 3</a></li>
                                <li><a class="white-text" href="#!">Link 4</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="footer-copyright">
                    <div class="container">
                        Made by <a class="orange-text text-lighten-3" href="http://materializecss.com">Materialize</a>
                    </div>
                </div>
            </footer>`;

        document.getElementById("footer-container").innerHTML = footerHtml;
        });
        ';

        return $menu_script;
    }

    $menu_script = gerar_menu_script($pagina);
    file_put_contents('public/js/menuAndFooter.js', $menu_script);

    foreach ($pagina as $pagina_nome => $conteudo_pagina) {
        if (is_int($pagina_nome)) {
            continue;
        }
        if ($acesso === 0) {
            $acesso = $pagina_nome;
        }
        $html = '<!DOCTYPE html>
        <html lang="pt-BR">
            <head>
                <title>' . htmlspecialchars($pagina_nome) . '</title>
                <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
                <meta name="viewport" content="width=device-width, initial-scale=1"/>
                <link href="css/materialize.min.css" rel="stylesheet">
                <link href="css/style.css" type="text/css" rel="stylesheet" media="screen,projection"/>
            </head>
        <body>
            <div id="menu-container"></div>
            <div class="container">';
        if (isset($conteudo_pagina['sections'])) {
            $html .= '<div class="row"><br>';
            foreach ($conteudo_pagina['sections'] as $sectionIndex => $section) {
                $sectionType = $section['type'];
                $sectionContent = '';
                if ($sectionType === 'text') {
                    $sectionContent = '<p>' . htmlspecialchars($section['content']) . '</p>';
                } elseif ($sectionType === 'image') {
                    $fileTmpPath = $_FILES['pagina']['tmp_name'][$pagina_nome]['sections'][$sectionIndex]['content'];
                    $fileName = basename($_FILES['pagina']['name'][$pagina_nome]['sections'][$sectionIndex]['content']);
                    $filePath = $uploads_dir . $fileName;
                    if (move_uploaded_file($fileTmpPath, 'public/' . $filePath)) {
                        $sectionContent = '<img src="' . $filePath . '" alt="' . htmlspecialchars($pagina_nome) . '" style="width: 150px;height: 150px;">';
                    } else {
                        $sectionContent = '<p>Erro ao fazer upload da imagem.</p>';
                    }
                } elseif ($sectionType === 'form') {
                    $sectionContent = '
                        <form>
                            <div class="input-field">
                                <input type="text" id="name" name="name" required>
                                <label for="name">Nome</label>
                            </div>
                            <div class="input-field">
                                <input type="email" id="email" name="email" required>
                                <label for="email">Email</label>
                            </div>
                            <div class="input-field">
                                <textarea id="message" name="message" class="materialize-textarea" required></textarea>
                                <label for="message">Mensagem</label>
                            </div>
                            <button type="submit" class="btn waves-effect waves-light">Enviar</button>
                        </form>
                    ';
                }
                $html .= '<div class="section col s12 m6">
                            <div class="card">
                              <div class="card-content">'
                    . $sectionContent .
                    '</div>
                              <div class="card-action">
                              </div>
                            </div>
                          </div>';
            }
        }
        $html .= '</div>';
        $html .= '</div>
        <script src="js/jquery-2.1.1.min.js"></script>
        <script src="js/materialize.js"></script>
        <script src="js/init.js"></script>
        <script src="js/menuAndFooter.js"></script>
        <div id="footer-container"></div>
        </body>
        </html>';
        file_put_contents('public/' . $pagina_nome . '.html', $html);
    }
    echo '<div class="container"><h3>Páginas geradas com sucesso, na pasta public!</h3>
    <p><a class="orange-text text-lighten-3" href="public/' . $acesso . '.html">Acessar</a> </p></div>';
}
?>
