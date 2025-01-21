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
}

nav ul li {
    display: inline;
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

.product {
    display: flex;
    flex-wrap: wrap;
    margin-bottom: 20px;
    border-bottom: 1px solid #ddd;
    padding-bottom: 20px;
}

.product img {
    max-width: 300px;
    height: auto;
    margin-right: 20px;
}

.product-details {
    flex: 1;
}

.product-details h2 {
    margin-top: 0;
}

.product-details p {
    margin: 5px 0;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin: 20px 0;
}

th, td {
    padding: 10px;
    border: 1px solid #ddd;
    text-align: left;
}

th {
    background-color: #f2f2f2;
}

tr:hover {
    background-color: #f5f5f5;
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
    .product {
        flex-direction: column;
        align-items: center;
    }

    .product img {
        margin: 0 0 20px 0;
    }

    nav ul li {
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
                    <li>Bienvenido, <strong><?= htmlspecialchars($_SESSION['user']['nombre']) ?></strong></li>
                    <li><a href="<?= BASE_URL ?>logout">Cerrar Sesión</a></li>
                    <li><a href="<?= BASE_URL ?>ver">ListaCat</a></li>
                    <li><a href="<?= BASE_URL ?>verP">ListaP</a></li>
                    <li><a href="<?= BASE_URL ?>carrito">carro</a></li>
                <?php else: ?>
                    <li><a href="<?= BASE_URL ?>">Inicio</a></li>
                    <li><a href="<?= BASE_URL ?>login">Iniciar Sesión</a></li>
                    <li><a href="<?= BASE_URL ?>register">Registrarse</a></li>
                    <li><a href="<?= BASE_URL ?>vista">Categotias</a></li>
                    <li><a href="<?= BASE_URL ?>CrearP">CrearPro</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>
