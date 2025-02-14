<!-- filepath: /c:/xampp/htdocs/TiendaEduardo/src/Views/Carrito.php -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Carrito de Compras</title>
    <style>
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            font-family: Arial, sans-serif;
        }

        .error {
            color: red;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th, td {
            border-bottom: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #007bff;
            color: white;
        }

        .cantidad-control {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .cantidad-control button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            font-size: 16px;
            border-radius: 5px;
            margin: 0 5px;
        }

        .cantidad-control button:hover {
            background-color: #0056b3;
        }

        .cantidad-control input {
            width: 40px;
            text-align: center;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 5px;
        }

        .btn-eliminar {
            background-color: #dc3545;
            color: white;
            padding: 8px 12px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }

        .btn-eliminar:hover {
            background-color: #c82333;
        }

        .total {
            font-size: 18px;
            margin-top: 20px;
        }

        button {
            background-color: #28a745;
            color: white;
            padding: 10px 15px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            font-size: 16px;
        }

        button:hover {
            background-color: #218838;
        }

        .btn-continuar {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 15px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }

        .btn-continuar:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🛒 Carrito de Compras</h1>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="error">
                <?= $_SESSION['error'] ?>
                <?php unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($_SESSION['carrito'])): ?>
            <form action="<?= BASE_URL ?>/actualizarCantidadCarrito" method="post">
                <table>
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Precio</th>
                            <th>Cantidad</th>
                            <th>Total</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($_SESSION['carrito'] as $item): ?>
                            <tr>
                                <td><?= htmlspecialchars($item['nombre']) ?></td>
                                <td><?= number_format($item['precio'], 2) ?> €</td>
                                <td>
                                    <div class="cantidad-control">
                                        <button type="submit" formaction="<?= BASE_URL ?>/disminuirCantidad" name="producto_id" value="<?= $item['producto_id'] ?>">-</button>
                                        <input type="number" name="cantidad[<?= $item['producto_id'] ?>]" value="<?= $item['cantidad'] ?>" min="1" max="<?= $item['stock'] ?>" readonly>
                                        <button type="submit" formaction="<?= BASE_URL ?>/aumentarCantidad" name="producto_id" value="<?= $item['producto_id'] ?>">+</button>
                                    </div>
                                </td>
                                <td><?= number_format($item['precio_total'], 2) ?> €</td>
                                <td>
                                    <button class="btn-eliminar" type="submit" formaction="<?= BASE_URL ?>/eliminarDelCarrito" name="producto_id" value="<?= $item['producto_id'] ?>">🗑 Eliminar</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <div class="total">
                    <strong>Total del Carrito: <?= number_format($_SESSION['total_carrito'], 2) ?> €</strong>
                </div>
                <button type="submit">Actualizar Cantidades</button>
            </form>

            <h2>Datos del Cliente</h2>
            <form method="POST" action="<?= BASE_URL ?>/crearPedido">
                <label for="provincia">Provincia:</label>
                <input type="text" id="provincia" name="provincia" required><br>

                <label for="localidad">Localidad:</label>
                <input type="text" id="localidad" name="localidad" required><br>

                <label for="direccion">Dirección:</label>
                <input type="text" id="direccion" name="direccion" required><br>

                <button type="submit">Confirmar Pedido</button>
            </form>
        <?php else: ?>
            <p>🛍 El carrito está vacío.</p>
        <?php endif; ?>

        <a class="btn-continuar" href="<?= BASE_URL ?>">⬅ Seguir Comprando</a>
    </div>
</body>
</html>