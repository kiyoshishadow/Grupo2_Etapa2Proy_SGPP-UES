<?php require_once __DIR__ . '/../../src/includes/header.php'; ?>

<div class="container">
    <h2>Dashboard del Administrador</h2>
    <p>Bienvenido, <?php echo htmlspecialchars($_SESSION['nombre_usuario']); ?>.</p>
    <p>Desde aquí puedes gestionar usuarios, roles, y ver reportes generales del sistema.</p>
    <ul>
        <li><a href="#">Gestionar Usuarios</a></li>
        <li><a href="#">Gestionar Prácticas</a></li>
        <li><a href="#">Ver Reportes</a></li>
    </ul>
</div>

