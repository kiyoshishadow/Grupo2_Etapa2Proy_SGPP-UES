<?php require_once __DIR__ . '/../../src/includes/header.php'; ?>

<?php require __DIR__ . '/../../src/includes/flash_messages.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-3">
  <h2 class="h4 mb-0">Mis prácticas</h2>
  <a class="btn btn-primary" href="/Grupo2_Etapa2Proy_SGPP-UES/public/index.php?page=docente_practicas&action=create">Nueva práctica</a>
</div>

<?php if (empty($practicas)): ?>
  <div class="alert alert-info">No tienes prácticas registradas aún.</div>
<?php else: ?>
  <div class="table-responsive">
    <table class="table table-hover align-middle">
      <thead class="table-light">
        <tr>
          <th>Título</th>
          <th>Fecha límite</th>
          <th class="text-end">Acciones</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($practicas as $p): ?>
          <tr>
            <td><?= htmlspecialchars($p['titulo']) ?></td>
            <td><?= htmlspecialchars($p['fecha_limite']) ?></td>
            <td class="text-end">
              <div class="btn-group btn-group-sm" role="group">
                <a class="btn btn-outline-secondary" href="/Grupo2_Etapa2Proy_SGPP-UES/public/index.php?page=docente_practicas&action=edit&id=<?= $p['id'] ?>">Editar</a>
                <a class="btn btn-outline-primary" href="/Grupo2_Etapa2Proy_SGPP-UES/public/index.php?page=evaluar&practica_id=<?= $p['id'] ?>">Evaluar</a>
                <form action="/Grupo2_Etapa2Proy_SGPP-UES/public/index.php?page=docente_practicas&action=destroy" method="post" onsubmit="return confirm('¿Eliminar esta práctica?');">
                  <input type="hidden" name="id" value="<?= $p['id'] ?>">
                  <button class="btn btn-outline-danger" type="submit">Eliminar</button>
                </form>
              </div>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
<?php endif; ?>

<?php require_once __DIR__ . '/../../src/includes/footer.php'; ?>
