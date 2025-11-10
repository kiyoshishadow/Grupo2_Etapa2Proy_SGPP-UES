<?php require_once __DIR__ . '/../../src/includes/header.php'; ?>

<?php require __DIR__ . '/../../src/includes/flash_messages.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-3">
  <h2 class="h4 mb-0">Prácticas disponibles</h2>
  <span class="text-muted small">Selecciona una práctica para postularte</span>
</div>

<?php if (empty($lista)): ?>
  <div class="alert alert-info">No hay prácticas disponibles por el momento.</div>
<?php else: ?>
  <div class="table-responsive">
    <table class="table table-striped table-hover align-middle">
      <thead class="table-light">
        <tr>
          <th>Título</th>
          <th>Docente</th>
          <th>Fecha límite</th>
          <th class="text-end">Acciones</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($lista as $p): ?>
          <tr>
            <td><?= htmlspecialchars($p['titulo']) ?></td>
            <td><?= htmlspecialchars($p['docente']) ?></td>
            <td><?= htmlspecialchars($p['fecha_limite']) ?></td>
            <td class="text-end">
              <a class="btn btn-sm btn-primary" href="/Grupo2_Etapa2Proy_SGPP-UES/public/index.php?page=inscribir&practica_id=<?= $p['id'] ?>">Inscribirme</a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
<?php endif; ?>

<?php require_once __DIR__ . '/../../src/includes/footer.php'; ?>
