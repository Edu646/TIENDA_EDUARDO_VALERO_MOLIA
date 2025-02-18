<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pago Completado</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/styles.css">
    <style>
        .success-container {
            max-width: 800px;
            margin: 50px auto;
            padding: 30px;
            background-color: #f9f9f9;
            border-radius: 5px;
            text-align: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .success-icon {
            font-size: 80px;
            color: #28a745;
            margin-bottom: 20px;
        }
        .success-title {
            color: #28a745;
            font-size: 32px;
            margin-bottom: 15px;
        }
        .success-message {
            font-size: 18px;
            margin-bottom: 30px;
            color: #666;
        }
        .btn-primary {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            text-decoration: none;
            transition: background-color 0.3s;
        }
        .btn-primary:hover {
            background-color: #0069d9;
        }
        .order-details {
            margin-top: 40px;
            text-align: left;
            background-color: white;
            padding: 20px;
            border-radius: 5px;
        }
        .order-title {
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
            margin-bottom: 20px;
            font-size: 22px;
            color: #333;
        }
    </style>
</head>
<body>
    <?php include 'partials/header.php'; ?>

    <div class="success-container">
        <div class="success-icon">
            <i class="fas fa-check-circle"></i>
        </div>
        <h1 class="success-title">¡Pago Completado con Éxito!</h1>
        <p class="success-message">Gracias por tu compra. Hemos recibido tu pago correctamente y estamos procesando tu pedido.</p>
        
        <div class="order-details">
            <h2 class="order-title">Detalles del Pedido</h2>
            <p><strong>Número de Referencia:</strong> <?= isset($_SESSION['payment_id']) ? $_SESSION['payment_id'] : 'No disponible' ?></p>
            <p><strong>Fecha de Compra:</strong> <?= date('d/m/Y H:i') ?></p>
            <p><strong>Estado:</strong> <span style="color: #28a745;">Pagado</span></p>
            <p><strong>Método de Pago:</strong> PayPal</p>
            
            <?php if(isset($_SESSION['last_order'])): ?>
            <p><strong>Total:</strong> <?= number_format($_SESSION['last_order']['total'], 2, ',', '.') ?> €</p>
            <?php endif; ?>
            
            <p>Recibirás un correo electrónico con los detalles de tu compra.</p>
        </div>
        
        <div style="margin-top: 30px;">
            <a href="<?= BASE_URL ?>" class="btn-primary">Volver a la Tienda</a>
        </div>
    </div>

    <?php include 'partials/footer.php'; ?>

    <!-- Cargar Font Awesome para el icono de éxito -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js"></script>
</body>
</html>