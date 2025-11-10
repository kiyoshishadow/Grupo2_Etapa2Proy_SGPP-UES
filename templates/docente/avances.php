<?php
$page_title = 'Seguimiento de Avances';
include __DIR__ . '/../../src/includes/header.php';
?>

<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>
                    <i class="fas fa-tasks me-2"></i>
                    Seguimiento de Avances
                </h2>
                <a href="/Grupo2_Etapa2Proy_SGPP-UES/public/index.php?page=home" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Volver al Dashboard
                </a>
            </div>
        </div>
    </div>

    <!-- Formulario para registrar nuevo avance -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-plus-circle me-2"></i>
                        Registrar Nuevo Avance
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label for="expediente_id" class="form-label">Estudiante *</label>
                                <select class="form-select" id="expediente_id" name="expediente_id" required>
                                    <option value="">Seleccionar estudiante...</option>
                                    <?php foreach ($expedientes_activos as $expediente): ?>
                                        <option value="<?= $expediente['id'] ?>">
                                            <?= htmlspecialchars($expediente['estudiante_nombre']) ?> - <?= htmlspecialchars($expediente['empresa_nombre']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label for="tipo" class="form-label">Tipo *</label>
                                <select class="form-select" id="tipo" name="tipo" required>
                                    <option value="">Seleccionar...</option>
                                    <option value="hito">Hito</option>
                                    <option value="reunion">Reunión</option>
                                    <option value="observacion">Observación</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label for="porcentaje_avance" class="form-label">% Avance</label>
                                <input type="number" class="form-control" id="porcentaje_avance" name="porcentaje_avance" 
                                       min="0" max="100" step="0.1" placeholder="0-100">
                            </div>
                            <div class="col-md-4">
                                <label for="descripcion" class="form-label">Descripción *</label>
                                <input type="text" class="form-control" id="descripcion" name="descripcion" 
                                       placeholder="Descripción del avance" required>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>Registrar Avance
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Avances recientes -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-history me-2"></i>
                        Avances Recientes
                    </h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($avances_recientes)): ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Fecha</th>
                                        <th>Estudiante</th>
                                        <th>Empresa</th>
                                        <th>Tipo</th>
                                        <th>Descripción</th>
                                        <th>% Avance</th>
                                        <th>Registrado por</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($avances_recientes as $avance): ?>
                                        <tr>
                                            <td><?= date('d/m/Y H:i', strtotime($avance['fecha_registro'])) ?></td>
                                            <td>
                                                <strong><?= htmlspecialchars($avance['estudiante_nombre']) ?></strong>
                                                <br><small class="text-muted"><?= htmlspecialchars($avance['estudiante_carrera']) ?></small>
                                            </td>
                                            <td><?= htmlspecialchars($avance['empresa_nombre']) ?></td>
                                            <td>
                                                <span class="badge bg-<?= $avance['tipo'] === 'hito' ? 'success' : ($avance['tipo'] === 'reunion' ? 'info' : 'warning') ?>">
                                                    <?= ucfirst($avance['tipo']) ?>
                                                </span>
                                            </td>
                                            <td><?= htmlspecialchars($avance['descripcion']) ?></td>
                                            <td>
                                                <?php if ($avance['porcentaje_avance']): ?>
                                                    <div class="d-flex align-items-center">
                                                        <div class="progress me-2" style="width: 50px; height: 20px;">
                                                            <div class="progress-bar" role="progressbar" style="width: <?= $avance['porcentaje_avance'] ?>%">
                                                            </div>
                                                        </div>
                                                        <small><?= $avance['porcentaje_avance'] ?>%</small>
                                                    </div>
                                                <?php else: ?>
                                                    <span class="text-muted">-</span>
                                                <?php endif; ?>
                                            </td>
                                            <td><?= htmlspecialchars($avance['docente_nombre'] ?? 'Sistema') ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="fas fa-clipboard-list fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No hay avances registrados</h5>
                            <p class="text-muted">Comienza a registrar el seguimiento de los estudiantes.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Resumen por estudiante -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-line me-2"></i>
                        Resumen de Avances por Estudiante
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <?php 
                        $avances_por_estudiante = [];
                        foreach ($avances_recientes as $avance) {
                            $estudiante_id = $avance['estudiante_id'];
                            if (!isset($avances_por_estudiante[$estudiante_id])) {
                                $avances_por_estudiante[$estudiante_id] = [
                                    'nombre' => $avance['estudiante_nombre'],
                                    'carrera' => $avance['estudiante_carrera'],
                                    'empresa' => $avance['empresa_nombre'],
                                    'total_avances' => 0,
                                    'ultimo_porcentaje' => 0
                                ];
                            }
                            $avances_por_estudiante[$estudiante_id]['total_avances']++;
                            if ($avance['porcentaje_avance'] > $avances_por_estudiante[$estudiante_id]['ultimo_porcentaje']) {
                                $avances_por_estudiante[$estudiante_id]['ultimo_porcentaje'] = $avance['porcentaje_avance'];
                            }
                        }
                        
                        foreach ($avances_por_estudiante as $estudiante_id => $data): 
                        ?>
                            <div class="col-md-6 col-lg-4 mb-3">
                                <div class="card border">
                                    <div class="card-body">
                                        <h6 class="card-title"><?= htmlspecialchars($data['nombre']) ?></h6>
                                        <p class="card-text">
                                            <small class="text-muted"><?= htmlspecialchars($data['carrera']) ?></small><br>
                                            <small class="text-muted"><?= htmlspecialchars($data['empresa']) ?></small>
                                        </p>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="badge bg-primary"><?= $data['total_avances'] ?> avances</span>
                                            <?php if ($data['ultimo_porcentaje'] > 0): ?>
                                                <small class="text-success"><?= $data['ultimo_porcentaje'] ?>%</small>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                        
                        <?php if (empty($avances_por_estudiante)): ?>
                            <div class="col-12">
                                <p class="text-muted text-center">No hay avances registrados para mostrar en el resumen.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../../src/includes/footer.php'; ?>
