<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $num_pages = $_POST['num_pages'];
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Gerenciador de Páginas</title>
        <link href="css/materialize.min.css" rel="stylesheet">
    </head>
    <body>
        <div class="container">
            <h3>Definir Páginas e Seções</h3>
            <form action="generate.php" method="post">
                <?php for ($i = 1; $i <= $num_pages; $i++): ?>
                    <div class="input-field">
                        <input type="text" name="pages[<?= $i ?>][name]" required>
                        <label for="page_name_<?= $i ?>">Nome da Página <?= $i ?></label>
                    </div>
                    <div class="input-field">
                        <input type="number" name="pages[<?= $i ?>][num_sections]" required>
                        <label for="num_sections_<?= $i ?>">Quantas Seções na Página <?= $i ?>?</label>
                    </div>
                <?php endfor; ?>
                <button type="submit" class="btn waves-effect waves-light">Próximo</button>
            </form>
        </div>
        <script src="js/materialize.min.js"></script>
    </body>
    </html>
    <?php
}
