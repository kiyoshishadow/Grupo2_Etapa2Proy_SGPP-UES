<?php require_once __DIR__ . '/../../src/includes/header.php'; ?>

<?php require __DIR__ . '/../../src/includes/flash_messages.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-3">
  <h2 class="h4 mb-0">Reporte de práctica</h2>
  <div>
    <a class="btn btn-outline-secondary me-2" href="/Grupo2_Etapa2Proy_SGPP-UES/public/index.php?page=docente_practicas">Volver</a>
    <a class="btn btn-success" href="?download=html" download>Descargar HTML</a>
  </div>
</div>

<div class="border p-3 bg-white">
  <?= $html ?>
</div>

<?php require_once __DIR__ . '/../../src/includes/footer.php'; ?>
