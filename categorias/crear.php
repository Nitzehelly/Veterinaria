<?php
include '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $stmt = $pdo->prepare("INSERT INTO categoria (nombre) VALUES (?)");
    $stmt->execute([$nombre]);
    header('Location: index.php');
    exit;
}

include '../includes/header.php';
include '../includes/navbar.php';
?>

<div class="container">
    <div class="card">
        <h2>Nueva Categoría</h2>
        <form method="POST">
            <div class="form-group">
                <label>Nombre de Categoría</label>
                <input type="text" name="nombre" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Guardar</button>
            <a href="index.php" class="btn btn-danger">Cancelar</a>
        </form>
    </div>
</div>

<?php include '../includes/footer.php'; ?>