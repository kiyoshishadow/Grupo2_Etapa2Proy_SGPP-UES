<?php

class EstudianteModel
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function findByUsuarioId(int $usuarioId)
    {
        $stmt = $this->pdo->prepare(
            "SELECT * FROM estudiante WHERE usuario_id = ? LIMIT 1"
        );
        $stmt->execute([$usuarioId]);
        return $stmt->fetch();
    }

    public function find(int $id)
    {
        $stmt = $this->pdo->prepare(
            "SELECT * FROM estudiante WHERE id = ? LIMIT 1"
        );
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function listar(): array
    {
        $stmt = $this->pdo->query(
            "SELECT * FROM estudiante ORDER BY nombre_completo"
        );
        return $stmt->fetchAll();
    }
}
