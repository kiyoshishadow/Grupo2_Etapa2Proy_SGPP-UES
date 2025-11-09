<?php require_once __DIR__ . '/../../src/includes/header.php'; ?>

<h2>Mis inscripciones</h2>

<?php if (isset($_GET['ok']) && $_GET['ok'] === 'subido'): ?>
  <p style="color:green;">¡Resultado enviado correctamente!</p>
<?php elseif (isset($_GET['error']) && $_GET['error'] === 'archivo'): ?>
  <p style="color:#b00;">Debes adjuntar un archivo para subir el resultado.</p>
<?php endif; ?>

<?php if (empty($registros)): ?>
  <p>No tienes inscripciones todavía.</p>
  <p><a href="/Grupo2_Etapa2Proy_SGPP-UES/public/index.php?page=practicas_disponibles">Ver prácticas disponibles</a></p>
<?php else: ?>
  <table border="1" cellpadding="6" width="100%">
    <tr>
      <th>Práctica</th>
      <th>Estado</th>
      <th>Fecha de postulación</th>
      <th>Subir resultado</th>
      <th>Resultados enviados</th>
      <th>Nota del docente</th>
    </tr>

    <?php foreach ($registros as $r): ?>
      <tr>
        <td><?= htmlspecialchars($r['titulo']) ?></td>
        <td><?= htmlspecialchars($r['estado'] ?? 'postulada') ?></td>
        <td><?= htmlspecialchars($r['fecha_postulacion'] ?? '-') ?></td>

        <!-- Subida de archivo -->
        <td style="min-width:340px;">
          <form method="post"
                action="/Grupo2_Etapa2Proy_SGPP-UES/public/index.php?page=subir_resultado"
                enctype="multipart/form-data">
            <input type="hidden" name="registro_id" value="<?= (int)$r['id'] ?>">
            <input type="file"   name="archivo" required>
            <input type="text"   name="comentario" placeholder="Comentario (opcional)" style="width:170px;">
            <button type="submit">Enviar</button>
          </form>
        </td>

        <!-- Resultados ya subidos + estado de envío -->
        <td>
          <?php if (empty($r['resultados'])): ?>
            <em>Aún no subiste resultados.</em>
            <div style="color:#b00;margin-top:4px;">Estado: <?= htmlspecialchars($r['estado_envio']) ?></div>
          <?php else: ?>
            <div style="margin-bottom:6px;">
              Estado: <strong><?= htmlspecialchars($r['estado_envio']) ?></strong>
            </div>
            <ul style="margin:0;padding-left:18px;">
              <?php foreach ($r['resultados'] as $it): ?>
                <li>
                  <a href="<?= htmlspecialchars($it['ruta_archivo']) ?>" target="_blank">Archivo</a>
                  <?php if (!empty($it['comentario'])): ?>
                    — <?= htmlspecialchars($it['comentario']) ?>
                  <?php endif; ?>
                  <small>(<?= htmlspecialchars($it['fecha_subida']) ?>)</small>
                </li>
              <?php endforeach; ?>
            </ul>
          <?php endif; ?>
        </td>

        <!-- Nota del docente -->
        <td>
          <?php if ($r['nota_docente'] === null): ?>
            <em>Aún sin calificación</em>
          <?php else: ?>
            <strong><?= htmlspecialchars($r['nota_docente']) ?></strong>
          <?php endif; ?>
        </td>
      </tr>
    <?php endforeach; ?>

  </table>
<?php endif; ?>

<p style="margin-top:14px;">
  <a href="/Grupo2_Etapa2Proy_SGPP-UES/public/index.php?page=practicas_disponibles">Ver prácticas disponibles</a>
</p>
