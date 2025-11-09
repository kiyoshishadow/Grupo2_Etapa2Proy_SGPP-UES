<?php
class EvaluacionModel {
    private $pdo; 
    public function __construct($pdo){ $this->pdo = $pdo; }

    public function calificar($resultado_id, $docente_id, $calificacion, $observacion){
        $sql = "INSERT INTO evaluacion (resultado_id, docente_id, calificacion, observacion)
                VALUES (?,?,?,?)";
        $st  = $this->pdo->prepare($sql);
        return $st->execute([$resultado_id, $docente_id, $calificacion, $observacion]);
    }

    public function porResultado($resultado_id){
        $st = $this->pdo->prepare(
            "SELECT id, resultado_id, docente_id, calificacion, observacion, fecha_eval
             FROM evaluacion WHERE resultado_id=? ORDER BY fecha_eval DESC"
        );
        $st->execute([$resultado_id]);
        return $st->fetchAll();
    }
}
