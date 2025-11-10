<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../models/InformeMensualModel.php';
require_once __DIR__ . '/../models/ExpedienteModel.php';
require_once __DIR__ . '/../includes/session_check.php';
require_once __DIR__ . '/../includes/flash.php';

check_session(['Docente']);

$informeModel = new InformeMensualModel($pdo);
$expedienteModel = new ExpedienteModel($pdo);

// Procesar acciones POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $informe_id = $_POST['informe_id'] ?? 0;
    $accion = $_POST['accion'] ?? '';
    $comentario = $_POST['comentario_revisor'] ?? '';
    
    if ($informe_id > 0 && in_array($accion, ['aprobar', 'observar', 'recibido'])) {
        $data = [
            'estado_revision' => $accion,
            'comentario_revisor' => $comentario,
            'fecha_revision' => date('Y-m-d H:i:s')
        ];
        
        if ($informeModel->actualizar($informe_id, $data)) {
            $mensaje = $accion === 'aprobar' ? 'Informe aprobado correctamente' : 
                      ($accion === 'observar' ? 'Informe observado con comentarios' : 'Informe marcado como recibido');
            flash_set('success', $mensaje, 'success');
        } else {
            flash_set('error', 'Error al actualizar el informe', 'danger');
        }
    }
    
    header('Location: /Grupo2_Etapa2Proy_SGPP-UES/public/index.php?page=docente_informes');
    exit;
}

// GET: Mostrar lista de informes
$estado_filtro = $_GET['estado'] ?? 'todos';
$informes = [];

if ($estado_filtro === 'todos') {
    $informes = $informeModel->listarTodosConDetalles();
} else {
    $informes = $informeModel->listarPorEstado($estado_filtro);
}

include __DIR__ . '/../../templates/docente/informes.php';
