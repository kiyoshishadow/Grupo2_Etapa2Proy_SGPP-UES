<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../models/AvanceExpedienteModel.php';
require_once __DIR__ . '/../models/ExpedienteModel.php';
require_once __DIR__ . '/../includes/session_check.php';
require_once __DIR__ . '/../includes/flash.php';

check_session(['Docente']);

$avanceModel = new AvanceExpedienteModel($pdo);
$expedienteModel = new ExpedienteModel($pdo);

// Procesar acciones POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $expediente_id = $_POST['expediente_id'] ?? 0;
    $tipo = $_POST['tipo'] ?? '';
    $descripcion = $_POST['descripcion'] ?? '';
    $porcentaje = $_POST['porcentaje_avance'] ?? 0;
    
    if ($expediente_id > 0 && !empty($tipo) && !empty($descripcion)) {
        $data = [
            'expediente_id' => $expediente_id,
            'tipo' => $tipo,
            'descripcion' => $descripcion,
            'porcentaje_avance' => $porcentaje,
            'registrado_por' => $_SESSION['user_id']
        ];
        
        if ($avanceModel->guardar($data)) {
            flash_set('success', 'Avance registrado correctamente', 'success');
        } else {
            flash_set('error', 'Error al registrar el avance', 'danger');
        }
    }
    
    header('Location: /Grupo2_Etapa2Proy_SGPP-UES/public/index.php?page=docente_avances');
    exit;
}

// GET: Mostrar lista de avances
$expedientes_activos = $expedienteModel->listarActivosConEstudiantes();
$avances_recientes = $avanceModel->listarRecientesConDetalles(20);

include __DIR__ . '/../../templates/docente/avances.php';
