<?php require_once __DIR__ . '/../../src/includes/header.php'; ?>

<?php require __DIR__ . '/../../src/includes/flash_messages.php'; ?>

<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
  <div>
    <h1 class="h4 mb-1">Gestión de usuarios</h1>
    <p class="text-muted mb-0">Administra cuentas del sistema y asigna roles institucionales.</p>
  </div>
  <a class="btn btn-outline-secondary" href="/Grupo2_Etapa2Proy_SGPP-UES/public/index.php?page=home">Volver al panel</a>
</div>

<?php
$esEdicion = $usuarioEditar !== null;
$valNombreUsuario = htmlspecialchars($esEdicion ? $usuarioEditar['nombre_usuario'] : '', ENT_QUOTES, 'UTF-8');
$valNombreCompleto = htmlspecialchars($esEdicion ? ($usuarioEditar['docente_nombre'] ?? $usuarioEditar['estudiante_nombre'] ?? $usuarioEditar['admin_nombre'] ?? '') : '', ENT_QUOTES, 'UTF-8');
$valDepartamento = htmlspecialchars($esEdicion ? ($usuarioEditar['departamento'] ?? '') : '', ENT_QUOTES, 'UTF-8');
$valCarnet = htmlspecialchars($esEdicion ? ($usuarioEditar['carnet'] ?? '') : '', ENT_QUOTES, 'UTF-8');
$valCarrera = htmlspecialchars($esEdicion ? ($usuarioEditar['carrera'] ?? '') : '', ENT_QUOTES, 'UTF-8');
$rolSeleccionado = $esEdicion ? (int)$usuarioEditar['rol_id'] : 0;
$estaActivo = $esEdicion ? (int)$usuarioEditar['activo'] === 1 : true;
?>

