<?php require_once __DIR__ . '/../docente/dashboard.php'; ?>
<h2>Postulantes</h2>
<table border="1" cellpadding="6">
    <tr>
        <th>Estudiante</th>
        <th>Estado</th>
        <th>Fecha de postulación</th>
    </tr>
    <?php foreach ($postulantes as $r): ?>
    <tr>
        <td><?= htmlspecialchars($r['nombre_completo'] ?? '-') ?></td>
        <td><?= htmlspecialchars($r['estado'] ?? 'postulada') ?></td>
        <td><?= htmlspecialchars($r['fecha_postulacion'] ?? '-') ?></td>
    </tr>
    <?php endforeach; ?>
</table>

