<?php

$method = $_SERVER['REQUEST_METHOD'];
switch ($method) {
    case 'GET':
        echo json_encode($id ? $estados->getOne($id) : $estados->getAll());
        break;

    case 'POST':
        $data = json_decode(file_get_contents("php://input"), true);
        echo json_encode(['success' => $estados->create($data)]);
        break;

    case 'PUT':
        $data = json_decode(file_get_contents("php://input"), true);
        echo json_encode(['success' => $estados->update($id, $data)]);
        break;

    case 'DELETE':
        if (!$id) {
            http_response_code(400);
            echo json_encode(['error' => 'ID requerido']);
            break;
        }

        // Verificar que el estado existe
        $existe = $estados->getOne($id);
        if (!$existe) {
            http_response_code(404);
            echo json_encode(['error' => 'Estado no encontrado']);
            break;
        }

        try {
            $result = $estados->delete($id);
            http_response_code(200);
            echo json_encode(['success' => $result]);
        } catch (PDOException $e) {
            // Error de integridad referencial (tiene asignaciones asociadas)
            if ($e->getCode() == 23000) {
                http_response_code(409);
                echo json_encode(['error' => 'No se puede eliminar el estado porque tiene asignaciones activas']);
            } else {
                http_response_code(500);
                echo json_encode(['error' => 'Error interno del servidor']);
            }
        }
        break;


    default:
        http_response_code(405);
        echo json_encode(['error' => 'Método no permitido']);
}
