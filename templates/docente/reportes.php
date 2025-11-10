<?php
$page_title = 'Reportes de Prácticas';
include __DIR__ . '/../../src/includes/header.php';
?>

<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>
                    <i class="fas fa-chart-bar me-2"></i>
                    Reportes de Prácticas Profesionales
                </h2>
                <a href="/Grupo2_Etapa2Proy_SGPP-UES/public/index.php?page=home" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Volver al Dashboard
                </a>
            </div>
        </div>
    </div>

    <!-- Estadísticas Generales -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0"><?= $estadisticas['total_estudiantes'] ?></h4>
                            <p class="mb-0">Estudiantes Activos</p>
                        </div>
                        <i class="fas fa-users fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-dark">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0"><?= $estadisticas['total_informes'] ?></h4>
                            <p class="mb-0">Informes Pendientes</p>
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
                            <h4 class="mb-0"><?= $estadisticas['total_evaluaciones'] ?></h4>
                            <p class="mb-0">Evaluaciones del Mes</p>
                        </div>
                        <i class="fas fa-check-circle fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0"><?= $estadisticas['total_empresas'] ?></h4>
                            <p class="mb-0">Empresas Activas</p>
                        </div>
                        <i class="fas fa-building fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Estudiantes por Carrera -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-graduation-cap me-2"></i>
                        Estudiantes por Carrera
                    </h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($expedientes_por_carrera)): ?>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Carrera</th>
                                        <th class="text-center">Estudiantes</th>
                                        <th class="text-center">Porcentaje</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $total_estudiantes = array_sum(array_column($expedientes_por_carrera, 'total'));
                                    foreach ($expedientes_por_carrera as $carrera): 
                                        $porcentaje = $total_estudiantes > 0 ? ($carrera['total'] / $total_estudiantes) * 100 : 0;
                                    ?>
                                        <tr>
                                            <td><?= htmlspecialchars($carrera['carrera']) ?></td>
                                            <td class="text-center">
                                                <span class="badge bg-primary"><?= $carrera['total'] ?></span>
                                            </td>
                                            <td class="text-center">
                                                <div class="progress" style="height: 20px;">
                                                    <div class="progress-bar" role="progressbar" style="width: <?= round($porcentaje) ?>%">
                                                        <?= round($porcentaje) ?>%
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p class="text-muted">No hay datos disponibles</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Empresas con Estudiantes -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-building me-2"></i>
                        Empresas con Estudiantes
                    </h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($empresas_con_estudiantes)): ?>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Empresa</th>
                                        <th class="text-center">Estudiantes</th>
                                        <th>Carreras</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($empresas_con_estudiantes as $empresa): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($empresa['nombre']) ?></td>
                                            <td class="text-center">
                                                <span class="badge bg-info"><?= $empresa['total_estudiantes'] ?></span>
                                            </td>
                                            <td>
                                                <small class="text-muted"><?= htmlspecialchars($empresa['carreras']) ?></small>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p class="text-muted">No hay empresas con estudiantes activos</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Informes Recientes -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-clock me-2"></i>
                        Informes Recientes
                    </h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($informes_recientes)): ?>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Estudiante</th>
                                        <th>Carrera</th>
                                        <th>Empresa</th>
                                        <th>Período</th>
                                        <th>Estado</th>
                                        <th>Fecha Subida</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($informes_recientes as $informe): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($informe['estudiante_nombre']) ?></td>
                                            <td><?= htmlspecialchars($informe['estudiante_carrera']) ?></td>
                                            <td><?= htmlspecialchars($informe['empresa_nombre']) ?></td>
                                            <td><?= $informe['periodo'] && $informe['periodo'] !== '0000-00-00' ? date('m/Y', strtotime($informe['periodo'])) : 'Sin período' ?></td>
                                            <td>
                                                <span class="badge bg-<?= $informe['estado_revision'] === 'aprobado' ? 'success' : ($informe['estado_revision'] === 'pendiente' ? 'warning' : 'info') ?>">
                                                    <?= ucfirst($informe['estado_revision']) ?>
                                                </span>
                                            </td>
                                            <td><?= date('d/m/Y', strtotime($informe['fecha_subida'])) ?></td>
                                            <td>
                                                <a href="/Grupo2_Etapa2Proy_SGPP-UES/public/index.php?page=descargar_archivo&archivo=<?= urlencode(basename($informe['ruta_archivo'])) ?>&tipo=informe" 
                                                   class="btn btn-sm btn-outline-primary" target="_blank">
                                                    <i class="fas fa-eye"></i> Ver
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p class="text-muted">No hay informes recientes</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../../src/includes/footer.php'; ?>
