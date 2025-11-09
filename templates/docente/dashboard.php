 <?php require_once __DIR__ . '/../../src/includes/header.php'; ?>

<div class="container">
    <h2>Dashboard del Docente</h2>
    <p>Bienvenido, <?php echo htmlspecialchars($_SESSION['nombre_usuario']); ?>.</p>
    <p>Desde aquí puedes supervisar a tus estudiantes, revisar informes y asignar calificaciones.</p>

    <ul>
        <!-- Coloca páginas reales en lugar de # -->
        <li><a href="/Grupo2_Etapa2Proy_SGPP-UES/public/index.php?page=docente_practicas">
            Gestionar / Ver mis prácticas
        </a></li>

        <li><a href="/Grupo2_Etapa2Proy_SGPP-UES/public/index.php?page=docente_practicas&action=create">
            Crear nueva práctica
        </a></li>

        <!-- Si quieres tener un acceso a postulantes, primero elige práctica desde la lista -->
        <li><a href="/Grupo2_Etapa2Proy_SGPP-UES/public/index.php?page=docente_practicas">
            Ver postulantes (elige una práctica)
        </a></li>
    </ul>
</div>
