<h2>Mis Prácticas</h2>
<a href="/Grupo2_Etapa2Proy_SGPP-UES/public/index.php?page=docente_practicas&action=create">Nueva práctica</a>
<table border="1" cellpadding="6">
<tr><th>Título</th><th>Fecha límite</th></tr>
<?php foreach($practicas as $p): ?>
<tr>
  <td><?= htmlspecialchars($p['titulo']) ?></td>
  <td><?= htmlspecialchars($p['fecha_limite']) ?></td>
</tr>
<?php endforeach; ?>
</table>
