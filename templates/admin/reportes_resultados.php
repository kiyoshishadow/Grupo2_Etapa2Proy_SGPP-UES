<?php require_once __DIR__ . '/../../src/includes/header.php'; ?>

<?php require __DIR__ . '/../../src/includes/flash_messages.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-3">
  <h2 class="h4 mb-0">Resultados y evaluaciones</h2>
  <a class="btn btn-outline-secondary" href="/Grupo2_Etapa2Proy_SGPP-UES/public/index.php?page=home">Volver al panel</a>
</div>

<div class="card border-0 shadow-sm mb-4">
  <div class="card-body">
    <form class="row g-3 align-items-end" method="get" action="/Grupo2_Etapa2Proy_SGPP-UES/public/index.php">
      <input type="hidden" name="page" value="admin_reportes">
      <div class="col-md-6">
        <label class="form-label" for="practica_id">Selecciona una práctica</label>
        <select class="form-select" id="practica_id" name="practica_id" required>
          <option value="">-- Elegir práctica --</option>
          <?php foreach ($practicas as $practica): ?>
            <option value="<?= (int)$practica['id'] ?>" <?= isset($practicaId) && (int)$practica['id'] === (int)($practicaId ?? 0) ? 'selected' : '' ?>>
              <?= htmlspecialchars($practica['titulo']) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-md-3 col-lg-2">
        <button class="btn btn-primary w-100" type="submit">Ver inscritos</button>
      </div>
    </form>
  </div>
</div>

