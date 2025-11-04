<?php
session_start();
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../src/includes/session_check.php';

$page = $_GET['page'] ?? 'home';

if (!isset($_SESSION['user_id'])) {
    $page = 'login';
}

switch ($page) {
    case 'login':
        include __DIR__ . '/../templates/login_campus.php';
        break;

    case 'logout':
        include __DIR__ . '/../src/auth/logout.php';
        break;

    case 'home':
        if (!isset($_SESSION['rol_nombre'])) {
            header("Location: index.php?page=login&error=Rol no definido");
            exit;
        }
        $rol = $_SESSION['rol_nombre'];
        switch ($rol) {
            case 'Admin':
                check_session(['Admin']);
                include __DIR__ . '/../templates/admin/dashboard.php';
                break;
            case 'Docente':
                check_session(['Docente']);
                include __DIR__ . '/../templates/docente/dashboard.php';
                break;
            case 'Estudiante':
                check_session(['Estudiante']);
                include __DIR__ . '/../templates/estudiante/dashboard.php';
                break;
            default:
                header("Location: index.php?page=login&error=Rol no válido");
                break;
        }
        break;

    default:
        http_response_code(404);
        echo "Página no encontrada";
        break;
}
