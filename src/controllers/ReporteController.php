<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../models/PracticaModel.php';
require_once __DIR__ . '/../models/RegistroModel.php';
require_once __DIR__ . '/../models/ResultadoModel.php';
require_once __DIR__ . '/../models/EvaluacionModel.php';
require_once __DIR__ . '/../includes/session_check.php';
require_once __DIR__ . '/../includes/flash.php';

check_session(['Docente', 'Admin']);

$practicaModel = new PracticaModel($pdo);
$registroModel = new RegistroModel($pdo);
$resultadoModel = new ResultadoModel($pdo);
$evaluacionModel = new EvaluacionModel($pdo);

$practica_id = (int)($_GET['practica_id'] ?? 0);
$practica = $practicaModel->obtenerPorId($practica_id);
if (!$practica) {
    flash_set('error', 'Práctica no encontrada.', 'danger');
    header('Location: /Grupo2_Etapa2Proy_SGPP-UES/public/index.php?page=docente_practicas');
    exit;
}

$inscritos = $registroModel->porPractica($practica_id);
$rows = [];
foreach ($inscritos as $ins) {
    $resultados = $resultadoModel->porRegistro($ins['id']);
    $evaluaciones = $evaluacionModel->porRegistro($ins['id']);
    $rows[] = [
        'estudiante' => $ins['estudiante_nombre'],
        'fecha_inscripcion' => $ins['fecha_inscripcion'],
        'estado' => $ins['estado'],
        'archivo' => $resultados[0]['ruta_archivo'] ?? null,
        'comentario_archivo' => $resultados[0]['comentario'] ?? null,
        'evaluacion' => $evaluaciones[0]['comentario'] ?? null,
        'evaluador' => $evaluaciones[0]['docente_nombre'] ?? null,
        'fecha_evaluacion' => $evaluaciones[0]['fecha_evaluacion'] ?? null
    ];
}

// Generar HTML
ob_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Reporte de práctica</title>
  <style>
    body { font-family: Arial, sans-serif; margin: 20px; }
    h1, h2 { color: #7a0b0b; }
    table { width: 100%; border-collapse: collapse; margin-top: 20px; }
    th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
    th { background-color: #f2f2f2; }
    .no-data { color: #999; }
  </style>
</head>
<body>
  <h1>Reporte de Práctica Profesional</h1>
  <h2><?= htmlspecialchars($practica['titulo']) ?></h2>
  <p><strong>Descripción:</strong> <?= htmlspecialchars($practica['descripcion']) ?></p>
  <p><strong>Fecha de inicio:</strong> <?= htmlspecialchars($practica['fecha_inicio']) ?></p>
  <p><strong>Fecha límite:</strong> <?= htmlspecialchars($practica['fecha_limite']) ?></p>
  <p><strong>Docente:</strong> <?= htmlspecialchars($_SESSION['docente_nombre'] ?? $_SESSION['nombre_usuario']) ?></p>
  <hr>
  <h3>Inscripciones y evaluaciones</h3>
  <table>
    <thead>
      <tr>
        <th>Estudiante</th>
        <th>Fecha de inscripción</th>
        <th>Estado</th>
        <th>Archivo</th>
        <th>Comentario del archivo</th>
        <th>Evaluación</th>
        <th>Evaluador</th>
        <th>Fecha de evaluación</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($rows as $r): ?>
      <tr>
        <td><?= htmlspecialchars($r['estudiante']) ?></td>
        <td><?= htmlspecialchars($r['fecha_inscripcion']) ?></td>
        <td><?= htmlspecialchars($r['estado']) ?></td>
        <td><?= $r['archivo'] ? '<a href="'.htmlspecialchars($r['archivo']).'">Ver</a>' : '<span class="no-data">—</span>' ?></td>
        <td><?= $r['comentario_archivo'] ? htmlspecialchars($r['comentario_archivo']) : '<span class="no-data">—</span>' ?></td>
        <td><?= $r['evaluacion'] ? htmlspecialchars($r['evaluacion']) : '<span class="no-data">—</span>' ?></td>
        <td><?= $r['evaluador'] ? htmlspecialchars($r['evaluador']) : '<span class="no-data">—</span>' ?></td>
        <td><?= $r['fecha_evaluacion'] ? htmlspecialchars($r['fecha_evaluacion']) : '<span class="no-data">—</span>' ?></td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  <hr>
  <p><small>Generado el <?= date('Y-m-d H:i:s') ?> por SGPP-UES</small></p>
</body>
</html>
<?php
$html = ob_get_clean();

// Descargar HTML
if (!empty($_GET['download']) && $_GET['download'] === 'html') {
    header('Content-Type: text/html; charset=utf-8');
    header('Content-Disposition: attachment; filename="reporte_practica_'.$practica_id.'.html"');
    echo $html;
    exit;
}

// Mostrar en pantalla
include __DIR__ . '/../../templates/docente/reporte_view.php';
