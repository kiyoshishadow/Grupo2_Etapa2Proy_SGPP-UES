<?php require_once __DIR__ . '/dashboard.php'; ?>
<h2>Nueva práctica</h2>

<?php if (!empty($error)): ?>
  <p style="color:#b00;"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<form method="post" action="/Grupo2_Etapa2Proy_SGPP-UES/public/index.php?page=docente_practicas&action=create">
  <label>Título:
    <input type="text" name="titulo" required>
  </label><br><br>

  <label>Descripción:<br>
    <textarea name="descripcion" rows="4" cols="60"></textarea>
  </label><br><br>

  <label>Fecha límite:
    <input type="date" name="fecha_fin" required>
  </label><br><br>

  <button type="submit">Guardar</button>
  <a href="/Grupo2_Etapa2Proy_SGPP-UES/public/index.php?page=docente_practicas">Cancelar</a>
</form>
