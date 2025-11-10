<?php
class EvaluacionModel {
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Guarda o actualiza la evaluación de un expediente.
     * Como expediente_id es UNIQUE, esto insertará una nueva evaluación
     * o actualizará la existente si ya hay una para ese expediente.
     */
    public function guardarEvaluacion(array $data): bool
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO evaluacion (expediente_id, nota_final, comentario_docente, docente_id, fecha_evaluacion)
             VALUES (:expediente_id, :nota_final, :comentario_docente, :docente_id, NOW())
             ON DUPLICATE KEY UPDATE
                nota_final = VALUES(nota_final),
                comentario_docente = VALUES(comentario_docente),
                docente_id = VALUES(docente_id),
                fecha_evaluacion = NOW()"
        );

        return $stmt->execute([
            ':expediente_id' => $data['expediente_id'],
            ':nota_final' => $data['nota_final'] ?? null,
            ':comentario_docente' => $data['comentario_docente'] ?? null,
            ':docente_id' => $data['docente_id']
        ]);
    }

    /**
     * Busca la evaluación asociada a un expediente.
     */
    public function porExpediente(int $expediente_id)
    {
        $stmt = $this->pdo->prepare(
            "SELECT e.*, d.nombre_completo AS docente_nombre
             FROM evaluacion e
             JOIN docente d ON d.id = e.docente_id
             WHERE e.expediente_id = ?
             LIMIT 1"
        );
        $stmt->execute([$expediente_id]);
        return $stmt->fetch();
    }

    public function obtenerPorExpediente(int $expedienteId): array
    {
        $stmt = $this->pdo->prepare(
            "SELECT e.*, d.nombre_completo AS docente_nombre
             FROM evaluacion e
             LEFT JOIN docente d ON d.id = e.docente_id
             WHERE e.expediente_id = ?
             ORDER BY e.fecha_evaluacion DESC"
        );
        $stmt->execute([$expedienteId]);
        return $stmt->fetchAll();
    }

    public function contarEvaluacionesMesActual(): int
    {
        $stmt = $this->pdo->prepare(
            "SELECT COUNT(*) as total 
             FROM evaluacion 
             WHERE MONTH(fecha_evaluacion) = MONTH(CURRENT_DATE()) 
             AND YEAR(fecha_evaluacion) = YEAR(CURRENT_DATE())"
        );
        $stmt->execute();
        $result = $stmt->fetch();
        return (int)$result['total'];
    }

    public function obtenerRecientes(int $limite = 5): array
    {
        $stmt = $this->pdo->prepare(
            "SELECT e.*, exp.id as expediente_id, est.nombre_completo AS estudiante_nombre, est.carrera AS estudiante_carrera
             FROM evaluacion e
             JOIN expediente exp ON exp.id = e.expediente_id
             JOIN estudiante est ON est.id = exp.estudiante_id
             ORDER BY e.fecha_evaluacion DESC
             LIMIT ?"
        );
        $stmt->execute([$limite]);
        return $stmt->fetchAll();
    }
}
