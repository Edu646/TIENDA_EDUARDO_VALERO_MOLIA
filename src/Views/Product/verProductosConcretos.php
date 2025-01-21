<?php
// Conexión a la base de datos
$db = new PDO('mysql:host=localhost;dbname=miTienda', 'root', '');

// Obtener el ID de la categoría desde la URL
$categoria_id = $_GET['categoria_id'];

// Consulta para obtener los productos por categoría
$stmt = $db->prepare('SELECT 
    c.nombre AS categoria_nombre,
    p.nombre AS producto_nombre
FROM 
    categorias c
JOIN 
    productos p ON c.id = p.categoria_id;');
$stmt->bindParam(':categoria_id', $categoria_id, PDO::PARAM_INT);
$stmt->execute();

// Fetch de los resultados
$productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos de la Categoría</title>
</head>
<body>
    <h1>Productos de la Categoría</h1>
    <ul>
        <?php foreach ($productos as $producto): ?>
            <li><?php echo htmlspecialchars($producto['producto_nombre']); ?></li>
        <?php endforeach; ?>
    </ul>
    <a href="VerCategorias.php">Volver a Categorías</a>
</body>
</html>