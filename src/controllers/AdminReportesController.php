<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../models/ExpedienteModel.php';
require_once __DIR__ . '/../includes/session_check.php';
require_once __DIR__ . '/../includes/flash.php';

check_session(['Admin']);

$expedienteModel = new ExpedienteModel($pdo);

$fechaDesde = $_GET['fecha_desde'] ?? date('Y-m-01');
$fechaHasta = $_GET['fecha_hasta'] ?? date('Y-m-t');
$resultados = [];
$error = null;

if (strtotime($fechaDesde) > strtotime($fechaHasta)) {
    $error = "La fecha 'desde' no puede ser posterior a la fecha 'hasta'.";
    flash_set('error', $error, 'danger');
} else {
    $resultados = $expedienteModel->contarActivosPorEmpresaYFechas($fechaDesde, $fechaHasta);
}

$page_title = 'Reporte de Estudiantes Activos por Empresa';

include __DIR__ . '/../../templates/admin/reportes_expedientes.php';
