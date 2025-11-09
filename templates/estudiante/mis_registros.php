<h2>Mis inscripciones</h2>
<table border="1" cellpadding="6">
<tr><th>Práctica</th><th>Estado</th><th>Subir resultado</th></tr>
<?php foreach($registros as $r): ?>
<tr>
  <td><?= htmlspecialchars($r['titulo']) ?></td>
  <td><?= htmlspecialchars($r['estado']) ?></td>
  <td>
    <form method="post" enctype="multipart/form-data" action="/Grupo2_Etapa2Proy_SGPP-UES/public/index.php?page=subir_resultado">
      <input type="hidden" name="registro_id" value="<?= $r['id'] ?>">
      <input type="file" name="archivo" required>
      <input type="text" name="comentario" placeholder="Comentario (opcional)">
      <button type="submit">Subir</button>
    </form>
  </td>
</tr>
<?php endforeach; ?>
</table>
