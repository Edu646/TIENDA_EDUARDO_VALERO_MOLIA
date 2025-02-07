

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito de Compras</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #333;
            color: white;
            padding: 15px;
            text-align: center;
        }

        .carrito-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-top: 20px;
        }

        .carrito-table {
            width: 80%;
            max-width: 1000px;
            margin-bottom: 20px;
            border-collapse: collapse;
        }

        .carrito-table th, .carrito-table td {
            padding: 15px;
            text-align: center;
            border: 1px solid #ddd;
        }

        .carrito-table th {
            background-color: #333;
            color: white;
        }

        .carrito-table td img {
            max-width: 100px;
            height: auto;
        }

        .carrito-table input[type="number"] {
            width: 60px;
            padding: 5px;
            margin-top: 5px;
        }

        .actions {
            margin-top: 20px;
        }

        .btn {
            padding: 10px 20px;
            background-color: #28a745;
            color: white;
            border: none;
            cursor: pointer;
            text-decoration: none;
            border-radius: 5px;
        }

        .btn-danger {
            background-color: #dc3545;
        }

        .error-message {
            color: red;
            margin-bottom: 20px;
        }

        .carrito-footer {
            display: flex;
            justify-content: space-between;
            width: 80%;
            max-width: 1000px;
            margin-top: 20px;
        }

        .total-price {
            font-size: 20px;
            font-weight: bold;
        }

        .empty-cart {
            font-size: 18px;
            text-align: center;
            margin-top: 20px;
        }

        .empty-cart a {
            text-decoration: none;
            color: #007bff;
        }
    </style>
</head>
<body>

<header>
    <h1>Carrito de Compras</h1>
</header>

<div class="carrito-container">
    <?php if (isset($_SESSION['error'])): ?>
        <div class="error-message">
            <?= $_SESSION['error']; ?>
            <?php unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['carrito']) && count($_SESSION['carrito']) > 0): ?>
        <table class="carrito-table">
            <thead>
                <tr>
                    <th>Imagen</th>
                    <th>Producto</th>
                    <th>Precio</th>
                    <th>Cantidad</th>
                    <th>Total</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    $total = 0;
                    foreach ($_SESSION['carrito'] as $item):
                        $subtotal = $item['precio'] * $item['cantidad'];
                        $total += $subtotal;
                ?>
                    <tr>
                        <td><img src="<?= $item['imagen']; ?>" alt="<?= $item['nombre']; ?>"></td>
                        <td><?= $item['nombre']; ?></td>
                        <td>$<?= number_format($item['precio'], 2); ?></td>
                        <td>
                            <form action="<?= BASE_URL; ?>/addcart" method="POST">
                                <input type="hidden" name="producto_id" value="<?= $item['producto_id']; ?>">
                                <input type="number" name="cantidad" value="<?= $item['cantidad']; ?>" min="1" max="<?= $item['stock']; ?>" required>
                                <button type="submit" class="btn">Actualizar</button>
                            </form>
                        </td>
                        <td>$<?= number_format($subtotal, 2); ?></td>
                        <td>
                            <form action="<?= BASE_URL; ?>/eliminarcartus" method="POST">
                                <input type="hidden" name="producto_id" value="<?= $item['producto_id']; ?>">
                                <button type="submit" class="btn btn-danger">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="carrito-footer">
            <div class="total-price">
                Total: $<?= number_format($total, 2); ?>
            </div>
            <div>
                <a href="<?= BASE_URL; ?>/checkout" class="btn">Proceder a la compra</a>
            </div>
        </div>
    <?php else: ?>
        <div class="empty-cart">
            <p>Tu carrito está vacío. <a href="<?= BASE_URL; ?>/productos">Agregar productos</a></p>
        </div>
    <?php endif; ?>
</div>

</body>
</html>
