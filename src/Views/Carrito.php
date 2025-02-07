<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito de Compras</title>

    <style>
        /* Estilos generales */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
            color: #343a40;
        }

        h1 {
            text-align: center;
            margin-top: 20px;
        }

        /* Contenedor principal */
        .container {
            width: 90%;
            max-width: 1200px;
            margin: 0 auto;
        }

        /* Estilo de la tabla */
        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
            background-color: #fff;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        /* Cabecera de la tabla */
        table th {
            padding: 15px;
            background-color: #007bff;
            color: white;
            font-weight: bold;
            text-align: left;
        }

        /* Celdas de la tabla */
        table td {
            padding: 15px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }

        /* Filas alternas */
        table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        /* Estilo para las imágenes */
        img {
            width: 80px;
            height: 80px;
            border-radius: 8px;
            object-fit: cover;
        }

        /* Texto cuando no hay imagen */
        span {
            color: #888;
            font-style: italic;
        }

        /* Botón para eliminar producto */
        .btn-eliminar {
            background-color: #f44336;
            color: white;
            padding: 8px 16px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .btn-eliminar:hover {
            background-color: #d32f2f;
        }

        /* Botón para actualizar cantidad */
        .btn-actualizar {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 8px 16px;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .btn-actualizar:hover {
            background-color: #218838;
        }

        /* Estilo para el total */
        .total {
            margin-top: 20px;
            font-size: 18px;
            font-weight: bold;
            text-align: right;
        }

        .total p {
            margin: 10px 0;
        }

        /* Botón de proceder al pago */
        .btn-pagar {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            text-decoration: none;
        }

        .btn-pagar:hover {
            background-color: #0056b3;
        }

        /* Responsividad */
        @media (max-width: 768px) {
            table td, table th {
                padding: 10px;
            }

            table img {
                width: 60px;
                height: 60px;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Carrito de Compras</h1>

    <?php if (isset($_SESSION['carrito']) && count($_SESSION['carrito']) > 0): ?>

        <table>
            <thead>
                <tr>
                    <th>Imagen</th>
                    <th>Nombre</th>
                    <th>Cantidad</th>
                    <th>Precio</th>
                    <th>Total</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php $totalCarrito = 0; ?>
                <?php foreach ($_SESSION['carrito'] as $item): ?>
                    <tr>
                        <td>
                            <?php if ($item['imagen']): ?>
                                <img src="<?= htmlspecialchars($item['imagen']) ?>" alt="Imagen del producto">
                            <?php else: ?>
                                <span>No disponible</span>
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($item['nombre']) ?></td>
                        <td>
                            <form action="<?= BASE_URL ?>/carrito/actualizarCantidad" method="POST">
                                <input type="number" name="cantidad" value="<?= $item['cantidad'] ?>" min="1" style="width: 60px;">
                                <input type="hidden" name="producto_id" value="<?= $item['producto_id'] ?>">
                                <button class="btn-actualizar" type="submit">Actualizar</button>
                            </form>
                        </td>
                        <td><?= htmlspecialchars($item['precio']) ?> €</td>
                        <td><?= htmlspecialchars($item['precio'] * $item['cantidad']) ?> €</td>
                        <td>
                            <!-- Formulario de eliminación -->
                            <form action="<?= BASE_URL ?>/eliminarcartus" method="POST" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este producto?');">
                                <input type="hidden" name="producto_id" value="<?= $item['producto_id'] ?>">
                                <button class="btn-eliminar" type="submit">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                    <?php $totalCarrito += $item['precio'] * $item['cantidad']; ?>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="total">
            <p>Total: <?= htmlspecialchars($totalCarrito) ?> €</p>
            <a href="<?= BASE_URL ?>/checkout" class="btn-pagar">Proceder al pago</a>
        </div>

    <?php else: ?>
        <p>No hay productos en tu carrito.</p>
    <?php endif; ?>
</div>

</body>
</html>