<?php if (!empty($practicaSeleccionada)): ?>
  <div class="card border-0 shadow-sm mb-4">
    <div class="card-body d-flex flex-column flex-md-row justify-content-between">
      <div>
        <h3 class="h5 mb-1"><?= htmlspecialchars($practicaSeleccionada['titulo']) ?></h3>
        <p class="mb-1 text-muted">Docente responsable: <?= htmlspecialchars($practicaSeleccionada['docente_nombre'] ?? 'N/D') ?></p>
        <p class="mb-0 text-muted">Estado: <span class="text-capitalize"><?= htmlspecialchars($practicaSeleccionada['estado'] ?? '') ?></span></p>
      </div>
      <div class="text-md-end mt-3 mt-md-0">
        <p class="mb-1"><strong>Inscritos:</strong> <?= (int)($practicaSeleccionada['total_inscritos'] ?? 0) ?></p>
        <p class="mb-0"><strong>Finalizados:</strong> <?= (int)($practicaSeleccionada['total_finalizados'] ?? 0) ?></p>
      </div>
    </div>
  </div>

  <?php if (!empty($detalle)): ?>
    <div class="row g-3 mb-4">
      <?php foreach ($detalle as $index => $inscrito): ?>
        <?php
          $ultimoResultado = $inscrito['resultados'][0] ?? null;
          $archivoDisponible = $ultimoResultado['archivo_existe'] ?? false;
          $ultimaEvaluacion = $inscrito['evaluaciones'][0] ?? null;
          $collapseId = 'resultadoCollapse' . $inscrito['id'];
          $estadoBadge = match(strtolower($inscrito['registro_estado'])) {
            'aprobado' => 'bg-success',
            'rechazado' => 'bg-danger',
            'finalizada' => 'bg-primary',
            default => 'bg-secondary'
          };
        ?>
        <div class="col-12 col-lg-6">
          <div class="card shadow-sm h-100">
            <div class="card-body d-flex flex-column gap-3">
              <div class="d-flex justify-content-between align-items-start">
                <div>
                  <h4 class="h6 mb-1"><?= htmlspecialchars($inscrito['estudiante_nombre']) ?></h4>
                  <p class="text-muted small mb-0">Carnet: <?= htmlspecialchars($inscrito['carnet'] ?? 'N/D') ?></p>
                </div>
                <span class="badge <?= $estadoBadge ?> text-uppercase"><?= htmlspecialchars($inscrito['registro_estado']) ?></span>
              </div>

              <div>
                <p class="mb-2 small text-muted">Fecha de inscripción: <?= htmlspecialchars($inscrito['fecha_postulacion']) ?></p>
                <?php if ($ultimoResultado && !empty($ultimoResultado['ruta_archivo']) && $archivoDisponible): ?>
                  <button class="btn btn-sm btn-outline-primary" type="button" data-bs-toggle="collapse" data-bs-target="#<?= $collapseId ?>">
                    Ver evidencia
                  </button>
                  <div class="collapse mt-2" id="<?= $collapseId ?>">
                    <div class="ratio ratio-4x3 border rounded">
                      <iframe src="<?= htmlspecialchars($ultimoResultado['ruta_archivo']) ?>" title="Evidencia" class="rounded" allowfullscreen></iframe>
                    </div>
                    <?php if (!empty($ultimoResultado['comentario'])): ?>
                      <p class="mt-2 small text-muted">Comentario del estudiante: "<?= htmlspecialchars($ultimoResultado['comentario']) ?>"</p>
                    <?php endif; ?>
                  </div>
                <?php elseif ($ultimoResultado && !empty($ultimoResultado['ruta_archivo'])): ?>
                  <p class="text-warning small mb-0">Archivo no encontrado en el servidor.</p>
                  <p class="text-muted small">Ruta esperada: <?= htmlspecialchars($ultimoResultado['ruta_archivo']) ?></p>
                <?php else: ?>
                  <p class="text-muted small">Sin archivo cargado.</p>
                <?php endif; ?>
              </div>

              <div class="border-top pt-3">
                <form method="post" action="/Grupo2_Etapa2Proy_SGPP-UES/public/index.php?page=admin_reportes">
                  <input type="hidden" name="registro_id" value="<?= (int)$inscrito['id'] ?>">
                  <input type="hidden" name="practica_id" value="<?= (int)$inscrito['practica_id'] ?>">
                  <input type="hidden" name="docente_id" value="<?= (int)$inscrito['docente_responsable_id'] ?>">

                  <div class="mb-2">
                    <label class="form-label small" for="estado-<?= (int)$inscrito['id'] ?>">Estado</label>
                    <select class="form-select form-select-sm" id="estado-<?= (int)$inscrito['id'] ?>" name="estado" required>
                      <?php foreach (['pendiente' => 'Pendiente', 'aprobado' => 'Aprobado', 'rechazado' => 'Rechazado'] as $valor => $texto): ?>
                        <option value="<?= $valor ?>" <?= ($ultimaEvaluacion['estado'] ?? strtolower($inscrito['registro_estado'])) === $valor ? 'selected' : '' ?>><?= $texto ?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>

                  <div class="mb-2">
                    <label class="form-label small" for="nota-<?= (int)$inscrito['id'] ?>">Nota (0 - 10)</label>
                    <input class="form-control form-control-sm" type="number" step="0.01" min="0" max="10" id="nota-<?= (int)$inscrito['id'] ?>" name="nota" value="<?= htmlspecialchars($ultimaEvaluacion['nota'] ?? '') ?>">
                  </div>

                  <div class="mb-2">
                    <label class="form-label small" for="comentario-<?= (int)$inscrito['id'] ?>">Comentario</label>
                    <textarea class="form-control form-control-sm" id="comentario-<?= (int)$inscrito['id'] ?>" name="comentario" rows="3" placeholder="Observaciones de la evaluación"><?= htmlspecialchars($ultimaEvaluacion['comentario'] ?? '') ?></textarea>
                  </div>

                  <div class="d-flex justify-content-between align-items-center">
                    <?php if (!empty($inscrito['evaluaciones'])): ?>
                      <small class="text-muted">Última evaluación: <?= htmlspecialchars($ultimaEvaluacion['fecha_evaluacion'] ?? '') ?></small>
                    <?php else: ?>
                      <small class="text-muted">Sin evaluaciones previas</small>
                    <?php endif; ?>
                    <button class="btn btn-sm btn-success" type="submit">Guardar evaluación</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php else: ?>
    <div class="alert alert-info">No se encontraron inscripciones para esta práctica.</div>
  <?php endif; ?>
