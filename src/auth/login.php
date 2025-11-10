<?php
session_start();
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../includes/flash.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['nombre_usuario'];
    $password = $_POST['contrasena'];

    $stmt = $pdo->prepare("SELECT u.*, r.nombre as rol_nombre FROM usuario u JOIN rol r ON u.rol_id = r.id WHERE u.nombre_usuario = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    $isValid = false;
    if ($user) {
        $stored = $user['contrasena'];
        if (is_string($stored) && (strpos($stored, '$2y$') === 0 || strpos($stored, '$argon2') === 0)) {
            $isValid = password_verify($password, $stored);
        } else {
            $isValid = hash_equals($stored, $password);
        }
    }

    if ($isValid) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['nombre_usuario'] = $user['nombre_usuario'];
        $_SESSION['rol_id'] = $user['rol_id'];
        $_SESSION['rol_nombre'] = $user['rol_nombre'];

        // Reinicia identificadores de perfil específicos
        $_SESSION['docente_id'] = null;
        $_SESSION['estudiante_id'] = null;

        if ($user['rol_nombre'] === 'Docente') {
            $docStmt = $pdo->prepare('SELECT id, nombre_completo FROM docente WHERE usuario_id = ? LIMIT 1');
            $docStmt->execute([$user['id']]);
            $docente = $docStmt->fetch();
            if ($docente) {
                $_SESSION['docente_id'] = (int)$docente['id'];
                $_SESSION['docente_nombre'] = $docente['nombre_completo'];
            }
        } elseif ($user['rol_nombre'] === 'Estudiante') {
            $estStmt = $pdo->prepare('SELECT id, nombre_completo FROM estudiante WHERE usuario_id = ? LIMIT 1');
            $estStmt->execute([$user['id']]);
            $estudiante = $estStmt->fetch();
            if ($estudiante) {
                $_SESSION['estudiante_id'] = (int)$estudiante['id'];
                $_SESSION['estudiante_nombre'] = $estudiante['nombre_completo'];
            }
        }

        header("Location: ../../public/index.php");
        exit;
    } else {
        flash_set('login_error', 'Credenciales incorrectas', 'danger');
        header("Location: ../../public/index.php?page=login");
        exit;
    }
}
?>