<div class="row g-4">
  <div class="col-lg-4">
    <div class="card border-0 shadow-sm h-100">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-start">
          <div>
            <h2 class="h5 mb-1"><?php echo $esEdicion ? 'Editar usuario' : 'Nuevo usuario'; ?></h2>
            <p class="text-muted small mb-0"><?php echo $esEdicion ? 'Actualiza los datos y asigna el rol correspondiente.' : 'Completa los datos básicos y asigna el rol correspondiente.'; ?></p>
          </div>
          <?php if ($esEdicion): ?>
            <a class="btn btn-sm btn-outline-secondary" href="/Grupo2_Etapa2Proy_SGPP-UES/public/index.php?page=admin_usuarios">Cancelar</a>
          <?php endif; ?>
        </div>
        <form method="post" action="/Grupo2_Etapa2Proy_SGPP-UES/public/index.php?page=admin_usuarios" class="mt-3">
          <input type="hidden" name="action" value="<?php echo $esEdicion ? 'update' : 'create'; ?>">
          <?php if ($esEdicion): ?>
            <input type="hidden" name="id" value="<?= (int)$usuarioEditar['id'] ?>">
          <?php endif; ?>
          <div class="mb-3">
            <label class="form-label" for="nombre_usuario">Nombre de usuario</label>
            <input class="form-control" id="nombre_usuario" name="nombre_usuario" value="<?= $valNombreUsuario ?>" required>
          </div>
          <?php if ($esEdicion): ?>
            <div class="mb-3">
              <label class="form-label" for="nueva_contrasena">Nueva contraseña</label>
              <input class="form-control" id="nueva_contrasena" type="password" name="nueva_contrasena" placeholder="Dejar en blanco para mantener">
            </div>
          <?php else: ?>
            <div class="mb-3">
              <label class="form-label" for="contrasena">Contraseña</label>
              <input class="form-control" id="contrasena" type="password" name="contrasena" required>
            </div>
          <?php endif; ?>
          <div class="mb-3">
            <label class="form-label" for="rol_id">Rol</label>
            <select class="form-select" id="rol_id" name="rol_id" required>
              <option value="">Selecciona un rol</option>
              <?php foreach ($roles as $rol): ?>
                <option value="<?= (int)$rol['id'] ?>" <?= $rolSeleccionado === (int)$rol['id'] ? 'selected' : '' ?>><?= htmlspecialchars($rol['nombre']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label" for="nombre_completo">Nombre completo</label>
            <input class="form-control" id="nombre_completo" name="nombre_completo" value="<?= $valNombreCompleto ?>">
            <div class="form-text">Requerido para Docente/Estudiante/Admin.</div>
          </div>
          <div class="mb-3" id="docenteFields" style="display:none;">
            <label class="form-label" for="departamento">Departamento</label>
            <input class="form-control" id="departamento" name="departamento" value="<?= $valDepartamento ?>">
          </div>
          <div id="estudianteFields" style="display:none;">
            <div class="mb-3">
              <label class="form-label" for="carnet">Carnet</label>
              <input class="form-control" id="carnet" name="carnet" value="<?= $valCarnet ?>">
            </div>
            <div class="mb-3">
              <label class="form-label" for="carrera">Carrera</label>
              <input class="form-control" id="carrera" name="carrera" value="<?= $valCarrera ?>">
            </div>
          </div>
          <?php if ($esEdicion): ?>
            <div class="form-check form-switch mb-3">
              <input class="form-check-input" type="checkbox" role="switch" id="activo" name="activo" <?= $estaActivo ? 'checked' : '' ?>>
              <label class="form-check-label" for="activo">Cuenta activa</label>
            </div>
          <?php endif; ?>
          <div class="d-grid">
            <button class="btn btn-primary" type="submit"><?php echo $esEdicion ? 'Actualizar usuario' : 'Crear usuario'; ?></button>
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
                <th>ID</th>
                <th>Usuario</th>
                <th>Rol</th>
                <th>Nombre completo</th>
                <th>Detalle</th>
                <th>Estado</th>
                <th class="text-end">Acciones</th>
              </tr>
            </thead>
            <tbody>
              <?php if (empty($usuarios)): ?>
                <tr>
                  <td colspan="7" class="text-center py-4 text-muted">Aún no hay usuarios registrados.</td>
                </tr>
              <?php else: ?>
                <?php foreach ($usuarios as $usr): ?>
                  <tr>
                    <td><?= (int)$usr['id'] ?></td>
                    <td class="fw-semibold"><?= htmlspecialchars($usr['nombre_usuario']) ?></td>
                    <td><span class="badge bg-primary-subtle text-primary text-uppercase small"><?= htmlspecialchars($usr['rol_nombre']) ?></span></td>
                    <td><?= htmlspecialchars($usr['nombre_persona'] ?: '—') ?></td>
                    <td>
                      <?php if ((int)$usr['rol_id'] === 2): ?>
                        <small class="text-muted">Departamento: <?= htmlspecialchars($usr['departamento'] ?: 'N/D') ?></small>
                      <?php elseif ((int)$usr['rol_id'] === 3): ?>
                        <small class="text-muted">Carnet: <?= htmlspecialchars($usr['carnet'] ?: 'N/D') ?></small>
                      <?php else: ?>
                        <small class="text-muted">Administrador</small>
                      <?php endif; ?>
                    </td>
                    <td>
                      <?php if ((int)$usr['activo'] === 1): ?>
                        <span class="badge bg-success">Activo</span>
                      <?php else: ?>
                        <span class="badge bg-secondary">Inactivo</span>
                      <?php endif; ?>
                    </td>
                    <td class="text-end">
                      <a class="btn btn-sm btn-outline-primary" href="/Grupo2_Etapa2Proy_SGPP-UES/public/index.php?page=admin_usuarios&edit=<?= (int)$usr['id'] ?>">Editar</a>
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
    const rolSelect = document.getElementById('rol_id');
    const docenteFields = document.getElementById('docenteFields');
    const estudianteFields = document.getElementById('estudianteFields');

    const toggleFields = () => {
      const rol = parseInt(rolSelect.value, 10);
      docenteFields.style.display = rol === 2 ? 'block' : 'none';
      estudianteFields.style.display = rol === 3 ? 'block' : 'none';
    };

    rolSelect.addEventListener('change', toggleFields);
    toggleFields();
  });
</script>

<?php require_once __DIR__ . '/../../src/includes/footer.php'; ?>
