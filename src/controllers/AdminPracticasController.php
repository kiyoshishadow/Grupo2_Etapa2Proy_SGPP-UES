<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../models/PracticaModel.php';
require_once __DIR__ . '/../models/AdminUserModel.php';
require_once __DIR__ . '/../includes/session_check.php';
require_once __DIR__ . '/../includes/flash.php';

check_session(['Admin']);

$practicaModel = new PracticaModel($pdo);
$userModel = new AdminUserModel($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? 'create';

    if ($action === 'create') {
        $data = [
            'titulo' => trim($_POST['titulo'] ?? ''),
            'descripcion' => trim($_POST['descripcion'] ?? ''),
            'fecha_inicio' => $_POST['fecha_inicio'] ?? null,
            'fecha_fin' => $_POST['fecha_fin'] ?? null,
            'cupo' => $_POST['cupo'] !== '' ? (int)$_POST['cupo'] : null,
            'estado' => $_POST['estado'] ?? 'activa',
            'docente_id' => (int)($_POST['docente_id'] ?? 0)
        ];

        if ($data['titulo'] === '' || empty($data['docente_id'])) {
            flash_set('error', 'Debes ingresar título y seleccionar un docente.', 'danger');
        } else {
            $fechaInicio = strtotime($data['fecha_inicio'] ?? '');
            $fechaFin = strtotime($data['fecha_fin'] ?? '');
            if ($fechaInicio && $fechaFin && $fechaInicio > $fechaFin) {
                flash_set('error', 'La fecha fin debe ser posterior a la fecha inicio.', 'danger');
            } else {
                $practicaModel->createAdmin($data);
                flash_set('success', 'Práctica creada correctamente.', 'success');
            }
        }
    } elseif ($action === 'update') {
        $id = (int)($_POST['id'] ?? 0);
        if ($id <= 0) {
            flash_set('error', 'Práctica inválida para actualizar.', 'danger');
        } else {
            $data = [
                'titulo' => trim($_POST['titulo'] ?? ''),
                'descripcion' => trim($_POST['descripcion'] ?? ''),
                'fecha_inicio' => $_POST['fecha_inicio'] ?? null,
                'fecha_fin' => $_POST['fecha_fin'] ?? null,
                'cupo' => $_POST['cupo'] !== '' ? (int)$_POST['cupo'] : null,
                'estado' => $_POST['estado'] ?? 'activa',
                'docente_id' => (int)($_POST['docente_id'] ?? 0)
            ];

            if ($data['titulo'] === '' || $data['docente_id'] === 0) {
                flash_set('error', 'Debes ingresar título y docente responsable.', 'danger');
            } else {
                $fechaInicio = strtotime($data['fecha_inicio'] ?? '');
                $fechaFin = strtotime($data['fecha_fin'] ?? '');
                if ($fechaInicio && $fechaFin && $fechaInicio > $fechaFin) {
                    flash_set('error', 'La fecha fin debe ser posterior a la fecha inicio.', 'danger');
                } else {
                    $resultado = $practicaModel->updateAdmin($id, $data);
                    if ($resultado) {
                        flash_set('success', 'Práctica actualizada correctamente.', 'success');
                    } else {
                        flash_set('error', 'No se pudo actualizar la práctica.', 'danger');
                    }
                }
            }
        }
    }

    header('Location: /Grupo2_Etapa2Proy_SGPP-UES/public/index.php?page=admin_practicas');
    exit;
}

$practicas = $practicaModel->allAdmin();
$docentes = $userModel->getDocentes();
$practicaEditar = null;

if (isset($_GET['edit'])) {
    $editId = (int)$_GET['edit'];
    if ($editId > 0) {
        $practicaEditar = $practicaModel->findAdmin($editId);
        if (!$practicaEditar) {
            flash_set('error', 'La práctica seleccionada no existe.', 'danger');
            header('Location: /Grupo2_Etapa2Proy_SGPP-UES/public/index.php?page=admin_practicas');
            exit;
        }
    }
}

include __DIR__ . '/../../templates/admin/practicas.php';
