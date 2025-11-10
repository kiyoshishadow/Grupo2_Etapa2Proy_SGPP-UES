<?php

class AvanceExpedienteModel
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function listarPorExpediente(int $expedienteId): array
    {
        $stmt = $this->pdo->prepare(
            "SELECT ae.*, d.nombre_completo AS registrado_por_nombre
             FROM avance_expediente ae
             LEFT JOIN docente d ON d.id = ae.registrado_por
             WHERE ae.expediente_id = ?
             ORDER BY ae.fecha_registro DESC, ae.id DESC"
        );
        $stmt->execute([$expedienteId]);
        return $stmt->fetchAll();
    }

    public function crear(array $data): int
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO avance_expediente (expediente_id, tipo, descripcion, porcentaje_avance, registrado_por, fecha_registro)
             VALUES (?,?,?,?,?,?)"
        );

        $fecha = $data['fecha_registro'] ?? date('Y-m-d H:i:s');

        $stmt->execute([
            $data['expediente_id'],
            $data['tipo'] ?? 'observacion',
            $data['descripcion'] ?? null,
            $data['porcentaje_avance'] ?? null,
            $data['registrado_por'] ?? null,
            $fecha,
        ]);

        return (int)$this->pdo->lastInsertId();
    }

    public function eliminar(int $id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM avance_expediente WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function guardar(array $data): int
    {
        return $this->crear($data);
    }

    public function listarRecientesConDetalles(int $limite = 20): array
    {
        $stmt = $this->pdo->prepare(
            "SELECT ae.*, 
                    est.nombre_completo AS estudiante_nombre,
                    est.carrera AS estudiante_carrera,
                    est.id AS estudiante_id,
                    emp.nombre AS empresa_nombre,
                    d.nombre_completo AS docente_nombre
             FROM avance_expediente ae
             JOIN expediente e ON e.id = ae.expediente_id
             JOIN estudiante est ON est.id = e.estudiante_id
             JOIN empresa emp ON emp.id = e.empresa_id
             LEFT JOIN docente d ON d.id = ae.registrado_por
             ORDER BY ae.fecha_registro DESC
             LIMIT ?"
        );
        $stmt->execute([$limite]);
        return $stmt->fetchAll();
    }
}
