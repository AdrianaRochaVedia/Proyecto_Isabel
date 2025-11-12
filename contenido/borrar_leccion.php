<?php
session_start();

if (empty($_SESSION['id']) || empty($_SESSION['tipo'])) {
    header("Location: /login/login.php");
    exit();
}

if ($_SESSION['tipo'] !== 'ADMINISTRADOR') {
    die("Error: No tienes permisos para eliminar lecciones");
}

$cn = mysqli_connect("localhost","root","","SISSOCIALES");
if(!$cn){ die("Error BD: " . mysqli_connect_error()); }
mysqli_set_charset($cn,"utf8mb4");

$id_leccion = (int)($_POST['id_leccion'] ?? 0);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['confirmar'] ?? '') === 'si'){
  if ($id_leccion <= 0) {
    die("Error: Lección inválida.");
  }
  
mysqli_query($cn, "DELETE FROM EVALUACION WHERE LECCION_id_leccion=$id_leccion");


mysqli_query($cn, "DELETE FROM SUBTEMA WHERE LECCION_id_leccion=$id_leccion");


mysqli_query($cn, "DELETE FROM LECCION WHERE id_leccion=$id_leccion");

  
  function eliminarDirectorio($dir) {
    if (!is_dir($dir)) return;
    $items = scandir($dir);
    foreach ($items as $item) {
      if ($item == '.' || $item == '..') continue;
      $path = $dir . '/' . $item;
      if (is_dir($path)) {
        eliminarDirectorio($path);
      } else {
        unlink($path);
      }
    }
    rmdir($dir);
  }
  
  $dirLeccion = __DIR__."/uploads/lecciones/$id_leccion";
  if (is_dir($dirLeccion)){
    eliminarDirectorio($dirLeccion);
  }

  $htmlDir = __DIR__."/lecciones_html";
  if (is_dir($htmlDir)){
    $files = glob($htmlDir."/leccion-$id_leccion*.html");
    foreach($files as $file){
      if(is_file($file)) unlink($file);
    }
  }
  
  echo "<!doctype html><html><head><meta charset='utf-8'><script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
  <style>

  body{
    font-family:'Segoe UI',Arial,sans-serif;
    display:flex;
    align-items:center;
    justify-content:center;
    min-height:100vh;
    background:linear-gradient(135deg,#ffd7cc,#fde2dc);
    margin:0;
  }
  </style>
  </head><body>
  <script>
  Swal.fire({
    icon: 'success',
    title: '✓ ¡Lección Eliminada!',
    text: 'La lección y todos sus subtemas fueron eliminados correctamente',
    confirmButtonColor: '#f6a89e',
    confirmButtonText: 'Ir a lecciones',
    background: '#fff',
    color: '#333'
  }).then(() => {
    window.location.href = 'lecciones.php';
  });
  </script></body></html>";
  exit;
}

die("Error: Falta confirmación para eliminar. Use el botón 'Eliminar lección' desde la interfaz.");
?>