<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Recuperar Cuenta</title>
</head>
<body>
    <h1>Recuperar Cuenta</h1>
    <?php if (isset($_SESSION['recovery']) && $_SESSION['recovery'] === 'fail'): ?>
        <p style="color: red;"><?php echo $_SESSION['error']; ?></p>
    <?php endif; ?>
    <form action="<?php echo BASE_URL; ?>sendPasswordRecoveryToken" method="POST">
        <label for="email">Correo Electrónico:</label>
        <input type="email" id="email" name="email" required>
        <button type="submit">Enviar Email de Recuperación</button>
    </form>
</body>
</html>