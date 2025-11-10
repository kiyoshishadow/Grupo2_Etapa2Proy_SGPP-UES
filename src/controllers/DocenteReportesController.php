<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../models/ExpedienteModel.php';
require_once __DIR__ . '/../models/InformeMensualModel.php';
require_once __DIR__ . '/../models/EvaluacionModel.php';
require_once __DIR__ . '/../models/EmpresaModel.php';
require_once __DIR__ . '/../includes/session_check.php';
require_once __DIR__ . '/../includes/flash.php';

check_session(['Docente']);

// Inicializar modelos
$expedienteModel = new ExpedienteModel($pdo);
$informeModel = new InformeMensualModel($pdo);
$evaluacionModel = new EvaluacionModel($pdo);
$empresaModel = new EmpresaModel($pdo);

// Obtener estadísticas para reportes
$estadisticas = [
    'total_estudiantes' => $expedienteModel->contarActivos(),
    'total_informes' => $informeModel->contarPendientes(),
    'total_evaluaciones' => $evaluacionModel->contarEvaluacionesMesActual(),
    'total_empresas' => $empresaModel->contarEmpresasConEstudiantesActivos()
];

// Obtener datos por carrera
$expedientes_por_carrera = $expedienteModel->contarPorCarrera();

// Obtener datos por empresa
$empresas_con_estudiantes = $empresaModel->listarConEstudiantesActivos();

// Obtener informes recientes
$informes_recientes = $informeModel->obtenerRecientes(10);

include __DIR__ . '/../../templates/docente/reportes.php';
