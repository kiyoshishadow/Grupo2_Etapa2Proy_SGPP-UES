<?php require_once __DIR__ . '/../src/includes/header.php'; ?>

<div class="login-container">
    <h1>Iniciar Sesión</h1>
    <?php
    if (isset($_GET['error'])) {
        echo '<p class="error">' . htmlspecialchars($_GET['error']) . '</p>';
    }
    ?>
    <form action="../src/auth/login.php" method="post">
        <input type="text" name="nombre_usuario" placeholder="Usuario" required>
        <input type="password" name="contrasena" placeholder="Contraseña" required>
        <input type="submit" value="Login">
    </form>
</div>

<?php require_once __DIR__ . '/../src/includes/footer.php'; ?>
