<?php require_once __DIR__ . '/../../src/includes/header.php'; ?>
<?php require __DIR__ . '/../../src/includes/flash_messages.php'; ?>
<?php
$old = $_POST ?? [];
?>
<div class="d-flex justify-content-between align-items-center mb-4">
  <div>
    <h1 class="h4 mb-1">Nuevo expediente</h1>
    <p class="text-muted mb-0">Registra la empresa, supervisor y periodo para el estudiante seleccionado.</p>
  </div>
  <a class="btn btn-outline-secondary" href="/Grupo2_Etapa2Proy_SGPP-UES/public/index.php?page=admin_expedientes">
    <i class="bi bi-arrow-left"></i> Volver
  </a>
</div>

<form class="card border-0 shadow-sm" method="post" action="">
  <div class="card-body">
    <div class="row g-4">
      <div class="col-md-6">
        <label class="form-label" for="estudiante_id">Estudiante *</label>
        <select class="form-select" id="estudiante_id" name="estudiante_id" required>
          <option value="">Seleccione un estudiante</option>
          <?php foreach ($estudiantes as $est): ?>
            <option value="<?= (int)$est['id'] ?>" <?= (($old['estudiante_id'] ?? '') == $est['id']) ? 'selected' : '' ?>>
              <?= htmlspecialchars($est['nombre_completo']) ?> (<?= htmlspecialchars($est['carnet']) ?>)
            </option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-md-6">
        <label class="form-label" for="empresa_id">Empresa (catálogo)</label>
        <select class="form-select" id="empresa_id" name="empresa_id">
          <option value="">Sin seleccionar / otra empresa</option>
          <?php foreach ($empresas as $empresa): ?>
            <option value="<?= (int)$empresa['id'] ?>" <?= (($old['empresa_id'] ?? '') == $empresa['id']) ? 'selected' : '' ?>>
              <?= htmlspecialchars($empresa['nombre']) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-md-6">
        <label class="form-label" for="empresa_nombre">Empresa (texto libre)</label>
        <input class="form-control" type="text" id="empresa_nombre" name="empresa_nombre" value="<?= htmlspecialchars($old['empresa_nombre'] ?? '') ?>" placeholder="Nombre comercial si no está en catálogo">
      </div>
      <div class="col-md-6">
        <label class="form-label" for="empresa_direccion">Dirección de la empresa</label>
        <input class="form-control" type="text" id="empresa_direccion" name="empresa_direccion" value="<?= htmlspecialchars($old['empresa_direccion'] ?? '') ?>">
      </div>
      <div class="col-md-4">
        <label class="form-label" for="supervisor_externo">Supervisor externo</label>
        <input class="form-control" type="text" id="supervisor_externo" name="supervisor_externo" value="<?= htmlspecialchars($old['supervisor_externo'] ?? '') ?>">
      </div>
      <div class="col-md-4">
        <label class="form-label" for="telefono_supervisor">Teléfono supervisor</label>
        <input class="form-control" type="text" id="telefono_supervisor" name="telefono_supervisor" value="<?= htmlspecialchars($old['telefono_supervisor'] ?? '') ?>">
      </div>
      <div class="col-md-4">
        <label class="form-label" for="correo_supervisor">Correo supervisor</label>
        <input class="form-control" type="email" id="correo_supervisor" name="correo_supervisor" value="<?= htmlspecialchars($old['correo_supervisor'] ?? '') ?>">
      </div>
      <div class="col-md-3">
        <label class="form-label" for="fecha_inicio">Fecha inicio</label>
        <input class="form-control" type="date" id="fecha_inicio" name="fecha_inicio" value="<?= htmlspecialchars($old['fecha_inicio'] ?? '') ?>">
      </div>
      <div class="col-md-3">
        <label class="form-label" for="fecha_fin">Fecha fin</label>
        <input class="form-control" type="date" id="fecha_fin" name="fecha_fin" value="<?= htmlspecialchars($old['fecha_fin'] ?? '') ?>">
      </div>
      <div class="col-md-3">
        <label class="form-label" for="horas_planificadas">Horas planificadas</label>
        <input class="form-control" type="number" min="0" id="horas_planificadas" name="horas_planificadas" value="<?= htmlspecialchars($old['horas_planificadas'] ?? '') ?>">
      </div>
      <div class="col-md-3">
        <label class="form-label" for="horas_cumplidas">Horas cumplidas</label>
        <input class="form-control" type="number" min="0" id="horas_cumplidas" name="horas_cumplidas" value="<?= htmlspecialchars($old['horas_cumplidas'] ?? '') ?>">
      </div>
      <div class="col-md-4">
        <label class="form-label" for="estado">Estado</label>
        <select class="form-select" id="estado" name="estado">
          <?php foreach (['planeado' => 'Planeado', 'activo' => 'Activo', 'en_pausa' => 'En pausa', 'finalizado' => 'Finalizado', 'cancelado' => 'Cancelado'] as $value => $label): ?>
            <option value="<?= $value ?>" <?= (($old['estado'] ?? 'planeado') === $value) ? 'selected' : '' ?>><?= $label ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-md-8">
        <label class="form-label" for="observaciones">Observaciones</label>
        <textarea class="form-control" id="observaciones" name="observaciones" rows="3" placeholder="Notas internas sobre el expediente"><?= htmlspecialchars($old['observaciones'] ?? '') ?></textarea>
      </div>
    </div>
  </div>
  <div class="card-footer bg-white d-flex justify-content-end gap-2">
    <a class="btn btn-outline-secondary" href="/Grupo2_Etapa2Proy_SGPP-UES/public/index.php?page=admin_expedientes">Cancelar</a>
    <button class="btn btn-primary" type="submit">Guardar expediente</button>
  </div>
</form>

<?php require_once __DIR__ . '/../../src/includes/footer.php'; ?>
