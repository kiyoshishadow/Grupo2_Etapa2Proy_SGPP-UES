<h2>Prácticas disponibles</h2>
<table border="1" cellpadding="6">
<tr><th>Título</th><th>Docente</th><th>Fecha límite</th><th></th></tr>
<?php foreach($lista as $p): ?>
<tr>
  <td><?= htmlspecialchars($p['titulo']) ?></td>
  <td><?= htmlspecialchars($p['docente']) ?></td>
  <td><?= htmlspecialchars($p['fecha_limite']) ?></td>
  <td><a href="/Grupo2_Etapa2Proy_SGPP-UES/public/index.php?page=inscribir&practica_id=<?= $p['id'] ?>">Inscribirme</a></td>
</tr>
<?php endforeach; ?>
</table>
