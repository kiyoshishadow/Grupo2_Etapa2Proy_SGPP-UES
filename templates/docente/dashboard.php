<?php require_once __DIR__ . '/../../src/includes/header.php'; ?>

<div class="container">
    <h2>Dashboard del Docente</h2>
    <p>Bienvenido, <?php echo htmlspecialchars($_SESSION['nombre_usuario'] ?? 'docente'); ?>.</p>
    <p>Desde aquí puedes supervisar a tus estudiantes, revisar informes y asignar calificaciones.</p>

    <ul>
        <li>
            <a href="/Grupo2_Etapa2Proy_SGPP-UES/public/index.php?page=docente_practicas">
                Gestionar / Ver mis prácticas
            </a>
        </li>

        <li>
            <a href="/Grupo2_Etapa2Proy_SGPP-UES/public/index.php?page=docente_practicas&action=create">
                Crear nueva práctica
            </a>
        </li>

        <li>
            <a href="/Grupo2_Etapa2Proy_SGPP-UES/public/index.php?page=docente_practicas">
                Ver postulantes (elige una práctica)
            </a>
        </li>
    </ul>

    <!-- Botón de Cerrar sesión al FINAL del dashboard -->
    <p style="margin-top: 20px;">
        <a href="/Grupo2_Etapa2Proy_SGPP-UES/public/index.php?page=logout">Cerrar sesión</a>
    </p>
</div>


