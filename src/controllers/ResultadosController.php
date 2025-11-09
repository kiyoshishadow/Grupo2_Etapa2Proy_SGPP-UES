<?php
require_once __DIR__ . '/../includes/session_check.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../models/ResultadoModel.php';

if (session_status() === PHP_SESSION_NONE) { session_start(); }

check_session(['Estudiante']); // este controlador es para subir resultados

$model = new ResultadoModel($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $registro_id = (int)($_POST['registro_id'] ?? 0);
    $comentario  = $_POST['comentario'] ?? '';

    $ruta_rel = null;
    if (!empty($_FILES['archivo']['name'])) {
        $name = basename($_FILES['archivo']['name']);
        $safe = preg_replace('/[^a-zA-Z0-9._-]/','_', $name);
        $file = time() . '_' . $safe;

        $destAbs = __DIR__ . '/../../public/uploads/' . $file;
        if (!is_dir(__DIR__ . '/../../public/uploads')) {
            @mkdir(__DIR__ . '/../../public/uploads', 0775, true);
        }
        if (move_uploaded_file($_FILES['archivo']['tmp_name'], $destAbs)) {
            // Ajusta el path según el subdirectorio de tu proyecto en Apache
            $ruta_rel = '/Grupo2_Etapa2Proy_SGPP-UES/public/uploads/' . $file;
        }
    }

    if ($registro_id && $ruta_rel) {
        $model->subir($registro_id, $ruta_rel, $comentario);
        header('Location: /Grupo2_Etapa2Proy_SGPP-UES/public/index.php?page=mis_registros&ok=subido');
    } else {
        header('Location: /Grupo2_Etapa2Proy_SGPP-UES/public/index.php?page=mis_registros&error=archivo');
    }
    exit;
}

http_response_code(405);
echo "Método no permitido";
