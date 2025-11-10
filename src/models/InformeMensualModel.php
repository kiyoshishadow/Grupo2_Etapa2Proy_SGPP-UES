<?php

class InformeMensualModel
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function listarPorExpediente(int $expedienteId): array
    {
        $stmt = $this->pdo->prepare(
            "SELECT im.*, d.nombre_completo AS revisor_nombre
             FROM informe_mensual im
             LEFT JOIN docente d ON d.id = im.revisor_id
             WHERE im.expediente_id = ?
             ORDER BY im.periodo DESC"
        );
        $stmt->execute([$expedienteId]);
        return $stmt->fetchAll();
    }

    public function find(int $id)
    {
        $stmt = $this->pdo->prepare(
            "SELECT im.*, d.nombre_completo AS revisor_nombre,
                    e.estudiante_id
             FROM informe_mensual im
             JOIN expediente e ON e.id = im.expediente_id
             LEFT JOIN docente d ON d.id = im.revisor_id
             WHERE im.id = ?
             LIMIT 1"
        );
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function findPorExpedienteYPeriodo(int $expedienteId, string $periodo)
    {
        $stmt = $this->pdo->prepare(
            "SELECT *
             FROM informe_mensual
             WHERE expediente_id = ? AND periodo = ?
             LIMIT 1"
        );
        $stmt->execute([$expedienteId, $periodo]);
        return $stmt->fetch();
    }

    public function guardar(array $data): int
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO informe_mensual (
                expediente_id, periodo, ruta_archivo,
                comentario_estudiante, estado_revision, comentario_revisor,
                revisor_id, fecha_subida, fecha_revision, hash_archivo
            ) VALUES (?,?,?,?,?,?,?,?,?,?)
            ON DUPLICATE KEY UPDATE
                ruta_archivo = VALUES(ruta_archivo),
                comentario_estudiante = VALUES(comentario_estudiante),
                estado_revision = VALUES(estado_revision),
                comentario_revisor = VALUES(comentario_revisor),
                revisor_id = VALUES(revisor_id),
                fecha_subida = VALUES(fecha_subida),
                fecha_revision = VALUES(fecha_revision),
                hash_archivo = VALUES(hash_archivo)"
        );

        $fechaSubida = $data['fecha_subida'] ?? date('Y-m-d H:i:s');
        $fechaRevision = $data['fecha_revision'] ?? null;

        $stmt->execute([
            $data['expediente_id'],
            $data['periodo'],
            $data['ruta_archivo'] ?? null,
            $data['comentario_estudiante'] ?? null,
            $data['estado_revision'] ?? 'pendiente',
            $data['comentario_revisor'] ?? null,
            $data['revisor_id'] ?? null,
            $fechaSubida,
            $fechaRevision,
            $data['hash_archivo'] ?? null,
        ]);

        if (!empty($data['id'])) {
            return (int)$data['id'];
        }

        return (int)$this->pdo->lastInsertId();
    }

    public function actualizarEstado(int $id, string $estado, ?string $comentarioRevisor = null, ?int $revisorId = null): bool
    {
        $stmt = $this->pdo->prepare(
            "UPDATE informe_mensual
             SET estado_revision = ?, comentario_revisor = ?, revisor_id = ?, fecha_revision = NOW()
             WHERE id = ?"
        );

        return $stmt->execute([$estado, $comentarioRevisor, $revisorId, $id]);
    }

    public function actualizar(int $id, array $data): bool
    {
        $allowed = [
            'periodo',
            'ruta_archivo',
            'comentario_estudiante',
            'estado_revision',
            'comentario_revisor',
            'revisor_id',
            'fecha_subida',
            'fecha_revision',
            'hash_archivo'
        ];

        $fields = [];
        $params = [];

        foreach ($data as $key => $value) {
            if (in_array($key, $allowed, true)) {
                $fields[] = "$key = ?";
                $params[] = $value;
            }
        }

        if (empty($fields)) {
            return false;
        }

        $params[] = $id;
        $sql = "UPDATE informe_mensual SET " . implode(', ', $fields) . " WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($params);
    }

    public function eliminar(int $id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM informe_mensual WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function listarTodosConDetalles(): array
    {
        $stmt = $this->pdo->query(
            "SELECT im.*, 
                    est.nombre_completo AS estudiante_nombre, 
                    est.carrera AS estudiante_carrera,
                    e.nombre AS empresa_nombre
             FROM informe_mensual im
             JOIN expediente exp ON exp.id = im.expediente_id
             JOIN estudiante est ON est.id = exp.estudiante_id
             JOIN empresa e ON e.id = exp.empresa_id
             ORDER BY im.fecha_subida DESC"
        );
        return $stmt->fetchAll();
    }

    public function listarPorEstado(string $estado): array
    {
        $stmt = $this->pdo->prepare(
            "SELECT im.*, 
                    est.nombre_completo AS estudiante_nombre, 
                    est.carrera AS estudiante_carrera,
                    e.nombre AS empresa_nombre
             FROM informe_mensual im
             JOIN expediente exp ON exp.id = im.expediente_id
             JOIN estudiante est ON est.id = exp.estudiante_id
             JOIN empresa e ON e.id = exp.empresa_id
             WHERE im.estado_revision = ?
             ORDER BY im.fecha_subida DESC"
        );
        $stmt->execute([$estado]);
        return $stmt->fetchAll();
    }

    public function listarTodos(): array
    {
        $stmt = $this->pdo->query(
            "SELECT * FROM informe_mensual ORDER BY fecha_subida DESC"
        );
        return $stmt->fetchAll();
    }

    public function contarPendientes(): int
    {
        $stmt = $this->pdo->prepare(
            "SELECT COUNT(*) as total 
             FROM informe_mensual 
             WHERE estado_revision = 'pendiente'"
        );
        $stmt->execute();
        $result = $stmt->fetch();
        return (int)$result['total'];
    }

    public function obtenerRecientes(int $limite = 5): array
    {
        $stmt = $this->pdo->prepare(
            "SELECT im.*, 
                    est.nombre_completo AS estudiante_nombre, 
                    est.carrera AS estudiante_carrera,
                    emp.nombre AS empresa_nombre
             FROM informe_mensual im
             JOIN expediente e ON e.id = im.expediente_id
             JOIN estudiante est ON est.id = e.estudiante_id
             JOIN empresa emp ON emp.id = e.empresa_id
             ORDER BY im.fecha_subida DESC
             LIMIT ?"
        );
        $stmt->execute([$limite]);
        return $stmt->fetchAll();
    }
}
