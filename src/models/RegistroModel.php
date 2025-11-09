<?php
class RegistroModel {
    private $pdo; public function __construct($pdo){ $this->pdo=$pdo; }
    public function inscribir($practica_id,$estudiante_id){
        $st=$this->pdo->prepare("INSERT INTO registro_practica (practica_id,estudiante_id) VALUES (?,?)");
        return $st->execute([$practica_id,$estudiante_id]);
    }
    public function misRegistros($estudiante_id){
        $sql="SELECT rp.*, p.titulo FROM registro_practica rp
             JOIN practica p ON p.id=rp.practica_id
             WHERE rp.estudiante_id=? ORDER BY rp.fecha_postulacion DESC";
        $st=$this->pdo->prepare($sql); $st->execute([$estudiante_id]); return $st->fetchAll();
    }
    public function porPractica($practica_id){
        $sql="SELECT rp.*, e.nombre_completo
              FROM registro_practica rp JOIN estudiante e ON e.id=rp.estudiante_id
              WHERE rp.practica_id=?";
        $st=$this->pdo->prepare($sql); $st->execute([$practica_id]); return $st->fetchAll();
    }
}
