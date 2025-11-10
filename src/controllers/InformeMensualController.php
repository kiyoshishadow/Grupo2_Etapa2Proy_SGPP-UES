<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../models/InformeMensualModel.php';
require_once __DIR__ . '/../models/ExpedienteModel.php';
require_once __DIR__ . '/../models/EstudianteModel.php';
require_once __DIR__ . '/../includes/session_check.php';
require_once __DIR__ . '/../includes/flash.php';

check_session(['Estudiante']);

// Obtener el ID del estudiante de la sesión
$estudianteModel = new EstudianteModel($pdo);
$estudiante = $estudianteModel->findByUsuarioId($_SESSION['user_id']);
if (!$estudiante) {
    flash_set('error', 'No se pudo identificar el perfil del estudiante en sesión.', 'danger');
    header('Location: /Grupo2_Etapa2Proy_SGPP-UES/public/index.php?page=login');
    exit;
}
$estudiante_id = $estudiante['id'];

// Inicializar modelos
$informeModel = new InformeMensualModel($pdo);
$expedienteModel = new ExpedienteModel($pdo);

// Obtener el expediente activo del estudiante
$expediente_activo = $expedienteModel->findRecientePorEstudiante($estudiante_id);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $periodo = $_POST['periodo'] ?? '';
    $comentario = $_POST['comentario_estudiante'] ?? '';
    
    // Validar que se haya subido un archivo
    if (!isset($_FILES['archivo']) || $_FILES['archivo']['error'] !== UPLOAD_ERR_OK) {
        flash_set('error', 'Debe seleccionar un archivo para subir.', 'danger');
        header('Location: /Grupo2_Etapa2Proy_SGPP-UES/public/index.php?page=subir_informe');
        exit;
    }
    
    // Validar tipo y tamaño de archivo
    $archivo = $_FILES['archivo'];
    $allowed_types = ['application/pdf', 'application/zip'];
    $max_size = 5 * 1024 * 1024; // 5MB
    
    if (!in_array($archivo['type'], $allowed_types)) {
        flash_set('error', 'El archivo debe ser PDF o ZIP.', 'danger');
        header('Location: /Grupo2_Etapa2Proy_SGPP-UES/public/index.php?page=subir_informe');
        exit;
    }
    
    if ($archivo['size'] > $max_size) {
        flash_set('error', 'El archivo no debe superar los 5MB.', 'danger');
        header('Location: /Grupo2_Etapa2Proy_SGPP-UES/public/index.php?page=subir_informe');
        exit;
    }
    
    // Crear directorio de uploads si no existe
    $upload_dir = __DIR__ . '/../../public/uploads';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }
    
    // Generar nombre único para el archivo
    $filename = 'informe_' . $estudiante_id . '_' . date('Y-m-d_H-i-s') . '_' . basename($archivo['name']);
    $filepath = $upload_dir . '/' . $filename;
    
    // Mover archivo
    if (move_uploaded_file($archivo['tmp_name'], $filepath)) {
        // Guardar en base de datos
        $data = [
            'expediente_id' => $expediente_activo['id'],
            'periodo' => $periodo,
            'ruta_archivo' => '/uploads/' . $filename,
            'comentario_estudiante' => $comentario,
            'estado_revision' => 'pendiente'
        ];
        
        $informe_id = $informeModel->guardar($data);
        
        if ($informe_id) {
            flash_set('success', 'Informe mensual subido correctamente.', 'success');
            header('Location: /Grupo2_Etapa2Proy_SGPP-UES/public/index.php?page=mis_registros');
        } else {
            flash_set('error', 'Error al guardar el informe en la base de datos.', 'danger');
            header('Location: /Grupo2_Etapa2Proy_SGPP-UES/public/index.php?page=subir_informe');
        }
    } else {
        flash_set('error', 'Error al subir el archivo.', 'danger');
        header('Location: /Grupo2_Etapa2Proy_SGPP-UES/public/index.php?page=subir_informe');
    }
    exit;
}

// GET: Mostrar formulario para subir informe
if (!$expediente_activo) {
    flash_set('error', 'No tienes un expediente activo. Contacta al administrador.', 'warning');
    header('Location: /Grupo2_Etapa2Proy_SGPP-UES/public/index.php?page=mis_registros');
    exit;
}

// Obtener informes existentes del estudiante
$informes_existentes = $informeModel->listarPorExpediente($expediente_activo['id']);

include __DIR__ . '/../../templates/estudiante/subir_informe.php';
