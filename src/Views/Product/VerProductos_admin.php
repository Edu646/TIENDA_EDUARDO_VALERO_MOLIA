<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <style>
/* Estilos generales */
body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f9;
    margin: 0;
    padding: 0;
}

/* Estilo de la tabla */
table {
    width: 90%;
    margin: 0 auto;
    border-collapse: collapse;
    background-color: #fff;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
    overflow: hidden;
}

/* Cabecera de la tabla */
table th {
    padding: 12px;
    text-align: left;
    background-color:rgb(0, 0, 0);
    color: white;
    font-weight: bold;
}

/* Celdas de la tabla */
table td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid #ddd;
    color: #555;
}

/* Filas alternas */
table tr:nth-child(even) {
    background-color: #f9f9f9;
}

/* Estilo para las imágenes */
img {
    max-width: 100px;
    height: auto;
    border-radius: 8px;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
}

/* Texto cuando no hay imagen */
span {
    color: #888;
    font-style: italic;
}

/* Botón de eliminación */
button {
    background-color: #f44336;
    color: white;
    border: none;
    padding: 8px 16px;
    cursor: pointer;
    border-radius: 4px;
    transition: background-color 0.3s;
}

button:hover {
    background-color: #d32f2f;
}

/* Estilo para el formulario de eliminación */
form {
    margin: 0;
    padding: 0;
}

/* Alineación y estilo de los textos */
td, th {
    vertical-align: middle;
}

/* Estilos para las celdas de precio y stock */
td:nth-child(4), td:nth-child(5) {
    font-weight: bold;
    color: #333;
}

td:nth-child(4) {
    color: #4CAF50; /* Verde para precios */
}

td:nth-child(5) {
    color: #FF9800; /* Naranja para el stock */
}

/* Estilo para la categoría */
td:nth-child(2) {
    color: #2196F3; /* Azul para categorías */
}
</style>


</head>
<body>
    
<h1>Listado de Productos</h1>


<table>
    <thead>
        <tr>
            <th>Nombre</th>
            <th>Categoría</th>
            <th>Descripción</th>
            <th>Precio</th>
            <th>Stock</th>
            <th>Oferta</th>
            <th>Fecha</th>
            <th>Imagen</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($productos as $producto): ?>
            <tr>
                <td><?= htmlspecialchars($producto->getNombre()) ?></td>
                <td><?= htmlspecialchars($producto->getCategoriaId()) ?></td>
                <td><?= htmlspecialchars($producto->getDescripcion()) ?></td>
                <td><?= htmlspecialchars($producto->getPrecio()) ?></td>
                <td><?= htmlspecialchars($producto->getStock()) ?></td>
                <td><?= htmlspecialchars($producto->getOferta()) ?></td>
                <td><?= htmlspecialchars($producto->getFecha()) ?></td>
                <td>
                    <?php if ($producto->getImagen()): ?>
                        <img src="<?= htmlspecialchars($producto->getImagen()) ?>" alt="Imagen del producto" width="100">
                    <?php else: ?>
                        <span>No disponible</span>
                    <?php endif; ?>
                </td>
                <td>
                    <!-- Formulario de eliminación -->
                    <form action="<?= BASE_URL ?>/verP_admin" method="POST" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este producto?');">
                        <input type="hidden" name="producto_id" value="<?= htmlspecialchars($producto->getId()) ?>">
                        <button type="submit">Eliminar</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
</body>
</html>

