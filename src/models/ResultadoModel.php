<?php
class ResultadoModel {
    private $pdo; public function __construct($pdo){ $this->pdo=$pdo; }
    public function listarTodos(): array
    {
        $stmt = $this->pdo->query(
            "SELECT * FROM resultado ORDER BY fecha_subida DESC"
        );
        return $stmt->fetchAll();
    }

    public function subir($registro_id,$ruta_archivo,$comentario){
        // Eliminar archivo anterior si existe
        $st = $this->pdo->prepare("SELECT ruta_archivo FROM resultado WHERE registro_id=?");
        $st->execute([$registro_id]);
        $old = $st->fetchColumn();
        if ($old && file_exists(__DIR__ . '/../../public' . parse_url($old, PHP_URL_PATH))) {
            unlink(__DIR__ . '/../../public' . parse_url($old, PHP_URL_PATH));
        }
        // Reemplazar registro
        $st = $this->pdo->prepare("DELETE FROM resultado WHERE registro_id=?");
        $st->execute([$registro_id]);
        $st=$this->pdo->prepare("INSERT INTO resultado (registro_id, ruta_archivo, comentario) VALUES (?,?,?)");
        return $st->execute([$registro_id,$ruta_archivo,$comentario]);
    }
    public function porRegistro($registro_id){
        $st=$this->pdo->prepare("SELECT * FROM resultado WHERE registro_id=? ORDER BY fecha_subida DESC");
        $st->execute([$registro_id]); return $st->fetchAll();
    }
}
