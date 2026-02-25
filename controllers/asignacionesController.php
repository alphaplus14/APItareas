<?php
header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        if ($id) {
            $result = $asignaciones->getOne($id);
            if (!$result) {
                http_response_code(404);
                echo json_encode(['error' => 'Asignación no encontrada']);
            } else {
                http_response_code(200);
                echo json_encode($result);
            }
        } else {
            http_response_code(200);
            echo json_encode($asignaciones->getAll());
        }
        break;

    case 'POST':
        $data = json_decode(file_get_contents("php://input"), true);

        // Validar que vengan los campos requeridos
        if (!isset($data['empleados_id_empleados'], $data['tareas_id_tareas'], $data['estados_id_estados'], $data['fecha_asignacion'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Faltan campos requeridos']);
            break;
        }

        // Validar fechas
        if (!empty($data['fecha_entrega']) && strtotime($data['fecha_asignacion']) > strtotime($data['fecha_entrega'])) {
            http_response_code(400);
            echo json_encode(['error' => 'La fecha de asignación no puede ser posterior a la fecha de entrega']);
            break;
        }

        $result = $asignaciones->create($data);

        if (is_array($result) && isset($result['error'])) {
            http_response_code($result['code']);
            echo json_encode(['error' => $result['error']]);
            break;
        }

        http_response_code(201);
        echo json_encode(['success' => true, 'message' => 'Asignación creada correctamente']);
        break;

    case 'PUT':
        if (!$id) {
            http_response_code(400);
            echo json_encode(['error' => 'ID requerido']);
            break;
        }

        $data = json_decode(file_get_contents("php://input"), true);

        // Validar fechas
        if (!empty($data['fecha_asignacion']) && !empty($data['fecha_entrega'])) {
            if (strtotime($data['fecha_asignacion']) > strtotime($data['fecha_entrega'])) {
                http_response_code(400);
                echo json_encode(['error' => 'La fecha de asignación no puede ser posterior a la fecha de entrega']);
                break;
            }
        }

        $result = $asignaciones->update($id, $data);

        if (is_array($result) && isset($result['error'])) {
            http_response_code($result['code']);
            echo json_encode(['error' => $result['error']]);
            break;
        }

        http_response_code(200);
        echo json_encode(['success' => true, 'message' => 'Asignación actualizada correctamente']);
        break;

    case 'DELETE':
        if (!$id) {
            http_response_code(400);
            echo json_encode(['error' => 'ID requerido']);
            break;
        }

        $result = $asignaciones->delete($id);

        if (is_array($result) && isset($result['error'])) {
            http_response_code($result['code']);
            echo json_encode(['error' => $result['error']]);
            break;
        }

        http_response_code(200);
        echo json_encode(['success' => true, 'message' => 'Asignación eliminada correctamente']);
        break;

    default:
        http_response_code(405);
        echo json_encode(['error' => 'Método no permitido']);
}
