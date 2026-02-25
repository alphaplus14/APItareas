<?php
class areas
{
    private $pdo;
    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAll()
    {
        return $this->pdo->query("SELECT * FROM areas")->fetchAll();
    }

    public function getOne($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM areas WHERE id_area= ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create($data)
    {
        $stmt = $this->pdo->prepare("INSERT INTO areas (nombre, descripcion) VALUES (?, ?)");
        return $stmt->execute([$data['nombre'], $data['descripcion']]);
    }

    public function update($id, $data)
    {
        $stmt = $this->pdo->prepare("UPDATE areas SET nombre = ?, descripcion = ? WHERE id_area = ?");
        return $stmt->execute([$data['nombre'], $data['descripcion'], $id]);
    }

    public function delete($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM areas WHERE id_area = ?");
        return $stmt->execute([$id]);
    }
}
