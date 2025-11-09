<?php
class PracticaModel {
    private $pdo;
    public function __construct($pdo){ $this->pdo = $pdo; }

    public function allByDocente($docente_id){
        $sql = "SELECT id, titulo, descripcion, fecha_inicio, fecha_fin
                FROM practica
                WHERE docente_id = ?
                ORDER BY id DESC";
        $st = $this->pdo->prepare($sql);
        $st->execute([$docente_id]);
        return $st->fetchAll();
    }

    public function create($data){
        // en tu BD no existe fecha_creacion ni fecha_limite: usamos fecha_fin
        $sql = "INSERT INTO practica (titulo, descripcion, fecha_inicio, fecha_fin, cupo, estado, docente_id)
                VALUES (?, ?, NULL, ?, NULL, 'activa', ?)";
        $st = $this->pdo->prepare($sql);
        $st->execute([
            $data['titulo'],
            $data['descripcion'],
            $data['fecha_fin'],
            $data['docente_id']
        ]);
        return $this->pdo->lastInsertId();
    }

    public function find($id){
        $st = $this->pdo->prepare(
            "SELECT id, titulo, descripcion, fecha_inicio, fecha_fin, docente_id
             FROM practica WHERE id = ?"
        );
        $st->execute([$id]);
        return $st->fetch();
    }

    public function update($id, $data){
        $st = $this->pdo->prepare(
            "UPDATE practica
             SET titulo = ?, descripcion = ?, fecha_fin = ?
             WHERE id = ?"
        );
        return $st->execute([
            $data['titulo'],
            $data['descripcion'],
            $data['fecha_fin'],
            $id
        ]);
    }

    public function delete($id){
        $st=$this->pdo->prepare("DELETE FROM practica WHERE id = ?");
        return $st->execute([$id]);
    }
}
