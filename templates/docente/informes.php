<?php
$page_title = 'Revisar Informes Mensuales';
include __DIR__ . '/../../src/includes/header.php';
?>

<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>
                    <i class="fas fa-file-alt me-2"></i>
                    Revisar Informes Mensuales
                </h2>
                <a href="/Grupo2_Etapa2Proy_SGPP-UES/public/index.php?page=home" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Volver al Dashboard
                </a>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" class="row g-3 align-items-end">
                        <input type="hidden" name="page" value="docente_informes">
                        <div class="col-md-4">
                            <label for="estado" class="form-label">Filtrar por Estado</label>
                            <select class="form-select" id="estado" name="estado" onchange="this.form.submit()">
                                <option value="todos" <?= $estado_filtro === 'todos' ? 'selected' : '' ?>>Todos los informes</option>
                                <option value="pendiente" <?= $estado_filtro === 'pendiente' ? 'selected' : '' ?>>Pendientes de revisión</option>
                                <option value="recibido" <?= $estado_filtro === 'recibido' ? 'selected' : '' ?>>Recibidos</option>
                                <option value="observado" <?= $estado_filtro === 'observado' ? 'selected' : '' ?>>Observados</option>
                                <option value="aprobado" <?= $estado_filtro === 'aprobado' ? 'selected' : '' ?>>Aprobados</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-outline-primary" onclick="window.location.href='?page=docente_informes&estado=pendiente'">
                                    <i class="fas fa-clock me-1"></i>Pendientes
                                </button>
                                <button type="button" class="btn btn-outline-success" onclick="window.location.href='?page=docente_informes&estado=aprobado'">
                                    <i class="fas fa-check me-1"></i>Aprobados
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Lista de Informes -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-list me-2"></i>
                        Informes (<?= count($informes) ?> registros)
                    </h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($informes)): ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Estudiante</th>
                                        <th>Carrera</th>
                                        <th>Empresa</th>
                                        <th>Período</th>
                                        <th>Fecha Subida</th>
                                        <th>Estado</th>
                                        <th>Archivo</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($informes as $informe): ?>
                                        <tr>
                                            <td>
                                                <strong><?= htmlspecialchars($informe['estudiante_nombre']) ?></strong>
                                            </td>
                                            <td><?= htmlspecialchars($informe['estudiante_carrera']) ?></td>
                                            <td><?= htmlspecialchars($informe['empresa_nombre']) ?></td>
                                            <td><?= $informe['periodo'] && $informe['periodo'] !== '0000-00-00' ? date('m/Y', strtotime($informe['periodo'])) : 'Sin período' ?></td>
                                            <td><?= date('d/m/Y H:i', strtotime($informe['fecha_subida'])) ?></td>
                                            <td>
                                                <span class="badge bg-<?= $informe['estado_revision'] === 'aprobado' ? 'success' : ($informe['estado_revision'] === 'pendiente' ? 'warning' : ($informe['estado_revision'] === 'observado' ? 'danger' : 'info')) ?>">
                                                    <?= ucfirst($informe['estado_revision']) ?>
                                                </span>
                                            </td>
                                            <td>
                                                <a href="/Grupo2_Etapa2Proy_SGPP-UES/public/index.php?page=descargar_archivo&archivo=<?= urlencode(basename($informe['ruta_archivo'])) ?>&tipo=informe" 
                                                   class="btn btn-sm btn-outline-primary" target="_blank" title="Ver archivo">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                            <td>
                                                <?php if ($informe['estado_revision'] === 'pendiente' || $informe['estado_revision'] === 'recibido'): ?>
                                                    <button class="btn btn-sm btn-success" onclick="mostrarFormularioAccion(<?= $informe['id'] ?>, 'aprobar')">
                                                        <i class="fas fa-check"></i> Aprobar
                                                    </button>
                                                    <button class="btn btn-sm btn-warning" onclick="mostrarFormularioAccion(<?= $informe['id'] ?>, 'observar')">
                                                        <i class="fas fa-exclamation-triangle"></i> Observar
                                                    </button>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                        
                                        <!-- Formulario oculto para acciones -->
                                        <tr id="formulario-<?= $informe['id'] ?>" class="d-none">
                                            <td colspan="8">
                                                <div class="bg-light p-3 rounded">
                                                    <form method="POST">
                                                        <input type="hidden" name="informe_id" value="<?= $informe['id'] ?>">
                                                        <input type="hidden" name="accion" id="accion-<?= $informe['id'] ?>">
                                                        <div class="row">
                                                            <div class="col-md-8">
                                                                <label class="form-label">Comentarios para el estudiante:</label>
                                                                <textarea class="form-control" name="comentario_revisor" rows="3" placeholder="Agrega tus comentarios..."></textarea>
                                                            </div>
                                                            <div class="col-md-4 d-flex align-items-end">
                                                                <div class="btn-group w-100">
                                                                    <button type="submit" class="btn btn-success">
                                                                        <i class="fas fa-save"></i> Guardar
                                                                    </button>
                                                                    <button type="button" class="btn btn-secondary" onclick="ocultarFormularioAccion(<?= $informe['id'] ?>)">
                                                                        <i class="fas fa-times"></i> Cancelar
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No hay informes para mostrar</h5>
                            <p class="text-muted">No se encontraron informes con el estado seleccionado.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function mostrarFormularioAccion(informeId, accion) {
    document.getElementById('formulario-' + informeId).classList.remove('d-none');
    document.getElementById('accion-' + informeId).value = accion;
}

function ocultarFormularioAccion(informeId) {
    document.getElementById('formulario-' + informeId).classList.add('d-none');
}
</script>

<?php include __DIR__ . '/../../src/includes/footer.php'; ?>
