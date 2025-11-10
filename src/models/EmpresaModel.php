<?php

class EmpresaModel
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function all(): array
    {
        $stmt = $this->pdo->query(
            "SELECT id, nombre, giro, direccion, contacto_principal, telefono, correo, activa, fecha_creacion, fecha_actualizacion
             FROM empresa
             ORDER BY nombre ASC"
        );
        return $stmt->fetchAll();
    }

    public function activas(): array
    {
        $stmt = $this->pdo->prepare(
            "SELECT id, nombre, giro, direccion, contacto_principal, telefono, correo
             FROM empresa
             WHERE activa = 1
             ORDER BY nombre ASC"
        );
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function find(int $id)
    {
        $stmt = $this->pdo->prepare(
            "SELECT * FROM empresa WHERE id = ? LIMIT 1"
        );
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create(array $data): int
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO empresa (nombre, giro, direccion, contacto_principal, telefono, correo, activa)
             VALUES (?,?,?,?,?,?,?)"
        );
        $stmt->execute([
            $data['nombre'],
            $data['giro'] ?? null,
            $data['direccion'] ?? null,
            $data['contacto_principal'] ?? null,
            $data['telefono'] ?? null,
            $data['correo'] ?? null,
            $data['activa'] ?? 1,
        ]);

        return (int)$this->pdo->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        $stmt = $this->pdo->prepare(
            "UPDATE empresa
             SET nombre = ?, giro = ?, direccion = ?, contacto_principal = ?, telefono = ?, correo = ?, activa = ?
             WHERE id = ?"
        );

        return $stmt->execute([
            $data['nombre'],
            $data['giro'] ?? null,
            $data['direccion'] ?? null,
            $data['contacto_principal'] ?? null,
            $data['telefono'] ?? null,
            $data['correo'] ?? null,
            $data['activa'] ?? 1,
            $id,
        ]);
    }

    public function toggleEstado(int $id, bool $activa): bool
    {
        $stmt = $this->pdo->prepare(
            "UPDATE empresa SET activa = ? WHERE id = ?"
        );
        return $stmt->execute([$activa ? 1 : 0, $id]);
    }

    public function contarEmpresasConEstudiantesActivos(): int
    {
        $stmt = $this->pdo->prepare(
            "SELECT COUNT(DISTINCT e.id) as total
             FROM empresa e
             JOIN expediente exp ON exp.empresa_id = e.id
             WHERE exp.estado = 'activo'"
        );
        $stmt->execute();
        $result = $stmt->fetch();
        return (int)$result['total'];
    }

    public function listarConEstudiantesActivos(): array
    {
        $stmt = $this->pdo->query(
            "SELECT e.nombre, 
                    COUNT(exp.id) as total_estudiantes,
                    GROUP_CONCAT(DISTINCT est.carrera ORDER BY est.carrera SEPARATOR ', ') as carreras
             FROM empresa e
             JOIN expediente exp ON exp.empresa_id = e.id
             JOIN estudiante est ON est.id = exp.estudiante_id
             WHERE exp.estado = 'activo'
             GROUP BY e.id, e.nombre
             ORDER BY total_estudiantes DESC, e.nombre"
        );
        return $stmt->fetchAll();
    }
}
