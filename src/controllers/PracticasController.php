<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../models/PracticaModel.php';
require_once __DIR__ . '/../includes/session_check.php';
require_once __DIR__ . '/../includes/flash.php';

check_session(['Docente']);

$model = new PracticaModel($pdo);
$action = $_GET['action'] ?? 'index';

switch ($action) {
  case 'index':
    if (empty($_SESSION['docente_id'])) {
      flash_set('error', 'No se pudo identificar al docente en sesión.', 'danger');
      header('Location: /Grupo2_Etapa2Proy_SGPP-UES/public/index.php?page=home');
      exit;
    }
    $practicas = $model->allByDocente((int)$_SESSION['docente_id']);
    include __DIR__ . '/../../templates/docente/practicas_index.php';
    break;

  case 'create':
    if (empty($_SESSION['docente_id'])) {
      flash_set('error', 'No se pudo identificar al docente en sesión.', 'danger');
      header('Location: /Grupo2_Etapa2Proy_SGPP-UES/public/index.php?page=home');
      exit;
    }
    include __DIR__ . '/../../templates/docente/practicas_create.php';
    break;

  case 'store':
    if (empty($_SESSION['docente_id'])) {
      flash_set('error', 'No se pudo identificar al docente en sesión.', 'danger');
      header('Location: /Grupo2_Etapa2Proy_SGPP-UES/public/index.php?page=home');
      exit;
    }
    $titulo = trim($_POST['titulo'] ?? '');
    $descripcion = trim($_POST['descripcion'] ?? '');
    $fecha_limite = $_POST['fecha_limite'] ?? null;

    if ($titulo === '' || $descripcion === '' || empty($fecha_limite)) {
      flash_set('error', 'Todos los campos son obligatorios para crear una práctica.', 'danger');
      header('Location: /Grupo2_Etapa2Proy_SGPP-UES/public/index.php?page=docente_practicas&action=create');
      exit;
    }
    $data = [
      'titulo' => $titulo,
      'descripcion' => $descripcion,
      'fecha_limite' => $fecha_limite,
      'docente_id' => (int)$_SESSION['docente_id']
    ];
    try {
      $model->create($data);
      flash_set('success', 'Práctica creada correctamente.', 'success');
    } catch (Exception $e) {
      flash_set('error', 'No se pudo crear la práctica: ' . $e->getMessage(), 'danger');
    }
    header('Location: /Grupo2_Etapa2Proy_SGPP-UES/public/index.php?page=docente_practicas');
    break;

  case 'edit':
    if (empty($_SESSION['docente_id'])) {
      flash_set('error', 'No se pudo identificar al docente en sesión.', 'danger');
      header('Location: /Grupo2_Etapa2Proy_SGPP-UES/public/index.php?page=home');
      exit;
    }
    $id = (int)($_GET['id'] ?? 0);
    $practica = $model->find($id);
    if (!$practica || (int)$practica['docente_id'] !== (int)$_SESSION['docente_id']) {
      flash_set('error', 'No se encontró la práctica solicitada.', 'danger');
      header('Location: /Grupo2_Etapa2Proy_SGPP-UES/public/index.php?page=docente_practicas');
      exit;
    }
    include __DIR__ . '/../../templates/docente/practicas_edit.php';
    break;

  case 'update':
    if (empty($_SESSION['docente_id'])) {
      flash_set('error', 'No se pudo identificar al docente en sesión.', 'danger');
      header('Location: /Grupo2_Etapa2Proy_SGPP-UES/public/index.php?page=home');
      exit;
    }
    $id = (int)($_POST['id'] ?? 0);
    $practica = $model->find($id);
    if (!$practica || (int)$practica['docente_id'] !== (int)$_SESSION['docente_id']) {
      flash_set('error', 'No se encontró la práctica solicitada.', 'danger');
      header('Location: /Grupo2_Etapa2Proy_SGPP-UES/public/index.php?page=docente_practicas');
      exit;
    }

    $titulo = trim($_POST['titulo'] ?? '');
    $descripcion = trim($_POST['descripcion'] ?? '');
    $fecha_limite = $_POST['fecha_limite'] ?? null;

    if ($titulo === '' || $descripcion === '' || empty($fecha_limite)) {
      flash_set('error', 'Todos los campos son obligatorios para actualizar la práctica.', 'danger');
      header('Location: /Grupo2_Etapa2Proy_SGPP-UES/public/index.php?page=docente_practicas&action=edit&id=' . $id);
      exit;
    }

    $data = [
      'titulo' => $titulo,
      'descripcion' => $descripcion,
      'fecha_limite' => $fecha_limite
    ];

    try {
      $model->update($id, $data);
      flash_set('success', 'Práctica actualizada correctamente.', 'success');
    } catch (Exception $e) {
      flash_set('error', 'No se pudo actualizar la práctica: ' . $e->getMessage(), 'danger');
    }
    header('Location: /Grupo2_Etapa2Proy_SGPP-UES/public/index.php?page=docente_practicas');
    break;

  case 'destroy':
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
      header('Location: /Grupo2_Etapa2Proy_SGPP-UES/public/index.php?page=docente_practicas');
      exit;
    }
    if (empty($_SESSION['docente_id'])) {
      flash_set('error', 'No se pudo identificar al docente en sesión.', 'danger');
      header('Location: /Grupo2_Etapa2Proy_SGPP-UES/public/index.php?page=home');
      exit;
    }
    $id = (int)($_POST['id'] ?? 0);
    $practica = $model->find($id);
    if (!$practica || (int)$practica['docente_id'] !== (int)$_SESSION['docente_id']) {
      flash_set('error', 'No se encontró la práctica solicitada.', 'danger');
      header('Location: /Grupo2_Etapa2Proy_SGPP-UES/public/index.php?page=docente_practicas');
      exit;
    }
    try {
      $model->delete($id);
      flash_set('success', 'Práctica eliminada correctamente.', 'success');
    } catch (Exception $e) {
      flash_set('error', 'No se pudo eliminar la práctica: ' . $e->getMessage(), 'danger');
    }
    header('Location: /Grupo2_Etapa2Proy_SGPP-UES/public/index.php?page=docente_practicas');
    break;

  default: http_response_code(404); echo "Acción no válida";
}
