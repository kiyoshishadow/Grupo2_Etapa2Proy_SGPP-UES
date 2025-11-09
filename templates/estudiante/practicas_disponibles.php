<?php require_once __DIR__ . '/../estudiante/dashboard.php'; ?>
<h2>Prácticas disponibles</h2>

<?php if (empty($lista)): ?>
<p>No hay prácticas disponibles por ahora.</p>
<?php else: ?>
<table border="1" cellpadding="6">
  <tr>
    <th>Título</th>
    <th>Docente</th>
    <th>Fecha límite</th>
    <th></th>
  </tr>
  <?php foreach ($lista as $p): ?>
  <tr>
    <td><?= htmlspecialchars($p['titulo']) ?></td>
    <td><?= htmlspecialchars($p['docente']) ?></td>
    <td><?= htmlspecialchars($p['fecha_limite'] ?? '-') ?></td>
    <td>
      <a href="/Grupo2_Etapa2Proy_SGPP-UES/public/index.php?page=inscribir&practica_id=<?= (int)$p['id'] ?>">
        Inscribirme
      </a>
    </td>
  </tr>
  <?php endforeach; ?>
</table>
<?php endif; ?>

