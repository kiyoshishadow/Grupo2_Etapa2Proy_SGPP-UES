<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../models/AdminUserModel.php';
require_once __DIR__ . '/../includes/session_check.php';
require_once __DIR__ . '/../includes/flash.php';

check_session(['Admin']);

$model = new AdminUserModel($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? 'create';

    if ($action === 'create') {
        $data = [
            'nombre_usuario' => trim($_POST['nombre_usuario'] ?? ''),
            'contrasena' => $_POST['contrasena'] ?? '',
            'rol_id' => (int)($_POST['rol_id'] ?? 0),
            'nombre_completo' => trim($_POST['nombre_completo'] ?? ''),
            'departamento' => trim($_POST['departamento'] ?? ''),
            'carnet' => trim($_POST['carnet'] ?? ''),
            'carrera' => trim($_POST['carrera'] ?? '')
        ];

        if ($data['nombre_usuario'] === '' || $data['contrasena'] === '' || $data['rol_id'] === 0) {
            flash_set('error', 'Completa usuario, contraseña y rol para crear un usuario.', 'danger');
        } else {
            $result = $model->create($data);
            if ($result['success']) {
                flash_set('success', 'Usuario creado correctamente.', 'success');
            } else {
                flash_set('error', 'No se pudo crear el usuario: ' . $result['message'], 'danger');
            }
        }
    } elseif ($action === 'update') {
        $id = (int)($_POST['id'] ?? 0);
        if ($id <= 0) {
            flash_set('error', 'Usuario no válido para actualizar.', 'danger');
        } else {
            $data = [
                'nombre_usuario' => trim($_POST['nombre_usuario'] ?? ''),
                'rol_id' => (int)($_POST['rol_id'] ?? 0),
                'nombre_completo' => trim($_POST['nombre_completo'] ?? ''),
                'departamento' => trim($_POST['departamento'] ?? ''),
                'carnet' => trim($_POST['carnet'] ?? ''),
                'carrera' => trim($_POST['carrera'] ?? ''),
                'activo' => isset($_POST['activo']) ? 1 : 0,
                'nueva_contrasena' => trim($_POST['nueva_contrasena'] ?? '')
            ];

            if ($data['nombre_usuario'] === '' || $data['rol_id'] === 0) {
                flash_set('error', 'Debes indicar nombre de usuario y rol.', 'danger');
            } else {
                $result = $model->update($id, $data);
                if ($result['success']) {
                    flash_set('success', 'Usuario actualizado correctamente.', 'success');
                } else {
                    flash_set('error', 'No se pudo actualizar el usuario: ' . $result['message'], 'danger');
                }
            }
        }
    }

    header('Location: /Grupo2_Etapa2Proy_SGPP-UES/public/index.php?page=admin_usuarios');
    exit;
}

$usuarios = $model->all();
$roles = $model->roles();
$usuarioEditar = null;

if (isset($_GET['edit'])) {
    $editId = (int)$_GET['edit'];
    if ($editId > 0) {
        $usuarioEditar = $model->find($editId);
        if (!$usuarioEditar) {
            flash_set('error', 'El usuario seleccionado no existe.', 'danger');
            header('Location: /Grupo2_Etapa2Proy_SGPP-UES/public/index.php?page=admin_usuarios');
            exit;
        }
    }
}

include __DIR__ . '/../../templates/admin/usuarios.php';
