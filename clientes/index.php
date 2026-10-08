<?php
include '../config/database.php';
include '../includes/header.php';
include '../includes/navbar.php';

$stmt = $pdo->query("SELECT * FROM cliente");
$clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container">
    <div class="card">
        <div class="table-header">
            <h2>Dueños de Mascotas</h2>
            <a href="crear.php" class="btn btn-primary">+ Nuevo Cliente</a>
        </div>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Correo</th>
                    <th>Teléfono</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($clientes as $cliente): ?>
                <tr>
                    <td><?= $cliente['cliente_id'] ?></td>
                    <td><?= htmlspecialchars($cliente['nombre']) ?></td>
                    <td><?= htmlspecialchars($cliente['correo']) ?></td>
                    <td><?= htmlspecialchars($cliente['telefono']) ?></td>
                    <td>
                        <a href="editar.php?id=<?= $cliente['cliente_id'] ?>" class="btn btn-warning">Editar</a>
                        <a href="eliminar.php?id=<?= $cliente['cliente_id'] ?>" class="btn btn-danger" onclick="return confirm('¿Eliminar registro?')">Eliminar</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include '../includes/footer.php'; ?>