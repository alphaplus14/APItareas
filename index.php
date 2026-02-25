<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");
    http_response_code(200);
    exit();
}

require 'models/area.php';
$areas = new areas($pdo);

require 'models/tareas.php';
$tareas = new tareas($pdo);

require 'models/estados.php';
$estados = new estados($pdo);

require 'models/empleado.php';
$empleado = new empleado($pdo);

require 'models/asignaciones.php';
$asignaciones = new asignaciones($pdo);

header("Content-Type: application/json");

$method = $_SERVER['REQUEST_METHOD'];
// Obtiene la URI solicitada, elimina las barras iniciales y finales, y la divide en partes usando '/' como separador
// Ejemplo: si la URL es "http://dominio.com/api/jugadores/3", entonces $uri será ['api', 'jugadores', '3']
$uri = explode('/', trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/'));

// Obtiene el segundo segmento de la URI (índice 1), que suele representar el recurso (por ejemplo: "jugadores")
// Usa el operador null coalescing para evitar errores si no existe el índice
$resource = $uri[1] ?? null;

// Obtiene el tercer segmento de la URI (índice 2), que usualmente representa el ID del recurso (por ejemplo: "3")
$id = $uri[2] ?? null;



switch ($resource) {
    case 'empleados':
        require 'controllers/empleadoController.php';
        break;

    case 'tareas':
        require 'controllers/tareasController.php';
        break;

    case 'estados':
        require 'controllers/estadosController.php';
        break;

    case 'areas':
        require 'controllers/areaController.php';
        break;

    case 'asignaciones':
        require 'controllers/asignacionesController.php';
        break;
    default:
        http_response_code(404);
        echo json_encode(['error' => 'Recurso no encontrado']);
        break;
}
