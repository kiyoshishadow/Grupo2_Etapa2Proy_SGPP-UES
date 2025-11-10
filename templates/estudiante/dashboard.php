<?php require_once __DIR__ . '/../../src/includes/header.php'; ?>

<section class="mb-4">
  <?php require __DIR__ . '/../../src/includes/flash_messages.php'; ?>
  <div class="row g-4 align-items-center">
    <div class="col-lg-8">
      <div class="card border-0 shadow-sm h-100">
        <div class="card-body">
          <h2 class="card-title h4">Hola, <?= htmlspecialchars($_SESSION['nombre_usuario']); ?> 👋</h2>
          <p class="card-text text-muted">Aquí puedes gestionar tu práctica profesional, subir informes y consultar tu progreso.</p>
          <div class="row g-3">
            <div class="col-sm-6">
              <a class="btn btn-primary w-100" href="/Grupo2_Etapa2Proy_SGPP-UES/public/index.php?page=mis_registros">
                Ver Mi Expediente
              </a>
            </div>
            <div class="col-sm-6">
              <a class="btn btn-success w-100" href="/Grupo2_Etapa2Proy_SGPP-UES/public/index.php?page=subir_informe">
                Subir Informe
              </a>
            </div>
            <div class="col-sm-6">
              <a class="btn btn-info w-100" href="/Grupo2_Etapa2Proy_SGPP-UES/public/index.php?page=ver_calificaciones">
                Ver Calificaciones
              </a>
            </div>
            <div class="col-sm-6">
              <a class="btn btn-warning w-100" href="/Grupo2_Etapa2Proy_SGPP-UES/public/index.php?page=ver_progreso">
                Ver Progreso
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-4">
      <div class="card border-0 shadow-sm h-100 bg-light">
        <div class="card-body">
          <h3 class="h6 text-uppercase text-muted">Recordatorios</h3>
          <ul class="list-unstyled small mb-0">
            <li class="mb-2"><span class="fw-semibold">1.</span> Revisa las fechas límite de cada práctica.</li>
            <li class="mb-2"><span class="fw-semibold">2.</span> Adjunta tus informes en PDF o ZIP.</li>
            <li><span class="fw-semibold">3.</span> Mantén tus datos de contacto actualizados.</li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</section>

<?php require_once __DIR__ . '/../../src/includes/footer.php'; ?>
