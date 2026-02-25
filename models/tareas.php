<?php
class tareas
{
    private $pdo;
    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAll()
    {
        return $this->pdo->query("SELECT * FROM tareas")->fetchAll();
    }

    public function getOne($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM tareas WHERE id_tareas= ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create($data)
    {
        $stmt = $this->pdo->prepare("INSERT INTO tareas (id_tareas, descripcion, prioridad) VALUES (?, ?, ?)");
        return $stmt->execute([$data['id_tareas'], $data['descripcion'], $data['prioridad']]);
    }

    public function update($id, $data)
    {
        $stmt = $this->pdo->prepare("UPDATE tareas SET descripcion = ?, prioridad = ? WHERE id_tareas = ?");
        return $stmt->execute([$data['descripcion'], $data['prioridad'], $id]);
    }

    public function delete($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM tareas WHERE id_tareas = ?");
        return $stmt->execute([$id]);
    }
}
