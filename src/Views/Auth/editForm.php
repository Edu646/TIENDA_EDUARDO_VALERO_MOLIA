<!-- Views/Auth/editForm.php -->
<form action="<?php echo BASE_URL; ?>editUser/<?php echo $usuario->getId(); ?>" method="POST">
    <h2>Editar Usuario</h2>
    
    <?php if (isset($_SESSION['edit']) && $_SESSION['edit'] == 'fail'): ?>
        <p style="color: red;">Hubo un error al intentar editar el usuario.</p>
    <?php endif; ?>

    <div>
        <label for="nombre">Nombre:</label>
        <input type="text" name="data[nombre]" value="<?php echo htmlspecialchars($usuario->getNombre()); ?>" required>
    </div>

    <div>
        <label for="email">Email:</label>
        <input type="email" name="data[email]" value="<?php echo htmlspecialchars($usuario->getEmail()); ?>" required>
    </div>

    <div>
        <label for="role">Rol:</label>
        <select name="data[role]">
            <option value="user" <?php echo $usuario->getRol() == 'user' ? 'selected' : ''; ?>>Usuario</option>
            <option value="admin" <?php echo $usuario->getRol() == 'admin' ? 'selected' : ''; ?>>Administrador</option>
        </select>
    </div>

    <div>
        <button type="submit">Guardar Cambios</button>
    </div>
</form>
