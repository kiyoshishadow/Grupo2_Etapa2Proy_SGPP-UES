<?php
class ResultadoModel {
    private $pdo; 
    public function __construct($pdo){ $this->pdo = $pdo; }

    // Crea un resultado (subida de archivo/comentario) asociado a un registro
    public function subir($registro_id, $ruta_archivo, $comentario){
        $sql = "INSERT INTO resultado (registro_id, ruta_archivo, comentario)
                VALUES (?,?,?)";
        $st  = $this->pdo->prepare($sql);
        return $st->execute([$registro_id, $ruta_archivo, $comentario]);
    }

    // Resultados por registro (para que el estudiante vea lo que subió)
    public function porRegistro($registro_id){
    $sql = "
        SELECT 
            r.id,
            r.registro_id,
            r.ruta_archivo,
            r.comentario,
            r.fecha_subida,
            e.calificacion,
            e.observacion,
            e.fecha_eval
        FROM resultado r
        LEFT JOIN evaluacion e ON e.resultado_id = r.id
        WHERE r.registro_id = ?
        ORDER BY r.fecha_subida DESC
    ";
    $st = $this->pdo->prepare($sql);
    $st->execute([$registro_id]);
    return $st->fetchAll();
}

    // Resultados por práctica (para que el docente revise)
   public function porPractica($practica_id){
    $sql = "SELECT 
                r.id AS resultado_id,
                r.registro_id,
                r.ruta_archivo,
                r.comentario,
                r.fecha_subida,
                e.nombre_completo AS estudiante
            FROM resultado r
            JOIN registro_practica rp ON rp.id = r.registro_id
            JOIN estudiante e       ON e.id = rp.estudiante_id
            WHERE rp.practica_id = ?
            ORDER BY r.fecha_subida DESC";
    $st = $this->pdo->prepare($sql);
    $st->execute([$practica_id]);
    return $st->fetchAll();
}

    }

