<?php require_once __DIR__ . '/../estudiante/dashboard.php'; ?>
<h2>Mis inscripciones</h2>

<?php if (empty($registros)): ?>
<p>Aún no te has inscrito en ninguna práctica.</p>
<?php else: ?>
<table border="1" cellpadding="6">
  <tr>
    <th>Práctica</th>
    <th>Estado</th>
    <th>Fecha de postulación</th>
  </tr>
  <?php foreach ($registros as $r): ?>
  <tr>
    <td><?= htmlspecialchars($r['titulo'] ?? 'Práctica') ?></td>
    <td><?= htmlspecialchars($r['estado'] ?? '-') ?></td>
    <td><?= htmlspecialchars($r['fecha_postulacion'] ?? '-') ?></td>
  </tr>
  <?php endforeach; ?>
</table>
<?php endif; ?>

