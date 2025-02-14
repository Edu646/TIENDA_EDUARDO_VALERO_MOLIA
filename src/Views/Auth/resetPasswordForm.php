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
    <form action="resetPassword" method="POST">
    <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
    
    <label for="password">Nueva Contraseña:</label>
    <input type="password" name="password" required>
    
    <button type="submit">Restablecer</button>
</form>
</body>
</html>