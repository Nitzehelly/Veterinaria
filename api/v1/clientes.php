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

        // 1. GET: Obtener todos o un cliente por ID
        case 'GET':
            if ($id) {
                $stmt = $pdo->prepare("SELECT * FROM cliente WHERE cliente_id = :id");
                $stmt->execute([':id' => $id]);
                $cliente = $stmt->fetch();

                if ($cliente) {
                    http_response_code(200);
                    echo json_encode($cliente);
                } else {
                    http_response_code(404);
                    echo json_encode(["status" => "error", "message" => "Cliente no encontrado"]);
                }
            } else {
                $stmt = $pdo->query("SELECT * FROM cliente");
                $clientes = $stmt->fetchAll();
                
                http_response_code(200);
                echo json_encode($clientes);
            }
            break;

        // 2. POST: Crear cliente
        case 'POST':
            $data = json_decode(file_get_contents("php://input"), true);

            if (!empty($data['nombre']) && !empty($data['correo'])) {
                $sql = "INSERT INTO cliente (nombre, correo, telefono) VALUES (:nombre, :correo, :telefono)";
                $stmt = $pdo->prepare($sql);
                
                $stmt->execute([
                    ':nombre' => $data['nombre'],
                    ':correo' => $data['correo'],
                    ':telefono' => $data['telefono'] ?? ''
                ]);

                $nuevoId = $pdo->lastInsertId();

                http_response_code(201);
                echo json_encode([
                    "status" => "success",
                    "message" => "Cliente creado exitosamente",
                    "cliente_id" => $nuevoId
                ]);
            } else {
                http_response_code(400);
                echo json_encode(["status" => "error", "message" => "Datos incompletos. Se requiere nombre y correo"]);
            }
            break;

        // 3. PUT: Actualizar cliente
        case 'PUT':
            if (!$id) {
                http_response_code(400);
                echo json_encode(["status" => "error", "message" => "Se requiere ID en la petición"]);
                break;
            }

            $data = json_decode(file_get_contents("php://input"), true);

            $check = $pdo->prepare("SELECT cliente_id FROM cliente WHERE cliente_id = :id");
            $check->execute([':id' => $id]);
            if ($check->rowCount() === 0) {
                http_response_code(404);
                echo json_encode(["status" => "error", "message" => "Cliente no encontrado"]);
                break;
            }

            if (!empty($data['nombre']) && !empty($data['correo'])) {
                $sql = "UPDATE cliente SET nombre = :nombre, correo = :correo, telefono = :telefono WHERE cliente_id = :id";
                $stmt = $pdo->prepare($sql);
                
                $stmt->execute([
                    ':id' => $id,
                    ':nombre' => $data['nombre'],
                    ':correo' => $data['correo'],
                    ':telefono' => $data['telefono'] ?? ''
                ]);

                http_response_code(200);
                echo json_encode(["status" => "success", "message" => "Cliente actualizado correctamente"]);
            } else {
                http_response_code(400);
                echo json_encode(["status" => "error", "message" => "Datos incompletos para actualizar"]);
            }
            break;

        case 'DELETE':
            if (!$id) {
                http_response_code(400);
                echo json_encode(["status" => "error", "message" => "Se requiere ID para eliminar"]);
                break;
            }

            $stmt = $pdo->prepare("DELETE FROM cliente WHERE cliente_id = :id");
            $stmt->execute([':id' => $id]);

            if ($stmt->rowCount() > 0) {
                http_response_code(200);
                echo json_encode(["status" => "success", "message" => "Cliente eliminado correctamente"]);
            } else {
                http_response_code(404);
                echo json_encode(["status" => "error", "message" => "Cliente no encontrado"]);
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