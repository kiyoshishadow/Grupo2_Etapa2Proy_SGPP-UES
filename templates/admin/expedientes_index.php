<?php require_once __DIR__ . '/../../src/includes/header.php'; ?>
<?php require __DIR__ . '/../../src/includes/flash_messages.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
  <div>
    <h1 class="h4 mb-1">Expedientes de prácticas profesionales</h1>
    <p class="text-muted mb-0">Gestiona los expedientes por estudiante, empresa y estado.</p>
  </div>
  <a class="btn btn-primary" href="/Grupo2_Etapa2Proy_SGPP-UES/public/index.php?page=admin_expedientes&amp;action=create">
    <i class="bi bi-plus-circle"></i> Nuevo expediente
  </a>
</div>

<div class="card mb-4">
  <div class="card-body">
    <form class="row g-3" method="get" action="">
      <input type="hidden" name="page" value="admin_expedientes">
      <div class="col-md-3">
        <label class="form-label small text-uppercase text-muted" for="estado">Estado</label>
        <select class="form-select" id="estado" name="estado">
          <option value="">Todos</option>
          <?php foreach (['planeado' => 'Planeado', 'activo' => 'Activo', 'en_pausa' => 'En pausa', 'finalizado' => 'Finalizado', 'cancelado' => 'Cancelado'] as $value => $label): ?>
            <option value="<?= $value ?>" <?= ($filtros['estado'] ?? '') === $value ? 'selected' : '' ?>><?= $label ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-md-3">
        <label class="form-label small text-uppercase text-muted" for="empresa_id">Empresa</label>
        <select class="form-select" id="empresa_id" name="empresa_id">
          <option value="">Todas</option>
          <?php foreach ($empresas as $empresa): ?>
            <option value="<?= (int)$empresa['id'] ?>" <?= ($filtros['empresa_id'] ?? null) == $empresa['id'] ? 'selected' : '' ?>>
              <?= htmlspecialchars($empresa['nombre']) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-md-3">
        <label class="form-label small text-uppercase text-muted" for="carrera">Carrera</label>
        <input class="form-control" type="text" id="carrera" name="carrera" value="<?= htmlspecialchars($filtros['carrera'] ?? '') ?>" placeholder="Ej. Ing. Sistemas">
      </div>
      <div class="col-md-3">
        <label class="form-label small text-uppercase text-muted" for="fecha_inicio_desde">Inicio desde</label>
        <input class="form-control" type="date" id="fecha_inicio_desde" name="fecha_inicio_desde" value="<?= htmlspecialchars($filtros['fecha_inicio_desde'] ?? '') ?>">
      </div>
      <div class="col-md-3">
        <label class="form-label small text-uppercase text-muted" for="fecha_inicio_hasta">Inicio hasta</label>
        <input class="form-control" type="date" id="fecha_inicio_hasta" name="fecha_inicio_hasta" value="<?= htmlspecialchars($filtros['fecha_inicio_hasta'] ?? '') ?>">
      </div>
      <div class="col-md-12 d-flex gap-2">
        <button class="btn btn-outline-secondary" type="submit">
          <i class="bi bi-search"></i> Filtrar
        </button>
        <a class="btn btn-link" href="/Grupo2_Etapa2Proy_SGPP-UES/public/index.php?page=admin_expedientes">Limpiar</a>
      </div>
    </form>
  </div>
</div>

<div class="card">
  <div class="table-responsive">
    <table class="table table-hover align-middle mb-0">
      <thead class="table-light">
        <tr>
          <th>Estudiante</th>
          <th>Empresa</th>
          <th>Supervisor</th>
          <th>Periodo</th>
          <th>Horas</th>
          <th>Estado</th>
          <th class="text-end">Acciones</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($expedientes)): ?>
          <tr>
            <td colspan="7" class="text-center py-4 text-muted">No se encontraron expedientes con los filtros seleccionados.</td>
          </tr>
        <?php else: ?>
          <?php foreach ($expedientes as $exp): ?>
            <tr>
              <td>
                <div class="fw-semibold"><?= htmlspecialchars($exp['estudiante_nombre'] ?? 'Estudiante N/D') ?></div>
                <div class="small text-muted">Carnet: <?= htmlspecialchars($exp['estudiante_carnet'] ?? '—') ?></div>
                <div class="small text-muted">Carrera: <?= htmlspecialchars($exp['estudiante_carrera'] ?? '—') ?></div>
              </td>
              <td class="text-nowrap">
                <?= htmlspecialchars($exp['empresa_catalogo'] ?: ($exp['empresa_nombre'] ?? 'Sin asignar')) ?>
              </td>
              <td class="text-nowrap">
                <?= htmlspecialchars($exp['supervisor_externo'] ?? 'N/D') ?>
                <?php if (!empty($exp['telefono_supervisor'])): ?>
                  <div class="small text-muted">Tel.: <?= htmlspecialchars($exp['telefono_supervisor']) ?></div>
                <?php endif; ?>
                <?php if (!empty($exp['correo_supervisor'])): ?>
                  <div class="small text-muted">Correo: <?= htmlspecialchars($exp['correo_supervisor']) ?></div>
                <?php endif; ?>
              </td>
              <td>
                <small class="text-muted">
                  <?= htmlspecialchars($exp['fecha_inicio'] ?? '—') ?>
                  –
                  <?= htmlspecialchars($exp['fecha_fin'] ?? '—') ?>
                </small>
              </td>
              <td class="text-nowrap">
                <div><?= htmlspecialchars($exp['horas_cumplidas'] ?? 0) ?> / <?= htmlspecialchars($exp['horas_planificadas'] ?? '—') ?>h</div>
              </td>
              <td>
                <?php
                  $estado = strtolower($exp['estado']);
                  $badge = match ($estado) {
                    'activo' => 'bg-success',
                    'en_pausa' => 'bg-warning text-dark',
                    'finalizado' => 'bg-primary',
                    'cancelado' => 'bg-danger',
                    default => 'bg-secondary'
                  };
                ?>
                <span class="badge <?= $badge ?> text-uppercase"><?= htmlspecialchars($estado) ?></span>
              </td>
              <td class="text-end">
                <a class="btn btn-sm btn-outline-primary" href="/Grupo2_Etapa2Proy_SGPP-UES/public/index.php?page=admin_expedientes&amp;action=detalle&amp;id=<?= (int)$exp['id'] ?>">
                  <i class="bi bi-eye"></i> Ver
                </a>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?php require_once __DIR__ . '/../../src/includes/footer.php'; ?>
