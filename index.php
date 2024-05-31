<!DOCTYPE html>
<html>
<head>
    <title>Gerenciador de Páginas</title>
    <link href="public/css/materialize.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h3>Gerenciador de Páginas</h3>
        <form action="processar.php" method="post">
            <div class="input-field">
                <input type="number" name="num_pages" id="num_pages" required>
                <label for="num_pages">Quantas páginas?</label>
            </div>
            <button type="submit" class="btn waves-effect waves-light">Próximo</button>
        </form>
    </div>
    <script src="public/js/materialize.min.js"></script>
</body>
</html>
