<?php
class RegistroModel {
    private $pdo; public function __construct($pdo){ $this->pdo=$pdo; }

    public function inscribir($practica_id,$estudiante_id){
        $info = $this->pdo->prepare("SELECT id, titulo, cupo, estado FROM practica WHERE id = ? LIMIT 1");
        $info->execute([$practica_id]);
        $practica = $info->fetch();

        if (!$practica) {
            return ['success' => false, 'code' => 'no_practica'];
        }

        if ($practica['estado'] !== 'activa') {
            return ['success' => false, 'code' => 'no_activa', 'practica' => $practica];
        }

        $dup = $this->pdo->prepare("SELECT id FROM registro_practica WHERE practica_id = ? AND estudiante_id = ? LIMIT 1");
        $dup->execute([$practica_id, $estudiante_id]);
        if ($dup->fetch()) {
            return ['success' => false, 'code' => 'duplicado', 'practica' => $practica];
        }

        if ($practica['cupo'] !== null) {
            $total = $this->pdo->prepare("SELECT COUNT(*) FROM registro_practica WHERE practica_id = ?");
            $total->execute([$practica_id]);
            $inscritos = (int)$total->fetchColumn();
            if ($inscritos >= (int)$practica['cupo']) {
                return ['success' => false, 'code' => 'sin_cupo', 'practica' => $practica, 'inscritos' => $inscritos];
            }
        }

        try {
            $st=$this->pdo->prepare("INSERT INTO registro_practica (practica_id,estudiante_id) VALUES (?,?)");
            $st->execute([$practica_id,$estudiante_id]);
            return ['success' => true, 'code' => 'ok', 'practica' => $practica];
        } catch (\PDOException $e) {
            return ['success' => false, 'code' => 'error', 'error' => $e->getMessage(), 'practica' => $practica];
        }
    }

    public function misRegistros($estudiante_id){
        $sql="SELECT rp.*, p.titulo, p.estado AS practica_estado, p.fecha_fin AS fecha_limite
             FROM registro_practica rp
             JOIN practica p ON p.id=rp.practica_id
             WHERE rp.estudiante_id=?
             ORDER BY rp.fecha_postulacion DESC";
        $st=$this->pdo->prepare($sql); $st->execute([$estudiante_id]); return $st->fetchAll();
    }

    public function findByIdForEstudiante(int $registroId, int $estudianteId)
    {
        $stmt = $this->pdo->prepare(
            "SELECT rp.*, p.titulo, p.fecha_fin AS fecha_limite, p.estado AS practica_estado
             FROM registro_practica rp
             JOIN practica p ON p.id = rp.practica_id
             WHERE rp.id = ? AND rp.estudiante_id = ?
             LIMIT 1"
        );
        $stmt->execute([$registroId, $estudianteId]);
        return $stmt->fetch();
    }
    public function porPractica($practica_id){
        $sql="SELECT rp.*, e.nombre_completo
              FROM registro_practica rp JOIN estudiante e ON e.id=rp.estudiante_id
              WHERE rp.practica_id=?";
        $st=$this->pdo->prepare($sql); $st->execute([$practica_id]); return $st->fetchAll();
    }

