<?php


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
        /* Estilos generales */
body {
    font-family: 'Arial', sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f4f4f9;
    color: #333;
    text-align: center;
}

/* Título principal */
#title {
    font-size: 2.5em;
    color: #222;
    margin-top: 20px;
}

/* Contenedor de categorías */
.cat {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 0;
    margin: 20px auto;
    max-width: 800px;
}

/* Tarjeta de cada categoría */
.cat li {
    background: #ffffff;
    width: 100%;
    margin: 10px 0;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    list-style: none;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.cat li:hover {
    transform: translateY(-5px);
    box-shadow: 0 6px 10px rgba(0, 0, 0, 0.15);
}

/* Nombre de la categoría */
.cat li h2 {
    font-size: 1.8em;
    color: #007BFF;
}

/* Productos dentro de cada categoría */
.productos {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 15px;
    margin-top: 10px;
}

/* Tarjeta de cada producto */
.productos div {
    background: #fff;
    padding: 15px;
    border-radius: 8px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    width: 220px;
    text-align: center;
    transition: transform 0.3s ease;
}

.productos div:hover {
    transform: scale(1.05);
}

/* Imagen del producto */
.productos img {
    max-width: 100%;
    height: auto;
    border-radius: 8px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

/* Texto de los productos */
.productos p {
    margin: 5px 0;
    font-size: 1em;
}

/* Botón para añadir al carrito */
button {
    background-color: #28a745;
    color: #fff;
    border: none;
    border-radius: 5px;
    padding: 10px 15px;
    cursor: pointer;
    font-size: 1em;
    transition: background-color 0.3s ease;
}

button:hover {
    background-color: #218838;
}

/* Responsivo para móviles */
@media (max-width: 600px) {
    .cat {
        max-width: 95%;
    }
    
    .productos {
        flex-direction: column;
        align-items: center;
    }
    
    .productos div {
        width: 90%;
    }
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
                    echo '<div class="producto-card">'; // Asegura que cada producto está en su propia tarjeta
                    echo '<img src="' . htmlspecialchars($producto['imagen']) . '" alt="' . htmlspecialchars($producto['nombre']) . '">';
                    echo '<p><strong>' . htmlspecialchars($producto['nombre']) . '</strong></p>';
                    echo '<p>Precio: $' . htmlspecialchars($producto['precio']) . '</p>';
                    echo '<p>Stock: ' . htmlspecialchars($producto['stock']) . '</p>';
                    echo '<p>Oferta: ' . (htmlspecialchars($producto['oferta']) ? 'Sí' : 'No') . '</p>';
                    echo '</div>'; // Cierre de tarjeta
                }
            } else {
                echo '<p>No hay productos en esta categoría.</p>';
            }
        }
        
        ?>
    </ul>
</body>
</html>
