<?php require_once __DIR__ . '/../../src/includes/header.php'; ?>

<?php require __DIR__ . '/../../src/includes/flash_messages.php'; ?>

<section class="page-hero">
  <div class="d-flex flex-column flex-md-row justify-content-between align-items-start gap-3">
    <div>
      <h1 class="h4 mb-1">Mi Expediente de Práctica Profesional</h1>
      <p class="text-muted mb-0">Consulta el estado de tu práctica y gestiona tus informes mensuales.</p>
    </div>
  </div>
</section>

<?php if (empty($expediente)): ?>
  <div class="card card-ues">
    <div class="card-body text-center py-5">
      <h3 class="h5 mb-2">Aún no tienes un expediente asignado</h3>
      <p class="text-muted mb-4">Contacta al administrador o a tu docente supervisor para que te asignen a una práctica profesional.</p>
    </div>
  </div>
<?php else: ?>
  <?php
    $estado = strtolower($expediente['estado'] ?? '');
    $badgeClass = match($estado) {
      'activo' => 'bg-success',
      'finalizado' => 'bg-primary',
      'cancelado' => 'bg-danger',
      'planeado' => 'bg-info text-dark',
      default => 'bg-secondary'
    };
  ?>
  <div class="card card-ues mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h2 class="h5 mb-0">Detalles de la Práctica</h2>
      <span class="badge <?= $badgeClass ?> fs-6"><?= htmlspecialchars(ucfirst($expediente['estado'])) ?></span>
    </div>
    <div class="card-body">
      <div class="row g-3">
        <div class="col-md-6 col-lg-4">
          <strong class="text-muted d-block">Empresa</strong>
          <span><?= htmlspecialchars($expediente['empresa_nombre']) ?></span>
        </div>
        <div class="col-md-6 col-lg-4">
          <strong class="text-muted d-block">Supervisor Externo</strong>
          <span><?= htmlspecialchars($expediente['supervisor_externo']) ?></span>
        </div>
        <div class="col-md-6 col-lg-4">
          <strong class="text-muted d-block">Contacto del Supervisor</strong>
          <span><?= htmlspecialchars($expediente['correo_supervisor']) ?></span>
        </div>
        <div class="col-md-6 col-lg-4">
          <strong class="text-muted d-block">Fecha de Inicio</strong>
          <span><?= htmlspecialchars(date('d/m/Y', strtotime($expediente['fecha_inicio']))) ?></span>
        </div>
        <div class="col-md-6 col-lg-4">
          <strong class="text-muted d-block">Fecha de Finalización</strong>
          <span><?= htmlspecialchars(date('d/m/Y', strtotime($expediente['fecha_fin']))) ?></span>
        </div>
        <div class="col-md-6 col-lg-4">
          <strong class="text-muted d-block">Horas Planificadas</strong>
          <span><?= htmlspecialchars($expediente['horas_planificadas']) ?> horas</span>
        </div>
      </div>
    </div>
  </div>

  <div class="card card-ues">
    <div class="card-header">
      <h3 class="h5 mb-0">Gestión de Informes Mensuales</h3>
    </div>
    <div class="card-body">
      <form class="mb-4 p-3 border rounded bg-light" method="post" enctype="multipart/form-data" action="/Grupo2_Etapa2Proy_SGPP-UES/public/index.php?page=subir_informe">
        <h4 class="h6">Subir Nuevo Informe</h4>
        <input type="hidden" name="expediente_id" value="<?= $expediente['id'] ?>">
        <div class="row g-3">
          <div class="col-md-4">
            <label for="periodo" class="form-label">Periodo (Mes y Año)</label>
            <input type="month" class="form-control" id="periodo" name="periodo" required>
          </div>
          <div class="col-md-5">
            <label for="archivo" class="form-label">Archivo (PDF, ZIP, DOCX)</label>
            <input class="form-control" type="file" id="archivo" name="archivo" required>
          </div>
          <div class="col-md-3 d-flex align-items-end">
            <button class="btn btn-primary w-100" type="submit">Subir Informe</button>
          </div>
        </div>
      </form>

      <h4 class="h6 mt-4">Historial de Entregas</h4>
      <?php if (empty($informes)): ?>
        <div class="alert alert-info">No has subido ningún informe todavía.</div>
      <?php else: ?>
        <div class="table-responsive">
          <table class="table table-striped table-hover">
            <thead>
              <tr>
                <th>Periodo</th>
                <th>Fecha de Subida</th>
                <th>Estado Revisión</th>
                <th>Acciones</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($informes as $informe): ?>
                <tr>
                  <td><?= htmlspecialchars(date('F Y', strtotime($informe['periodo'] . '-01'))) ?></td>
                  <td><?= htmlspecialchars(date('d/m/Y H:i', strtotime($informe['fecha_subida']))) ?></td>
                  <td>
                    <?php
                      $estadoRev = strtolower($informe['estado_revision'] ?? 'pendiente');
                      $badgeRevClass = match($estadoRev) {
                        'aprobado' => 'bg-success',
                        'observado' => 'bg-warning text-dark',
                        default => 'bg-secondary'
                      };
                    ?>
                    <span class="badge <?= $badgeRevClass ?>"><?= htmlspecialchars(ucfirst($informe['estado_revision'])) ?></span>
                  </td>
                  <td>
                    <a href="/Grupo2_Etapa2Proy_SGPP-UES/public/index.php?page=descargar_archivo&archivo=<?= urlencode(basename($informe['ruta_archivo'])) ?>&tipo=informe" class="btn btn-sm btn-outline-primary" target="_blank" rel="noopener noreferrer" title="Ver archivo">
                      Ver Archivo
                    </a>
                    <!-- Aquí se podrían agregar botones para editar o eliminar si la lógica de negocio lo permite -->
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php endif; ?>
    </div>
  </div>

<?php endif; ?>

<?php require_once __DIR__ . '/../../src/includes/footer.php'; ?>
