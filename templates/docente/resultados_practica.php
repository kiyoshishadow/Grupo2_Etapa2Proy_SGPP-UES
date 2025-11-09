<?php require_once __DIR__ . '/../../src/includes/header.php'; ?>
<h2>Resultados de la práctica</h2>

<?php if (empty($resultados)): ?>
  <p>No hay resultados subidos aún.</p>
<?php else: ?>
<table border="1" cellpadding="6">
<tr>
        <th>Estudiante</th>
        <th>Nota</th>
        <th>Observaciones</th>
        <th>Fecha</th>
</tr>
<?php foreach($resultados as $res): ?>
<tr>
  <td><?= htmlspecialchars($res['estudiante']) ?></td>
  <td><a href="<?= htmlspecialchars($res['ruta_archivo']) ?>" target="_blank">Ver archivo</a></td>
  <td><?= htmlspecialchars($res['comentario']) ?></td>
  <td><?= htmlspecialchars($res['fecha_subida']) ?></td>
  <td>
    <form method="post" action="/Grupo2_Etapa2Proy_SGPP-UES/public/index.php?page=calificar_resultado">
      <input type="hidden" name="resultado_id" value="<?= (int)$res['resultado_id'] ?>">
      <input type="hidden" name="practica_id"  value="<?= (int)($_GET['practica_id'] ?? 0) ?>">
      <input type="number" name="calificacion" step="0.1" min="0" max="10" required>
      <input type="text" name="observacion" placeholder="Observación (opcional)">
      <button type="submit">Guardar</button>
    </form>
  </td>
</tr>
<?php endforeach; ?>
</table>
<?php endif; ?>
