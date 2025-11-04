<?php require_once __DIR__ . '/../../src/includes/header.php'; ?>

<div class="container">
    <h2>Dashboard del Docente</h2>
    <p>Bienvenido, <?php echo htmlspecialchars($_SESSION['nombre_usuario']); ?>.</p>
    <p>Desde aquí puedes supervisar a tus estudiantes, revisar informes y asignar calificaciones.</p>
    <ul>
        <li><a href="#">Ver Estudiantes Asignados</a></li>
        <li><a href="#">Revisar Informes</a></li>
        <li><a href="#">Calificar Proyectos</a></li>
    </ul>
</div>

