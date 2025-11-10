<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../models/ExpedienteModel.php';
require_once __DIR__ . '/../models/AvanceExpedienteModel.php';
require_once __DIR__ . '/../models/InformeMensualModel.php';
require_once __DIR__ . '/../models/EstudianteModel.php';
require_once __DIR__ . '/../includes/session_check.php';
require_once __DIR__ . '/../includes/flash.php';

check_session(['Estudiante']);

// Obtener el estudiante de la sesión
$estudianteModel = new EstudianteModel($pdo);
$estudiante = $estudianteModel->findByUsuarioId($_SESSION['user_id']);
if (!$estudiante) {
    flash_set('error', 'No se pudo identificar tu perfil de estudiante.', 'danger');
    header('Location: /Grupo2_Etapa2Proy_SGPP-UES/public/index.php?page=login');
    exit;
}

// Inicializar modelos
$expedienteModel = new ExpedienteModel($pdo);
$avanceModel = new AvanceExpedienteModel($pdo);
$informeModel = new InformeMensualModel($pdo);

// Obtener expediente activo del estudiante
$expediente = $expedienteModel->findRecientePorEstudiante($estudiante['id']);

if (!$expediente) {
    flash_set('warning', 'No tienes un expediente activo asignado.', 'warning');
    header('Location: /Grupo2_Etapa2Proy_SGPP-UES/public/index.php?page=mis_registros');
    exit;
}

// Obtener avances del expediente
$avances = $avanceModel->listarPorExpediente($expediente['id']);

// Obtener informes del expediente
$informes = $informeModel->listarPorExpediente($expediente['id']);

// Calcular progreso
$estadisticas = [
    'total_avances' => count($avances),
    'total_informes' => count($informes),
    'informes_aprobados' => count(array_filter($informes, fn($inf) => $inf['estado_revision'] === 'aprobado')),
    'porcentaje_informes' => 0,
    'porcentaje_avance' => 0,
    'dias_transcurridos' => 0,
    'dias_totales' => 0,
    'porcentaje_tiempo' => 0
];

// Calcular progreso de informes
if ($estadisticas['total_informes'] > 0) {
    $estadisticas['porcentaje_informes'] = round(($estadisticas['informes_aprobados'] / $estadisticas['total_informes']) * 100, 1);
}

// Calcular progreso basado en el último avance
if (!empty($avances)) {
    $ultimo_avance = end($avances);
    $estadisticas['porcentaje_avance'] = $ultimo_avance['porcentaje_avance'] ?? 0;
}

// Calcular progreso de tiempo
if ($expediente['fecha_inicio'] && $expediente['fecha_fin']) {
    $fecha_inicio = new DateTime($expediente['fecha_inicio']);
    $fecha_fin = new DateTime($expediente['fecha_fin']);
    $hoy = new DateTime();
    
    $estadisticas['dias_totales'] = $fecha_inicio->diff($fecha_fin)->days;
    $estadisticas['dias_transcurridos'] = $fecha_inicio->diff($hoy)->days;
    
    if ($estadisticas['dias_totales'] > 0) {
        $estadisticas['porcentaje_tiempo'] = min(100, round(($estadisticas['dias_transcurridos'] / $estadisticas['dias_totales']) * 100, 1));
    }
}

include __DIR__ . '/../../templates/estudiante/progreso.php';
