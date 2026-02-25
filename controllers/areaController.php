<?php

$method = $_SERVER['REQUEST_METHOD'];
switch ($method) {
    case 'GET':
        echo json_encode($id ? $areas->getOne($id) : $areas->getAll());
        break;

    case 'POST':
        $data = json_decode(file_get_contents("php://input"), true);
        echo json_encode(['success' => $areas->create($data)]);
        break;

    case 'PUT':
        $data = json_decode(file_get_contents("php://input"), true);
        echo json_encode(['success' => $areas->update($id, $data)]);
        break;

    case 'DELETE':
        echo json_encode(['success' => $areas->delete($id)]);
        break;

    default:
        http_response_code(405);
        echo json_encode(['error' => 'Método no permitido']);
}
