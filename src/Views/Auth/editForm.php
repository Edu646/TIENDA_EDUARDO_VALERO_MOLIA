<!-- resources/views/Auth/editForm.php -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuario</title>
</head>
<body>
    <header>
        <h1>Editar Usuario</h1>
    </header>

    <?php if (isset($_SESSION['edit']) && $_SESSION['edit'] === 'success'): ?>
    <div class="alert success">Usuario actualizado con éxito.</div>
    <?php unset($_SESSION['edit']); ?>
<?php elseif (isset($_SESSION['edit']) && $_SESSION['edit'] === 'fail'): ?>
    <div class="alert fail">
        <?php echo isset($_SESSION['error']) ? $_SESSION['error'] : 'Hubo un error al intentar actualizar el usuario.'; ?>
    </div>
    <?php unset($_SESSION['edit'], $_SESSION['error']); ?>
<?php endif; ?>

<h2>Editar Usuario</h2>

<form action="<?= BASE_URL ?>/editUser<?= $usuario->getId() ?>" method="POST">
    <div class="form-group">
        <label for="nombre">Nombre:</label>
        <input type="text" name="data[nombre]" id="nombre" value="<?= htmlspecialchars($usuario->getNombre()) ?>" required>
    </div>

    <div class="form-group">
        <label for="email">Correo electrónico:</label>
        <input type="email" name="data[email]" id="email" value="<?= htmlspecialchars($usuario->getEmail()) ?>" required>
    </div>

    <div class="form-group">
        <label for="role">Rol:</label>
        <select name="data[role]" id="role" required>
            <option value="user" <?= $usuario->getRol() === 'user' ? 'selected' : '' ?>>Usuario</option>
            <option value="admin" <?= $usuario->getRol() === 'admin' ? 'selected' : '' ?>>Administrador</option>
        </select>
    </div>

    <div class="form-group">
        <button type="submit">Actualizar Usuario</button>
    </div>
</form>

<p><a href="<?= BASE_URL ?>/list">Volver al listado de usuarios</a></p>



        <button type="submit">Actualizar</button>
    </form>

    <footer>
        <a href="<?= BASE_URL ?>list">Volver a la lista de usuarios</a>
    </footer>
</body>
</html>
