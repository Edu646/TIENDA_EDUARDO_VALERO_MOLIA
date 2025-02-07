

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Lista de Productos</title>
    <style>
        body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
    margin: 0;
    padding: 0;
}

#P{
    text-align: center;
    color: #333;
    margin-top: 20px;
}

.container {
    width: 80%;
    margin: 20px auto;
    background-color: #fff;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    padding: 20px;
}

.product {
    display: flex;
    flex-wrap: wrap;
    margin-bottom: 20px;
    border-bottom: 1px solid #ddd;
    padding-bottom: 20px;
}

.product img {
    max-width: 300px;
    height: auto;
    margin-right: 20px;
}

.product-details {
    flex: 1;
}

.product-details h2 {
    margin-top: 0;
}

.product-details p {
    margin: 5px 0;
}
    </style>
</head>
<body>
    <h1 id="P">Lista de Productos</h1>
    <div class="container">
        <?php foreach ($productos as $producto): ?>
            <div class="product">
                <img src="<?= $producto->getImagen() ?>" alt="<?= $producto->getNombre() ?>">
                <div class="product-details">
                    <h2><?= $producto->getNombre() ?></h2>
                    <p><strong>Categoría ID:</strong> <?= $producto->getCategoriaId() ?></p>
                    <p><strong>Descripción:</strong> <?= $producto->getDescripcion() ?></p>
                    <p><strong>Precio:</strong> <?= $producto->getPrecio() ?></p>
                    <p><strong>Stock:</strong> <?= $producto->getStock() ?></p>
                    <p><strong>Oferta:</strong> <?= $producto->getOferta() ?></p>
                    <p><strong>Fecha:</strong> <?= $producto->getFecha() ?></p>

                    <form action="<?= BASE_URL ?>/addcart" method="POST">
                        <input type="hidden" name="producto_id" value="<?= htmlspecialchars($producto->getId()) ?>">
                        <button class="btn-agregar-carrito" type="submit">Agregar al carrito</button>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>