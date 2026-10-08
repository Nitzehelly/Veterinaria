<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once __DIR__ . '/../../config/database.php';

$method = $_SERVER['REQUEST_METHOD'];
$id = isset($_GET['id']) ? intval($_GET['id']) : null;

try {
    switch ($method) {

        case 'GET':
            if ($id) {
                $stmt = $pdo->prepare("SELECT * FROM categoria WHERE categoria_id = :id");
                $stmt->execute([':id' => $id]);
                $categoria = $stmt->fetch();

                if ($categoria) {
                    http_response_code(200);
                    echo json_encode($categoria);
                } else {
                    http_response_code(404);
                    echo json_encode(["status" => "error", "message" => "Categoría no encontrada"]);
                }
            } else {
                $stmt = $pdo->query("SELECT * FROM categoria");
                $categorias = $stmt->fetchAll();
                
                http_response_code(200);
                echo json_encode($categorias);
            }
            break;

        default:
            http_response_code(405);
            echo json_encode(["status" => "error", "message" => "Método no permitido"]);
            break;
    }

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "Error en la base de datos: " . $e->getMessage()]);
}
?>