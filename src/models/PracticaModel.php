<?php
class PracticaModel {
    private $pdo;
    public function __construct($pdo){ $this->pdo = $pdo; }

    public function allByDocente($docente_id){
        $st = $this->pdo->prepare(
            "SELECT id, titulo, descripcion, fecha_fin AS fecha_limite
             FROM practica
             WHERE docente_id = ?
             ORDER BY fecha_creacion DESC"
        );
        $st->execute([$docente_id]);
        return $st->fetchAll();
    }

    public function create($data){
        $st = $this->pdo->prepare(
            "INSERT INTO practica (titulo, descripcion, fecha_fin, docente_id)
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

    public function createAdmin(array $data)
    {
        $st = $this->pdo->prepare(
            "INSERT INTO practica (titulo, descripcion, fecha_inicio, fecha_fin, cupo, estado, docente_id)
             VALUES (?,?,?,?,?,?,?)"
        );
        $st->execute([
            $data['titulo'],
            $data['descripcion'],
            $data['fecha_inicio'],
            $data['fecha_fin'],
            $data['cupo'],
            $data['estado'],
            $data['docente_id']
        ]);
        return $this->pdo->lastInsertId();
    }

    public function listarActivasConDocentes(): array
    {
        $stmt = $this->pdo->query(
            "SELECT p.*, d.nombre_completo AS docente_nombre, d.email AS docente_email,
                    e.nombre AS empresa_nombre
             FROM practica p
             LEFT JOIN docente d ON d.id = p.docente_id
             LEFT JOIN empresa e ON e.id = p.empresa_id
             WHERE p.estado = 'activa'
             ORDER BY p.fecha_inicio DESC"
        );
        return $stmt->fetchAll();
    }

    public function find($id){
        $st = $this->pdo->prepare(
            "SELECT id, titulo, descripcion, fecha_fin AS fecha_limite, docente_id
             FROM practica
             WHERE id = ?
             LIMIT 1"
        );
        $st->execute([$id]);
        return $st->fetch() ?: [];
    }

    public function findAdmin($id)
    {
        $st = $this->pdo->prepare(
            "SELECT p.*, d.nombre_completo AS docente_nombre
             FROM practica p
             LEFT JOIN docente d ON d.id = p.docente_id
             WHERE p.id = ?
             LIMIT 1"
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
            $data['fecha_limite'],
            $id
        ]);
    }

    public function updateAdmin($id, array $data)
    {
        $st = $this->pdo->prepare(
            "UPDATE practica
             SET titulo = ?, descripcion = ?, fecha_inicio = ?, fecha_fin = ?, cupo = ?, estado = ?, docente_id = ?
             WHERE id = ?"
        );
        return $st->execute([
            $data['titulo'],
            $data['descripcion'],
            $data['fecha_inicio'],
            $data['fecha_fin'],
            $data['cupo'],
            $data['estado'],
            $data['docente_id'],
            $id
        ]);
    }

    public function delete($id){
        $st = $this->pdo->prepare("DELETE FROM practica WHERE id = ?");
        return $st->execute([$id]);
    }

    public function disponibles(){
        $sql = "SELECT p.id, p.titulo, p.descripcion, p.fecha_fin AS fecha_limite,
                       d.nombre_completo AS docente
                FROM practica p
                JOIN docente d ON d.id = p.docente_id
                WHERE p.fecha_fin >= CURRENT_DATE()
                ORDER BY p.id DESC";
        return $this->pdo->query($sql)->fetchAll();
    }

    public function allAdmin()
    {
        $sql = "SELECT
                    p.*,
                    d.nombre_completo AS docente_nombre,
                    (SELECT COUNT(*) FROM registro_practica rp WHERE rp.practica_id = p.id) AS total_inscritos,
                    (SELECT COUNT(*) FROM registro_practica rp WHERE rp.practica_id = p.id AND rp.estado = 'finalizada') AS total_finalizados
                FROM practica p
                LEFT JOIN docente d ON d.id = p.docente_id
                ORDER BY p.fecha_fin DESC, p.id DESC";
        return $this->pdo->query($sql)->fetchAll();
    }
}

