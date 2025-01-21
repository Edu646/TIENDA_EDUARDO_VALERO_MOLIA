<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Insertar Categoría</title>
</head>
<body>
    <h1>Insertar Categoría</h1>
    <form action="<?= BASE_URL ?>vista" method="post">
        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="nombre" required>
        <button type="submit">Insertar</button>
    </form>
</body>
</html>