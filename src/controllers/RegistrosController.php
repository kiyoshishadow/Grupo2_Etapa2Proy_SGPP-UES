<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../models/RegistroModel.php';
require_once __DIR__ . '/../models/PracticaModel.php';
require_once __DIR__ . '/../models/InformeMensualModel.php';
require_once __DIR__ . '/../models/ExpedienteModel.php';
require_once __DIR__ . '/../models/ResultadoModel.php';
require_once __DIR__ . '/../includes/session_check.php';
require_once __DIR__ . '/../includes/flash.php';

check_session(['Estudiante']);

$reg = new RegistroModel($pdo);
$prac = new PracticaModel($pdo);
$informeModel = new InformeMensualModel($pdo);
$expedienteModel = new ExpedienteModel($pdo);
$resultadoModel = new ResultadoModel($pdo);
$action = $_GET['action'] ?? 'lista';

if (!function_exists('sgpp_normalizar_ruta_publica')) {
    function sgpp_normalizar_ruta_publica(?string $ruta, ?string $publicRoot): ?string
    {
        if (!$ruta || !$publicRoot) {
            return null;
        }

        $urlPath = parse_url($ruta, PHP_URL_PATH);
        if (!$urlPath) {
            return null;
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
        return $absolute;
    }
}

if (!function_exists('sgpp_asegurar_expediente')) {
    function sgpp_asegurar_expediente(PDO $pdo, ExpedienteModel $expedienteModel, int $estudianteId, array $registro, ?string $periodo): ?array
    {
        if (!$registro) {
            return null;
        }

        $expediente = null;
        if (!empty($registro['practica_id'])) {
            $expediente = $expedienteModel->findPorEstudianteYPractica($estudianteId, (int)$registro['practica_id']);
        }

        if (!$expediente) {
            $expediente = $expedienteModel->findRecientePorEstudiante($estudianteId);
        }

        if ($expediente && !$periodo) {
            $periodo = date('Y-m-01');
        }

        return $expediente ? ['datos' => $expediente, 'periodo' => $periodo ?? date('Y-m-01')] : null;
    }
}

switch ($action) {
  case 'lista':
    $lista = $prac->disponibles();
    include __DIR__ . '/../../templates/estudiante/practicas_disponibles.php';
    break;

  case 'inscribir':
    if (empty($_SESSION['estudiante_id'])) {
      flash_set('error', 'No se pudo identificar al estudiante en sesión.', 'danger');
      header('Location: /Grupo2_Etapa2Proy_SGPP-UES/public/index.php?page=practicas_disponibles');
      exit;
    }

    $resultado = $reg->inscribir((int)$_GET['practica_id'], (int)$_SESSION['estudiante_id']);
    if ($resultado['success']) {
      flash_set('success', 'Inscripción enviada correctamente para "' . htmlspecialchars($resultado['practica']['titulo'] ?? '') . '".', 'success');
    } else {
      switch ($resultado['code']) {
        case 'duplicado':
          flash_set('info', 'Ya estás inscrito en esta práctica.', 'info');
          break;
        case 'sin_cupo':
          flash_set('warning', 'No hay cupos disponibles para esta práctica.', 'warning');
          break;
        case 'no_activa':
          flash_set('warning', 'La práctica ya no está activa.', 'warning');
          break;
        case 'no_practica':
          flash_set('danger', 'La práctica seleccionada no existe.', 'danger');
          break;
        default:
          $msg = !empty($resultado['error']) ? ' Detalle: ' . $resultado['error'] : '';
          flash_set('danger', 'Ocurrió un problema al inscribirse.' . $msg, 'danger');
      }
    }
    header('Location: /Grupo2_Etapa2Proy_SGPP-UES/public/index.php?page=mis_registros');
    break;

  case 'mis_registros':
    if (empty($_SESSION['estudiante_id'])) {
      flash_set('error', 'No se pudo identificar al estudiante en sesión.', 'danger');
      header('Location: /Grupo2_Etapa2Proy_SGPP-UES/public/index.php?page=login');
      exit;
    }
    $estudianteId = (int)$_SESSION['estudiante_id'];

    $expediente = $expedienteModel->findRecientePorEstudiante($estudianteId);
    
    $informes = [];
    if ($expediente) {
        $informes = $informeModel->listarPorExpediente($expediente['id']);
    }

    include __DIR__ . '/../../templates/estudiante/mis_registros.php';
    break;

  case 'cancelar':
    if (empty($_SESSION['estudiante_id'])) {
      flash_set('error', 'No se pudo identificar al estudiante en sesión.', 'danger');
      header('Location: /Grupo2_Etapa2Proy_SGPP-UES/public/index.php?page=practicas_disponibles');
      exit;
    }

    $registroId = (int)($_POST['registro_id'] ?? 0);
    if ($registroId <= 0) {
      flash_set('error', 'Registro no válido.', 'danger');
      header('Location: /Grupo2_Etapa2Proy_SGPP-UES/public/index.php?page=mis_registros');
      exit;
    }

    $resultado = $reg->cancelar($registroId, (int)$_SESSION['estudiante_id']);
    if ($resultado['success']) {
      flash_set('success', 'Te has dado de baja de "' . htmlspecialchars($resultado['titulo'] ?? '') . '".', 'success');
    } else {
      switch ($resultado['code'] ?? '') {
        case 'no_cancelable':
          flash_set('warning', 'No puedes darte de baja porque la inscripción ya está finalizada/aprobada.', 'warning');
          break;
        case 'no_encontrado':
          flash_set('danger', 'No se encontró la inscripción solicitada.', 'danger');
          break;
        default:
          flash_set('danger', 'No fue posible cancelar la inscripción.', 'danger');
      }
    }

    header('Location: /Grupo2_Etapa2Proy_SGPP-UES/public/index.php?page=mis_registros');
    break;

  case 'eliminar_resultado':
    if (empty($_SESSION['estudiante_id'])) {
      flash_set('error', 'No se pudo identificar al estudiante en sesión.', 'danger');
      header('Location: /Grupo2_Etapa2Proy_SGPP-UES/public/index.php?page=mis_registros');
      exit;
    }

    $registroId = (int)($_POST['registro_id'] ?? 0);
    if ($registroId <= 0) {
      flash_set('error', 'Registro no válido.', 'danger');
      header('Location: /Grupo2_Etapa2Proy_SGPP-UES/public/index.php?page=mis_registros');
      exit;
    }

    $resultado = $reg->eliminarResultado($registroId, (int)$_SESSION['estudiante_id']);
    if ($resultado['success']) {
      flash_set('success', 'Archivo eliminado correctamente.', 'success');
    } else {
      switch ($resultado['code'] ?? '') {
        case 'no_encontrado':
          flash_set('warning', 'No se encontró el archivo solicitado.', 'warning');
          break;
        case 'no_autorizado':
          flash_set('danger', 'No tienes permiso para eliminar este archivo.', 'danger');
          break;
        default:
          flash_set('danger', 'No fue posible eliminar el archivo.', 'danger');
      }
    }

    header('Location: /Grupo2_Etapa2Proy_SGPP-UES/public/index.php?page=mis_registros');
    break;

  case 'subir_resultado':
    http_response_code(405);
    echo "Método no permitido";
    exit;

  case 'editar_resultado':
    if (empty($_SESSION['estudiante_id'])) {
      flash_set('error', 'No se pudo identificar al estudiante en sesión.', 'danger');
      header('Location: /Grupo2_Etapa2Proy_SGPP-UES/public/index.php?page=mis_registros');
      exit;
    }

    $registroId = (int)($_POST['registro_id'] ?? 0);
    if ($registroId <= 0) {
      flash_set('error', 'Registro no válido.', 'danger');
      header('Location: /Grupo2_Etapa2Proy_SGPP-UES/public/index.php?page=mis_registros');
      exit;
    }

    $comentario = $_POST['comentario'] ?? '';
    $ruta_rel = null;

    if (!empty($_FILES['archivo']['name'])) {
      $allowed = ['pdf','zip','doc','docx'];
      $maxSize = 5 * 1024 * 1024;
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
        flash_set('error', 'No se pudo guardar el archivo en el servidor.', 'danger');
        header('Location: /Grupo2_Etapa2Proy_SGPP-UES/public/index.php?page=mis_registros');
        exit;
      }
      $ruta_rel = '/Grupo2_Etapa2Proy_SGPP-UES/public/uploads/' . $file;
    }

    $resultado = $reg->editarResultado($registroId, (int)$_SESSION['estudiante_id'], $ruta_rel, $comentario);
    if ($resultado['success']) {
      flash_set('success', 'Archivo actualizado correctamente.', 'success');
    } else {
      switch ($resultado['code'] ?? '') {
        case 'no_encontrado':
          flash_set('warning', 'No se encontró el archivo solicitado.', 'warning');
          break;
        case 'no_autorizado':
          flash_set('danger', 'No tienes permiso para editar este archivo.', 'danger');
          break;
        default:
          flash_set('danger', 'No fue posible actualizar el archivo.', 'danger');
      }
    }

    header('Location: /Grupo2_Etapa2Proy_SGPP-UES/public/index.php?page=mis_registros');
    break;

  default: http_response_code(404); echo "Acción no válida";
}
