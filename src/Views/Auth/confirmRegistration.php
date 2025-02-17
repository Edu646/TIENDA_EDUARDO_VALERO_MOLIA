<!-- filepath: /c:/xampp/htdocs/TiendaEduardo/views/Auth/confirmRegistration.php -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmación de Registro</title>
</head>
<body>
    <h1>Confirmación de Registro</h1>
    <?php if (isset($_SESSION['confirm']) && $_SESSION['confirm'] === 'success'): ?>
        <p style="color: green;">Registro confirmado exitosamente. Ahora puede iniciar sesión.</p>
    <?php elseif (isset($_SESSION['confirm']) && $_SESSION['confirm'] === 'fail'): ?>
        <p style="color: red;">Error: <?php echo $_SESSION['error']; ?></p>
    <?php endif; ?>
    <a href="<?php echo BASE_URL; ?>login">Ir a la página de inicio de sesión</a>
</body>
</html>