<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../models/ResultadoModel.php';
require_once __DIR__ . '/../includes/session_check.php';
require_once __DIR__ . '/../includes/flash.php';

check_session(['Estudiante']);

$model = new ResultadoModel($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (empty($_SESSION['estudiante_id'])) {
    flash_set('error', 'No se pudo identificar al estudiante en sesión.', 'danger');
    header('Location: /Grupo2_Etapa2Proy_SGPP-UES/public/index.php?page=mis_registros');
    exit;
  }

  $registro_id = (int)$_POST['registro_id'];
  $comentario  = $_POST['comentario'] ?? '';
  $ruta_rel = null;

  if (!empty($_FILES['archivo']['name'])) {
    $allowed = ['pdf','zip','doc','docx'];
    $maxSize = 5 * 1024 * 1024; // 5MB
    $ext = strtolower(pathinfo($_FILES['archivo']['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, $allowed)) {
      flash_set('error', 'Solo se permiten archivos PDF, ZIP, DOC o DOCX.', 'danger');
      header('Location: /Grupo2_Etapa2Proy_SGPP-UES/public/index.php?page=mis_registros');
      exit;
    }
    if ($_FILES['archivo']['size'] > $maxSize) {
      flash_set('error', 'El archivo no debe superar los 5 MB.', 'danger');
      header('Location: /Grupo2_Etapa2Proy_SGPP-UES/public/index.php?page=mis_registros');
      exit;
    }
    $safe = preg_replace('/[^a-zA-Z0-9._-]/','_', basename($_FILES['archivo']['name']));
    $file = time().'_'.$safe;
    $dest = __DIR__ . '/../../public/uploads/' . $file;
    if (!move_uploaded_file($_FILES['archivo']['tmp_name'], $dest)) {
      flash_set('error', 'No se pudo guardar el archivo en el servidor. Contacta al administrador.', 'danger');
      header('Location: /Grupo2_Etapa2Proy_SGPP-UES/public/index.php?page=mis_registros');
      exit;
    }
    $ruta_rel = '/Grupo2_Etapa2Proy_SGPP-UES/public/uploads/' . $file;
  }

  if ($ruta_rel) {
    $model->subir($registro_id, $ruta_rel, $comentario);
    flash_set('success', 'Archivo subido correctamente.', 'success');
    header('Location: /Grupo2_Etapa2Proy_SGPP-UES/public/index.php?page=mis_registros');
  } else {
    flash_set('error', 'No se pudo subir el archivo. Verifica el formato y vuelve a intentar.', 'danger');
    header('Location: /Grupo2_Etapa2Proy_SGPP-UES/public/index.php?page=mis_registros');
  }
  exit;
}
http_response_code(405); echo "Método no permitido";
