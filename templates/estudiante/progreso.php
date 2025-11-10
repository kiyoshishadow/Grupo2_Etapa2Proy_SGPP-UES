<?php
$page_title = 'Mi Progreso';
include __DIR__ . '/../../src/includes/header.php';
?>

<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>
                    <i class="fas fa-chart-line me-2"></i>
                    Mi Progreso
                </h2>
                <a href="/Grupo2_Etapa2Proy_SGPP-UES/public/index.php?page=home" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Volver al Dashboard
                </a>
            </div>
        </div>
    </div>

    <!-- Métricas de Progreso -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0"><?= $estadisticas['total_avances'] ?></h4>
                            <p class="mb-0">Total Avances</p>
                        </div>
                        <i class="fas fa-tasks fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0"><?= $estadisticas['porcentaje_avance'] ?>%</h4>
                            <p class="mb-0">Progreso General</p>
                        </div>
                        <i class="fas fa-percentage fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0"><?= $estadisticas['dias_transcurridos'] ?></h4>
                            <p class="mb-0">Días Transcurridos</p>
                        </div>
                        <i class="fas fa-calendar-day fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-dark">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0"><?= $estadisticas['dias_totales'] - $estadisticas['dias_transcurridos'] ?></h4>
                            <p class="mb-0">Días Restantes</p>
                        </div>
                        <i class="fas fa-hourglass-half fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Barras de Progreso -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Progreso de Informes</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span><?= $estadisticas['informes_aprobados'] ?> de <?= $estadisticas['total_informes'] ?> informes aprobados</span>
                        <span class="fw-bold"><?= $estadisticas['porcentaje_informes'] ?>%</span>
                    </div>
                    <div class="progress" style="height: 25px;">
                        <div class="progress-bar bg-success" role="progressbar" style="width: <?= $estadisticas['porcentaje_informes'] ?>%">
                            <?= $estadisticas['porcentaje_informes'] ?>%
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Progreso de Tiempo</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span><?= $estadisticas['dias_transcurridos'] ?> de <?= $estadisticas['dias_totales'] ?> días</span>
                        <span class="fw-bold"><?= $estadisticas['porcentaje_tiempo'] ?>%</span>
                    </div>
                    <div class="progress" style="height: 25px;">
                        <div class="progress-bar bg-<?= $estadisticas['porcentaje_tiempo'] > 80 ? 'danger' : ($estadisticas['porcentaje_tiempo'] > 50 ? 'warning' : 'info') ?>" role="progressbar" style="width: <?= $estadisticas['porcentaje_tiempo'] ?>%">
                            <?= $estadisticas['porcentaje_tiempo'] ?>%
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Timeline de Avances -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-history me-2"></i>
                        Historial de Avances
                    </h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($avances)): ?>
                        <div class="timeline">
                            <?php foreach ($avances as $index => $avance): ?>
                                <div class="timeline-item">
                                    <div class="timeline-marker bg-<?= $avance['tipo'] === 'hito' ? 'success' : ($avance['tipo'] === 'reunion' ? 'info' : 'warning') ?>"></div>
                                    <div class="timeline-content">
                                        <div class="card border-left-<?= $avance['tipo'] === 'hito' ? 'success' : ($avance['tipo'] === 'reunion' ? 'info' : 'warning') ?>">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between align-items-start">
                                                    <div>
                                                        <h6 class="card-title mb-1">
                                                            <?= ucfirst($avance['tipo']) ?>
                                                            <?php if ($avance['porcentaje_avance']): ?>
                                                                <span class="badge bg-primary ms-2"><?= $avance['porcentaje_avance'] ?>%</span>
                                                            <?php endif; ?>
                                                        </h6>
                                                        <p class="card-text"><?= htmlspecialchars($avance['descripcion']) ?></p>
                                                    </div>
                                                    <small class="text-muted">
                                                        <?= date('d/m/Y H:i', strtotime($avance['fecha_registro'])) ?>
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="fas fa-clipboard-list fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No hay avances registrados</h5>
                            <p class="text-muted">Tu docente registrará avances importantes de tu práctica.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Informes Subidos -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-file-alt me-2"></i>
                        Informes Mensuales Subidos
                    </h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($informes)): ?>
                        <div class="row">
                            <?php foreach ($informes as $informe): ?>
                                <div class="col-md-6 col-lg-4 mb-3">
                                    <div class="card border">
                                        <div class="card-body">
                                            <h6 class="card-title">
                                                <?= $informe['periodo'] && $informe['periodo'] !== '0000-00-00' ? date('F Y', strtotime($informe['periodo'])) : 'Sin período' ?>
                                            </h6>
                                            <p class="card-text">
                                                <small class="text-muted">Subido: <?= date('d/m/Y', strtotime($informe['fecha_subida'])) ?></small>
                                            </p>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <span class="badge bg-<?= $informe['estado_revision'] === 'aprobado' ? 'success' : ($informe['estado_revision'] === 'pendiente' ? 'warning' : ($informe['estado_revision'] === 'observado' ? 'danger' : 'info')) ?>">
                                                    <?= ucfirst($informe['estado_revision']) ?>
                                                </span>
                                                <a href="/Grupo2_Etapa2Proy_SGPP-UES/public/index.php?page=descargar_archivo&archivo=<?= urlencode(basename($informe['ruta_archivo'])) ?>&tipo=informe" 
                                                   class="btn btn-sm btn-outline-primary" target="_blank" title="Ver archivo">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-4">
                            <p class="text-muted">No has subido informes mensuales todavía.</p>
                            <a href="/Grupo2_Etapa2Proy_SGPP-UES/public/index.php?page=subir_informe" class="btn btn-primary">
                                <i class="fas fa-upload me-2"></i>Subir Informe
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 15px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #dee2e6;
}

.timeline-item {
    position: relative;
    margin-bottom: 30px;
}

.timeline-marker {
    position: absolute;
    left: -22px;
    top: 5px;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    border: 2px solid #fff;
    box-shadow: 0 0 0 2px #dee2e6;
}

.timeline-content {
    background: transparent;
}

.border-left-success {
    border-left: 4px solid #28a745 !important;
}

.border-left-info {
    border-left: 4px solid #17a2b8 !important;
}

.border-left-warning {
    border-left: 4px solid #ffc107 !important;
}
</style>

<?php include __DIR__ . '/../../src/includes/footer.php'; ?>
