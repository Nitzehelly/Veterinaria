<?php
include '../config/database.php';
include '../includes/header.php';
include '../includes/navbar.php';

$query = "SELECT p.*, c.nombre AS categoria FROM producto p 
          INNER JOIN categoria c ON p.categoria_id = c.categoria_id";
$stmt = $pdo->query($query);
$productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container">
    <div class="card">
        <div class="table-header">
            <h2>Inventario y Servicios Veterinarios</h2>
            <a href="crear.php" class="btn btn-primary">+ Nuevo Producto / Servicio</a>
        </div>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Categoría</th>
                    <th>Precio</th>
                    <th>Stock</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($productos as $prod): ?>
                <tr>
                    <td><?= $prod['producto_id'] ?></td>
                    <td><?= htmlspecialchars($prod['nombre']) ?></td>
                    <td><?= htmlspecialchars($prod['categoria']) ?></td>
                    <td>$<?= number_format($prod['precio'], 2) ?></td>
                    <td><?= $prod['stock'] ?></td>
                    <td>
                        <a href="editar.php?id=<?= $prod['producto_id'] ?>" class="btn btn-warning">Editar</a>
                        <a href="eliminar.php?id=<?= $prod['producto_id'] ?>" class="btn btn-danger" onclick="return confirm('¿Eliminar producto?')">Eliminar</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include '../includes/footer.php'; ?>