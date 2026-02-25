<?php
class empleado
{
    private $pdo;
    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAll()
    {
        return $this->pdo->query("SELECT * FROM empleados")->fetchAll();
    }

    public function getOne($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM empleados WHERE id_empleados= ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create($data)
    {
        $stmt = $this->pdo->prepare("INSERT INTO empleados (id_empleados, nombre, apellidos,telefono) VALUES (?, ?, ?,?)");
        return $stmt->execute([$data['id_empleados'], $data['nombre'], $data['apellidos'], $data['telefono']]);
    }

    public function update($id, $data)
    {
        $stmt = $this->pdo->prepare("UPDATE empleados SET nombre = ?, apellidos = ?, telefono = ? WHERE id_empleados = ?");
        return $stmt->execute([$data['nombre'], $data['apellidos'], $data['telefono'], $id]);
    }

    public function delete($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM empleados WHERE id_empleados = ?");
        return $stmt->execute([$id]);
    }
}
