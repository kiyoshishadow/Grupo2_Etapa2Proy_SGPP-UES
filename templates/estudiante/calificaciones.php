<?php
$page_title = 'Mis Calificaciones';
include __DIR__ . '/../../src/includes/header.php';
?>

<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>
                    <i class="fas fa-graduation-cap me-2"></i>
                    Mis Calificaciones
                </h2>
                <a href="/Grupo2_Etapa2Proy_SGPP-UES/public/index.php?page=home" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Volver al Dashboard
                </a>
            </div>
        </div>
    </div>

    <!-- Resumen de Calificaciones -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0"><?= $estadisticas['total_informes'] ?></h4>
                            <p class="mb-0">Total Informes</p>
                        </div>
                        <i class="fas fa-file-alt fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0"><?= $estadisticas['informes_aprobados'] ?></h4>
                            <p class="mb-0">Informes Aprobados</p>
                        </div>
                        <i class="fas fa-check-circle fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-dark">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0"><?= $estadisticas['informes_pendientes'] ?></h4>
                            <p class="mb-0">Informes Pendientes</p>
                        </div>
                        <i class="fas fa-clock fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0"><?= $estadisticas['promedio_informes'] ?></h4>
                            <p class="mb-0">Promedio</p>
                        </div>
                        <i class="fas fa-chart-line fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Evaluación Final -->
    <?php if ($estadisticas['evaluacion_final']): ?>
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-success">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-award me-2"></i>
                            Evaluación Final
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h4 class="text-success">Nota Final: <?= number_format($estadisticas['evaluacion_final']['nota_final'], 2) ?></h4>
                                <p class="mb-2">
                                    <strong>Evaluado por:</strong> <?= htmlspecialchars($estadisticas['evaluacion_final']['docente_nombre'] ?? 'Docente asignado') ?>
                                </p>
                                <p class="mb-0">
                                    <strong>Fecha:</strong> <?= date('d/m/Y H:i', strtotime($estadisticas['evaluacion_final']['fecha_evaluacion'])) ?>
                                </p>
                            </div>
                            <div class="col-md-6">
                                <h6>Comentarios:</h6>
                                <p class="text-muted"><?= htmlspecialchars($estadisticas['evaluacion_final']['comentario_docente'] ?? 'Sin comentarios') ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Detalle de Informes -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-list me-2"></i>
                        Detalle de Informes Mensuales
                    </h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($informes)): ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Período</th>
                                        <th>Fecha de Subida</th>
                                        <th>Estado</th>
                                        <th>Fecha Revisión</th>
                                        <th>Comentarios</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($informes as $informe): ?>
                                        <tr>
                                            <td>
                                                <?= $informe['periodo'] && $informe['periodo'] !== '0000-00-00' ? date('m/Y', strtotime($informe['periodo'])) : 'Sin período' ?>
                                            </td>
                                            <td><?= date('d/m/Y H:i', strtotime($informe['fecha_subida'])) ?></td>
                                            <td>
                                                <span class="badge bg-<?= $informe['estado_revision'] === 'aprobado' ? 'success' : ($informe['estado_revision'] === 'pendiente' ? 'warning' : ($informe['estado_revision'] === 'observado' ? 'danger' : 'info')) ?>">
                                                    <?= ucfirst($informe['estado_revision']) ?>
                                                </span>
                                            </td>
                                            <td>
                                                <?= $informe['fecha_revision'] ? date('d/m/Y H:i', strtotime($informe['fecha_revision'])) : '-' ?>
                                            </td>
                                            <td>
                                                <?= $informe['comentario_revisor'] ? htmlspecialchars($informe['comentario_revisor']) : 'Sin comentarios' ?>
                                            </td>
                                            <td>
                                                <a href="/Grupo2_Etapa2Proy_SGPP-UES/public/index.php?page=descargar_archivo&archivo=<?= urlencode(basename($informe['ruta_archivo'])) ?>&tipo=informe" 
                                                   class="btn btn-sm btn-outline-primary" target="_blank" title="Ver archivo">
                                                    <i class="fas fa-eye"></i> Ver
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No has subido informes</h5>
                            <p class="text-muted">Comienza a subir tus informes mensuales para ver tus calificaciones.</p>
                            <a href="/Grupo2_Etapa2Proy_SGPP-UES/public/index.php?page=subir_informe" class="btn btn-primary">
                                <i class="fas fa-upload me-2"></i>Subir Primer Informe
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../../src/includes/footer.php'; ?>
