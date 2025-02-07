<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tiendilla</title>
    <style>
        /* Reset CSS */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            color: #333;
            line-height: 1.6;
        }

        header {
            background: #333;
            color: #fff;
            padding: 10px 0;
            text-align: center;
        }

        header h1 {
            margin: 0;
            font-size: 2em;
        }

        nav ul {
            list-style: none;
            padding: 0;
            text-align: center;
        }

        nav ul li {
            display: inline-block;
            margin: 0 10px;
        }

        nav ul li a {
            color: #fff;
            text-decoration: none;
            font-weight: bold;
        }

        nav ul li a:hover {
            text-decoration: underline;
        }

        .container {
            width: 80%;
            margin: 20px auto;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        .admin-section {
            background-color: #222;
            color: #fff;
            padding: 10px;
            margin-top: 20px;
            text-align: center;
        }

        .admin-section h2 {
            margin-bottom: 10px;
        }

        .admin-section ul {
            list-style: none;
            padding: 0;
        }

        .admin-section ul li {
            display: inline-block;
            margin: 0 10px;
        }

        .admin-section ul li a {
            color: #ffcc00;
            text-decoration: none;
            font-weight: bold;
        }

        .admin-section ul li a:hover {
            text-decoration: underline;
        }

        footer {
            background: #333;
            color: #fff;
            text-align: center;
            padding: 10px 0;
            position: fixed;
            bottom: 0;
            width: 100%;
        }

        @media (max-width: 768px) {
            nav ul li, .admin-section ul li {
                display: block;
                margin: 10px 0;
            }
        }
    </style>
</head>
<body>

<header>
    <h1>Bienvenido a Tiendilla</h1>
    <nav>
        <ul>
            <?php if (isset($_SESSION['user'])): ?>
                <!-- Opciones para usuario autenticado -->
                <li>Bienvenido, <strong><?= htmlspecialchars($_SESSION['user']['nombre']) ?></strong></li>
                <li><a href="<?= BASE_URL ?>logout">Cerrar Sesión</a></li>
                <li><a href="<?= BASE_URL ?>ver">Lista Categorías</a></li>
                <li><a href="<?= BASE_URL ?>verP">Lista de Productos</a></li>
                <li><a href="<?= BASE_URL ?>carrito">Carrito</a></li>

            <?php else: ?>
                <!-- Opciones para usuarios no autenticados -->
                <li><a href="<?= BASE_URL ?>">Inicio</a></li>
                <li><a href="<?= BASE_URL ?>login">Iniciar Sesión</a></li>
                <li><a href="<?= BASE_URL ?>register">Registrarse</a></li>
                <li><a href="<?= BASE_URL ?>verP">Lista de Productos</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>

<div class="container">
    <!-- Sección especial para administradores -->
    <?php if (isset($_SESSION['user']) && $_SESSION['user']['rol'] === 'admin'): ?>
        <div class="admin-section">
            <h2>Panel de Administración</h2>
            <ul>
                <li><a href="<?= BASE_URL ?>listus">Gestionar Usuarios</a></li>
                <li><a href="<?= BASE_URL ?>CrearP">Gestionar Productos</a></li>
                <li><a href="<?= BASE_URL ?>verP_admin">verProductos_admin</a></li>
                <li><a href="<?= BASE_URL ?>vista">Categorías</a></li>
            </ul>
        </div>
    <?php endif; ?>
</div>

<footer>
    <p>&copy; 2025 Tiendilla. Todos los derechos reservados.</p>
</footer>

</body>
</html>
