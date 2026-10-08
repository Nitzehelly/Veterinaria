<?php
include '../config/database.php';
include '../includes/header.php';
include '../includes/navbar.php';

$stmt = $pdo->query("SELECT * FROM categoria");
$categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container">
    <div class="card">
        <div class="table-header">
            <h2>Categorías de Productos y Servicios</h2>
            <a href="crear.php" class="btn btn-primary">+ Nueva Categoría</a>
        </div>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($categorias as $cat): ?>
                <tr>
                    <td><?= $cat['categoria_id'] ?></td>
                    <td><?= htmlspecialchars($cat['nombre']) ?></td>
                    <td>
                        <a href="editar.php?id=<?= $cat['categoria_id'] ?>" class="btn btn-warning">Editar</a>
                        <a href="eliminar.php?id=<?= $cat['categoria_id'] ?>" class="btn btn-danger" onclick="return confirm('¿Eliminar esta categoría?')">Eliminar</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include '../includes/footer.php'; ?>