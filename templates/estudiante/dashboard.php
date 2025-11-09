<?php require_once __DIR__ . '/../../src/includes/header.php'; ?>

<div class="container">
  <h2>Dashboard del Estudiante</h2>
  <p>Bienvenido, <?php echo htmlspecialchars($_SESSION['nombre_usuario']); ?>.</p>

  <ul>
    <!-- Ver prácticas disponibles -->
    <li>
      <a href="/Grupo2_Etapa2Proy_SGPP-UES/public/index.php?page=practicas_disponibles">
        Ver prácticas disponibles
      </a>
    </li>

    <!-- Ver mis inscripciones -->
    <li>
      <a href="/Grupo2_Etapa2Proy_SGPP-UES/public/index.php?page=mis_registros">
        Mis inscripciones / resultados
      </a>
    </li>

    <!-- Cerrar sesión -->
    <li>
      <a href="/Grupo2_Etapa2Proy_SGPP-UES/public/index.php?page=logout">
        Cerrar sesión
      </a>
    </li>
  </ul>
</div>
