<?php

// Verificar si el carrito está vacío
if (!isset($_SESSION['carrito']) || empty($_SESSION['carrito'])) {
    echo "Tu carrito está vacío.";
    exit;
}

// Actualizar cantidades
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['producto_id']) && isset($_POST['cantidad'])) {
    $productoId = $_POST['producto_id'];
    $cantidad = $_POST['cantidad'];

    if ($cantidad <= 0) {
        // Eliminar el producto si la cantidad es 0 o menos
        unset($_SESSION['carrito'][$productoId]);
    } else {
        // Actualizar la cantidad
        $_SESSION['carrito'][$productoId]['cantidad'] = $cantidad;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Carrito de Compras</title>
    <style>
        /* Estilos para la vista del carrito */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
            color: #333;
        }

        #title {
            text-align: center;
            color: #444;
            margin: 20px 0;
            font-size: 2em;
        }

        .producto-carrito {
            width: 80%;
            max-width: 600px;
            margin: 10px auto;
            padding: 15px;
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .producto-carrito img {
            width: 100px;
            height: auto;
            border-radius: 8px;
            margin-right: 15px;
        }

        .producto-carrito div {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        button {
            background-color: #007BFF;
            color: #fff;
            border: none;
            border-radius: 5px;
            padding: 10px 15px;
            cursor: pointer;
            font-size: 1em;
            margin-top: 10px;
        }

        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <h1 id="title">Tu Carrito de Compras</h1>

    <div class="productos-carrito">
        <?php
        try {
            $dsn = "mysql:host=localhost;dbname=tienda;charset=utf8";
            $username = "root";
            $password = "";
            $options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];

            $pdo = new PDO($dsn, $username, $password, $options);

            // Mostrar productos del carrito
            foreach ($_SESSION['carrito'] as $productoId => $productoData) {
                $sqlProducto = "SELECT id, nombre, precio, imagen FROM productos WHERE id = :productoId";
                $stmtProducto = $pdo->prepare($sqlProducto);
                $stmtProducto->execute(['productoId' => $productoId]);

                $producto = $stmtProducto->fetch(PDO::FETCH_ASSOC);

                echo '<div class="producto-carrito">';
                echo '<img src="' . $producto['imagen'] . '" alt="' . $producto['nombre'] . '">';
                echo '<div>';
                echo '<p>' . $producto['nombre'] . '</p>';
                echo '<p>Precio: $' . $producto['precio'] . '</p>';
                echo '<p>Cantidad: 
                    <form method="POST" action="">
                        <input type="hidden" name="producto_id" value="' . $producto['id'] . '">
                        <input type="number" name="cantidad" value="' . $productoData['cantidad'] . '" min="1">
                        <button type="submit">Actualizar Cantidad</button>
                    </form>
                </p>';
                echo '</div>';
                echo '</div>';
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        ?>
    </div>
</body>
</html>
