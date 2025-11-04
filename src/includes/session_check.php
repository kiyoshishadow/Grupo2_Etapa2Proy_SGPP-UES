<?php
function check_session($allowed_roles) {
    if (!isset($_SESSION['user_id'])) {
        header("Location: index.php?page=login&error=Debes iniciar sesión");
        exit;
    }

    if (!in_array($_SESSION['rol_nombre'], $allowed_roles)) {
        header("Location: index.php?page=login&error=No tienes permiso para acceder a esta página");
        exit;
    }
}
?>