<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Restablecer Contraseña</title>
</head>
<body>
    <h1>Restablecer Contraseña</h1>
    <?php if (isset($_SESSION['reset']) && $_SESSION['reset'] === 'fail'): ?>
        <p style="color: red;"><?php echo $_SESSION['error']; ?></p>
    <?php endif; ?>
    <form method="POST" action="resetPassword">
    <input type="password" name="new_password" required />
    <button type="submit">Restablecer contraseña</button>
</form>
</body>
</html>