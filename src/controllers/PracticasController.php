<?php
require_once __DIR__ . '/../includes/session_check.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../models/PracticaModel.php';

// ¡No vuelvas a llamar session_start() aquí!
// index.php ya inició la sesión. Solo valida rol:
check_session(['Docente','Admin']);

$model  = new PracticaModel($pdo);
$action = $_GET['action'] ?? 'index';

// por ahora, si no tienes mapeado usuario→docente, usa 1
$docenteId = $_SESSION['docente_id'] ?? 1;

switch ($action) {
    case 'index':
        $practicas = $model->allByDocente($docenteId);
        require __DIR__ . '/../../templates/docente/practicas_index.php';
        break;

    case 'create':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // del formulario
            $data = [
                'titulo'       => trim($_POST['titulo'] ?? ''),
                'descripcion'  => trim($_POST['descripcion'] ?? ''),
                // en tu esquema la fecha límite es fecha_fin
                'fecha_fin'    => ($_POST['fecha_fin'] ?? null),
                'docente_id'   => $docenteId
            ];
            // validación mínima
            if ($data['titulo'] !== '' && $data['fecha_fin'] !== null) {
                $model->create($data);
                header('Location: /Grupo2_Etapa2Proy_SGPP-UES/public/index.php?page=docente_practicas');
                exit;
            }
            $error = 'Completa título y fecha.';
        }
        require __DIR__ . '/../../templates/docente/practicas_create.php';
        break;

    default:
        http_response_code(404);
        echo 'Acción no válida';
}
