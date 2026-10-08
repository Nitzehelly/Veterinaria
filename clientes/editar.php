<?php
include '../config/database.php';

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM cliente WHERE cliente_id = ?");
$stmt->execute([$id]);
$cliente = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $correo = $_POST['correo'];
    $telefono = $_POST['telefono'];

    $stmt = $pdo->prepare("UPDATE cliente SET nombre = ?, correo = ?, telefono = ? WHERE cliente_id = ?");
    $stmt->execute([$nombre, $correo, $telefono, $id]);
    header('Location: index.php');
    exit;
}

include '../includes/header.php';
include '../includes/navbar.php';
?>

<div class="container">
    <div class="card">
        <h2>Editar Cliente</h2>
        <form method="POST">
            <div class="form-group">
                <label>Nombre Completo</label>
                <input type="text" name="nombre" value="<?= htmlspecialchars($cliente['nombre']) ?>" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Correo Electrónico</label>
                <input type="email" name="correo" value="<?= htmlspecialchars($cliente['correo']) ?>" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Teléfono</label>
                <input type="text" name="telefono" value="<?= htmlspecialchars($cliente['telefono']) ?>" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Actualizar</button>
            <a href="index.php" class="btn btn-danger">Cancelar</a>
        </form>
    </div>
</div>

<?php include '../includes/footer.php'; ?>