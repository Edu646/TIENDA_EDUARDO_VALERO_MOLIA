<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Usuarios</title>
</head>
<style>
/* Estilos generales */
body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f4f4f4;
    text-align: center;
}

header {
    background-color: #333;
    color: white;
    padding: 20px 0;
    text-align: center;
}

h1 {
    margin: 0;
    font-size: 24px;
}

/* Tabla de usuarios */
table {
    width: 80%;
    margin: 20px auto;
    border-collapse: collapse;
    background: white;
    box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
    border-radius: 10px;
    overflow: hidden;
}

thead {
    background-color: #333;
    color: white;
}

th, td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid #ddd;
}

tr:hover {
    background-color: #f1f1f1;
}

/* Enlaces de acciones */
a {
    text-decoration: none;
    padding: 6px 12px;
    margin: 5px;
    display: inline-block;
    border-radius: 5px;
}

/* Botón Editar */
a[href*="edit"] {
    background-color: #4CAF50;
    color: white;
}

a[href*="edit"]:hover {
    background-color: #45a049;
}

/* Botón Eliminar */
a[href*="delete"] {
    background-color: #ff4d4d;
    color: white;
}

a[href*="delete"]:hover {
    background-color: #cc0000;
}

/* Mensaje cuando no hay usuarios */
p {
    font-size: 18px;
    color: #555;
}

/* Estilo para el formulario de añadir usuario */
form {
    width: 80%;
    margin: 20px auto;
    padding: 20px;
    background-color: #fff;
    box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
    border-radius: 10px;
}

input, select, button {
    padding: 10px;
    margin: 10px 0;
    width: 100%;
    border: 1px solid #ccc;
    border-radius: 5px;
}

button {
    background-color: #4CAF50;
    color: white;
    border: none;
    cursor: pointer;
}

button:hover {
    background-color: #45a049;
}
</style>
<body>
    <header>
        <h1>Lista de Usuarios</h1>
    </header>

    <!-- Formulario para añadir un nuevo usuario -->
    <form action="<?= BASE_URL ?>/AddUs" method="POST">
        <h3>Añadir Nuevo Usuario</h3>

        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="data[nombre]" required>

        <label for="email">Email:</label>
        <input type="email" id="email" name="data[email]" required>

        <label for="password">Contraseña:</label>
        <input type="password" id="password" name="data[password]" required>

        <label for="role">Rol:</label>
        <select id="role" name="data[role]">
            <option value="user">Usuario</option>
            <option value="admin">Administrador</option>
        </select>

        <button type="submit">Añadir Usuario</button>
    </form>

    <?php if (isset($usuarios) && count($usuarios) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Rol</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($usuarios as $usuario): ?>
                    <tr>
                        <td><?= htmlspecialchars($usuario->getId()) ?></td>
                        <td><?= htmlspecialchars($usuario->getNombre()) ?></td>
                        <td><?= htmlspecialchars($usuario->getEmail()) ?></td>
                        <td><?= htmlspecialchars($usuario->getRol()) ?></td>
                        <td>
                            <!-- Enlace de editar usuario -->
                            <a href="<?= BASE_URL ?>editUser/<?= $usuario->getId() ?>">Editar</a>
                            
                            <!-- Enlace de eliminar usuario -->
                            <a href="<?= BASE_URL ?>deleteUser/<?= $usuario->getId() ?>" onclick="return confirm('¿Estás seguro de que quieres eliminar este usuario?')">Eliminar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No hay usuarios registrados.</p>
    <?php endif; ?>
</body>
</html>
