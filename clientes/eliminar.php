<?php
include '../config/database.php';

if (isset($_GET['id'])) {
    $stmt = $pdo->prepare("DELETE FROM cliente WHERE cliente_id = ?");
    $stmt->execute([$_GET['id']]);
}

header('Location: index.php');
exit;
?>