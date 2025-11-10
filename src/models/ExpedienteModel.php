<?php

class ExpedienteModel
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function listar(array $filtros = []): array
    {
        $sql = "SELECT e.*, emp.nombre AS empresa_catalogo, emp.giro, emp.contacto_principal,
                       est.nombre_completo AS estudiante_nombre, est.carnet AS estudiante_carnet, est.carrera AS estudiante_carrera
                FROM expediente e
                JOIN estudiante est ON est.id = e.estudiante_id
                LEFT JOIN empresa emp ON emp.id = e.empresa_id
                WHERE 1 = 1";
        $params = [];

        if (!empty($filtros['estado'])) {
            $sql .= " AND e.estado = ?";
            $params[] = $filtros['estado'];
        }

        if (!empty($filtros['empresa_id'])) {
            $sql .= " AND e.empresa_id = ?";
            $params[] = $filtros['empresa_id'];
        }

        if (!empty($filtros['carrera'])) {
            $sql .= " AND est.carrera LIKE ?";
            $params[] = '%' . $filtros['carrera'] . '%';
        }

        if (!empty($filtros['fecha_inicio_desde'])) {
            $sql .= " AND e.fecha_inicio >= ?";
            $params[] = $filtros['fecha_inicio_desde'];
        }

        if (!empty($filtros['fecha_inicio_hasta'])) {
            $sql .= " AND e.fecha_inicio <= ?";
            $params[] = $filtros['fecha_inicio_hasta'];
        }

        $sql .= " ORDER BY e.fecha_inicio DESC, e.id DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function find(int $id)
    {
        $stmt = $this->pdo->prepare(
            "SELECT e.*, emp.nombre AS empresa_catalogo, emp.giro, emp.contacto_principal,
                    est.nombre_completo AS estudiante_nombre,
                    est.carnet AS estudiante_carnet,
                    est.carrera AS estudiante_carrera
             FROM expediente e
             JOIN estudiante est ON est.id = e.estudiante_id
             LEFT JOIN empresa emp ON emp.id = e.empresa_id
             WHERE e.id = ?
             LIMIT 1"
        );
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function findByEstudiante(int $estudianteId)
    {
        $stmt = $this->pdo->prepare(
            "SELECT * FROM expediente
             WHERE estudiante_id = ?
             ORDER BY fecha_inicio DESC, id DESC"
        );
        $stmt->execute([$estudianteId]);
        return $stmt->fetchAll();
    }

    public function findRecientePorEstudiante(int $estudianteId)
    {
        $stmt = $this->pdo->prepare(
            "SELECT * FROM expediente
             WHERE estudiante_id = ?
             ORDER BY
                CASE WHEN estado = 'activo' THEN 0 ELSE 1 END,
                fecha_inicio DESC,
                id DESC
             LIMIT 1"
        );
        $stmt->execute([$estudianteId]);
        return $stmt->fetch();
    }

    public function findPorEstudianteYPractica(int $estudianteId, int $practicaId)
    {
        $stmt = $this->pdo->prepare(
            "SELECT e.*
             FROM expediente e
             LEFT JOIN expediente_practica ep
               ON ep.expediente_id = e.id AND ep.practica_id = ?
             WHERE e.estudiante_id = ?
             ORDER BY
               CASE WHEN ep.practica_id = ? THEN 0 ELSE 1 END,
               CASE WHEN e.estado = 'activo' THEN 0 ELSE 1 END,
               e.fecha_inicio DESC,
               e.id DESC
             LIMIT 1"
        );

        $stmt->execute([$practicaId, $estudianteId, $practicaId]);
        return $stmt->fetch();
    }

    public function create(array $data): int
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO expediente (
                estudiante_id, empresa_id, empresa_nombre, empresa_direccion,
                supervisor_externo, telefono_supervisor, correo_supervisor,
                fecha_inicio, fecha_fin, estado, horas_planificadas, horas_cumplidas, observaciones
            ) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)"
        );

        $stmt->execute([
            $data['estudiante_id'],
            $data['empresa_id'] ?? null,
            $data['empresa_nombre'] ?? null,
            $data['empresa_direccion'] ?? null,
            $data['supervisor_externo'] ?? null,
            $data['telefono_supervisor'] ?? null,
            $data['correo_supervisor'] ?? null,
            $data['fecha_inicio'] ?? null,
            $data['fecha_fin'] ?? null,
            $data['estado'] ?? 'planeado',
            $data['horas_planificadas'] ?? null,
            $data['horas_cumplidas'] ?? null,
            $data['observaciones'] ?? null,
        ]);

        return (int)$this->pdo->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        $stmt = $this->pdo->prepare(
            "UPDATE expediente
             SET empresa_id = ?, empresa_nombre = ?, empresa_direccion = ?,
                 supervisor_externo = ?, telefono_supervisor = ?, correo_supervisor = ?,
                 fecha_inicio = ?, fecha_fin = ?, estado = ?, horas_planificadas = ?, horas_cumplidas = ?, observaciones = ?
             WHERE id = ?"
        );

        return $stmt->execute([
            $data['empresa_id'] ?? null,
            $data['empresa_nombre'] ?? null,
            $data['empresa_direccion'] ?? null,
            $data['supervisor_externo'] ?? null,
            $data['telefono_supervisor'] ?? null,
            $data['correo_supervisor'] ?? null,
            $data['fecha_inicio'] ?? null,
            $data['fecha_fin'] ?? null,
            $data['estado'] ?? 'planeado',
            $data['horas_planificadas'] ?? null,
            $data['horas_cumplidas'] ?? null,
            $data['observaciones'] ?? null,
            $id,
        ]);
    }

    public function actualizarHoras(int $id, ?int $planificadas, ?int $cumplidas): bool
    {
        $stmt = $this->pdo->prepare(
            "UPDATE expediente SET horas_planificadas = ?, horas_cumplidas = ? WHERE id = ?"
        );
        return $stmt->execute([$planificadas, $cumplidas, $id]);
    }

    public function contarActivosPorEmpresaYFechas(string $fechaDesde, string $fechaHasta): array
    {
        $sql = "SELECT
                    COALESCE(emp.nombre, e.empresa_nombre, 'Empresa no especificada') AS empresa_nombre,
                    COUNT(DISTINCT e.estudiante_id) AS total_estudiantes
                FROM expediente e
                LEFT JOIN empresa emp ON emp.id = e.empresa_id
                WHERE e.estado = 'activo'
                  AND e.fecha_inicio <= ?
                  AND (e.fecha_fin IS NULL OR e.fecha_fin >= ?)
                GROUP BY empresa_nombre
                ORDER BY total_estudiantes DESC, empresa_nombre ASC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$fechaHasta, $fechaDesde]);
        return $stmt->fetchAll();
    }

    public function contarActivos(): int
    {
        $stmt = $this->pdo->prepare(
            "SELECT COUNT(DISTINCT estudiante_id) as total 
             FROM expediente 
             WHERE estado = 'activo'"
        );
        $stmt->execute();
        $result = $stmt->fetch();
        return (int)$result['total'];
    }

    public function contarEmpresasConEstudiantesActivos(): int
    {
        $stmt = $this->pdo->prepare(
            "SELECT COUNT(DISTINCT COALESCE(empresa_id, empresa_nombre)) as total
             FROM expediente 
             WHERE estado = 'activo'"
        );
        $stmt->execute();
        $result = $stmt->fetch();
        return (int)$result['total'];
    }

    public function contarActivosPorEmpresa(): array
    {
        $sql = "SELECT
                    COALESCE(emp.nombre, e.empresa_nombre, 'Empresa no especificada') AS empresa_nombre,
                    COUNT(DISTINCT e.estudiante_id) AS total_estudiantes,
                    GROUP_CONCAT(DISTINCT est.carrera SEPARATOR ', ') AS carreras
                FROM expediente e
                LEFT JOIN empresa emp ON emp.id = e.empresa_id
                LEFT JOIN estudiante est ON est.id = e.estudiante_id
                WHERE e.estado = 'activo'
                GROUP BY empresa_nombre
                ORDER BY total_estudiantes DESC, empresa_nombre ASC
                LIMIT 10";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function contarPorCarrera(): array
    {
        $stmt = $this->pdo->query(
            "SELECT est.carrera, COUNT(*) as total
             FROM expediente e
             JOIN estudiante est ON est.id = e.estudiante_id
             WHERE e.estado = 'activo'
             GROUP BY est.carrera
             ORDER BY total DESC"
        );
        return $stmt->fetchAll();
    }

    public function listarActivosConEstudiantes(): array
    {
        $stmt = $this->pdo->query(
            "SELECT e.*, 
                    est.nombre_completo AS estudiante_nombre,
                    est.carrera AS estudiante_carrera,
                    emp.nombre AS empresa_nombre
             FROM expediente e
             JOIN estudiante est ON est.id = e.estudiante_id
             JOIN empresa emp ON emp.id = e.empresa_id
             WHERE e.estado = 'activo'
             ORDER BY est.nombre_completo"
        );
        return $stmt->fetchAll();
    }

    public function listarPorEmpresa(int $empresaId): array
    {
        $stmt = $this->pdo->prepare(
            "SELECT e.*, 
                    est.nombre_completo AS estudiante_nombre,
                    est.carrera AS estudiante_carrera,
                    est.email AS estudiante_email
             FROM expediente e
             JOIN estudiante est ON est.id = e.estudiante_id
             WHERE e.empresa_id = ?
             ORDER BY e.fecha_inicio DESC"
        );
        $stmt->execute([$empresaId]);
        return $stmt->fetchAll();
    }

    public function obtenerRecientes(int $limite = 5): array
    {
        $stmt = $this->pdo->prepare(
            "SELECT e.*, est.nombre_completo AS estudiante_nombre, est.carrera AS estudiante_carrera
             FROM expediente e
             LEFT JOIN estudiante est ON est.id = e.estudiante_id
             ORDER BY e.creado_en DESC
             LIMIT ?"
        );
        $stmt->execute([$limite]);
        return $stmt->fetchAll();
    }
}
