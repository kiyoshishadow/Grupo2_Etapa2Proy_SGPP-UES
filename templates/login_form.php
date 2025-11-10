<?php require_once __DIR__ . '/../src/includes/header.php'; ?>

<section class="d-flex align-items-center justify-content-center min-vh-100">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-6 col-lg-5">
        <?php require __DIR__ . '/../src/includes/flash_messages.php'; ?>
        <div class="card border-0 shadow-sm">
          <div class="card-body p-4 p-md-5">
            <h1 class="h4 text-center mb-4">Iniciar Sesión</h1>
            <form action="../src/auth/login.php" method="post" class="row g-3">
              <div class="col-12">
                <label class="form-label">Usuario</label>
                <input class="form-control" type="text" name="nombre_usuario" placeholder="Usuario" required>
              </div>
              <div class="col-12">
                <label class="form-label">Contraseña</label>
                <input class="form-control" type="password" name="contrasena" placeholder="Contraseña" required>
              </div>
              <div class="col-12">
                <button class="btn btn-primary w-100" type="submit">Ingresar</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<?php require_once __DIR__ . '/../src/includes/footer.php'; ?>
