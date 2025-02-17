<h1>Gestión de Pedidos</h1>

<?php if (!empty($pedidos)) : ?>
    <table border="1" cellpadding="5">
        <thead>
            <tr>
                <th>ID Pedido</th>
                <th>Usuario</th>
                <th>Fecha</th>
                <th>Estado</th>
                <th>Actualizar Estado</th>
            </tr>

            <style>
                table {
    width: 100%;
    border-collapse: collapse;
    margin: 20px 0;
    font-size: 18px;
    text-align: left;
}

table thead {
    background-color: #007BFF;
    color: #ffffff;
}

table th, table td {
    padding: 12px;
    border: 1px solid #ddd;
}

table tbody tr:nth-child(even) {
    background-color: #f2f2f2;
}

table tbody tr:hover {
    background-color: #ddd;
}

select {
    padding: 5px;
    border-radius: 5px;
    border: 1px solid #ccc;
}

button {
    background-color: #28a745;
    color: white;
    border: none;
    padding: 8px 12px;
    cursor: pointer;
    border-radius: 5px;
    font-size: 16px;
}

button:hover {
    background-color: #218838;
}

            </style>
        </thead>
        <tbody>
            <?php foreach ($pedidos as $pedido) : ?>
                <tr>
                    <td><?= htmlspecialchars($pedido['id']) ?></td>
                    <td><?= htmlspecialchars($pedido['usuario_id']) ?></td>
                    <td><?= htmlspecialchars($pedido['fecha']) ?></td>
                    <td><?= htmlspecialchars($pedido['estado']) ?></td>
                    <td>
                        <form action="<?= BASE_URL ?>actualizar-estado" method="POST">
                            <input type="hidden" name="pedido_id" value="<?= $pedido['id'] ?>">
                            <select name="estado">
                                <option value="Pendiente" <?= $pedido['estado'] == 'Pendiente' ? 'selected' : '' ?>>Pendiente</option>
                                <option value="En proceso" <?= $pedido['estado'] == 'En proceso' ? 'selected' : '' ?>>En proceso</option>
                                <option value="Enviado" <?= $pedido['estado'] == 'Enviado' ? 'selected' : '' ?>>Enviado</option>
                                <option value="Entregado" <?= $pedido['estado'] == 'Entregado' ? 'selected' : '' ?>>Entregado</option>
                                <option value="Cancelado" <?= $pedido['estado'] == 'Cancelado' ? 'selected' : '' ?>>Cancelado</option>
                            </select>
                            <button type="submit">Actualizar</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else : ?>
    <p>No hay pedidos registrados.</p>
<?php endif; ?>
