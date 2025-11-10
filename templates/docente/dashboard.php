<?php require_once __DIR__ . '/../../src/includes/header.php'; ?>

<section class="mb-4">
  <?php require __DIR__ . '/../../src/includes/flash_messages.php'; ?>

  <div class="row g-4 align-items-center">
    <div class="col-lg-8">
      <div class="card border-0 shadow-sm h-100">
        <div class="card-body">
          <h2 class="card-title h4">Hola, <?= htmlspecialchars($_SESSION['nombre_usuario']); ?> 👋</h2>
          <p class="card-text text-muted">Gestiona la supervisión de estudiantes en práctica profesional, evalúa expedientes y genera reportes de avance.</p>
          <div class="row g-3">
            <div class="col-sm-6">
              <a class="btn btn-primary w-100" href="/Grupo2_Etapa2Proy_SGPP-UES/public/index.php?page=evaluar">
                <i class="fas fa-clipboard-check me-2"></i>Evaluar Expedientes
              </a>
            </div>
            <div class="col-sm-6">
              <a class="btn btn-outline-secondary w-100" href="/Grupo2_Etapa2Proy_SGPP-UES/public/index.php?page=docente_reportes">
                <i class="fas fa-chart-bar me-2"></i>Ver Reportes
              </a>
            </div>
            <div class="col-sm-6">
              <a class="btn btn-info w-100" href="/Grupo2_Etapa2Proy_SGPP-UES/public/index.php?page=docente_informes">
                <i class="fas fa-file-alt me-2"></i>Revisar Informes Mensuales
              </a>
            </div>
            <div class="col-sm-6">
              <a class="btn btn-success w-100" href="/Grupo2_Etapa2Proy_SGPP-UES/public/index.php?page=docente_avances">
                <i class="fas fa-tasks me-2"></i>Seguimiento de Avances
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-4">
      <div class="card border-0 shadow-sm h-100 bg-light">
        <div class="card-body">
          <h3 class="h6 text-uppercase text-muted">Panel de Supervisión</h3>
          <ul class="list-unstyled small mb-0">
            <li class="mb-2"><span class="fw-semibold">1.</span> Evalúa expedientes de estudiantes asignados.</li>
            <li class="mb-2"><span class="fw-semibold">2.</span> Revisa informes mensuales de progreso.</li>
            <li class="mb-2"><span class="fw-semibold">3.</span> Controla el avance de las prácticas.</li>
            <li><span class="fw-semibold">4.</span> Genera reportes por carrera y empresa.</li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Estadísticas Rápidas -->
<section class="mb-4">
  <div class="row g-4">
    <div class="col-md-3">
      <div class="card border-0 shadow-sm">
        <div class="card-body text-center">
          <div class="text-primary mb-2">
            <i class="fas fa-users fa-2x"></i>
          </div>
          <h5 class="card-title">Estudiantes Activos</h5>
          <p class="card-text display-6 fw-bold"><?= number_format($estadisticas['estudiantes_activos']) ?></p>
          <small class="text-muted">En práctica actualmente</small>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card border-0 shadow-sm">
        <div class="card-body text-center">
          <div class="text-success mb-2">
            <i class="fas fa-check-circle fa-2x"></i>
          </div>
          <h5 class="card-title">Expedientes Evaluados</h5>
          <p class="card-text display-6 fw-bold"><?= number_format($estadisticas['expedientes_evaluados_mes']) ?></p>
          <small class="text-muted">Este mes</small>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card border-0 shadow-sm">
        <div class="card-body text-center">
          <div class="text-warning mb-2">
            <i class="fas fa-file-alt fa-2x"></i>
          </div>
          <h5 class="card-title">Informes Pendientes</h5>
          <p class="card-text display-6 fw-bold"><?= number_format($estadisticas['informes_pendientes']) ?></p>
          <small class="text-muted">Por revisar</small>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card border-0 shadow-sm">
        <div class="card-body text-center">
          <div class="text-info mb-2">
            <i class="fas fa-building fa-2x"></i>
          </div>
          <h5 class="card-title">Empresas Asociadas</h5>
          <p class="card-text display-6 fw-bold"><?= number_format($estadisticas['empresas_con_estudiantes']) ?></p>
          <small class="text-muted">Con estudiantes activos</small>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Actividad Reciente -->
