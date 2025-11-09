<?php
// src/controllers/RegistrosController.php

require_once __DIR__ . '/../includes/session_check.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../models/PracticaModel.php';
require_once __DIR__ . '/../models/RegistroModel.php';
require_once __DIR__ . '/../models/ResultadoModel.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$action    = $_GET['action'] ?? 'lista';
$practicaM = new PracticaModel($pdo);
$registroM = new RegistroModel($pdo);

switch ($action) {
    // -------------------------------------------
    // Estudiante: ver prácticas disponibles
    // -------------------------------------------
    case 'lista':
        check_session(['Estudiante']);
        $lista = $practicaM->disponiblesParaEstudiante();
        include __DIR__ . '/../../templates/estudiante/practicas_disponibles.php';
        break;

    // -------------------------------------------
    // Estudiante: inscribirse en una práctica
    // -------------------------------------------
    case 'inscribir':
        check_session(['Estudiante']);
        $practica_id   = isset($_GET['practica_id']) ? (int) $_GET['practica_id'] : 0;
        $estudiante_id = $_SESSION['estudiante_id'] ?? 1; // ajusta si ya guardas el id real

        if ($practica_id > 0) {
            // Evita duplicados si ya existe el registro
            $registroM->inscribirSiNoExiste($practica_id, $estudiante_id);
        }

        header('Location: /Grupo2_Etapa2Proy_SGPP-UES/public/index.php?page=mis_registros');
        exit;

    // -------------------------------------------
    // Estudiante: ver mis inscripciones + envíos
    // -------------------------------------------
    case 'mis_registros':
        check_session(['Estudiante']);

        $estudiante_id = $_SESSION['estudiante_id'] ?? 1;

        // Registros (inscripciones) del estudiante
        $registros = $registroM->misRegistros($estudiante_id);

        // Resultados por registro que consumirá la plantilla
        $resultadoM              = new ResultadoModel($pdo);
        $resultadosPorRegistro   = [];   // [registro_id => array de resultados]
        
        // Para conveniencia en la vista, agregamos dos campos a cada $registro:
        // - estado_envio: 'enviado' o 'pendiente'
        // - nota_docente: última calificación (o null si no existe)
        foreach ($registros as &$r) {
            $registroId = (int) $r['id'];

            // Trae todos los uploads de ese registro (con posible LEFT JOIN evaluacion)
            $items = $resultadoM->porRegistro($registroId);
            $resultadosPorRegistro[$registroId] = $items;

            // Estado de envío (si ya hay al menos un resultado subido)
            $r['estado_envio'] = empty($items) ? 'pendiente' : 'enviado';

            // Última calificación del docente (si existe)
            $ultimaNota  = null;
            $ultimaFecha = null;

            foreach ($items as $it) {
                // la consulta porRegistro trae e.calificacion y e.fecha_eval (pueden venir null)
                if ($it['calificacion'] !== null && $it['calificacion'] !== '') {
                    // si trae fecha_eval, usamos la más reciente
                    $f = $it['fecha_eval'] ?? null;
                    if ($ultimaFecha === null || ($f !== null && $f > $ultimaFecha)) {
                        $ultimaFecha = $f;
                        $ultimaNota  = $it['calificacion'];
                    }
                }
            }
            $r['nota_docente'] = $ultimaNota; // null si no hay evaluación todavía
        }
        unset($r);

        include __DIR__ . '/../../templates/estudiante/mis_registros.php';
        break;

    // -------------------------------------------
    // Docente/Admin: ver postulantes por práctica
    // -------------------------------------------
    case 'postulantes':
        check_session(['Docente','Admin']);
        $practica_id = isset($_GET['practica_id']) ? (int) $_GET['practica_id'] : 0;
        $postulantes = $practica_id ? $registroM->porPractica($practica_id) : [];
        include __DIR__ . '/../../templates/docente/postulantes.php';
        break;

    // -------------------------------------------
    // Ruta no válida
    // -------------------------------------------
    default:
        http_response_code(404);
        echo "Acción no válida";
        break;
}

