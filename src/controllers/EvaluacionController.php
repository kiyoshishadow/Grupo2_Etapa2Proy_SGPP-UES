<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../models/EvaluacionModel.php';
require_once __DIR__ . '/../models/ExpedienteModel.php';
require_once __DIR__ . '/../models/DocenteModel.php'; // Necesario para obtener el ID del docente
require_once __DIR__ . '/../includes/session_check.php';
require_once __DIR__ . '/../includes/flash.php';

check_session(['Docente']);

$evaluacionModel = new EvaluacionModel($pdo);
$expedienteModel = new ExpedienteModel($pdo);

// Obtener el ID del docente de la sesión
$docenteModel = new DocenteModel($pdo);
$docente = $docenteModel->findByUsuarioId($_SESSION['user_id']);
if (!$docente) {
    flash_set('error', 'No se pudo identificar el perfil del docente en sesión.', 'danger');
    header('Location: /Grupo2_Etapa2Proy_SGPP-UES/public/index.php?page=home');
    exit;
}
$docente_id = $docente['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $expediente_id = (int)($_POST['expediente_id'] ?? 0);
    
    $data = [
        'expediente_id' => $expediente_id,
        'nota_final' => $_POST['nota_final'] !== '' ? (float)$_POST['nota_final'] : null,
        'comentario_docente' => trim($_POST['comentario_docente'] ?? ''),
        'docente_id' => $docente_id
    ];

    if ($expediente_id > 0) {
        if ($evaluacionModel->guardarEvaluacion($data)) {
            flash_set('success', 'Evaluación guardada correctamente.', 'success');
        } else {
            flash_set('error', 'No se pudo guardar la evaluación.', 'danger');
        }
    } else {
        flash_set('error', 'ID de expediente no válido.', 'danger');
    }

    header('Location: /Grupo2_Etapa2Proy_SGPP-UES/public/index.php?page=evaluar');
    exit;
}

// Lógica GET: Mostrar lista de expedientes para evaluar
$expedientes = $expedienteModel->listar();

// Para cada expediente, obtener su evaluación existente si la hay
foreach ($expedientes as &$expediente) {
    $expediente['evaluacion'] = $evaluacionModel->porExpediente($expediente['id']);
}
unset($expediente);


include __DIR__ . '/../../templates/docente/evaluar_expediente.php';
