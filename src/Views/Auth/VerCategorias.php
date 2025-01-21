<?php
session_start(); // Inicia la sesión para usar $_SESSION

// Verificar si el carrito ya existe, si no, crear uno
if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];
}

// Verificar si el formulario se ha enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['producto_id'])) {
    $productoId = $_POST['producto_id']; // ID del producto
    $cantidad = isset($_POST['cantidad']) ? $_POST['cantidad'] : 1; // Cantidad del producto

    // Verificar si el producto ya está en el carrito
    if (isset($_SESSION['carrito'][$productoId])) {
        // Si el producto ya existe, sumar la cantidad
        $_SESSION['carrito'][$productoId]['cantidad'] += $cantidad;
    } else {
        // Si el producto no existe, agregarlo con la cantidad
        $_SESSION['carrito'][$productoId] = [
            'id' => $productoId,
            'cantidad' => $cantidad
        ];
    }

    // Redirigir al carrito después de agregar el producto
    header('Location: carrito.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ver Categorías</title>
    <style>
        /* Estilos mejorados */
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

        .cat {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 0;
            margin: 0;
        }

        .cat li {
            background: #ffffff;
            width: 90%;
            max-width: 600px;
            margin: 10px 0;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            list-style: none;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .cat li a {
            display: inline-block;
            margin-top: 10px;
            text-decoration: none;
            color: #007BFF;
            font-weight: bold;
            transition: color 0.3s;
        }

        .cat li a:hover {
            color: #0056b3;
        }

        .productos {
            width: 100%;
            margin-top: 20px;
            padding: 10px;
            background: #f9f9f9;
            border-radius: 8px;
        }

        .productos p {
            margin: 5px 0;
        }

        .productos img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
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
    <h1 id="title">Ver Categorías</h1>

    <ul class="cat">
        <?php
        try {
            $dsn = "mysql:host=localhost;dbname=tienda;charset=utf8";
            $username = "root";
            $password = "";
            $options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];

            $pdo = new PDO($dsn, $username, $password, $options);

            // Obtener todas las categorías
            $sqlCategorias = "SELECT id, nombre FROM categorias";
            $stmtCategorias = $pdo->query($sqlCategorias);

            if ($stmtCategorias->rowCount() > 0) {
                while ($categoria = $stmtCategorias->fetch(PDO::FETCH_ASSOC)) {
                    echo '<li>';
                    echo '<h2>' . $categoria['nombre'] . '</h2>';

                    // Mostrar productos de esta categoría
                    echo '<div class="productos">';
                    pintarProductos($pdo, $categoria['id']);
                    echo '</div>';

                    echo '</li>';
                }
            } else {
                echo '<p>No hay categorías disponibles.</p>';
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }

        // Función para mostrar productos
        function pintarProductos($pdo, $idCategoria) {
            $sqlProductos = "SELECT id, nombre, precio, stock, oferta, imagen FROM productos WHERE categoria_id = :idCategoria";
            $stmtProductos = $pdo->prepare($sqlProductos);
            $stmtProductos->execute(['idCategoria' => $idCategoria]);

            if ($stmtProductos->rowCount() > 0) {
                while ($producto = $stmtProductos->fetch(PDO::FETCH_ASSOC)) {
                    echo '<div>';
                    echo '<img src="' . $producto['imagen'] . '" alt="' . $producto['nombre'] . '">';
                    echo '<p>' . htmlspecialchars($producto['nombre']) . '</p>';
                    echo '<p>Precio: $' . htmlspecialchars($producto['precio']) . '</p>';
                    echo '<p>Stock: ' . htmlspecialchars($producto['stock']) . '</p>';
                    echo '<p>Oferta: ' . (htmlspecialchars($producto['oferta']) ? 'Sí' : 'No') . '</p>';

                    // Formulario para agregar al carrito
                    echo '<form method="POST" action="">
                            <input type="hidden" name="producto_id" value="' . $producto['id'] . '">
                            <input type="number" name="cantidad" value="1" min="1">
                            <button type="submit">Agregar al Carrito</button>
                          </form>';
                    echo '</div>';
                }
            } else {
                echo '<p>No hay productos en esta categoría.</p>';
            }
        }
        ?>
    </ul>
</body>
</html>
