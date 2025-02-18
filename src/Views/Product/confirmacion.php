<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Confirmación de Pedido</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/styles.css">
</head>
<body>
    <div class="container">
        <h1>✅ Pedido Confirmado</h1>
        <p>Gracias por tu compra. Tu pedido ha sido procesado con éxito.</p>
        <p>Recibirás un correo de confirmación con los detalles de tu pedido.</p>
        <p>Gracias por tu pedido. Tu número de pedido es: <?php echo htmlspecialchars($pedidoId); ?></p>
        <p>Se ha enviado un correo de confirmación a tu dirección de correo electrónico.</p>

</form>

        <a class="btn-continuar" href="<?= BASE_URL ?>">⬅ Volver a la tienda</a>
    </div>
</body>
</html>