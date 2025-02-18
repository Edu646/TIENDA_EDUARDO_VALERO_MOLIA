<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pago Cancelado</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/styles.css">
    <style>
        .cancel-container {
            max-width: 800px;
            margin: 50px auto;
            padding: 30px;
            background-color: #f9f9f9;
            border-radius: 5px;
            text-align: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .cancel-icon {
            font-size: 80px;
            color: #dc3545;
            margin-bottom: 20px;
        }
        .cancel-title {
            color: #dc3545;
            font-size: 32px;
            margin-bottom: 15px;
        }
        .cancel-message {
            font-size: 18px;
            margin-bottom: 30px;
            color: #666;
        }
        .btn-group {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-top: 30px;
            flex-wrap: wrap;
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
        .btn-secondary {
            background-color: #6c757d;
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
        .btn-secondary:hover {
            background-color: #5a6268;
        }
        .help-section {
            margin-top: 40px;
            text-align: left;
            background-color: white;
            padding: 20px;
            border-radius: 5px;
        }
        .help-title {
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

    <div class="cancel-container">
        <div class="cancel-icon">
            <i class="fas fa-times-circle"></i>
        </div>
        <h1 class="cancel-title">Pago Cancelado</h1>
        <p class="cancel-message">
            <?= isset($mensaje) ? $mensaje : "Has cancelado el proceso de pago. No se ha realizado ningún cargo a tu cuenta." ?>
        </p>
        
        <div class="help-section">
            <h2 class="help-title">¿Tuviste algún problema?</h2>
            <p>Si has cancelado el pago porque tuviste algún problema, aquí tienes algunas opciones:</p>
            <ul style="text-align: left; margin-top: 15px;">
                <li>Verifica que los datos de tu cuenta PayPal estén actualizados</li>
                <li>Comprueba que tienes fondos suficientes en tu cuenta</li>
                <li>Si necesitas asistencia, no dudes en contactar con nuestro servicio de atención al cliente</li>
            </ul>
        </div>
        
        <div class="btn-group">
            <a href="<?= BASE_URL ?>/carrito" class="btn-primary">Volver al Carrito</a>
            <a href="<?= BASE_URL ?>" class="btn-secondary">Continuar Comprando</a>
            <a href="<?= BASE_URL ?>/contacto" class="btn-secondary">Contactar Soporte</a>
        </div>
    </div>

    <?php include 'partials/footer.php'; ?>

    <!-- Cargar Font Awesome para el icono de error -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js"></script>
</body>
</html>