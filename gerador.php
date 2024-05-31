<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $pages = $_POST['pagina'];
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Gerenciador de Páginas</title>
        <link href="public/css/materialize.min.css" rel="stylesheet">
    </head>
    <body>
        <div class="container">
            <h3>Inserir Conteúdo das Seções</h3>
            <form action="gerar_paginas.php" method="post">
                <?php foreach ($pages as $index => $page): ?>
                    <h4><?= htmlspecialchars($page['name']) ?></h4>
                    <?php for ($i = 1; $i <= $page['num_sections']; $i++): ?>
                        <div class="input-field">
                            <textarea name="pagina[<?= htmlspecialchars($page['name']) ?>][sections][<?= $i ?>]" class="materialize-textarea" required></textarea>
                            <label for="section_<?= $index ?>_<?= $i ?>">Conteúdo da Seção <?= $i ?></label>
                        </div>
                    <?php endfor; ?>
                <?php endforeach; ?>
                <button type="submit" class="btn waves-effect waves-light">Gerar Páginas</button>
            </form>
        </div>
        <script src="public/js/materialize.min.js"></script>
    </body>
    </html>
    <?php
}