<section class="mb-4">
  <div class="card border-0 shadow-sm">
    <div class="card-header py-3">
      <h6 class="m-0 font-weight-bold text-primary">
        <i class="fas fa-clock me-2"></i>Actividad Reciente
      </h6>
    </div>
    <div class="card-body">
      <div class="list-group list-group-flush">
        <?php if (!empty($actividad_reciente['informes_recientes'])): ?>
          <?php foreach (array_slice($actividad_reciente['informes_recientes'], 0, 2) as $informe): ?>
            <div class="list-group-item d-flex justify-content-between align-items-center">
              <div>
                <h6 class="mb-1">Nuevo informe mensual recibido</h6>
                <p class="mb-1 text-muted small"><?= htmlspecialchars($informe['estudiante_nombre']) ?> - <?= htmlspecialchars($informe['estudiante_carrera']) ?></p>
                <small>Hace <?= tiempoTranscurrido($informe['fecha_subida']) ?></small>
              </div>
              <span class="badge bg-<?= $informe['estado_revision'] === 'pendiente' ? 'warning' : 'success' ?> rounded-pill">
                <?= ucfirst($informe['estado_revision']) ?>
              </span>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>

        <?php if (!empty($actividad_reciente['evaluaciones_recientes'])): ?>
          <?php foreach (array_slice($actividad_reciente['evaluaciones_recientes'], 0, 1) as $evaluacion): ?>
            <div class="list-group-item d-flex justify-content-between align-items-center">
              <div>
                <h6 class="mb-1">Expediente evaluado</h6>
                <p class="mb-1 text-muted small"><?= htmlspecialchars($evaluacion['estudiante_nombre']) ?> - <?= htmlspecialchars($evaluacion['estudiante_carrera']) ?></p>
                <small>Hace <?= tiempoTranscurrido($evaluacion['fecha_evaluacion']) ?></small>
              </div>
              <span class="badge bg-success rounded-pill">Completado</span>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>

        <?php if (!empty($actividad_reciente['expedientes_nuevos'])): ?>
          <?php foreach (array_slice($actividad_reciente['expedientes_nuevos'], 0, 1) as $expediente): ?>
            <div class="list-group-item d-flex justify-content-between align-items-center">
              <div>
                <h6 class="mb-1">Nuevo estudiante asignado</h6>
                <p class="mb-1 text-muted small"><?= htmlspecialchars($expediente['estudiante_nombre']) ?> - <?= htmlspecialchars($expediente['estudiante_carrera']) ?></p>
                <small>Hace <?= tiempoTranscurrido($expediente['creado_en']) ?></small>
              </div>
              <span class="badge bg-info rounded-pill">Nuevo</span>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>

        <?php if (empty($actividad_reciente['informes_recientes']) && empty($actividad_reciente['evaluaciones_recientes']) && empty($actividad_reciente['expedientes_nuevos'])): ?>
          <div class="list-group-item">
            <p class="text-muted mb-0">No hay actividad reciente registrada.</p>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</section>

<!-- Empresas con más estudiantes -->
<section class="mb-4">
  <div class="card border-0 shadow-sm">
    <div class="card-header py-3">
      <h6 class="m-0 font-weight-bold text-primary">
        <i class="fas fa-building me-2"></i>Empresas con Estudiantes Activos
      </h6>
    </div>
    <div class="card-body">
      <?php if (!empty($empresas_activas)): ?>
        <div class="table-responsive">
          <table class="table table-hover">
            <thead>
              <tr>
                <th>Empresa</th>
                <th>Estudiantes Activos</th>
                <th>Carreras</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($empresas_activas as $empresa): ?>
                <tr>
                  <td><strong><?= htmlspecialchars($empresa['empresa_nombre']) ?></strong></td>
                  <td><span class="badge bg-primary"><?= number_format($empresa['total_estudiantes']) ?></span></td>
                  <td><?= htmlspecialchars($empresa['carreras'] ?: 'No especificado') ?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php else: ?>
        <div class="alert alert-info">
          <i class="fas fa-info-circle me-2"></i>
          No hay empresas con estudiantes activos actualmente.
        </div>
      <?php endif; ?>
    </div>
  </div>
</section>

<?php 
// Función helper para calcular tiempo transcurrido
function tiempoTranscurrido($fecha) {
    if (!$fecha) return 'Fecha desconocida';
    
    $datetime = new DateTime($fecha);
    $now = new DateTime();
    $interval = $now->diff($datetime);
    
    if ($interval->days == 0) {
        if ($interval->h == 0) {
            return $interval->i . ' minutos';
        }
        return $interval->h . ' horas';
    } elseif ($interval->days == 1) {
        return 'Ayer';
    } elseif ($interval->days < 7) {
        return $interval->days . ' días';
    } elseif ($interval->days < 30) {
        $semanas = floor($interval->days / 7);
        return $semanas . ' semana' . ($semanas > 1 ? 's' : '');
    } else {
        return $interval->days . ' días';
    }
}
?>

<?php require_once __DIR__ . '/../../src/includes/footer.php'; ?>

