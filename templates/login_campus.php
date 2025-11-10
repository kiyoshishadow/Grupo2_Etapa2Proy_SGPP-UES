<?php
require_once __DIR__ . '/../src/includes/flash.php';
// Standalone login page styled similar to Campus UES
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Entrar al sitio | Campus UES</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
  <style>
    :root {
      --ues-red: #7a0b0b;
      --ues-red-light: #9a2b2b;
      --ues-red-dark: #5a0505;
    }
    body { background: #f3f4f6; }
    .login-wrap { max-width: 900px; margin: 40px auto; transform: scale(0.85); transform-origin: top center; }
    .card-login { border-radius: 20px; overflow: hidden; box-shadow: 0 6px 20px rgba(0,0,0,0.08); background: #fff; border: 1px solid #e6e6e6; }
    .brand-side { background: #fff; display: flex; align-items: center; justify-content: center; padding: 20px; }
    .brand-img { width: 100%; height: auto; border-radius: 16px; }
    .form-side { background: #fff; padding: 32px 40px; }
    .logo-ues { height: 48px; margin-bottom: 16px; display: block; margin-left: auto; margin-right: auto; }
    .small-text { font-size: .93rem; color: #555; }
    .input-group-text { background: #fff; }
    .form-control { height: 48px; border-radius: 8px; }
    .btn-eye { border: none; background: transparent; color: #6c757d; }
    .eye-addon { background: #fff; cursor: pointer; }
    .btn-primary, .btn-danger { background-color: var(--ues-red); border-color: var(--ues-red); }
    .btn-primary:hover, .btn-danger:hover { background-color: var(--ues-red-light); border-color: var(--ues-red-light); }
    .info-box { background: #e9f2ff; border: 1px solid #cfe1ff; padding: 14px; border-radius: 10px; color: #1b4b91; }
    .badges { background: #e9f2ff; border: 1px solid #cfe1ff; border-radius: 10px; padding: 10px 12px; display: inline-flex; gap: 12px; }
    .badges img { height: 34px; }
    @media (max-width: 992px){
      .login-wrap{ transform: none; }
    }
  </style>
</head>
<body>
  <div class="login-wrap">
    <div class="row g-0 card-login">
      <div class="col-lg-7 brand-side">
        <img class="brand-img" src="../public/img/aulavirtualues.png" alt="Campus UES">
      </div>
      <div class="col-lg-5 form-side">
        <img class="logo-ues" src="../public/img/minerva_v2_r.png" alt="Universidad de El Salvador">

        <?php
        $flash_messages = flash_pull_all();
        foreach ($flash_messages as $msg):
          $type = htmlspecialchars($msg['type'], ENT_QUOTES, 'UTF-8');
          $message = htmlspecialchars($msg['message'], ENT_QUOTES, 'UTF-8');
        ?>
          <div class="alert alert-<?= $type ?> alert-dismissible fade show text-center" role="alert">
            <?= $message ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
        <?php endforeach; ?>

        <form action="../src/auth/login.php" method="post">
          <div class="mb-3">
            <label for="username" class="form-label">Nombre de usuario</label>
            <div class="input-group input-group-lg">
              <span class="input-group-text"><i class="bi bi-person"></i></span>
              <input type="text" class="form-control" id="username" name="nombre_usuario" placeholder="Nombre de usuario" required>
            </div>
          </div>
          <div class="mb-3">
            <label for="password" class="form-label">Contraseña</label>
            <div class="input-group input-group-lg">
              <span class="input-group-text"><i class="bi bi-key"></i></span>
              <input type="password" class="form-control" id="password" name="contrasena" placeholder="Contraseña" required>
              <span class="input-group-text eye-addon" onclick="togglePassword()" title="Mostrar/Ocultar">
                <i id="eyeIcon" class="bi bi-eye"></i>
              </span>
            </div>
          </div>
          <div class="d-grid mb-3">
            <button type="submit" class="btn btn-danger btn-lg py-2">Acceder</button>
          </div>
          <div class="info-box mb-3 text-center">
            Ingrese con su usuario institucional. No necesita escribir @ues.edu.sv
          </div>
        </form>

        <div class="mt-3 text-center">
          <div class="badges">
            <a href="https://apps.apple.com/app/id1554535606" target="_blank"><img src="../public/img/download-on-the-app-store.svg" alt="iOS"></a>
            <a href="https://play.google.com/store/apps/details?id=com.campusues.moodle" target="_blank"><img src="../public/img/unnamed.png" alt="Android"></a>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    function togglePassword() {
      var pwd = document.getElementById('password');
      var btn = pwd.nextElementSibling;
      if (pwd.type === 'password') { pwd.type = 'text'; btn.textContent = 'Ocultar'; }
      else { pwd.type = 'password'; btn.textContent = 'Mostrar'; }
    }
  </script>
</body>
</html>
