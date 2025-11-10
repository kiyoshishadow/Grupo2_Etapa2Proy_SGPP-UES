<?php
$page_title = 'Subir Informe Mensual';
include __DIR__ . '/../../src/includes/header.php';
?>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">
                        <i class="fas fa-file-upload me-2"></i>
                        Subir Informe Mensual
                    </h4>
                </div>
                <div class="card-body">
                    <?php include __DIR__ . '/../../src/includes/flash_messages.php'; ?>

                    <?php if ($expediente_activo): ?>
                        <div class="alert alert-info mb-4">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Expediente activo:</strong> <?= htmlspecialchars($expediente_activo['empresa_nombre'] ?? 'Empresa asignada') ?>
                            <br>
                            <small>Período de práctica: <?= date('d/m/Y', strtotime($expediente_activo['fecha_inicio'])) ?> - <?= date('d/m/Y', strtotime($expediente_activo['fecha_fin'])) ?></small>
                        </div>

                        <form method="POST" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="periodo" class="form-label">Período del Informe *</label>
                                <input type="month" class="form-control" id="periodo" name="periodo" required max="<?= date('Y-m') ?>">
                                <div class="form-text">Selecciona el mes y año correspondiente al informe</div>
                            </div>

                            <div class="mb-3">
                                <label for="archivo" class="form-label">Archivo del Informe *</label>
                                <input type="file" class="form-control" id="archivo" name="archivo" accept=".pdf,.zip" required>
                                <div class="form-text">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Formatos permitidos: PDF, ZIP (Máximo 5MB)
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="comentario_estudiante" class="form-label">Comentarios</label>
                                <textarea class="form-control" id="comentario_estudiante" name="comentario_estudiante" rows="4" placeholder="Describe brevemente las actividades realizadas durante este período..."></textarea>
                            </div>

                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <a href="/Grupo2_Etapa2Proy_SGPP-UES/public/index.php?page=mis_registros" class="btn btn-secondary me-md-2">
                                    <i class="fas fa-arrow-left me-2"></i>Cancelar
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-upload me-2"></i>Subir Informe
                                </button>
                            </div>
                        </form>

                        <?php if (!empty($informes_existentes)): ?>
                            <hr class="my-4">
                            <h5 class="mb-3">
                                <i class="fas fa-history me-2"></i>
                                Informes Anteriores
                            </h5>
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Período</th>
                                            <th>Estado</th>
                                            <th>Fecha de Subida</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($informes_existentes as $informe): ?>
                                            <tr>
                                                <td><?= date('m/Y', strtotime($informe['periodo'])) ?></td>
                                                <td>
                                                    <span class="badge bg-<?= $informe['estado_revision'] === 'aprobado' ? 'success' : ($informe['estado_revision'] === 'pendiente' ? 'warning' : 'info') ?>">
                                                        <?= ucfirst($informe['estado_revision']) ?>
                                                    </span>
                                                </td>
                                                <td><?= date('d/m/Y', strtotime($informe['fecha_subida'])) ?></td>
                                                <td>
                                                    <?php if ($informe['ruta_archivo'] && file_exists(__DIR__ . '/../../public/uploads/' . basename($informe['ruta_archivo']))): ?>
                                                        <a href="/Grupo2_Etapa2Proy_SGPP-UES/public/index.php?page=descargar_archivo&archivo=<?= urlencode(basename($informe['ruta_archivo'])) ?>&tipo=informe" class="btn btn-sm btn-outline-primary" target="_blank" title="Ver archivo">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>

                    <?php else: ?>
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            No tienes un expediente activo. Contacta al administrador para asignarte una práctica.
                        </div>
                        <div class="text-center">
                            <a href="/Grupo2_Etapa2Proy_SGPP-UES/public/index.php?page=mis_registros" class="btn btn-primary">
                                <i class="fas fa-arrow-left me-2"></i>Volver a Mis Registros
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../../src/includes/footer.php'; ?>
