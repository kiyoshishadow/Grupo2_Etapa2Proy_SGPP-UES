<?php 
require_once __DIR__ . '/../../src/includes/header.php';
// Definir tipos de archivo permitidos
$allowed_types = ['pdf', 'doc', 'docx'];
?>
<h2>Mis inscripciones</h2>

<table border="1" cellpadding="6">
<tr><th>Práctica</th><th>Estado</th><th>Fecha de postulación</th><th>Subir resultado</th></tr>
<?php foreach($registros as $r): ?>
<tr>
  <td><?= htmlspecialchars($r['titulo']) ?></td>
  <td><?= htmlspecialchars($r['estado']) ?></td>
  <td><?= htmlspecialchars($r['fecha_postulacion']) ?></td>
  <td>
    <form method="post" enctype="multipart/form-data" 
          action="<?= BASE_URL ?>public/index.php?page=subir_resultado">
      <input type="hidden" name="registro_id" value="<?= (int)$r['id'] ?>">
      <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
      <input type="file" name="archivo" accept=".pdf,.doc,.docx" required>
      <input type="text" name="comentario" placeholder="Comentario (opcional)">
      <button type="submit">Subir</button>
    </form>
  </td>
</tr>
<?php endforeach; ?>
</table>