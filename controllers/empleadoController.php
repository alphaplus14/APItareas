<?php

$method = $_SERVER['REQUEST_METHOD'];
switch ($method) {
    case 'GET':
        echo json_encode($id ? $empleado->getOne($id) : $empleado->getAll());
        break;

    case 'POST':
        $data = json_decode(file_get_contents("php://input"), true);
        echo json_encode(['success' => $empleado->create($data)]);
        break;

    case 'PUT':
        $data = json_decode(file_get_contents("php://input"), true);
        echo json_encode(['success' => $empleado->update($id, $data)]);
        break;

    case 'DELETE':
        if (!$id) {
            http_response_code(400);
            echo json_encode(['error' => 'ID requerido']);
            break;
        }

        $existe = $empleado->getOne($id);
        if (!$existe) {
            http_response_code(404);
            echo json_encode(['error' => 'Empleado no encontrado']);
            break;
        }

        try {
            $result = $empleado->delete($id);
            http_response_code(200);
            echo json_encode(['success' => $result]);
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) { //este error (23000) es de la base de datos cuando se intenta eliminar un registro que tiene relaciones activas
                http_response_code(409);
                echo json_encode(['error' => 'No se puede eliminar el empleado porque tiene asignaciones activas']);
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