<?php endif; ?>

<div class="card border-0 shadow-sm">
  <div class="card-header bg-white border-0">
    <h3 class="h5 mb-0">Consolidado de resultados</h3>
  </div>
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <thead class="table-light">
          <tr>
            <th>Práctica</th>
            <th>Docente responsable</th>
            <th>Estudiante</th>
            <th>Estado inscripción</th>
            <th>Archivo</th>
            <th>Evaluación</th>
            <th>Nota</th>
            <th>Última actualización</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($registros)): ?>
            <tr>
              <td colspan="8" class="text-center py-4 text-muted">Aún no hay resultados registrados.</td>
            </tr>
          <?php else: ?>
            <?php foreach ($registros as $item): ?>
              <?php
                $estadoRegistro = strtolower($item['registro_estado']);
                $estadoBadge = match($estadoRegistro) {
                  'aceptada', 'aprobado' => 'bg-success',
                  'rechazado' => 'bg-danger',
                  'finalizada' => 'bg-primary',
                  default => 'bg-secondary'
                };
                $fechaActualizacion = $item['fecha_evaluacion'] ?? $item['fecha_subida'] ?? $item['fecha_postulacion'];
              ?>
              <tr>
                <td>
                  <div class="fw-semibold"><?= htmlspecialchars($item['practica_titulo']) ?></div>
                  <small class="text-muted">Estado práctica: <?= htmlspecialchars($item['practica_estado']) ?></small>
                </td>
                <td><?= htmlspecialchars($item['docente_responsable']) ?></td>
                <td><?= htmlspecialchars($item['estudiante_nombre']) ?></td>
                <td>
                  <span class="badge <?= $estadoBadge ?> text-uppercase"><?= htmlspecialchars($item['registro_estado']) ?></span>
                </td>
                <td>
                  <?php if ($item['ruta_archivo']): ?>
                    <a class="btn btn-sm btn-outline-primary" href="<?= htmlspecialchars($item['ruta_archivo']) ?>" target="_blank">Descargar</a>
                    <?php if (!empty($item['resultado_comentario'])): ?>
                      <div class="small text-muted mt-1">"<?= htmlspecialchars($item['resultado_comentario']) ?>"</div>
                    <?php endif; ?>
                  <?php else: ?>
                    <span class="text-muted">Sin archivo</span>
                  <?php endif; ?>
                </td>
                <td>
                  <?php if ($item['evaluacion_estado']): ?>
                    <div class="d-flex flex-column">
                      <span class="fw-semibold text-capitalize"><?= htmlspecialchars($item['evaluacion_estado']) ?></span>
                      <?php if (!empty($item['evaluacion_comentario'])): ?>
                        <small class="text-muted">"<?= htmlspecialchars($item['evaluacion_comentario']) ?>"</small>
                      <?php endif; ?>
                      <?php if (!empty($item['evaluador_nombre'])): ?>
                        <small class="text-muted">Evaluó: <?= htmlspecialchars($item['evaluador_nombre']) ?></small>
                      <?php endif; ?>
                    </div>
                  <?php else: ?>
                    <span class="text-muted">Pendiente</span>
                  <?php endif; ?>
                </td>
                <td>
                  <?= $item['evaluacion_nota'] !== null ? htmlspecialchars($item['evaluacion_nota']) : '<span class="text-muted">—</span>' ?>
                </td>
                <td>
                  <small class="text-muted">
                    <?= htmlspecialchars($fechaActualizacion ?? 'N/A') ?>
                  </small>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/../../src/includes/footer.php'; ?>
