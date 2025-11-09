<?php
class EvaluacionModel {
    private $pdo; public function __construct($pdo){ $this->pdo=$pdo; }
    public function calificar($resultado_id,$docente_id,$calificacion,$observacion){
        $st=$this->pdo->prepare("INSERT INTO evaluacion (resultado_id,docente_id,calificacion,observacion) VALUES (?,?,?,?)");
        return $st->execute([$resultado_id,$docente_id,$calificacion,$observacion]);
    }
}
