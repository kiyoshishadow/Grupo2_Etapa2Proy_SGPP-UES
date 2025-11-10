<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../models/ExpedienteModel.php';
require_once __DIR__ . '/../models/EvaluacionModel.php';
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
$evaluacionModel = new EvaluacionModel($pdo);
$informeModel = new InformeMensualModel($pdo);

// Obtener expediente activo del estudiante
$expediente = $expedienteModel->findRecientePorEstudiante($estudiante['id']);

if (!$expediente) {
    flash_set('warning', 'No tienes un expediente activo asignado.', 'warning');
    header('Location: /Grupo2_Etapa2Proy_SGPP-UES/public/index.php?page=mis_registros');
    exit;
}

// Obtener evaluaciones del expediente
$evaluaciones = $evaluacionModel->obtenerPorExpediente($expediente['id']);

// Obtener informes del expediente con sus estados
$informes = $informeModel->listarPorExpediente($expediente['id']);

// Calcular estadísticas
$estadisticas = [
    'total_informes' => count($informes),
    'informes_aprobados' => count(array_filter($informes, fn($inf) => $inf['estado_revision'] === 'aprobado')),
    'informes_pendientes' => count(array_filter($informes, fn($inf) => $inf['estado_revision'] === 'pendiente')),
    'evaluacion_final' => !empty($evaluaciones) ? end($evaluaciones) : null,
    'promedio_informes' => 0
];

if (!empty($informes)) {
    $suma_notas = 0;
    $conteo_notas = 0;
    foreach ($informes as $informe) {
        if ($informe['estado_revision'] === 'aprobado') {
            $suma_notas += 8; // Nota base para informes aprobados
            $conteo_notas++;
        }
    }
    $estadisticas['promedio_informes'] = $conteo_notas > 0 ? round($suma_notas / $conteo_notas, 2) : 0;
}

include __DIR__ . '/../../templates/estudiante/calificaciones.php';
