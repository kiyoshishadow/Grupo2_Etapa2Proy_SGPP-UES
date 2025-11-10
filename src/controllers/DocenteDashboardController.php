<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../models/ExpedienteModel.php';
require_once __DIR__ . '/../models/EvaluacionModel.php';
require_once __DIR__ . '/../models/InformeMensualModel.php';
require_once __DIR__ . '/../models/DocenteModel.php';
require_once __DIR__ . '/../models/EmpresaModel.php';
require_once __DIR__ . '/../includes/session_check.php';
require_once __DIR__ . '/../includes/flash.php';

check_session(['Docente']);

// Obtener el ID del docente de la sesión
$docenteModel = new DocenteModel($pdo);
$docente = $docenteModel->findByUsuarioId($_SESSION['user_id']);
if (!$docente) {
    flash_set('error', 'No se pudo identificar el perfil del docente en sesión.', 'danger');
    header('Location: /Grupo2_Etapa2Proy_SGPP-UES/public/index.php?page=login');
    exit;
}
$docente_id = $docente['id'];

// Inicializar modelos
$expedienteModel = new ExpedienteModel($pdo);
$evaluacionModel = new EvaluacionModel($pdo);
$informeModel = new InformeMensualModel($pdo);
$empresaModel = new EmpresaModel($pdo);

// Obtener estadísticas
$estadisticas = [
    'estudiantes_activos' => $expedienteModel->contarActivos(),
    'expedientes_evaluados_mes' => $evaluacionModel->contarEvaluacionesMesActual(),
    'informes_pendientes' => $informeModel->contarPendientes(),
    'empresas_con_estudiantes' => $expedienteModel->contarEmpresasConEstudiantesActivos()
];

// Obtener actividad reciente
$actividad_reciente = [
    'informes_recientes' => $informeModel->obtenerRecientes(5),
    'evaluaciones_recientes' => $evaluacionModel->obtenerRecientes(5),
    'expedientes_nuevos' => $expedienteModel->obtenerRecientes(5)
];

// Obtener empresas con estudiantes activos
$empresas_activas = $expedienteModel->contarActivosPorEmpresa();

// Cargar la vista con los datos
include __DIR__ . '/../../templates/docente/dashboard.php';
