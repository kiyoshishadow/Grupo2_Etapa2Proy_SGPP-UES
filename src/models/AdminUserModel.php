<?php
class AdminUserModel
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function all()
    {
        $sql = "SELECT u.id,
                       u.nombre_usuario,
                       u.rol_id,
                       u.activo,
                       r.nombre AS rol_nombre,
                       COALESCE(d.nombre_completo, e.nombre_completo, a.nombre_completo, '') AS nombre_persona,
                       d.departamento,
                       e.carrera,
                       e.carnet
                FROM usuario u
                JOIN rol r ON r.id = u.rol_id
                LEFT JOIN docente d ON d.usuario_id = u.id
                LEFT JOIN estudiante e ON e.usuario_id = u.id
                LEFT JOIN administrador a ON a.usuario_id = u.id
                ORDER BY u.id ASC";
        return $this->pdo->query($sql)->fetchAll();
    }

    public function find(int $id)
    {
        $stmt = $this->pdo->prepare(
            "SELECT u.*, r.nombre AS rol_nombre,
                    d.id AS docente_id, d.nombre_completo AS docente_nombre, d.departamento,
                    e.id AS estudiante_id, e.nombre_completo AS estudiante_nombre, e.carrera, e.carnet,
                    a.id AS admin_id, a.nombre_completo AS admin_nombre
             FROM usuario u
             JOIN rol r ON r.id = u.rol_id
             LEFT JOIN docente d ON d.usuario_id = u.id
             LEFT JOIN estudiante e ON e.usuario_id = u.id
             LEFT JOIN administrador a ON a.usuario_id = u.id
             WHERE u.id = ?
             LIMIT 1"
        );
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function roles()
    {
        return $this->pdo->query('SELECT id, nombre FROM rol ORDER BY id ASC')->fetchAll();
    }

    public function getDocentes()
    {
        $sql = "SELECT id, nombre_completo FROM docente ORDER BY nombre_completo";
        return $this->pdo->query($sql)->fetchAll();
    }

    public function getEstudiantes()
    {
        $sql = "SELECT id, nombre_completo, carnet FROM estudiante ORDER BY nombre_completo";
        return $this->pdo->query($sql)->fetchAll();
    }

    public function create(array $data)
    {
        $this->pdo->beginTransaction();
        try {
            $exists = $this->pdo->prepare('SELECT id FROM usuario WHERE nombre_usuario = ? LIMIT 1');
            $exists->execute([$data['nombre_usuario']]);
            if ($exists->fetch()) {
                throw new RuntimeException('El nombre de usuario ya existe.');
            }

            $hash = password_hash($data['contrasena'], PASSWORD_BCRYPT);
            $stmt = $this->pdo->prepare('INSERT INTO usuario (nombre_usuario, contrasena, rol_id) VALUES (?,?,?)');
            $stmt->execute([
                $data['nombre_usuario'],
                $hash,
                $data['rol_id']
            ]);
            $usuario_id = (int)$this->pdo->lastInsertId();

            $rolId = (int)$data['rol_id'];
            if ($rolId === 2) { // Docente
                $doc = $this->pdo->prepare('INSERT INTO docente (nombre_completo, departamento, usuario_id) VALUES (?,?,?)');
                $doc->execute([
                    $data['nombre_completo'] ?? 'Docente sin nombre',
                    $data['departamento'] ?? 'Sin departamento',
                    $usuario_id
                ]);
            } elseif ($rolId === 3) { // Estudiante
                $est = $this->pdo->prepare('INSERT INTO estudiante (carnet, nombre_completo, carrera, usuario_id) VALUES (?,?,?,?)');
                $est->execute([
                    $data['carnet'] ?? 'SIN-CARNET',
                    $data['nombre_completo'] ?? 'Estudiante sin nombre',
                    $data['carrera'] ?? 'Sin carrera',
                    $usuario_id
                ]);
            } else { // Admin u otros
                if (!empty($data['nombre_completo'])) {
                    $adm = $this->pdo->prepare('INSERT INTO administrador (nombre_completo, usuario_id) VALUES (?,?)');
                    $adm->execute([$data['nombre_completo'], $usuario_id]);
                }
            }

            $this->pdo->commit();
            return ['success' => true];
        } catch (\Throwable $th) {
            $this->pdo->rollBack();
            return ['success' => false, 'message' => $th->getMessage()];
        }
    }

    public function update(int $id, array $data)
    {
        $this->pdo->beginTransaction();
        try {
            $current = $this->find($id);
            if (!$current) {
                throw new RuntimeException('Usuario no encontrado.');
            }

            $params = [
                $data['nombre_usuario'],
                $data['rol_id'],
                $data['activo'] ?? (int)$current['activo']
            ];

            $setPassword = '';
            if (!empty($data['nueva_contrasena'])) {
                $setPassword = ', contrasena = ?';
                $params[] = password_hash($data['nueva_contrasena'], PASSWORD_BCRYPT);
            }

            $params[] = $id;

            $stmt = $this->pdo->prepare("UPDATE usuario SET nombre_usuario = ?, rol_id = ?, activo = ?{$setPassword} WHERE id = ?");
            $stmt->execute($params);

            $nuevoRol = (int)$data['rol_id'];
            $rolActual = (int)$current['rol_id'];

            if ($rolActual !== $nuevoRol) {
                if ($rolActual === 2) {
                    $this->pdo->prepare('DELETE FROM docente WHERE usuario_id = ?')->execute([$id]);
                } elseif ($rolActual === 3) {
                    $this->pdo->prepare('DELETE FROM estudiante WHERE usuario_id = ?')->execute([$id]);
                } else {
                    $this->pdo->prepare('DELETE FROM administrador WHERE usuario_id = ?')->execute([$id]);
                }
            }

            if ($nuevoRol === 2) {
                $docStmt = $this->pdo->prepare('SELECT id FROM docente WHERE usuario_id = ? LIMIT 1');
                $docStmt->execute([$id]);
                if ($docStmt->fetch()) {
                    $this->pdo->prepare('UPDATE docente SET nombre_completo = ?, departamento = ? WHERE usuario_id = ?')
                        ->execute([$data['nombre_completo'], $data['departamento'], $id]);
                } else {
                    $this->pdo->prepare('INSERT INTO docente (nombre_completo, departamento, usuario_id) VALUES (?,?,?)')
                        ->execute([
                            $data['nombre_completo'] ?? 'Docente sin nombre',
                            $data['departamento'] ?? 'Sin departamento',
                            $id
                        ]);
                }
            } elseif ($nuevoRol === 3) {
                $estStmt = $this->pdo->prepare('SELECT id FROM estudiante WHERE usuario_id = ? LIMIT 1');
                $estStmt->execute([$id]);
                if ($estStmt->fetch()) {
                    $this->pdo->prepare('UPDATE estudiante SET carnet = ?, nombre_completo = ?, carrera = ? WHERE usuario_id = ?')
                        ->execute([$data['carnet'], $data['nombre_completo'], $data['carrera'], $id]);
                } else {
                    $this->pdo->prepare('INSERT INTO estudiante (carnet, nombre_completo, carrera, usuario_id) VALUES (?,?,?,?)')
                        ->execute([
                            $data['carnet'] ?? 'SIN-CARNET',
                            $data['nombre_completo'] ?? 'Estudiante sin nombre',
                            $data['carrera'] ?? 'Sin carrera',
                            $id
                        ]);
                }
            } else {
                $admStmt = $this->pdo->prepare('SELECT id FROM administrador WHERE usuario_id = ? LIMIT 1');
                $admStmt->execute([$id]);
                if ($admStmt->fetch()) {
                    $this->pdo->prepare('UPDATE administrador SET nombre_completo = ? WHERE usuario_id = ?')
                        ->execute([$data['nombre_completo'], $id]);
                } else {
                    $this->pdo->prepare('INSERT INTO administrador (nombre_completo, usuario_id) VALUES (?,?)')
                        ->execute([$data['nombre_completo'] ?? 'Administrador', $id]);
                }
            }

            $this->pdo->commit();
            return ['success' => true];
        } catch (\Throwable $th) {
            $this->pdo->rollBack();
            return ['success' => false, 'message' => $th->getMessage()];
        }
    }

    public function deactivate(int $id)
    {
        $stmt = $this->pdo->prepare('UPDATE usuario SET activo = 0 WHERE id = ?');
        return $stmt->execute([$id]);
    }
}