    public function porPracticaConResultados($practica_id)
    {
        $sql = "SELECT
                    rp.id,
                    rp.practica_id,
                    rp.estudiante_id,
                    rp.estado AS registro_estado,
                    rp.fecha_postulacion,
                    e.nombre_completo AS estudiante_nombre,
                    e.carnet,
                    p.titulo AS practica_titulo,
                    p.estado AS practica_estado,
                    doc.id AS docente_responsable_id,
                    doc.nombre_completo AS docente_responsable,
                    doc.departamento AS docente_departamento,
                    r.ruta_archivo,
                    r.comentario AS resultado_comentario,
                    r.fecha_subida
                FROM registro_practica rp
                JOIN estudiante e ON e.id = rp.estudiante_id
                JOIN practica p ON p.id = rp.practica_id
                JOIN docente doc ON doc.id = p.docente_id
                LEFT JOIN resultado r ON r.registro_id = rp.id
                WHERE rp.practica_id = ?
                ORDER BY e.nombre_completo ASC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$practica_id]);
        return $stmt->fetchAll();
    }

    public function eliminarResultado($registro_id, $estudiante_id)
    {
        $stmt = $this->pdo->prepare(
            "SELECT r.ruta_archivo FROM resultado r
             JOIN registro_practica rp ON rp.id = r.registro_id
             WHERE r.registro_id = ? AND rp.estudiante_id = ?"
        );
        $stmt->execute([$registro_id, $estudiante_id]);
        $resultado = $stmt->fetch();
        if (!$resultado) {
            return ['success' => false, 'code' => 'no_encontrado'];
        }

        $stmt = $this->pdo->prepare("DELETE FROM resultado WHERE registro_id = ?");
        $deleted = $stmt->execute([$registro_id]);
        if (!$deleted) {
            return ['success' => false, 'code' => 'error'];
        }

        if (!empty($resultado['ruta_archivo'])) {
            $path = __DIR__ . '/../../public' . parse_url($resultado['ruta_archivo'], PHP_URL_PATH);
            if (file_exists($path)) {
                unlink($path);
            }
        }

        return ['success' => true];
    }

    public function editarResultado($registro_id, $estudiante_id, $ruta_archivo = null, $comentario = null)
    {
        $stmt = $this->pdo->prepare(
            "SELECT r.ruta_archivo FROM resultado r
             JOIN registro_practica rp ON rp.id = r.registro_id
             WHERE r.registro_id = ? AND rp.estudiante_id = ?"
        );
        $stmt->execute([$registro_id, $estudiante_id]);
        $resultado = $stmt->fetch();
        if (!$resultado) {
            return ['success' => false, 'code' => 'no_encontrado'];
        }

        if ($ruta_archivo !== null) {
            if (!empty($resultado['ruta_archivo'])) {
                $oldPath = __DIR__ . '/../../public' . parse_url($resultado['ruta_archivo'], PHP_URL_PATH);
                if (file_exists($oldPath)) {
                    unlink($oldPath);
                }
            }
            $stmt = $this->pdo->prepare(
                "UPDATE resultado SET ruta_archivo = ?, comentario = ? WHERE registro_id = ?"
            );
            $updated = $stmt->execute([$ruta_archivo, $comentario, $registro_id]);
        } else {
            $stmt = $this->pdo->prepare(
                "UPDATE resultado SET comentario = ? WHERE registro_id = ?"
            );
            $updated = $stmt->execute([$comentario, $registro_id]);
        }

        if (!$updated) {
            return ['success' => false, 'code' => 'error'];
        }

        return ['success' => true];
    }

    public function cancelar($registro_id, $estudiante_id)
    {
        $stmt = $this->pdo->prepare(
            "SELECT rp.id, rp.estado, p.titulo
             FROM registro_practica rp
             JOIN practica p ON p.id = rp.practica_id
             WHERE rp.id = ? AND rp.estudiante_id = ?
             LIMIT 1"
        );
        $stmt->execute([$registro_id, $estudiante_id]);
        $registro = $stmt->fetch();

        if (!$registro) {
            return ['success' => false, 'code' => 'no_encontrado'];
        }

        $estado = strtolower($registro['estado'] ?? '');
        if (in_array($estado, ['finalizada', 'aprobado'], true)) {
            return ['success' => false, 'code' => 'no_cancelable', 'titulo' => $registro['titulo']];
        }

        // eliminar archivos asociados antes de borrar el registro (on delete cascade no lo hace)
        $archivos = $this->pdo->prepare('SELECT ruta_archivo FROM resultado WHERE registro_id = ?');
        $archivos->execute([$registro_id]);
        foreach ($archivos->fetchAll(
            \PDO::FETCH_COLUMN
        ) as $ruta) {
            if ($ruta) {
                $path = __DIR__ . '/../../public' . parse_url($ruta, PHP_URL_PATH);
                if (file_exists($path)) {
                    @unlink($path);
                }
            }
        }

        $del = $this->pdo->prepare('DELETE FROM registro_practica WHERE id = ?');
        if ($del->execute([$registro_id])) {
            return ['success' => true, 'titulo' => $registro['titulo']];
        }

        return ['success' => false, 'code' => 'error'];
    }

    public function todosConResultados(){
        $sql = "SELECT
                    rp.id,
                    rp.practica_id,
                    rp.estudiante_id,
                    rp.estado AS registro_estado,
                    rp.fecha_postulacion,
                    p.titulo AS practica_titulo,
                    p.estado AS practica_estado,
                    p.fecha_fin AS practica_fecha_limite,
                    doc_res.id AS docente_responsable_id,
                    doc_res.nombre_completo AS docente_responsable,
                    e.nombre_completo AS estudiante_nombre,
                    r.ruta_archivo,
                    r.comentario AS resultado_comentario,
                    r.fecha_subida,
                    ev.estado AS evaluacion_estado,
                    ev.comentario AS evaluacion_comentario,
                    ev.nota AS evaluacion_nota,
                    ev.fecha_evaluacion,
                    doc_eval.nombre_completo AS evaluador_nombre
                FROM registro_practica rp
                JOIN practica p ON p.id = rp.practica_id
                JOIN docente doc_res ON doc_res.id = p.docente_id
                JOIN estudiante e ON e.id = rp.estudiante_id
                LEFT JOIN resultado r ON r.registro_id = rp.id
                LEFT JOIN evaluacion ev ON ev.id = (
                    SELECT ev2.id
                    FROM evaluacion ev2
                    WHERE ev2.registro_id = rp.id
                    ORDER BY ev2.fecha_evaluacion DESC
                    LIMIT 1
                )
                LEFT JOIN docente doc_eval ON doc_eval.id = ev.docente_id
                ORDER BY p.fecha_fin DESC, rp.fecha_postulacion DESC";

        $st = $this->pdo->query($sql);
        return $st->fetchAll();
    }
}
