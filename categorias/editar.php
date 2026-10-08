<?php
include '../config/database.php';

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM categoria WHERE categoria_id = ?");
$stmt->execute([$id]);
$categoria = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $stmt = $pdo->prepare("UPDATE categoria SET nombre = ? WHERE categoria_id = ?");
    $stmt->execute([$nombre, $id]);
    header('Location: index.php');
    exit;
}

include '../includes/header.php';
include '../includes/navbar.php';
?>

<div class="container">
    <div class="card">
        <h2>Editar Categoría</h2>
        <form method="POST">
            <div class="form-group">
                <label>Nombre de Categoría</label>
                <input type="text" name="nombre" value="<?= htmlspecialchars($categoria['nombre']) ?>" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Actualizar</button>
            <a href="index.php" class="btn btn-danger">Cancelar</a>
        </form>
    </div>
</div>

<?php include '../includes/footer.php'; ?>