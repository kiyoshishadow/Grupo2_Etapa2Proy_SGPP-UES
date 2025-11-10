<?php require_once __DIR__ . '/../../src/includes/header.php'; ?>

<?php require __DIR__ . '/../../src/includes/flash_messages.php'; ?>

<?php
$practica_id = $_GET['practica_id'] ?? 0;
require_once __DIR__ . '/../../src/models/RegistroModel.php';
require_once __DIR__ . '/../../src/models/EvaluacionModel.php';
require_once __DIR__ . '/../../src/models/ResultadoModel.php';
require_once __DIR__ . '/../../config/db.php';

$registroModel = new RegistroModel($pdo);
$evaluacionModel = new EvaluacionModel($pdo);
$resultadoModel = new ResultadoModel($pdo);
$inscritos = $registroModel->porPractica($practica_id);
?>

<div class="d-flex justify-content-between align-items-center mb-3">
  <h2 class="h4 mb-0">Evaluar inscritos</h2>
  <div>
    <a class="btn btn-outline-secondary me-2" href="/Grupo2_Etapa2Proy_SGPP-UES/public/index.php?page=reporte&practica_id=<?= $practica_id ?>">Ver reporte</a>
    <a class="btn btn-outline-secondary" href="/Grupo2_Etapa2Proy_SGPP-UES/public/index.php?page=docente_practicas">Volver</a>
  </div>
</div>

<?php if (empty($inscritos)): ?>
  <div class="alert alert-info">No hay inscritos para esta práctica.</div>
<?php else: ?>
  <div class="table-responsive">
    <table class="table table-striped table-hover align-middle">
      <thead class="table-light">
        <tr>
          <th>Estudiante</th>
          <th>Fecha de inscripción</th>
          <th>Archivo</th>
          <th>Estado actual</th>
          <th>Evaluación</th>
          <th class="text-end">Acciones</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($inscritos as $ins): ?>
          <?php
          $resultados = $resultadoModel->porRegistro($ins['id']);
          $evaluaciones = $evaluacionModel->porRegistro($ins['id']);
          $ultimoResultado = $resultados[0] ?? null;
          $ultimaEvaluacion = $evaluaciones[0] ?? null;
          ?>
          <tr>
            <td><?= htmlspecialchars($ins['estudiante_nombre']) ?></td>
            <td><?= htmlspecialchars($ins['fecha_inscripcion']) ?></td>
            <td>
              <?php if ($ultimoResultado): ?>
                <a href="<?= htmlspecialchars($ultimoResultado['ruta_archivo']) ?>" target="_blank" class="btn btn-sm btn-outline-primary">Ver archivo</a>
              <?php else: ?>
                <span class="text-muted">—</span>
              <?php endif; ?>
            </td>
            <td>
              <?php
              $badgeClass = match(strtolower($ins['estado'])) {
                'aprobado' => 'bg-success',
                'rechazado' => 'bg-danger',
                'pendiente' => 'bg-warning text-dark',
                default => 'bg-secondary'
              };
              ?>
              <span class="badge <?= $badgeClass ?>"><?= htmlspecialchars($ins['estado']) ?></span>
            </td>
            <td>
              <?php if ($ultimaEvaluacion): ?>
                <small class="text-muted"><?= htmlspecialchars($ultimaEvaluacion['comentario']) ?></small>
              <?php else: ?>
                <span class="text-muted">—</span>
              <?php endif; ?>
            </td>
            <td class="text-end">
              <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#evalModal<?= $ins['id'] ?>">
                Evaluar
              </button>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
<?php endif; ?>

<?php foreach($inscritos as $ins): ?>
  <div class="modal fade" id="evalModal<?= $ins['id'] ?>" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <form method="post" action="/Grupo2_Etapa2Proy_SGPP-UES/public/index.php?page=evaluar">
          <div class="modal-header">
            <h5 class="modal-title">Evaluar inscripción</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <input type="hidden" name="registro_id" value="<?= $ins['id'] ?>">
            <input type="hidden" name="practica_id" value="<?= $practica_id ?>">
            <div class="mb-3">
              <label class="form-label">Estudiante</label>
              <input type="text" class="form-control" value="<?= htmlspecialchars($ins['estudiante_nombre']) ?>" readonly>
            </div>
            <div class="mb-3">
              <label class="form-label">Estado</label>
              <select class="form-select" name="estado" required>
                <option value="pendiente" <?= $ins['estado'] === 'pendiente' ? 'selected' : '' ?>>Pendiente</option>
                <option value="aprobado">Aprobado</option>
                <option value="rechazado">Rechazado</option>
              </select>
            </div>
            <div class="mb-3">
              <label class="form-label">Comentario</label>
              <textarea class="form-control" name="comentario" rows="3"></textarea>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-primary">Guardar evaluación</button>
          </div>
        </form>
      </div>
    </div>
  </div>
<?php endforeach; ?>

<?php require_once __DIR__ . '/../../src/includes/footer.php'; ?>
