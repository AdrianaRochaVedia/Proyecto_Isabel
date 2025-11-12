<?php
session_start();

if (empty($_SESSION['id']) || empty($_SESSION['tipo'])) {
    header("Location: /login/login.php");
    exit();
}

$cn = mysqli_connect("localhost","root","","SISSOCIALES");
if(!$cn){ die("Error BD: " . mysqli_connect_error()); }
mysqli_set_charset($cn,"utf8mb4");

$regenerarPath = __DIR__."/regenerar_html.php";
if(file_exists($regenerarPath)){
  require_once $regenerarPath;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST'){
  $id_leccion = (int)($_POST['id_leccion'] ?? 0);
  $orden = (int)($_POST['orden'] ?? 0);
  
  if ($id_leccion <= 0 || $orden <= 0) {
    die("Error: Datos inválidos. ID Lección: $id_leccion, Orden: $orden");
  }

  $checkSubtema = mysqli_query($cn, "SELECT id_subtema FROM SUBTEMA WHERE LECCION_id_leccion=$id_leccion AND orden=$orden");
  if(mysqli_num_rows($checkSubtema) === 0){
    die("Error: No se encontró el subtema con orden $orden en la lección $id_leccion");
  }

  $deleteResult = mysqli_query($cn, "DELETE FROM SUBTEMA WHERE LECCION_id_leccion=$id_leccion AND orden=$orden");
  if(!$deleteResult){
    die("Error al eliminar subtema: " . mysqli_error($cn));
  }
  
  $reorderResult = mysqli_query($cn, "UPDATE SUBTEMA SET orden=orden-1 WHERE LECCION_id_leccion=$id_leccion AND orden>$orden");
  if(!$reorderResult){
    die("Error al reordenar subtemas: " . mysqli_error($cn));
  }
  
  $galDir = __DIR__."/uploads/lecciones/$id_leccion/subtemas/subtema-$orden";
  if (is_dir($galDir)){
    $files = glob($galDir."/*");
    foreach($files as $file){
      if(is_file($file)) unlink($file);
    }
    rmdir($galDir);
  }

  $htmlFile = __DIR__."/lecciones_html/leccion-$id_leccion-s$orden.html";
  if(file_exists($htmlFile)){
    unlink($htmlFile);
  }

  if(function_exists('regenerar_leccion_html')){
    $tituloImgRel = path_title_image_rel($id_leccion);
    regenerar_leccion_html($cn, $id_leccion, $tituloImgRel);
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
    title: '✓ ¡Subtema Eliminado!',
    text: 'El subtema fue eliminado correctamente',
    confirmButtonColor: '#f6a89e',
    confirmButtonText: 'Volver a la lección',
    background: '#fff',
    color: '#333'
  }).then(() => {
    window.location.href = 'lecciones_html/leccion-$id_leccion.html';
  });
  </script></body></html>";
  exit;
} else {
  die("Método no permitido. Use POST para eliminar.");
}
?>