<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../models/DocenteModel.php';
require_once __DIR__ . '/../models/PracticaModel.php';
require_once __DIR__ . '/../models/ExpedienteModel.php';
require_once __DIR__ . '/../models/EstudianteModel.php';
require_once __DIR__ . '/../includes/session_check.php';
require_once __DIR__ . '/../includes/flash.php';

check_session(['Estudiante']);

$docenteModel = new DocenteModel($pdo);
$practicaModel = new PracticaModel($pdo);
$expedienteModel = new ExpedienteModel($pdo);
$estudianteModel = new EstudianteModel($pdo);

// Obtener el estudiante de la sesión
$estudiante = $estudianteModel->findByUsuarioId($_SESSION['user_id']);
if (!$estudiante) {
    flash_set('error', 'No se pudo identificar tu perfil de estudiante.', 'danger');
    header('Location: /Grupo2_Etapa2Proy_SGPP-UES/public/index.php?page=login');
    exit;
}

// Verificar si ya tiene un expediente activo
$expediente_activo = $expedienteModel->findRecientePorEstudiante($estudiante['id']);
if ($expediente_activo && $expediente_activo['estado'] === 'activo') {
    flash_set('info', 'Ya tienes un expediente activo con el docente: ' . $expediente_activo['docente_nombre'], 'info');
    header('Location: /Grupo2_Etapa2Proy_SGPP-UES/public/index.php?page=mis_registros');
    exit;
}

// Procesar POST para asignar docente
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $docente_id = $_POST['docente_id'] ?? 0;
    $practica_id = $_POST['practica_id'] ?? 0;
    
    if ($docente_id <= 0 || $practica_id <= 0) {
        flash_set('error', 'Debes seleccionar un docente y una práctica.', 'danger');
    } else {
        try {
            // Obtener datos de la práctica
            $practica = $practicaModel->find($practica_id);
            if (!$practica) {
                flash_set('error', 'La práctica seleccionada no existe.', 'danger');
            } elseif ($practica['estado'] !== 'activa') {
                flash_set('error', 'La práctica seleccionada no está activa.', 'danger');
            } else {
                // Crear expediente
                $expediente_data = [
                    'estudiante_id' => $estudiante['id'],
                    'docente_id' => $docente_id,
                    'empresa_id' => $practica['empresa_id'] ?? 1, // Empresa por defecto si no tiene
                    'fecha_inicio' => date('Y-m-d'),
                    'fecha_fin' => $practica['fecha_fin'] ?? date('Y-m-d', strtotime('+6 months')),
                    'estado' => 'activo'
                ];
                
                $expediente_id = $expedienteModel->create($expediente_data);
                
                if ($expediente_id) {
                    flash_set('success', 'Has sido asignado correctamente al docente. ¡Bienvenido a tu práctica!', 'success');
                    header('Location: /Grupo2_Etapa2Proy_SGPP-UES/public/index.php?page=mis_registros');
                    exit;
                } else {
                    flash_set('error', 'No se pudo crear el expediente. Intenta nuevamente.', 'danger');
                }
            }
        } catch (Exception $e) {
            flash_set('error', 'Error al asignar docente: ' . $e->getMessage(), 'danger');
        }
    }
}

// Obtener prácticas activas con docentes asignados
$practicas = $practicaModel->listarActivasConDocentes();

include __DIR__ . '/../../templates/estudiante/elegir_docente.php';
