<?php require_once __DIR__ . '/../../src/includes/header.php'; ?>

<?php require __DIR__ . '/../../src/includes/flash_messages.php'; ?>

<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
  <div>
    <h1 class="h4 mb-1">Gestión de prácticas</h1>
    <p class="text-muted mb-0">Crea nuevas prácticas y supervisa su estado general.</p>
  </div>
  <a class="btn btn-outline-secondary" href="/Grupo2_Etapa2Proy_SGPP-UES/public/index.php?page=home">Volver al panel</a>
</div>

<?php
$esEdicion = $practicaEditar !== null;
$valTitulo = htmlspecialchars($esEdicion ? $practicaEditar['titulo'] : '', ENT_QUOTES, 'UTF-8');
$valDescripcion = htmlspecialchars($esEdicion ? $practicaEditar['descripcion'] : '', ENT_QUOTES, 'UTF-8');
$valFechaInicio = $esEdicion && !empty($practicaEditar['fecha_inicio']) ? substr($practicaEditar['fecha_inicio'], 0, 10) : '';
$valFechaFin = $esEdicion && !empty($practicaEditar['fecha_fin']) ? substr($practicaEditar['fecha_fin'], 0, 10) : '';
$valCupo = $esEdicion && $practicaEditar['cupo'] !== null ? (int)$practicaEditar['cupo'] : '';
$valDocenteId = $esEdicion ? (int)$practicaEditar['docente_id'] : 0;
$valEstado = $esEdicion ? $practicaEditar['estado'] : 'activa';
?>

<div class="row g-4">
  <div class="col-lg-4">
    <div class="card border-0 shadow-sm h-100">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-start">
          <div>
            <h2 class="h5 mb-1"><?= $esEdicion ? 'Editar práctica' : 'Nueva práctica' ?></h2>
            <p class="text-muted small mb-0">Asigna título, fechas y docente responsable.</p>
          </div>
          <?php if ($esEdicion): ?>
            <a class="btn btn-sm btn-outline-secondary" href="/Grupo2_Etapa2Proy_SGPP-UES/public/index.php?page=admin_practicas">Cancelar</a>
          <?php endif; ?>
        </div>
        <form method="post" action="/Grupo2_Etapa2Proy_SGPP-UES/public/index.php?page=admin_practicas" class="mt-3">
          <input type="hidden" name="action" value="<?= $esEdicion ? 'update' : 'create' ?>">
          <?php if ($esEdicion): ?>
            <input type="hidden" name="id" value="<?= (int)$practicaEditar['id'] ?>">
          <?php endif; ?>
          <div class="mb-3">
            <label class="form-label" for="titulo">Título</label>
            <input class="form-control" id="titulo" name="titulo" value="<?= $valTitulo ?>" required>
          </div>
          <div class="mb-3">
            <label class="form-label" for="descripcion">Descripción</label>
            <textarea class="form-control" id="descripcion" name="descripcion" rows="3" required><?= $valDescripcion ?></textarea>
          </div>
          <div class="row g-3">
            <div class="col-6">
              <label class="form-label" for="fecha_inicio">Fecha inicio</label>
              <input class="form-control" id="fecha_inicio" type="date" name="fecha_inicio" value="<?= $valFechaInicio ?>" required>
            </div>
            <div class="col-6">
              <label class="form-label" for="fecha_fin">Fecha fin</label>
              <input class="form-control" id="fecha_fin" type="date" name="fecha_fin" value="<?= $valFechaFin ?>" required>
            </div>
          </div>
          <div class="mb-3">
            <label class="form-label" for="cupo">Cupo (opcional)</label>
            <input class="form-control" id="cupo" type="number" min="0" name="cupo" value="<?= $valCupo ?>" placeholder="10">
          </div>
          <div class="mb-3">
            <label class="form-label" for="estado">Estado</label>
            <select class="form-select" id="estado" name="estado">
              <option value="activa" <?= $valEstado === 'activa' ? 'selected' : '' ?>>Activa</option>
              <option value="cerrada" <?= $valEstado === 'cerrada' ? 'selected' : '' ?>>Cerrada</option>
            </select>
          </div>
          <div class="mb-4">
            <label class="form-label" for="docente_id">Docente responsable</label>
            <select class="form-select" id="docente_id" name="docente_id" required>
              <option value="">Selecciona un docente</option>
              <?php foreach ($docentes as $doc): ?>
                <option value="<?= (int)$doc['id'] ?>" <?= $valDocenteId === (int)$doc['id'] ? 'selected' : '' ?>><?= htmlspecialchars($doc['nombre_completo']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="d-grid">
            <button class="btn btn-primary" type="submit"><?= $esEdicion ? 'Actualizar práctica' : 'Crear práctica' ?></button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <div class="col-lg-8">
    <div class="card border-0 shadow-sm">
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
              <tr>
                <th>Título</th>
                <th>Docente</th>
                <th>Fechas</th>
                <th>Cupo</th>
                <th>Estado</th>
                <th>Inscritos</th>
                <th>Finalizados</th>
                <th class="text-end">Acciones</th>
              </tr>
            </thead>
            <tbody>
              <?php if (empty($practicas)): ?>
                <tr>
                  <td colspan="8" class="text-center py-4 text-muted">Aún no hay prácticas registradas.</td>
                </tr>
              <?php else: ?>
                <?php foreach ($practicas as $pr): ?>
                  <?php
                    $badgeClass = $pr['estado'] === 'activa' ? 'bg-success' : 'bg-secondary';
                  ?>
                  <tr>
                    <td class="fw-semibold"><?= htmlspecialchars($pr['titulo']) ?></td>
                    <td><?= htmlspecialchars($pr['docente_nombre'] ?? 'Sin asignar') ?></td>
                    <td>
                      <small class="text-muted">Ini: <?= htmlspecialchars($pr['fecha_inicio'] ?? 'N/D') ?></small><br>
                      <small class="text-muted">Fin: <?= htmlspecialchars($pr['fecha_fin'] ?? 'N/D') ?></small>
                    </td>
                    <td><?= $pr['cupo'] !== null ? (int)$pr['cupo'] : '—' ?></td>
                    <td><span class="badge <?= $badgeClass ?> text-uppercase"><?= htmlspecialchars($pr['estado']) ?></span></td>
                    <td><?= (int)$pr['total_inscritos'] ?></td>
                    <td><?= (int)$pr['total_finalizados'] ?></td>
                    <td class="text-end">
                      <a class="btn btn-sm btn-outline-primary" href="/Grupo2_Etapa2Proy_SGPP-UES/public/index.php?page=admin_practicas&edit=<?= (int)$pr['id'] ?>">Editar</a>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', () => {
    const form = document.querySelector('form');
    const fechaInicio = document.getElementById('fecha_inicio');
    const fechaFin = document.getElementById('fecha_fin');

    form.addEventListener('submit', (event) => {
      if (fechaInicio.value && fechaFin.value && fechaInicio.value > fechaFin.value) {
        event.preventDefault();
        alert('La fecha fin debe ser posterior a la fecha inicio.');
      }
    });
  });
</script>

<?php require_once __DIR__ . '/../../src/includes/footer.php'; ?>
