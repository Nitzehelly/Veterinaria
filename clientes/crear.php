<?php
include '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $correo = $_POST['correo'];
    $telefono = $_POST['telefono'];

    $stmt = $pdo->prepare("INSERT INTO cliente (nombre, correo, telefono) VALUES (?, ?, ?)");
    $stmt->execute([$nombre, $correo, $telefono]);
    header('Location: index.php');
    exit;
}

include '../includes/header.php';
include '../includes/navbar.php';
?>

<div class="container">
    <div class="card">
        <h2>Registrar Nuevo Cliente</h2>
        <form method="POST">
            <div class="form-group">
                <label>Nombre Completo</label>
                <input type="text" name="nombre" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Correo Electrónico</label>
                <input type="email" name="correo" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Teléfono</label>
                <input type="text" name="telefono" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Guardar</button>
            <a href="index.php" class="btn btn-danger">Cancelar</a>
        </form>
    </div>
</div>

<?php include '../includes/footer.php'; ?>