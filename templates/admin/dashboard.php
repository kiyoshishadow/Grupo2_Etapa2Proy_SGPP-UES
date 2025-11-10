<?php require_once __DIR__ . '/../../src/includes/header.php'; ?>

<section class="mb-4">
  <?php require __DIR__ . '/../../src/includes/flash_messages.php'; ?>

  <div class="row g-4 align-items-center">
    <div class="col-lg-8">
      <div class="card border-0 shadow-sm h-100">
        <div class="card-body">
          <h2 class="card-title h4">Hola, <?= htmlspecialchars($_SESSION['nombre_usuario']); ?> 👋</h2>
          <p class="card-text text-muted">Desde aquí puedes gestionar usuarios, roles y ver reportes generales del sistema.</p>
          <div class="row g-3">
            <div class="col-sm-6">
              <a class="btn btn-primary w-100" href="/Grupo2_Etapa2Proy_SGPP-UES/public/index.php?page=admin_usuarios">
                Gestionar usuarios
              </a>
            </div>
            <div class="col-sm-6">
              <a class="btn btn-outline-secondary w-100" href="/Grupo2_Etapa2Proy_SGPP-UES/public/index.php?page=admin_expedientes">
                Gestionar expedientes
              </a>
            </div>
            <div class="col-sm-6">
              <a class="btn btn-info w-100" href="/Grupo2_Etapa2Proy_SGPP-UES/public/index.php?page=admin_reportes_expedientes">
                Reporte de Activos
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-4">
      <div class="card border-0 shadow-sm h-100 bg-light">
        <div class="card-body">
          <h3 class="h6 text-uppercase text-muted">Panel de control</h3>
          <ul class="list-unstyled small mb-0">
            <li class="mb-2"><span class="fw-semibold">1.</span> Administra cuentas y roles de usuarios.</li>
            <li class="mb-2"><span class="fw-semibold">2.</span> Supervisa prácticas activas y cerradas.</li>
            <li><span class="fw-semibold">3.</span> Genera reportes consolidados.</li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</section>

<?php require_once __DIR__ . '/../../src/includes/footer.php'; ?>

