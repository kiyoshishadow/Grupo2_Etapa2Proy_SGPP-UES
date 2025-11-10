<?php require_once __DIR__ . '/../../src/includes/header.php'; ?>
<?php require __DIR__ . '/../../src/includes/flash_messages.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
  <div>
    <h1 class="h4 mb-1">Expediente #<?= htmlspecialchars($expediente['id']) ?></h1>
    <p class="text-muted mb-0">Detalle del expediente de <?= htmlspecialchars($expediente['estudiante_nombre']) ?>.</p>
  </div>
  <a class="btn btn-outline-secondary" href="/Grupo2_Etapa2Proy_SGPP-UES/public/index.php?page=admin_expedientes">
    <i class="bi bi-arrow-left"></i> Volver
  </a>
</div>

<section class="mb-4">
  <div class="card border-0 shadow-sm">
    <div class="card-body">
      <div class="row g-4">
        <div class="col-md-4">
          <h2 class="h6 text-uppercase text-muted">Estudiante</h2>
          <p class="mb-1 fw-semibold"><?= htmlspecialchars($expediente['estudiante_nombre']) ?></p>
          <p class="mb-1 small text-muted">Carnet: <?= htmlspecialchars($expediente['estudiante_carnet']) ?></p>
          <p class="mb-0 small text-muted">Carrera: <?= htmlspecialchars($expediente['estudiante_carrera']) ?></p>
        </div>
        <div class="col-md-4">
          <h2 class="h6 text-uppercase text-muted">Empresa</h2>
          <p class="mb-1 fw-semibold"><?= htmlspecialchars($expediente['empresa_catalogo'] ?: ($expediente['empresa_nombre'] ?? 'Sin asignar')) ?></p>
          <?php if (!empty($expediente['empresa_direccion'])): ?>
            <p class="mb-0 small text-muted">Dirección: <?= htmlspecialchars($expediente['empresa_direccion']) ?></p>
          <?php endif; ?>
          <?php if (!empty($expediente['giro'])): ?>
            <p class="mb-0 small text-muted">Giro: <?= htmlspecialchars($expediente['giro']) ?></p>
          <?php endif; ?>
        </div>
        <div class="col-md-4">
          <h2 class="h6 text-uppercase text-muted">Supervisor externo</h2>
          <p class="mb-1 fw-semibold"><?= htmlspecialchars($expediente['supervisor_externo'] ?? 'N/D') ?></p>
          <?php if (!empty($expediente['telefono_supervisor'])): ?>
            <p class="mb-0 small text-muted">Tel.: <?= htmlspecialchars($expediente['telefono_supervisor']) ?></p>
          <?php endif; ?>
          <?php if (!empty($expediente['correo_supervisor'])): ?>
            <p class="mb-0 small text-muted">Correo: <?= htmlspecialchars($expediente['correo_supervisor']) ?></p>
          <?php endif; ?>
        </div>
      </div>
      <hr>
      <div class="row g-4">
        <div class="col-md-3">
          <h3 class="h6 text-uppercase text-muted">Periodo</h3>
          <p class="mb-0">
            <?= htmlspecialchars($expediente['fecha_inicio'] ?? '—') ?>
            -
            <?= htmlspecialchars($expediente['fecha_fin'] ?? '—') ?>
          </p>
        </div>
        <div class="col-md-3">
          <h3 class="h6 text-uppercase text-muted">Horas</h3>
          <p class="mb-0"><?= htmlspecialchars($expediente['horas_cumplidas'] ?? 0) ?> / <?= htmlspecialchars($expediente['horas_planificadas'] ?? '—') ?> h</p>
        </div>
        <div class="col-md-3">
          <h3 class="h6 text-uppercase text-muted">Estado</h3>
          <?php
            $estado = strtolower($expediente['estado']);
            $badge = match ($estado) {
              'activo' => 'bg-success',
              'en_pausa' => 'bg-warning text-dark',
              'finalizado' => 'bg-primary',
              'cancelado' => 'bg-danger',
              default => 'bg-secondary'
            };
          ?>
          <span class="badge <?= $badge ?> text-uppercase"><?= htmlspecialchars($estado) ?></span>
        </div>
        <div class="col-md-3">
          <h3 class="h6 text-uppercase text-muted">Observaciones</h3>
          <p class="mb-0"><?= nl2br(htmlspecialchars($expediente['observaciones'] ?? 'N/A')) ?></p>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="mb-4">
  <h2 class="h5 mb-3">Informes mensuales</h2>
  <div class="card border-0 shadow-sm">
    <div class="table-responsive">
      <table class="table table-hover mb-0">
        <thead class="table-light">
          <tr>
            <th>Periodo</th>
            <th>Archivo</th>
            <th>Comentario estudiante</th>
            <th>Estado revisión</th>
            <th>Revisor</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($informes)): ?>
            <tr>
              <td colspan="6" class="text-center py-3 text-muted">Sin informes registrados.</td>
            </tr>
          <?php else: ?>
            <?php foreach ($informes as $info): ?>
              <tr>
                <td><?= date('F Y', strtotime($info['periodo'])) ?></td>
                <td>
                  <?php if (!empty($info['ruta_archivo_normalizada'])): ?>
                    <a href="<?= htmlspecialchars($info['ruta_archivo_normalizada']) ?>" target="_blank" rel="noopener" class="btn btn-sm btn-outline-primary">
                      <i class="bi bi-box-arrow-up-right"></i> Abrir
                    </a>
                  <?php elseif (!empty($info['ruta_archivo'])): ?>
                    <span class="badge bg-warning text-dark">Archivo no encontrado</span>
                  <?php else: ?>
                    <span class="text-muted">—</span>
                  <?php endif; ?>
                </td>
                <td><?= htmlspecialchars($info['comentario_estudiante'] ?? '—') ?></td>
                <td>
                  <?php
                    $estadoInforme = strtolower($info['estado_revision']);
                    $badgeInfo = match ($estadoInforme) {
                      'aprobado' => 'bg-success',
                      'observado' => 'bg-warning text-dark',
                      'recibido' => 'bg-primary',
                      default => 'bg-secondary'
                    };
                  ?>
                  <span class="badge <?= $badgeInfo ?> text-uppercase"><?= htmlspecialchars($estadoInforme) ?></span>
                </td>
                <td><?= htmlspecialchars($info['revisor_nombre'] ?? '—') ?></td>
                <td>
                  <button class="btn btn-sm btn-outline-secondary" disabled>Editar (pendiente)</button>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</section>

<section class="mb-4">
  <h2 class="h5 mb-3">Bitácora de avance</h2>
  <div class="card border-0 shadow-sm">
    <div class="table-responsive">
      <table class="table table-hover mb-0">
        <thead class="table-light">
          <tr>
            <th>Fecha</th>
            <th>Tipo</th>
            <th>Descripción</th>
            <th>Porcentaje</th>
            <th>Registrado por</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($avances)): ?>
            <tr>
              <td colspan="5" class="text-center py-3 text-muted">Sin registros de avance.</td>
            </tr>
          <?php else: ?>
            <?php foreach ($avances as $av): ?>
              <tr>
                <td><?= htmlspecialchars($av['fecha_registro']) ?></td>
                <td><?= htmlspecialchars($av['tipo']) ?></td>
                <td><?= nl2br(htmlspecialchars($av['descripcion'] ?? '—')) ?></td>
                <td><?= htmlspecialchars($av['porcentaje_avance'] !== null ? $av['porcentaje_avance'] . '%' : '—') ?></td>
                <td><?= htmlspecialchars($av['registrado_por_nombre'] ?? '—') ?></td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</section>

<?php require_once __DIR__ . '/../../src/includes/footer.php'; ?>
