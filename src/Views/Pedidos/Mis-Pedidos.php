<h1>Mis Pedidos</h1>

<?php if (!empty($pedidos)) : ?>
    <table border="1" cellpadding="5">
        <thead>
            <tr>
                <th>ID Pedido</th>
                <th>Fecha</th>
                <th>Estado</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($pedidos as $pedido) : ?>
                <tr>
                    <td><?= htmlspecialchars($pedido['id']) ?></td>
                    <td><?= htmlspecialchars($pedido['fecha']) ?></td>
                    <td><?= htmlspecialchars($pedido['estado']) ?></td>
                    <td><?= !empty($pedido['total']) ? number_format($pedido['total'], 2) . ' €' : '0.00 €' ?></td>

                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else : ?>
    <p>No tienes pedidos registrados.</p>
<?php endif; ?>
