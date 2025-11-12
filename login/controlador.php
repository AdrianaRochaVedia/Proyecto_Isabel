<?php
session_start();
require_once __DIR__ . '/conexion.php';

header('Content-Type: text/html; charset=utf-8'); 

function swal_page(string $icon, string $title, string $text, string $afterJs = ''): void {
  echo "<!doctype html>
<html lang='es'>
<head>
  <meta charset='utf-8'>
  <meta name='viewport' content='width=device-width, initial-scale=1'>
  <title>Mensaje</title>
  <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
  <style>html,body{height:100%;margin:0}body{display:flex;align-items:center;justify-content:center;font-family:system-ui}</style>
</head>
<body>
<script>
  Swal.fire({
    icon: ".json_encode($icon).",
    title: ".json_encode($title).",
    text: ".json_encode($text).",
    ".($icon==='success' ? "timer:1500, showConfirmButton:false," : "")."
  }).then(function(){ $afterJs });
</script>
</body>
</html>";
  exit;
}

if (isset($_POST['Ingresar'])) {
  $usuario = trim($_POST['usuario'] ?? '');
  $clave   = trim($_POST['contra'] ?? '');

  if ($usuario === '' || $clave === '') {
    swal_page('info', 'Campos vacíos', 'Completa usuario y contraseña.', 'history.back();');
  }

  $stmt = $conexion->prepare("
    SELECT id_usuario, nombre, apellido, rol, contrasenia
    FROM usuario
    WHERE (usuario = ? OR correo = ?)
    LIMIT 1
  ");

  if (!$stmt) {
    swal_page('error', 'Error interno', 'Problema con la base de datos.', 'history.back();');
  }

  $stmt->bind_param('ss', $usuario, $usuario);
  $stmt->execute();
  $res = $stmt->get_result();

  if ($datos = $res->fetch_object()) {
    $ok = false;
    if (preg_match('/^\$2[aby]\$/', $datos->contrasenia)) {
      $ok = password_verify($clave, $datos->contrasenia);
    } else {

      $ok = hash_equals((string)$datos->contrasenia, $clave);
    }

    if ($ok) {

      $_SESSION['id']       = (int)$datos->id_usuario;
      $_SESSION['nombre']   = (string)$datos->nombre;
      $_SESSION['apellido'] = (string)$datos->apellido;
      $_SESSION['tipo']     = (string)$datos->rol;
      $_SESSION['is_admin'] = ($datos->rol === 'ADMINISTRADOR') ? 1 : 0;

      swal_page('success', 'Bienvenido', 'Inicio de sesión correcto.', "window.location.href = '../contenido/lecciones.php';");
    }
  }

  swal_page('error', 'Acceso denegado', 'Usuario o contraseña incorrectos.', 'history.back();');
}

swal_page('info', 'Sin datos', 'Abre el formulario de login.', "window.location.href = 'login.php';");
