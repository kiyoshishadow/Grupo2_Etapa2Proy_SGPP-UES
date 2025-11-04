<?php
session_start();
require_once __DIR__ . '/../../config/db.php';

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
        header("Location: ../../public/index.php");
        exit;
    } else {
        header("Location: ../../public/index.php?page=login&error=Credenciales incorrectas");
        exit;
    }
}
?>