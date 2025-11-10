<?php
$page_title = 'Evaluar Expedientes de Estudiantes';
include __DIR__ . '/../../src/includes/header.php';
?>

<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800"><?= htmlspecialchars($page_title) ?></h1>

    <?php include __DIR__ . '/../../src/includes/flash_messages.php'; ?>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Lista de Expedientes</h6>
        </div>
        <div class="card-body">
            <?php if (empty($expedientes)): ?>
                <div class="alert alert-info">No hay expedientes para mostrar.</div>
            <?php else: ?>
                <div class="accordion" id="accordionExpedientes">
                    <?php foreach ($expedientes as $index => $expediente): ?>
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="heading-<?= $expediente['id'] ?>">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-<?= $expediente['id'] ?>" aria-expanded="false" aria-controls="collapse-<?= $expediente['id'] ?>">
                                    <strong><?= htmlspecialchars($expediente['estudiante_nombre']) ?></strong>&nbsp;(<?= htmlspecialchars($expediente['estudiante_carnet']) ?>) -
                                    <span class="ms-2 badge bg-secondary"><?= htmlspecialchars($expediente['estado']) ?></span>
                                </button>
                            </h2>
                            <div id="collapse-<?= $expediente['id'] ?>" class="accordion-collapse collapse" aria-labelledby="heading-<?= $expediente['id'] ?>" data-bs-parent="#accordionExpedientes">
                                <div class="accordion-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h5>Detalles del Expediente</h5>
                                            <ul class="list-group list-group-flush">
                                                <li class="list-group-item"><strong>Carrera:</strong> <?= htmlspecialchars($expediente['estudiante_carrera']) ?></li>
                                                <li class="list-group-item"><strong>Empresa:</strong> <?= htmlspecialchars($expediente['empresa_catalogo'] ?? $expediente['empresa_nombre'] ?? 'N/A') ?></li>
                                                <li class="list-group-item"><strong>Supervisor:</strong> <?= htmlspecialchars($expediente['supervisor_externo'] ?? 'N/A') ?></li>
                                                <li class="list-group-item"><strong>Fecha de Inicio:</strong> <?= htmlspecialchars(date('d/m/Y', strtotime($expediente['fecha_inicio']))) ?></li>
                                            </ul>
                                        </div>
                                        <div class="col-md-6">
                                            <h5>Evaluación Final</h5>
                                            <form method="POST" action="/Grupo2_Etapa2Proy_SGPP-UES/public/index.php?page=evaluar">
                                                <input type="hidden" name="expediente_id" value="<?= $expediente['id'] ?>">
                                                
                                                <div class="mb-3">
                                                    <label for="nota_final_<?= $expediente['id'] ?>" class="form-label">Nota Final (0.00 - 10.00)</label>
                                                    <input type="number" step="0.01" min="0" max="10" class="form-control" id="nota_final_<?= $expediente['id'] ?>" name="nota_final" value="<?= htmlspecialchars($expediente['evaluacion']['nota_final'] ?? '') ?>">
                                                </div>

                                                <div class="mb-3">
                                                    <label for="comentario_docente_<?= $expediente['id'] ?>" class="form-label">Comentarios</label>
                                                    <textarea class="form-control" id="comentario_docente_<?= $expediente['id'] ?>" name="comentario_docente" rows="4"><?= htmlspecialchars($expediente['evaluacion']['comentario_docente'] ?? '') ?></textarea>
                                                </div>

                                                <button type="submit" class="btn btn-primary">
                                                    <i class="fas fa-save"></i> Guardar Evaluación
                                                </button>

                                                <?php if ($expediente['evaluacion']): ?>
                                                    <p class="mt-2 small text-muted">
                                                        Última actualización: <?= htmlspecialchars(date('d/m/Y H:i', strtotime($expediente['evaluacion']['fecha_evaluacion']))) ?>
                                                    </p>
                                                <?php endif; ?>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../../src/includes/footer.php'; ?>
