<?php
session_start();
session_unset();
session_destroy();
header("Location: /Grupo2_Etapa2Proy_SGPP-UES/public/");
exit;
?>