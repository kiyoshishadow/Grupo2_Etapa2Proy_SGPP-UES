<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../models/InformeMensualModel.php';
require_once __DIR__ . '/../models/ResultadoModel.php';
require_once __DIR__ . '/../includes/session_check.php';

// Verificar sesión para cualquier rol
check_session(['Admin', 'Docente', 'Estudiante']);

$archivo = $_GET['archivo'] ?? '';
$tipo = $_GET['tipo'] ?? 'informe'; // informe o resultado

if (empty($archivo)) {
    http_response_code(404);
    echo 'Archivo no especificado';
    exit;
}

// Seguridad: evitar path traversal
$archivo = basename($archivo);
$ruta_completa = __DIR__ . '/../../public/uploads/' . $archivo;

if (!file_exists($ruta_completa)) {
    http_response_code(404);
    echo 'Archivo no encontrado';
    exit;
}

// Verificar que el usuario tenga permisos para este archivo
$tiene_permiso = false;

if ($tipo === 'informe') {
    $informeModel = new InformeMensualModel($pdo);
    $informes = $informeModel->listarTodos(); // método que necesitamos agregar
    
    foreach ($informes as $informe) {
        if (basename($informe['ruta_archivo']) === $archivo) {
            // Si es estudiante, solo puede ver sus propios informes
            if ($_SESSION['rol_nombre'] === 'Estudiante') {
                // Verificar que el informe pertenezca al estudiante
                $stmt = $pdo->prepare("
                    SELECT im.id 
                    FROM informe_mensual im
                    JOIN expediente e ON e.id = im.expediente_id
                    JOIN estudiante est ON est.id = e.estudiante_id
                    JOIN usuario u ON u.id = est.usuario_id
                    WHERE im.id = ? AND u.id = ?
                ");
                $stmt->execute([$informe['id'], $_SESSION['user_id']]);
                if ($stmt->fetch()) {
                    $tiene_permiso = true;
                }
            } else {
                // Admin y Docente pueden ver todos los informes
                $tiene_permiso = true;
            }
            break;
        }
    }
} elseif ($tipo === 'resultado') {
    // Lógica similar para resultados
    $resultadoModel = new ResultadoModel($pdo);
    $resultados = $resultadoModel->listarTodos(); // método que necesitamos agregar
    
    foreach ($resultados as $resultado) {
        if (basename($resultado['ruta_archivo']) === $archivo) {
            if ($_SESSION['rol_nombre'] === 'Estudiante') {
                // Verificar que el resultado pertenezca al estudiante
                $stmt = $pdo->prepare("
                    SELECT r.id 
                    FROM resultado r
                    JOIN registro reg ON reg.id = r.registro_id
                    JOIN practica p ON p.id = reg.practica_id
                    JOIN estudiante est ON est.id = p.estudiante_id
                    JOIN usuario u ON u.id = est.usuario_id
                    WHERE r.id = ? AND u.id = ?
                ");
                $stmt->execute([$resultado['id'], $_SESSION['user_id']]);
                if ($stmt->fetch()) {
                    $tiene_permiso = true;
                }
            } else {
                $tiene_permiso = true;
            }
            break;
        }
    }
}

if (!$tiene_permiso) {
    http_response_code(403);
    echo 'No tienes permiso para acceder a este archivo';
    exit;
}

// Obtener tipo MIME
$mime_type = mime_content_type($ruta_completa);
if (!$mime_type) {
    $mime_type = 'application/octet-stream';
}

// Configurar headers
header('Content-Type: ' . $mime_type);
header('Content-Length: ' . filesize($ruta_completa));
header('Content-Disposition: inline; filename="' . $archivo . '"');
header('Cache-Control: public, max-age=3600');
header('Pragma: public');

// Enviar archivo
readfile($ruta_completa);
exit;
