<?php
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
    body { background: #f3f4f6; }
    .login-wrap { max-width: 1240px; margin: 56px auto; transform: scale(0.9); transform-origin: top center; }
    .card-login { border-radius: 28px; overflow: hidden; box-shadow: 0 6px 20px rgba(0,0,0,0.08); background: #fff; border: 1px solid #e6e6e6; }
    .brand-side { background: #fff; display: flex; align-items: center; justify-content: center; padding: 28px; }
    .brand-img { width: 100%; height: auto; border-radius: 24px; }
    .form-side { background: #fff; padding: 48px 56px; }
    .logo-ues { height: 64px; margin-bottom: 18px; display: block; margin-left: auto; margin-right: auto; }
    .small-text { font-size: .93rem; color: #555; }
    .input-group-text { background: #fff; }
    .form-control { height: 48px; border-radius: 8px; }
    .btn-eye { border: none; background: transparent; color: #6c757d; }
    .eye-addon { background: #fff; cursor: pointer; }
    .btn-primary, .btn-danger { background-color: #9b0d0d; border-color: #9b0d0d; }
    .btn-primary:hover, .btn-danger:hover { background-color: #7f0a0a; border-color: #7f0a0a; }
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

        <?php if (isset($_GET['error']) && $_GET['error'] === 'timeout'): ?>
          <div class="alert alert-warning text-center" role="alert">
            Su sesión ha excedido el tiempo límite. Por favor, acceda de nuevo.
          </div>
        <?php elseif (isset($_GET['error'])): ?>
          <div class="alert alert-danger text-center" role="alert">
            <?php echo htmlspecialchars($_GET['error']); ?>
          </div>
        <?php endif; ?>

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
          <div class="mb-2 text-end">
            <a href="#" class="small-text">¿Olvidó su nombre de usuario o contraseña?</a>
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
