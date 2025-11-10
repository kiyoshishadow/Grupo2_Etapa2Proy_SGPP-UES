<?php
$page_title = $page_title ?? 'Reportes del Sistema';
include __DIR__ . '/../../src/includes/header.php';
?>

<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><?= htmlspecialchars($page_title) ?></h1>
    </div>

    <?php include __DIR__ . '/../../src/includes/flash_messages.php'; ?>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Filtros del Reporte</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="/Grupo2_Etapa2Proy_SGPP-UES/public/index.php">
                <input type="hidden" name="page" value="admin_reportes_expedientes">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="fecha_desde">Desde:</label>
                            <input type="date" id="fecha_desde" name="fecha_desde" class="form-control" value="<?= htmlspecialchars($fechaDesde) ?>">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="fecha_hasta">Hasta:</label>
                            <input type="date" id="fecha_hasta" name="fecha_hasta" class="form-control" value="<?= htmlspecialchars($fechaHasta) ?>">
                        </div>
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-filter"></i> Generar Reporte
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <?php if ($error): ?>
        <div class="alert alert-danger">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php else: ?>
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Resultados</h6>
            </div>
            <div class="card-body">
                <p>Mostrando estudiantes activos para el rango de fechas: <strong><?= htmlspecialchars(date('d/m/Y', strtotime($fechaDesde))) ?></strong> a <strong><?= htmlspecialchars(date('d/m/Y', strtotime($fechaHasta))) ?></strong>.</p>
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Nombre de la Empresa</th>
                                <th>Total de Estudiantes Activos</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($resultados)): ?>
                                <tr>
                                    <td colspan="2" class="text-center">No se encontraron estudiantes activos para el rango de fechas especificado.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($resultados as $fila): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($fila['empresa_nombre']) ?></td>
                                        <td><?= htmlspecialchars($fila['total_estudiantes']) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php endif; ?>

</div>

<?php include __DIR__ . '/../../src/includes/footer.php'; ?>
