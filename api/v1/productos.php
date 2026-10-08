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

        // 1. GET
        case 'GET':
            if ($id) {
                $stmt = $pdo->prepare("SELECT * FROM producto WHERE producto_id = :id");
                $stmt->execute([':id' => $id]);
                $producto = $stmt->fetch();

                if ($producto) {
                    http_response_code(200);
                    echo json_encode($producto);
                } else {
                    http_response_code(404);
                    echo json_encode(["status" => "error", "message" => "Producto no encontrado"]);
                }
            } else {
                $stmt = $pdo->query("SELECT * FROM producto");
                $productos = $stmt->fetchAll();
                
                http_response_code(200);
                echo json_encode($productos);
            }
            break;

        // 2. POST
        case 'POST':
            $data = json_decode(file_get_contents("php://input"), true);

            if (!empty($data['nombre']) && isset($data['precio']) && isset($data['categoria_id'])) {
                $sql = "INSERT INTO producto (categoria_id, nombre, precio, stock) 
                        VALUES (:categoria_id, :nombre, :precio, :stock)";
                $stmt = $pdo->prepare($sql);
                
                $stmt->execute([
                    ':categoria_id' => $data['categoria_id'],
                    ':nombre' => $data['nombre'],
                    ':precio' => $data['precio'],
                    ':stock' => $data['stock'] ?? 0
                ]);

                $nuevoId = $pdo->lastInsertId();

                http_response_code(201);
                echo json_encode([
                    "status" => "success",
                    "message" => "Producto creado exitosamente",
                    "producto_id" => $nuevoId
                ]);
            } else {
                http_response_code(400);
                echo json_encode(["status" => "error", "message" => "Datos incompletos. Requiere nombre, precio y categoria_id"]);
            }
            break;

        // 3. PUT
        case 'PUT':
            if (!$id) {
                http_response_code(400);
                echo json_encode(["status" => "error", "message" => "Se requiere ID en la petición"]);
                break;
            }

            $data = json_decode(file_get_contents("php://input"), true);

            $check = $pdo->prepare("SELECT producto_id FROM producto WHERE producto_id = :id");
            $check->execute([':id' => $id]);
            if ($check->rowCount() === 0) {
                http_response_code(404);
                echo json_encode(["status" => "error", "message" => "Producto no encontrado"]);
                break;
            }

            if (!empty($data['nombre']) && isset($data['precio']) && isset($data['categoria_id'])) {
                $sql = "UPDATE producto 
                        SET categoria_id = :categoria_id, nombre = :nombre, precio = :precio, stock = :stock 
                        WHERE producto_id = :id";
                $stmt = $pdo->prepare($sql);
                
                $stmt->execute([
                    ':id' => $id,
                    ':categoria_id' => $data['categoria_id'],
                    ':nombre' => $data['nombre'],
                    ':precio' => $data['precio'],
                    ':stock' => $data['stock'] ?? 0
                ]);

                http_response_code(200);
                echo json_encode(["status" => "success", "message" => "Producto actualizado correctamente"]);
            } else {
                http_response_code(400);
                echo json_encode(["status" => "error", "message" => "Datos incompletos para actualizar"]);
            }
            break;

        // 4. DELETE
        case 'DELETE':
            if (!$id) {
                http_response_code(400);
                echo json_encode(["status" => "error", "message" => "Se requiere ID para eliminar"]);
                break;
            }

            $stmt = $pdo->prepare("DELETE FROM producto WHERE producto_id = :id");
            $stmt->execute([':id' => $id]);

            if ($stmt->rowCount() > 0) {
                http_response_code(200);
                echo json_encode(["status" => "success", "message" => "Producto eliminado correctamente"]);
            } else {
                http_response_code(404);
                echo json_encode(["status" => "error", "message" => "Producto no encontrado"]);
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