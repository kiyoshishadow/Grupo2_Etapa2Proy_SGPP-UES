<?php
require_once __DIR__ . '/../includes/session_check.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../models/ResultadoModel.php';
require_once __DIR__ . '/../models/EvaluacionModel.php';

if (session_status() === PHP_SESSION_NONE) { session_start(); }

$action = $_GET['action'] ?? 'listar';
$resultadoM = new ResultadoModel($pdo);
$evalM      = new EvaluacionModel($pdo);

switch ($action) {

case 'listar_resultados':
    // Docente/Admin revisa resultados por práctica
    check_session(['Docente','Admin']);
    $practica_id = (int)($_GET['practica_id'] ?? 0);
    $resultados  = $practica_id ? $resultadoM->porPractica($practica_id) : [];
    include __DIR__ . '/../../templates/docente/resultados_practica.php';
    break;

case 'calificar':
    check_session(['Docente','Admin']);
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $resultado_id = (int)($_POST['resultado_id'] ?? 0);
        $calificacion = (float)($_POST['calificacion'] ?? 0);
        $observacion  = $_POST['observacion'] ?? '';
        $docente_id   = $_SESSION['docente_id'] ?? 1; // ajusta si ya guardas el id real

        if ($resultado_id) {
            $evalM->calificar($resultado_id, $docente_id, $calificacion, $observacion);
        }
        $practica_id = (int)($_POST['practica_id'] ?? 0);
        header("Location: /Grupo2_Etapa2Proy_SGPP-UES/public/index.php?page=docente_practicas&ok=evaluado");
        exit;
    }
    http_response_code(405);
    echo "Método no permitido";
    break;

default:
    http_response_code(404);
    echo "Acción no válida";
}
