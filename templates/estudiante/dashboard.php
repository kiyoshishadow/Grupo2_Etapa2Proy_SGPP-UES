<?php require_once __DIR__ . '/../../src/includes/header.php'; ?>

<div class="container">
    <h2>Dashboard del Estudiante</h2>
    <p>Bienvenido, <?php echo htmlspecialchars($_SESSION['nombre_usuario']); ?>.</p>
    <p>Aquí puedes ver el estado de tu práctica profesional, subir informes y ver tus calificaciones.</p>
    <ul>
        <li><a href="#">Ver mi Práctica</a></li>
        <li><a href="#">Subir Informe Mensual</a></li>
        <li><a href="#">Ver Calificaciones</a></li>
    </ul>
</div>

