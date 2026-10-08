<?php
include '../config/database.php';

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM producto WHERE producto_id = ?");
$stmt->execute([$id]);
$producto = $stmt->fetch(PDO::FETCH_ASSOC);

$categorias = $pdo->query("SELECT * FROM categoria")->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $categoria_id = $_POST['categoria_id'];
    $nombre = $_POST['nombre'];
    $precio = $_POST['precio'];
    $stock = $_POST['stock'];

    $stmt = $pdo->prepare("UPDATE producto SET categoria_id = ?, nombre = ?, precio = ?, stock = ? WHERE producto_id = ?");
    $stmt->execute([$categoria_id, $nombre, $precio, $stock, $id]);
    header('Location: index.php');
    exit;
}

include '../includes/header.php';
include '../includes/navbar.php';
?>

<div class="container">
    <div class="card">
        <h2>Editar Producto / Servicio</h2>
        <form method="POST">
            <div class="form-group">
                <label>Categoría</label>
                <select name="categoria_id" class="form-control" required>
                    <?php foreach ($categorias as $cat): ?>
                        <option value="<?= $cat['categoria_id'] ?>" <?= $cat['categoria_id'] == $producto['categoria_id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cat['nombre']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Nombre del Producto/Servicio</label>
                <input type="text" name="nombre" value="<?= htmlspecialchars($producto['nombre']) ?>" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Precio ($)</label>
                <input type="number" step="0.01" name="precio" value="<?= $producto['precio'] ?>" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Stock</label>
                <input type="number" name="stock" value="<?= $producto['stock'] ?>" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Actualizar</button>
            <a href="index.php" class="btn btn-danger">Cancelar</a>
        </form>
    </div>
</div>

<?php include '../includes/footer.php'; ?>