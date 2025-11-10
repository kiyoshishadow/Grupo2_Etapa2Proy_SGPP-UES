<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../models/RegistroModel.php';
require_once __DIR__ . '/../models/PracticaModel.php';
require_once __DIR__ . '/../models/ResultadoModel.php';
require_once __DIR__ . '/../models/EvaluacionModel.php';
require_once __DIR__ . '/../includes/session_check.php';
require_once __DIR__ . '/../includes/flash.php';

check_session(['Admin']);

$registroModel = new RegistroModel($pdo);
$practicaModel = new PracticaModel($pdo);
$resultadoModel = new ResultadoModel($pdo);
$evaluacionModel = new EvaluacionModel($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $registroId = (int)($_POST['registro_id'] ?? 0);
    $practicaId = (int)($_POST['practica_id'] ?? 0);
    $docenteId = (int)($_POST['docente_id'] ?? 0);
    $estado = $_POST['estado'] ?? 'pendiente';
    $comentario = trim($_POST['comentario'] ?? '');
    $nota = $_POST['nota'] !== '' ? (float)$_POST['nota'] : null;

    if ($registroId <= 0 || $practicaId <= 0 || $docenteId <= 0) {
        flash_set('error', 'Información incompleta para registrar la evaluación.', 'danger');
    } else {
        $evaluacionModel->actualizarEstadoInscripcion($registroId, $estado);
        $evaluacionModel->evaluarInscripcion($registroId, $estado, $comentario, $docenteId, $nota);
        flash_set('success', 'Evaluación registrada correctamente.', 'success');
    }

    header('Location: /Grupo2_Etapa2Proy_SGPP-UES/public/index.php?page=admin_reportes&practica_id=' . $practicaId);
    exit;
}

$practicas = $practicaModel->allAdmin();
$practicaId = isset($_GET['practica_id']) ? (int)$_GET['practica_id'] : null;
$practicaSeleccionada = null;
$detalle = null;
$publicRoot = realpath(__DIR__ . '/../../public');

$archivoExiste = function (?string $ruta) use ($publicRoot) {
    if (!$ruta || !$publicRoot) {
        return false;
    }

    $urlPath = parse_url($ruta, PHP_URL_PATH);
    if (!$urlPath) {
        return false;
    }

    $relative = ltrim($urlPath, '/');
    $projectPrefix = 'Grupo2_Etapa2Proy_SGPP-UES/';
    if (strpos($relative, $projectPrefix) === 0) {
        $relative = substr($relative, strlen($projectPrefix));
    }

    if (strpos($relative, 'public/') === 0) {
        $relative = substr($relative, strlen('public/'));
    }

    $absolute = $publicRoot . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $relative);
    return is_file($absolute);
};

if ($practicaId) {
    foreach ($practicas as $practica) {
        if ((int)$practica['id'] === $practicaId) {
            $practicaSeleccionada = $practica;
            break;
        }
    }

    if (!$practicaSeleccionada) {
        $practicaSeleccionada = $practicaModel->findAdmin($practicaId);
    }

    $detalle = $registroModel->porPracticaConResultados($practicaId);
    if (!$detalle) {
        flash_set('info', 'Esta práctica aún no tiene inscripciones con resultados.', 'info');
    } else {
        foreach ($detalle as &$item) {
            $item['resultados'] = $resultadoModel->porRegistro($item['id']);
            $item['evaluaciones'] = $evaluacionModel->porRegistro($item['id']);
            foreach ($item['resultados'] as &$resultado) {
                $resultado['archivo_existe'] = $archivoExiste($resultado['ruta_archivo'] ?? null);
            }
            unset($resultado);
        }
        unset($item);

        if ($practicaSeleccionada) {
            $practicaSeleccionada['total_inscritos'] = count($detalle);
            $practicaSeleccionada['total_finalizados'] = count(array_filter($detalle, function ($row) {
                $estado = strtolower($row['registro_estado'] ?? '');
                return in_array($estado, ['aprobado', 'finalizada'], true);
            }));
        }
    }
}

$registros = $registroModel->todosConResultados();

include __DIR__ . '/../../templates/admin/reportes_resultados.php';
