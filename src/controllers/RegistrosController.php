<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

<?php
session_start();
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../models/RegistroModel.php';
require_once __DIR__ . '/../models/PracticaModel.php';

$reg = new RegistroModel($pdo);
$prac = new PracticaModel($pdo);
$action = $_GET['action'] ?? 'lista';

switch ($action) {
  case 'lista':
    $lista = $prac->disponibles();
    include __DIR__ . '/../../templates/estudiante/practicas_disponibles.php';
    break;

  case 'inscribir':
    $reg->inscribir((int)$_GET['practica_id'], $_SESSION['estudiante_id'] ?? 1);
    header('Location: /Grupo2_Etapa2Proy_SGPP-UES/public/index.php?page=mis_registros');
    break;

  case 'mis_registros':
    $registros = $reg->misRegistros($_SESSION['estudiante_id'] ?? 1);
    include __DIR__ . '/../../templates/estudiante/mis_registros.php';
    break;

  default: http_response_code(404); echo "Acción no válida";
}
