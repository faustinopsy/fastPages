<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $pagina = $_POST['pagina'];
    $acesso=0;
    function gerar_menu($pagina) {
      $numero=0;
        $menu = '<nav class="light-blue lighten-1" role="navigation">
                  <div class="nav-wrapper container">
                  <a id="logo-container" href="#" class="brand-logo">Logo</a>
                  <ul class="right hide-on-med-and-down">';
        foreach ($pagina as $pagina_nome => $conteudo_pagina) {
            $menu .= '<li><a href="' . htmlspecialchars($pagina_nome) . '.html">' . htmlspecialchars($pagina_nome) . '</a></li>';
        }
        $menu .= '</ul><ul id="nav-mobile" class="sidenav">';
        foreach ($pagina as $pagina_nome => $conteudo_pagina) {
          $numero=$numero+1;
          
          $menu .= '<li><a href="' . htmlspecialchars($pagina_nome) . '.html">
          <i class="material-icons">filter_'.$numero.'</i>' . htmlspecialchars($pagina_nome) . '</a></li>';
      }
       $menu .= '</ul>
                  <a href="#" data-target="nav-mobile" class="sidenav-trigger">
                  <i class="material-icons">menu</i>
                  </a>
                  </div>
                  </nav>';
        return $menu;
    }

    $menu = gerar_menu($pagina);

    foreach ($pagina as $pagina_nome => $conteudo_pagina) {
      if($acesso===0){
        $acesso =  $pagina_nome;
      }
        $html = '<!DOCTYPE html>
        <html lang="pt-BR">
            <title>' . htmlspecialchars($pagina_nome) . '</title>
            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
            <meta name="viewport" content="width=device-width, initial-scale=1"/>
            <link href="css/materialize.min.css" rel="stylesheet">
            <link href="css/style.css" type="text/css" rel="stylesheet" media="screen,projection"/>
        </head>
        <body>
            ' . $menu . '
            <div class="container">';
        if (isset($conteudo_pagina['sections'])) {
            $html .= '<div class="row"><br>';
            foreach ($conteudo_pagina['sections'] as $section) {
                $html .= '<div class="section col s12 m6">
                            <div class="card">
                              <div class="card-content">
                    <p>' . htmlspecialchars($section) . '</p>
                    </div>
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
        </body>
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
      </footer>
        </html>';
        file_put_contents('public/'.$pagina_nome . '.html', $html);
    }
    echo '<div class="container"><h3>Páginas geradas com sucesso, na pasta public!</h3>
    <p><a class="orange-text text-lighten-3" href="public/'.$acesso.'.html">Acessar</a> </p></div>';
}
