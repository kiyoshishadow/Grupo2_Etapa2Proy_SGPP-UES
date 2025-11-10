<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>SGPP-UES</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/Grupo2_Etapa2Proy_SGPP-UES/public/css/style.css">
</head>
<body>
  <div class="main-wrapper d-flex flex-column min-vh-100">
    <nav class="navbar navbar-ues navbar-expand-lg navbar-dark shadow-sm py-2">
      <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="/Grupo2_Etapa2Proy_SGPP-UES/public/index.php">
          <img src="/Grupo2_Etapa2Proy_SGPP-UES/public/img/logo_bl_v2.png" alt="Universidad de El Salvador">
          <span>SGPP-UES</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain" aria-controls="navbarMain" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarMain">
          <ul class="navbar-nav ms-auto align-items-lg-center">
            <?php if (isset($_SESSION['rol_nombre'])): ?>
              <?php if ($_SESSION['rol_nombre'] === 'Estudiante'): ?>
                <!-- El menú de estudiante puede llevar a su dashboard o a una página de perfil -->
                <li class="nav-item">
                   <a class="nav-link text-white" href="/Grupo2_Etapa2Proy_SGPP-UES/public/index.php?page=mis_registros">Mi Expediente</a>
                </li>
                <li class="nav-item">
                   <a class="nav-link text-white" href="/Grupo2_Etapa2Proy_SGPP-UES/public/index.php?page=estudiante_elegir_docente">Elegir Docente</a>
                </li>
              <?php elseif ($_SESSION['rol_nombre'] === 'Docente'): ?>
                <li class="nav-item dropdown">
                  <a class="nav-link dropdown-toggle text-white" href="#" id="navDocente" role="button" data-bs-toggle="dropdown">
                    Menú docente
                  </a>
                  <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="/Grupo2_Etapa2Proy_SGPP-UES/public/index.php?page=evaluar">Evaluar Expedientes</a></li>
                  </ul>
                </li>
              <?php elseif ($_SESSION['rol_nombre'] === 'Admin'): ?>
                <li class="nav-item dropdown">
                  <a class="nav-link dropdown-toggle text-white" href="#" id="navAdmin" role="button" data-bs-toggle="dropdown">
                    Menú admin
                  </a>
                  <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="/Grupo2_Etapa2Proy_SGPP-UES/public/index.php?page=admin_usuarios">Gestionar usuarios</a></li>
                    <li><a class="dropdown-item" href="/Grupo2_Etapa2Proy_SGPP-UES/public/index.php?page=admin_expedientes">Gestionar expedientes</a></li>
                    <li><a class="dropdown-item" href="/Grupo2_Etapa2Proy_SGPP-UES/public/index.php?page=admin_practicas">Gestionar prácticas</a></li>
                    <li><a class="dropdown-item" href="/Grupo2_Etapa2Proy_SGPP-UES/public/index.php?page=admin_reportes_expedientes">Reporte de Activos</a></li>
                  </ul>
                </li>
              <?php endif; ?>
            <?php endif; ?>
            <li class="nav-item me-lg-3">
              <span class="nav-link px-0 text-white-50">Gestión de prácticas profesionales</span>
            </li>
            <?php if (isset($_SESSION['user_id'])): ?>
              <li class="nav-item">
                <a class="btn btn-outline-light" href="/Grupo2_Etapa2Proy_SGPP-UES/public/index.php?page=logout">Salir</a>
              </li>
            <?php endif; ?>
          </ul>
        </div>
      </div>
    </nav>
    <main class="main-content flex-grow-1 py-4">
      <div class="container">
