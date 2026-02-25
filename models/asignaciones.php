<?php
class asignaciones
{
    private $pdo;
    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAll()
    {
        return $this->pdo->query("SELECT * FROM asignaciones")->fetchAll();
    }

    public function getOne($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM asignaciones WHERE id_asignacion= ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create($data)
    {
        //validar que exista empleado
        $emp = $this->pdo->prepare("SELECT id_empleados FROM empleados WHERE id_empleados = ?");
        $emp->execute([$data['empleados_id_empleados']]);
        if (!$emp->fetch()) return ['error' => 'Empleado no existe', 'code' => 404];

        //validar que exista tarea
        $tar = $this->pdo->prepare("SELECT id_tareas FROM tareas WHERE id_tareas = ?");
        $tar->execute([$data['tareas_id_tareas']]);
        if (!$tar->fetch()) return ['error' => 'Tarea no existe', 'code' => 404];

        //validar que exista estado
        $est = $this->pdo->prepare("SELECT id_estados FROM estados WHERE id_estados = ?");
        $est->execute([$data['estados_id_estados']]);
        if (!$est->fetch()) return ['error' => 'Estado no existe', 'code' => 404];

        //Evitar asignar la misma tarea a un empleado mas de una vez
        $dup = $this->pdo->prepare("SELECT id_asignacion FROM asignaciones WHERE empleados_id_empleados = ? AND tareas_id_tareas = ?");
        $dup->execute([$data['empleados_id_empleados'], $data['tareas_id_tareas']]);
        if ($dup->fetch()) return ['error' => 'El empleado ya tiene asignada esa tarea', 'code' => 409];

        //validar que la fecha de asignacion no sea posterior a la fecha de entrega
        if (!empty($data['fecha_entrega'])) {
            if (strtotime($data['fecha_asignacion']) > strtotime($data['fecha_entrega'])) {
                return ['error' => 'La fecha de asignación no puede ser posterior a la fecha de entrega', 'code' => 400];
            }
        }

        $stmt = $this->pdo->prepare("INSERT INTO asignaciones (empleados_id_empleados,tareas_id_tareas,estados_id_estados,fecha_asignacion,fecha_entrega) VALUES (?, ?, ?,?,?)");
        return $stmt->execute([$data['empleados_id_empleados'], $data['tareas_id_tareas'], $data['estados_id_estados'], $data['fecha_asignacion'], $data['fecha_entrega']]);
    }

    public function update($id, $data)
    {
        // Verificar que la asignacion exista
        $check = $this->pdo->prepare("SELECT id_asignacion FROM asignaciones WHERE id_asignacion = ?");
        $check->execute([$id]);
        if (!$check->fetch()) return ['error' => 'Asignación no encontrada', 'code' => 404];

        // Validar que exista empleado
        $emp = $this->pdo->prepare("SELECT id_empleados FROM empleados WHERE id_empleados = ?");
        $emp->execute([$data['empleados_id_empleados']]);
        if (!$emp->fetch()) return ['error' => 'Empleado no existe', 'code' => 404];

        // Validar que exista tarea
        $tar = $this->pdo->prepare("SELECT id_tareas FROM tareas WHERE id_tareas = ?");
        $tar->execute([$data['tareas_id_tareas']]);
        if (!$tar->fetch()) return ['error' => 'Tarea no existe', 'code' => 404];

        // Validar que exista estado
        $est = $this->pdo->prepare("SELECT id_estados FROM estados WHERE id_estados = ?");
        $est->execute([$data['estados_id_estados']]);
        if (!$est->fetch()) return ['error' => 'Estado no existe', 'code' => 404];

        // Validar fechas
        if (!empty($data['fecha_entrega'])) {
            if (strtotime($data['fecha_asignacion']) > strtotime($data['fecha_entrega'])) {
                return ['error' => 'La fecha de asignación no puede ser posterior a la fecha de entrega', 'code' => 400];
            }
        }
        $stmt = $this->pdo->prepare("UPDATE asignaciones SET empleados_id_empleados = ?, tareas_id_tareas = ?, estados_id_estados = ?, fecha_asignacion = ?, fecha_entrega = ? WHERE id_asignacion = ?");
        return $stmt->execute([
            $data['empleados_id_empleados'],
            $data['tareas_id_tareas'],
            $data['estados_id_estados'],
            $data['fecha_asignacion'],
            $data['fecha_entrega'],
            $id
        ]);
    }

    public function delete($id)
    {
        // Verificar si tiene asignaciones activas
        $check = $this->pdo->prepare("SELECT id_asignacion FROM asignaciones WHERE id_asignacion = ?");
        $check->execute([$id]);
        if (!$check->fetch()) return ['error' => 'Asignación no encontrada', 'code' => 404];

        $stmt = $this->pdo->prepare("DELETE FROM asignaciones WHERE id_asignacion = ?");
        return $stmt->execute([$id]);
    }
}
