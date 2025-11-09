<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

<?php
session_start();
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../models/ResultadoModel.php';

$model = new ResultadoModel($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $registro_id = (int)$_POST['registro_id'];
  $comentario  = $_POST['comentario'] ?? '';
  $ruta_rel = null;

  if (!empty($_FILES['archivo']['name'])) {
    $safe = preg_replace('/[^a-zA-Z0-9._-]/','_', basename($_FILES['archivo']['name']));
    $file = time().'_'.$safe;
    $dest = __DIR__ . '/../../public/uploads/' . $file;
    if (move_uploaded_file($_FILES['archivo']['tmp_name'], $dest)) {
      $ruta_rel = '/Grupo2_Etapa2Proy_SGPP-UES/public/uploads/' . $file;
    }
  }

  if ($ruta_rel) {
    $model->subir($registro_id, $ruta_rel, $comentario);
    header('Location: /Grupo2_Etapa2Proy_SGPP-UES/public/index.php?page=mis_registros&ok=subido');
  } else {
    header('Location: /Grupo2_Etapa2Proy_SGPP-UES/public/index.php?page=mis_registros&error=archivo');
  }
  exit;
}
http_response_code(405); echo "Método no permitido";
