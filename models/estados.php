<?php
class estados
{
    private $pdo;
    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAll()
    {
        return $this->pdo->query("SELECT * FROM estados")->fetchAll();
    }

    public function getOne($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM estados WHERE id_estados= ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create($data)
    {
        $stmt = $this->pdo->prepare("INSERT INTO estados (nombre) VALUES (?)");
        return $stmt->execute([$data['nombre']]);
    }

    public function update($id, $data)
    {
        $stmt = $this->pdo->prepare("UPDATE estados SET nombre = ? WHERE id_estados = ?");
        return $stmt->execute([$data['nombre'], $id]);
    }

    public function delete($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM estados WHERE id_estados = ?");
        return $stmt->execute([$id]);
    }
}
