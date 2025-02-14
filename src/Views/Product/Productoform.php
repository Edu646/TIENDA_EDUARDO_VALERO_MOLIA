<?php
$conexion = new mysqli("localhost", "root", "", "tienda");

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

$sql = "SELECT id, nombre FROM categorias";
$resultado = $conexion->query($sql);

$categorias = [];

if ($resultado->num_rows > 0) {
    while ($fila = $resultado->fetch_assoc()) {
        $categorias[] = $fila;
    }
}

$conexion->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Añadir Producto</title>
    <style>
        .form-container {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
            margin: auto;
        }

        .form-container h1 {
            text-align: center;
            color: #333;
        }

        .form-container label {
            display: block;
            margin-top: 10px;
            font-weight: bold;
        }

        .form-container input, 
        .form-container textarea, 
        .form-container select, 
        .form-container button {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .form-container button {
            background-color: #28a745;
            color: white;
            font-size: 16px;
            margin-top: 15px;
            cursor: pointer;
        }

        .form-container button:hover {
            background-color: #218838;
        }

        .error {
            color: red;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <h1>Añadir Producto</h1>
    <form class="form-container" action="CrearP" method="POST" enctype="multipart/form-data">
        <label for="nombre">Nombre del Producto:</label>
        <input type="text" id="nombre" name="nombre" required><br>

        <label for="categoria_id">Categoría:</label>
        <select id="categoria_id" name="categoria_id" required>
            <option value="">Seleccione una categoría</option>
            <?php foreach ($categorias as $categoria): ?>
                <option value="<?php echo $categoria['id']; ?>">
                    <?php echo htmlspecialchars($categoria['nombre']); ?>
                </option>
            <?php endforeach; ?>
        </select><br>

        <label for="descripcion">Descripción:</label>
        <textarea id="descripcion" name="descripcion"></textarea><br>

        <label for="precio">Precio:</label>
        <input type="text" id="precio" name="precio"><br>
        <?php if (isset($errors['precio'])): ?>
            <div class="error"><?php echo htmlspecialchars($errors['precio']); ?></div>
        <?php endif; ?>

        <label for="stock">Stock:</label>
        <input type="text" id="stock" name="stock"><br>
        <?php if (isset($errors['stock'])): ?>
            <div class="error"><?php echo htmlspecialchars($errors['stock']); ?></div>
        <?php endif; ?>

        <label for="oferta">Oferta:</label>
        <input type="text" id="oferta" name="oferta"><br>
        <?php if (isset($errors['oferta'])): ?>
            <div class="error"><?php echo htmlspecialchars($errors['oferta']); ?></div>
        <?php endif; ?>

        <label for="fecha">Fecha:</label>
        <input type="date" id="fecha" name="fecha"><br>

        <label for="imagen">Subir Imagen:</label>
        <input type="file" id="imagen" name="imagen"><br>

        <label for="imagen_url">o URL de la Imagen:</label>
        <input type="text" id="imagen_url" name="imagen_url"><br>

        <button type="submit">Añadir</button>
    </form>
</body>
</html>