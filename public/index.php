<?php
// public/index.php

// Sesión protegida (evita “session already active”)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../src/includes/session_check.php';

$page = $_GET['page'] ?? 'home';

// Si no hay sesión iniciada, llevamos a login (sin romper páginas públicas)
if (!isset($_SESSION['user_id']) && !in_array($page, ['login'])) {
    $page = 'login';
}

switch ($page) {

    // --- Autenticación y Home por rol ---
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
                exit;
        }
        break;

    // --- Docente: CRUD Prácticas ---
    case 'docente_practicas':
        // Internamente, PracticasController decide la acción por ?action=
        require_once __DIR__ . '/../src/controllers/PracticasController.php';
        break;

    // --- Estudiante: Registro y resultados ---
    case 'practicas_disponibles':
        $_GET['action'] = 'lista';
        require_once __DIR__ . '/../src/controllers/RegistrosController.php';
        break;

    case 'inscribir':
        $_GET['action'] = 'inscribir';
        require_once __DIR__ . '/../src/controllers/RegistrosController.php';
        break;

    case 'mis_registros':
        $_GET['action'] = 'mis_registros';
        require_once __DIR__ . '/../src/controllers/RegistrosController.php';
        break;

        case 'postulantes':
    // Docente/Admin: ver postulantes de una práctica
    $_GET['action'] = 'postulantes';
    require_once __DIR__ . '/../src/controllers/RegistrosController.php';
    break;

    case 'subir_resultado':
        require_once __DIR__ . '/../src/controllers/ResultadosController.php';
        break;

            // --- Docente: ver resultados y calificar ---
   case 'resultados_practica':
    require_once __DIR__ . '/../src/controllers/EvaluacionesController.php';
    break;

    case 'calificar_resultado':
        $_GET['action'] = 'calificar';
        require_once __DIR__ . '/../src/controllers/EvaluacionesController.php';
        break;


    default:
        http_response_code(404);
        echo "Página no encontrada";
        break;
}
