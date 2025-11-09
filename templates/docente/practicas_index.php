<?php require_once __DIR__ . '/dashboard.php'; ?>
<h2>Mis Prácticas</h2>

<p><a href="/Grupo2_Etapa2Proy_SGPP-UES/public/index.php?page=docente_practicas&action=create">Nueva práctica</a></p>

<table border="1" cellpadding="6">
  <tr>
    <th>Título</th>
    <th>Fecha límite</th>
    <th>Acciones</th>
  </tr>
  <?php foreach ($practicas as $p): ?>
  <tr>
    <td><?= htmlspecialchars($p['titulo']) ?></td>
    <td><?= htmlspecialchars($p['fecha_limite'] ?? '-') ?></td>
    <td>
      <a href="/Grupo2_Etapa2Proy_SGPP-UES/public/index.php?page=postulantes&practica_id=<?= (int)$p['id'] ?>">
        Ver postulantes
      </a>
    </td>
  </tr>
  <?php endforeach; ?>
</table>
