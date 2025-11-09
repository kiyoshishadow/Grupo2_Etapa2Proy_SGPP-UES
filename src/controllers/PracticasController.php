<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
session_start();
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../models/PracticaModel.php';

$model = new PracticaModel($pdo);
$action = $_GET['action'] ?? 'index';

switch ($action) {
  case 'index':
    $docente_id = $_SESSION['docente_id'] ?? 1; // temporal si aún no mapeamos
    $practicas = $model->allByDocente($docente_id);
    include __DIR__ . '/../../templates/docente/practicas_index.php';
    break;

  case 'create':
    include __DIR__ . '/../../templates/docente/practicas_create.php';
    break;

  case 'store':
    $data = [
      'titulo' => $_POST['titulo'],
      'descripcion' => $_POST['descripcion'],
      'fecha_limite' => $_POST['fecha_limite'],
      'docente_id' => $_SESSION['docente_id'] ?? 1
    ];
    $model->create($data);
    header('Location: /Grupo2_Etapa2Proy_SGPP-UES/public/index.php?page=docente_practicas');
    break;

  default: http_response_code(404); echo "Acción no válida";
}
