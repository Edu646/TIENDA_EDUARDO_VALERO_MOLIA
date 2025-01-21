<h3>Registrarse</h3>
<form action="<?= BASE_URL ?>register" method="POST">
    <label for="nombre">Nombre</label>
    <input type="text" id="nombre" name="data[nombre]">

    <label for="apellidos">Apellidos</label>
    <input type="text" id="apellidos" name="data[apellidos]">

    <label for="email">Correo Electrónico</label>
    <input type="email" id="email" name="data[email]">

    <label for="password">Contraseña</label>
    <input type="password" id="password" name="data[password]">


    <button type="submit">Enviar</button>
</form>

<!-- Mensaje de éxito -->
<?php if (isset($_SESSION['register_success'])): ?>
    <p style="color: green;"><?= $_SESSION['register_success'] ?></p>
    <?php unset($_SESSION['register_success']); ?>
<?php endif; ?>

<!-- Mensaje de error -->
<?php if (isset($_SESSION['register_error'])): ?>
    <p style="color: red;"><?= $_SESSION['register_error'] ?></p>
    <?php unset($_SESSION['register_error']); ?>
<?php endif; ?>