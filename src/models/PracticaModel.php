<?php
class PracticaModel {
    private $pdo;
    public function __construct($pdo){ $this->pdo = $pdo; }

    public function allByDocente($docente_id){
        $st = $this->pdo->prepare(
            "SELECT id, titulo, descripcion, fecha_limite, fecha_creacion
             FROM practica
             WHERE docente_id = ?
             ORDER BY fecha_creacion DESC"
        );
        $st->execute([$docente_id]);
        return $st->fetchAll();
    }

    public function create($data){
        $st = $this->pdo->prepare(
            "INSERT INTO practica (titulo, descripcion, fecha_limite, docente_id)
             VALUES (?,?,?,?)"
        );
        $st->execute([
            $data['titulo'],
            $data['descripcion'],
            $data['fecha_limite'],
            $data['docente_id']
        ]);
        return $this->pdo->lastInsertId();
    }

    public function find($id){
        $st = $this->pdo->prepare(
            "SELECT id, titulo, descripcion, fecha_limite, fecha_creacion, docente_id
             FROM practica WHERE id = ?"
        );
        $st->execute([$id]);
        return $st->fetch();
    }

    public function update($id, $data){
        $st = $this->pdo->prepare(
            "UPDATE practica
             SET titulo = ?, descripcion = ?, fecha_limite = ?
             WHERE id = ?"
        );
        return $st->execute([
            $data['titulo'],
            $data['descripcion'],
            $data['fecha_limite'],
            $id
        ]);
    }

    public function delete($id){
        $st = $this->pdo->prepare("DELETE FROM practica WHERE id = ?");
        return $st->execute([$id]);
    }

    public function disponibles(){
        $sql = "SELECT p.id, p.titulo, p.descripcion, p.fecha_limite, p.fecha_creacion,
                       d.nombre_completo AS docente
                FROM practica p
                JOIN docente d ON d.id = p.docente_id
                WHERE p.fecha_limite >= CURRENT_DATE()
                ORDER BY p.fecha_creacion DESC";
        return $this->pdo->query($sql)->fetchAll();
    }
}

