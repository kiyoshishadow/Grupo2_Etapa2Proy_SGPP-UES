<?php

class DocenteModel
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function findByUsuarioId(int $usuarioId)
    {
        $stmt = $this->pdo->prepare(
            "SELECT * FROM docente WHERE usuario_id = ? LIMIT 1"
        );
        $stmt->execute([$usuarioId]);
        return $stmt->fetch();
    }

    public function listarActivos(): array
    {
        $stmt = $this->pdo->query(
            "SELECT d.*, u.nombre_usuario, u.email
             FROM docente d
             JOIN usuario u ON u.id = d.usuario_id
             WHERE u.activo = 1
             ORDER BY d.nombre_completo"
        );
        return $stmt->fetchAll();
    }

    public function find($id): array
    {
        $stmt = $this->pdo->prepare(
            "SELECT d.*, u.nombre_usuario, u.email
             FROM docente d
             JOIN usuario u ON u.id = d.usuario_id
             WHERE d.id = ? LIMIT 1"
        );
        $stmt->execute([$id]);
        return $stmt->fetch() ?: [];
    }
}
