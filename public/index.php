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
                require_once __DIR__ . '/../src/controllers/DocenteDashboardController.php';
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

    // --- Rutas Principales ---

    case 'evaluar':
        require_once __DIR__ . '/../src/controllers/EvaluacionController.php';
        break;

    case 'docente_reportes':
        check_session(['Docente']);
        require_once __DIR__ . '/../src/controllers/DocenteReportesController.php';
        break;

    case 'docente_informes':
        check_session(['Docente']);
        require_once __DIR__ . '/../src/controllers/DocenteInformesController.php';
        break;

    case 'docente_avances':
        check_session(['Docente']);
        require_once __DIR__ . '/../src/controllers/DocenteAvancesController.php';
        break;

    case 'admin_reportes_expedientes':
        require_once __DIR__ . '/../src/controllers/AdminReportesController.php';
        break;

    case 'admin_usuarios':
        require_once __DIR__ . '/../src/controllers/AdminUsuariosController.php';
        break;

    case 'admin_expedientes':
        require_once __DIR__ . '/../src/controllers/ExpedientesController.php';
        break;

    case 'admin_practicas':
        require_once __DIR__ . '/../src/controllers/AdminPracticasController.php';
        break;

    case 'estudiante_elegir_docente':
        check_session(['Estudiante']);
        require_once __DIR__ . '/../src/controllers/EstudianteElegirDocenteController.php';
        break;

    case 'mis_registros':
        check_session(['Estudiante']);
        $_GET['action'] = 'mis_registros';
        require_once __DIR__ . '/../src/controllers/RegistrosController.php';
        break;

    case 'subir_informe':
        check_session(['Estudiante']);
        require_once __DIR__ . '/../src/controllers/InformeMensualController.php';
        break;

    case 'ver_calificaciones':
        check_session(['Estudiante']);
        require_once __DIR__ . '/../src/controllers/EstudianteCalificacionesController.php';
        break;

    case 'ver_progreso':
        check_session(['Estudiante']);
        require_once __DIR__ . '/../src/controllers/EstudianteProgresoController.php';
        break;

    case 'descargar_archivo':
        require_once __DIR__ . '/../src/controllers/FileController.php';
        break;

    default:
        http_response_code(404);
        echo "Página no encontrada";
        break;
}
