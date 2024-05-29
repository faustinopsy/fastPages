<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $pages = $_POST['pages'];

    function generate_menu($pages) {
        $menu = '<nav><div class="nav-wrapper"><ul id="nav-mobile" class="right hide-on-med-and-down">';
        foreach ($pages as $page_name => $page_content) {
            $menu .= '<li><a href="' . htmlspecialchars($page_name) . '.html">' . htmlspecialchars($page_name) . '</a></li>';
        }
        $menu .= '</ul></div></nav>';
        return $menu;
    }

    $menu = generate_menu($pages);

    foreach ($pages as $page_name => $page_content) {
        $html = '<!DOCTYPE html>
        <html lang="pt-BR">
            <title>' . htmlspecialchars($page_name) . '</title>
            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
            <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0"/>
            <link href="css/materialize.min.css" rel="stylesheet">
        </head>
        <body>
            ' . $menu . '
            <div class="container">
                <h1>' . htmlspecialchars($page_name) . '</h1>';
        if (isset($page_content['sections'])) {
            $html .= '<div class="row">';
            foreach ($page_content['sections'] as $section) {
                $html .= '<div class="section col s12 m4">
                    <p>' . htmlspecialchars($section) . '</p>
                </div>';
            }
        }
        $html .= '</div>';
        $html .= '</div>
            <script src="js/materialize.min.js"></script>
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
        file_put_contents($page_name . '.html', $html);
    }
    echo '<div class="container"><h3>Páginas geradas com sucesso!</h3></div>';
}
