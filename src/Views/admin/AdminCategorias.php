<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Administrar Categorías</title>
    <style>
        .admin-container {
            width: 80%;
            margin: auto;
            padding: 20px;
            background: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .admin-container h1 {
            text-align: center;
            color: #333;
        }

        .admin-container table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .admin-container table, th, td {
            border: 1px solid #ccc;
        }

        .admin-container th, td {
            padding: 10px;
            text-align: left;
        }

        .admin-container th {
            background-color: #f2f2f2;
        }

        .admin-container .actions {
            display: flex;
            gap: 10px;
        }

        .admin-container .actions form {
            display: inline;
        }

        .admin-container .actions button {
            padding: 5px 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .admin-container .actions .edit {
            background-color: #007bff;
            color: white;
        }

        .admin-container .actions .delete {
            background-color: #dc3545;
            color: white;
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <h1>Administrar Categorías</h1>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($categorias as $categoria): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($categoria->getId()); ?></td>
                        <td><?php echo htmlspecialchars($categoria->getNombre()); ?></td>
                        <td class="actions">
                            <form action="editarCategoria" method="POST">
                                <input type="hidden" name="id" value="<?php echo htmlspecialchars($categoria->getId()); ?>">
                                <input type="text" name="nombre" value="<?php echo htmlspecialchars($categoria->getNombre()); ?>" required>
                                <button type="submit" class="edit">Editar</button>
                            </form>
                            <form action="borrarCategoria" method="POST">
                                <input type="hidden" name="id" value="<?php echo htmlspecialchars($categoria->getId()); ?>">
                                <button type="submit" class="delete">Borrar</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>