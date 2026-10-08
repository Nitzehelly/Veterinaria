<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

require_once __DIR__ . '/../../config/database.php';

try {
    // Obtener la lista de todas las tablas en la base de datos
    $tablasStmt = $pdo->query("SHOW TABLES");
    $tablas = $tablasStmt->fetchAll(PDO::FETCH_COLUMN);

    $baseDeDatos = [];

    foreach ($tablas as $tabla) {
        $stmt = $pdo->query("SELECT * FROM {$tabla}");
        $baseDeDatos[$tabla] = $stmt->fetchAll();
    }

    http_response_code(200);
    echo json_encode($baseDeDatos);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "Error en la base de datos: " . $e->getMessage()]);
}
?>