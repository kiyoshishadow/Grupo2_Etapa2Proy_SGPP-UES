<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../models/ExpedienteModel.php';
require_once __DIR__ . '/../models/EmpresaModel.php';
require_once __DIR__ . '/../models/InformeMensualModel.php';
require_once __DIR__ . '/../models/AvanceExpedienteModel.php';
require_once __DIR__ . '/../includes/session_check.php';
require_once __DIR__ . '/../includes/flash.php';

check_session(['Admin', 'Docente']);

$expedienteModel = new ExpedienteModel($pdo);
$empresaModel = new EmpresaModel($pdo);
$informeModel = new InformeMensualModel($pdo);
$avanceModel = new AvanceExpedienteModel($pdo);

$action = $_GET['action'] ?? 'index';

function obtenerEstudiantes(PDO $pdo): array
{
    $stmt = $pdo->query(
        "SELECT id, carnet, nombre_completo, carrera
         FROM estudiante
         ORDER BY nombre_completo ASC"
    );
    return $stmt->fetchAll();
}

switch ($action) {
    case 'create':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'estudiante_id' => (int)($_POST['estudiante_id'] ?? 0),
                'empresa_id' => $_POST['empresa_id'] !== '' ? (int)$_POST['empresa_id'] : null,
                'empresa_nombre' => trim($_POST['empresa_nombre'] ?? ''),
                'empresa_direccion' => trim($_POST['empresa_direccion'] ?? ''),
                'supervisor_externo' => trim($_POST['supervisor_externo'] ?? ''),
                'telefono_supervisor' => trim($_POST['telefono_supervisor'] ?? ''),
                'correo_supervisor' => trim($_POST['correo_supervisor'] ?? ''),
                'fecha_inicio' => $_POST['fecha_inicio'] ?? null,
                'fecha_fin' => $_POST['fecha_fin'] ?? null,
                'estado' => $_POST['estado'] ?? 'planeado',
                'horas_planificadas' => $_POST['horas_planificadas'] !== '' ? (int)$_POST['horas_planificadas'] : null,
                'horas_cumplidas' => $_POST['horas_cumplidas'] !== '' ? (int)$_POST['horas_cumplidas'] : null,
                'observaciones' => trim($_POST['observaciones'] ?? ''),
            ];

            if ($data['estudiante_id'] <= 0) {
                flash_set('error', 'Debes seleccionar un estudiante.', 'danger');
            } else {
                try {
                    $expedienteModel->create($data);
                    flash_set('success', 'Expediente creado correctamente.', 'success');
                    header('Location: /Grupo2_Etapa2Proy_SGPP-UES/public/index.php?page=admin_expedientes');
                    exit;
                } catch (PDOException $e) {
                    flash_set('error', 'No se pudo crear el expediente: ' . $e->getMessage(), 'danger');
                }
            }
        }

        $empresas = $empresaModel->activas();
        $estudiantes = obtenerEstudiantes($pdo);
        include __DIR__ . '/../../templates/admin/expedientes_form.php';
        break;

    case 'detalle':
        $id = (int)($_GET['id'] ?? 0);
        if ($id <= 0) {
            flash_set('error', 'Expediente no válido.', 'danger');
            header('Location: /Grupo2_Etapa2Proy_SGPP-UES/public/index.php?page=admin_expedientes');
            exit;
        }
        $expediente = $expedienteModel->find($id);
        if (!$expediente) {
            flash_set('error', 'No se encontró el expediente solicitado.', 'danger');
            header('Location: /Grupo2_Etapa2Proy_SGPP-UES/public/index.php?page=admin_expedientes');
            exit;
        }
        $informes = $informeModel->listarPorExpediente($id);
        $publicRoot = realpath(__DIR__ . '/../../public');
        $normalizarRuta = function (?string $ruta) use ($publicRoot) {
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
            return is_file($absolute) ? $ruta : null;
        };
        foreach ($informes as &$info) {
            $rutaNormalizada = $normalizarRuta($info['ruta_archivo'] ?? null);
            $info['ruta_archivo_normalizada'] = $rutaNormalizada;
        }
        unset($info);
        $avances = $avanceModel->listarPorExpediente($id);
        include __DIR__ . '/../../templates/admin/expedientes_detalle.php';
        break;

    default:
        $filtros = [
            'estado' => $_GET['estado'] ?? null,
            'empresa_id' => isset($_GET['empresa_id']) && $_GET['empresa_id'] !== '' ? (int)$_GET['empresa_id'] : null,
            'carrera' => $_GET['carrera'] ?? null,
            'fecha_inicio_desde' => $_GET['fecha_inicio_desde'] ?? null,
            'fecha_inicio_hasta' => $_GET['fecha_inicio_hasta'] ?? null,
        ];
        $expedientes = $expedienteModel->listar($filtros);
        $empresas = $empresaModel->activas();
        include __DIR__ . '/../../templates/admin/expedientes_index.php';
        break;
}
