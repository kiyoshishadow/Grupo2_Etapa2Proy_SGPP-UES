<?php
class ResultadoModel {
    private $pdo; public function __construct($pdo){ $this->pdo=$pdo; }
    public function subir($registro_id,$ruta_archivo,$comentario){
        $st=$this->pdo->prepare("INSERT INTO resultado (registro_id, ruta_archivo, comentario) VALUES (?,?,?)");
        return $st->execute([$registro_id,$ruta_archivo,$comentario]);
    }
    public function porRegistro($registro_id){
        $st=$this->pdo->prepare("SELECT * FROM resultado WHERE registro_id=? ORDER BY fecha_subida DESC");
        $st->execute([$registro_id]); return $st->fetchAll();
    }
}
<?php
class ResultadoModel {
    private $pdo; public function __construct($pdo){ $this->pdo=$pdo; }
    public function subir($registro_id,$ruta_archivo,$comentario){
        $st=$this->pdo->prepare("INSERT INTO resultado (registro_id, ruta_archivo, comentario) VALUES (?,?,?)");
        return $st->execute([$registro_id,$ruta_archivo,$comentario]);
    }
    public function porRegistro($registro_id){
        $st=$this->pdo->prepare("SELECT * FROM resultado WHERE registro_id=? ORDER BY fecha_subida DESC");
        $st->execute([$registro_id]); return $st->fetchAll();
    }
}
