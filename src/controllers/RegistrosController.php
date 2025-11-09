<?php
require_once __DIR__ . '/../includes/session_check.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../models/PracticaModel.php';
require_once __DIR__ . '/../models/RegistroModel.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$action = $_GET['action'] ?? 'lista';

$practicaM = new PracticaModel($pdo);
$registroM = new RegistroModel($pdo);

switch ($action) {
    // Estudiante: ver prácticas disponibles
    case 'lista':
        check_session(['Estudiante']);
        $lista = $practicaM->disponiblesParaEstudiante();
        include __DIR__ . '/../../templates/estudiante/practicas_disponibles.php';
        break;

    // Estudiante: inscribirse
    case 'inscribir':
        check_session(['Estudiante']);
        $practica_id  = isset($_GET['practica_id']) ? (int)$_GET['practica_id'] : 0;
        $estudiante_id = $_SESSION['estudiante_id'] ?? 1; // si ya guardas el id real, úsalo

        if ($practica_id > 0) {
            // usa el que prefieras:
            // $ok = $registroM->inscribir($practica_id, $estudiante_id);
            $ok = $registroM->inscribirSiNoExiste($practica_id, $estudiante_id);
        }
        header('Location: /Grupo2_Etapa2Proy_SGPP-UES/public/index.php?page=mis_registros');
        exit;

    // Estudiante: ver mis inscripciones

case 'mis_registros':
    check_session(['Estudiante']);
    $estudiante_id = $_SESSION['estudiante_id'] ?? 1;

    // 1) Registros del estudiante
    $registros = $registroM->misRegistros($estudiante_id);

    // 2) Resultados por registro (para mostrar lo subido)
    require_once __DIR__ . '/../models/ResultadoModel.php';
    $resultadoM = new ResultadoModel($pdo);
    $resultadosPorRegistro = [];
    foreach ($registros as $r) {
        $resultadosPorRegistro[(int)$r['id']] = $resultadoM->porRegistro((int)$r['id']);
    }

    include __DIR__ . '/../../templates/estudiante/mis_registros.php';
    break;

    // Docente: ver postulantes a una práctica
    case 'postulantes':
        check_session(['Docente','Admin']);
        $practica_id = isset($_GET['practica_id']) ? (int)$_GET['practica_id'] : 0;
        $postulantes = $practica_id ? $registroM->porPractica($practica_id) : [];
        include __DIR__ . '/../../templates/docente/postulantes.php';
        break;

    default:
        http_response_code(404);
        echo "Acción no válida";
}
