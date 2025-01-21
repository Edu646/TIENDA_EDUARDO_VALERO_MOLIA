<?php if (isset($_SESSION['register_success'])): ?>
    <p style="color: green;"><?= $_SESSION['register_success'] ?></p>
    <?php unset($_SESSION['register_success']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['register_error'])): ?>
    <p style="color: red;"><?= $_SESSION['register_error'] ?></p>
    <?php unset($_SESSION['register_error']); ?>
<?php endif; ?>

<h3>Iniciar Sesión</h3>
<form action="<?= BASE_URL ?>login" method="POST">
    <label for="email">Correo Electrónico:</label>
    <input type="email" id="email" name="email" required>

    <label for="password">Contraseña:</label>
    <input type="password" id="password" name="password" required>

    <button type="submit">Iniciar Sesión</button>
</form>
