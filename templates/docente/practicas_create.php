<?php require_once __DIR__ . '/../../src/includes/header.php'; ?>

<?php require __DIR__ . '/../../src/includes/flash_messages.php'; ?>

<div class="card border-0 shadow-sm">
  <div class="card-body">
    <h2 class="card-title h4 mb-4">Nueva práctica</h2>
    <form method="post" action="/Grupo2_Etapa2Proy_SGPP-UES/public/index.php?page=docente_practicas&action=store" class="row g-3" id="formCreatePractica">
      <div class="col-12">
        <label class="form-label">Título</label>
        <input class="form-control" name="titulo" required>
      </div>
      <div class="col-12">
        <label class="form-label">Descripción</label>
        <textarea class="form-control" name="descripcion" rows="4" required></textarea>
      </div>
      <div class="col-sm-6 col-md-4">
        <label class="form-label">Fecha de inicio</label>
        <input class="form-control" type="date" name="fecha_inicio" required>
      </div>
      <div class="col-sm-6 col-md-4">
        <label class="form-label">Fecha límite</label>
        <input class="form-control" type="date" name="fecha_limite" required>
      </div>
      <div class="col-sm-6 col-md-4">
        <label class="form-label">Cupos (opcional)</label>
        <input class="form-control" type="number" name="cupo" min="1">
      </div>
      <div class="col-12 d-flex gap-2">
        <button type="submit" class="btn btn-primary">Guardar práctica</button>
        <a class="btn btn-outline-secondary" href="/Grupo2_Etapa2Proy_SGPP-UES/public/index.php?page=docente_practicas">Cancelar</a>
      </div>
    </form>
  </div>
</div>

<script>
document.getElementById('formCreatePractica').addEventListener('submit', function(e) {
  const inicio = new Date(this.fecha_inicio.value);
  const fin = new Date(this.fecha_limite.value);
  if (fin <= inicio) {
    e.preventDefault();
    alert('La fecha límite debe ser posterior a la fecha de inicio.');
    this.fecha_limite.focus();
  }
});
</script>

<?php require_once __DIR__ . '/../../src/includes/footer.php'; ?>
