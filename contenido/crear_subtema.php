<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (empty($_SESSION['id']) || empty($_SESSION['tipo'])) {
    header("Location: /login/login.php");
    exit();
}

if (!isset($_SESSION['tipo']) || $_SESSION['tipo'] !== 'ADMINISTRADOR') {
  die("<!doctype html><html><head><meta charset='utf-8'><title>Acceso Denegado</title><style>body{font-family:system-ui;display:flex;align-items:center;justify-content:center;height:100vh;margin:0;background:linear-gradient(135deg,#ffd7cc,#fde2dc);color:#b42b6f}</style></head><body><div style='text-align:center'><h1>🚫 Acceso Denegado</h1><p>No tienes permisos para crear subtemas.</p><a href='../contenido/lecciones.php' style='color:#f28b6b;font-weight:bold'>← Volver a lecciones</a></div></body></html>");
}

$cn = mysqli_connect("localhost","root","","SISSOCIALES");
if(!$cn){ die("Error de conexión: ".mysqli_connect_error()); }
mysqli_set_charset($cn,"utf8mb4");

$regenerarPath = __DIR__."/regenerar_html.php";
if(!file_exists($regenerarPath)){
  die("Error: Falta el archivo regenerar_html.php en la carpeta del proyecto.");
}
require_once $regenerarPath;

if ($_SERVER['REQUEST_METHOD'] !== 'POST' && !isset($_POST['id_leccion']) && !isset($_POST['accion'])) {
    die("<!doctype html><html><head><meta charset='utf-8'><title>Acceso Denegado</title><style>body{font-family:system-ui;display:flex;align-items:center;justify-content:center;height:100vh;margin:0;background:linear-gradient(135deg,#ffd7cc,#fde2dc);color:#b42b6f}</style></head><body><div style='text-align:center'><h1>🚫 Acceso Incorrecto</h1><p>Usa el botón de crear subtema desde la lección.</p></div></body></html>");
}

$id_leccion = (int)($_POST['id_leccion'] ?? 0);
if ($id_leccion <= 0) die("ID de lección inválido.");

$leccion = mysqli_fetch_assoc(mysqli_query($cn,"SELECT titulo FROM LECCION WHERE id_leccion=$id_leccion"));
if(!$leccion) die("Lección no encontrada.");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['accion'] ?? '') === 'crear'){
  $titulo = trim($_POST['titulo'] ?? '');
  $cuerpo = trim($_POST['cuerpo'] ?? '');
  
  if ($titulo === '' || $cuerpo === '') {
    die("<!doctype html><html><head><meta charset='utf-8'><script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script></head><body><script>
    Swal.fire({
      icon: 'error',
      title: 'Campos incompletos',
      text: 'Debes completar el título y el contenido del subtema',
      confirmButtonColor: '#f6a89e'
    }).then(() => {
      window.history.back();
    });
    </script></body></html>");
  }

  $maxOrden = mysqli_fetch_row(mysqli_query($cn,"SELECT MAX(orden) FROM SUBTEMA WHERE LECCION_id_leccion=$id_leccion"));
  $orden = ($maxOrden && $maxOrden[0]) ? (int)$maxOrden[0] + 1 : 1;

  $st = mysqli_prepare($cn,"INSERT INTO SUBTEMA (titulo, cuerpo_html, orden, LECCION_id_leccion) VALUES (?, ?, ?, ?)");
  mysqli_stmt_bind_param($st,"ssii",$titulo,$cuerpo,$orden,$id_leccion);
  mysqli_stmt_execute($st);
  $id_subtema = mysqli_insert_id($cn);
  mysqli_stmt_close($st);

  if (!empty($_FILES['galeria']['name'][0])) {
    $galDir = __DIR__."/uploads/lecciones/$id_leccion/subtemas/subtema-$orden";
    @mkdir($galDir, 0777, true);
    
    foreach ($_FILES['galeria']['tmp_name'] as $key => $tmp_name) {
      if ($_FILES['galeria']['error'][$key] === UPLOAD_ERR_OK) {
        $fileName = basename($_FILES['galeria']['name'][$key]);
        $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        if (in_array($ext, ['jpg', 'jpeg', 'png'])) {
          $newName = uniqid('img_') . '.' . $ext;
          move_uploaded_file($tmp_name, $galDir . '/' . $newName);
        }
      }
    }
  }

  $tituloImgRel = path_title_image_rel($id_leccion);
  regenerar_leccion_html($cn, $id_leccion, $tituloImgRel);

  echo "<!doctype html><html><head><meta charset='utf-8'><script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script></head><body><script>
  Swal.fire({
    icon: 'success',
    title: ' ¡Subtema Creado!',
    text: 'El subtema se ha guardado correctamente',
    confirmButtonColor: '#f6a89e',
    timer: 2000,
    showConfirmButton: false
  }).then(() => {
    window.location.href = 'lecciones_html/leccion-$id_leccion-s$orden.html';
  });
  </script></body></html>";
  exit;
}

$tituloLeccion = htmlspecialchars($leccion['titulo']);
?>
<!doctype html>
<html lang="es"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Crear Subtema</title>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
*{box-sizing:border-box;margin:0;padding:0}
body{
  min-height:100vh;
  display:flex;
  align-items:center;
  justify-content:center;
  background:linear-gradient(135deg, #ffd7cc 0%, #fde2dc 50%, #ffe7e2 100%);
  font-family:'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  padding:20px;
}
.container{
  width:100%;
  max-width:680px;
  animation:fadeIn 0.6s ease-out;
}
@keyframes fadeIn{
  from{opacity:0;transform:translateY(20px)}
  to{opacity:1;transform:translateY(0)}
}
.form{
  background:#fff;
  border:2px solid #f5cfd0;
  border-radius:24px;
  padding:32px;
  box-shadow:0 20px 60px rgba(242, 139, 107, 0.2);
}
.header{
  text-align:center;
  margin-bottom:28px;
}
.header h2{
  color:#b42b6f;
  font-size:32px;
  font-weight:900;
  margin-bottom:8px;
  text-shadow:2px 2px 4px rgba(180, 43, 111, 0.1);
}
.header p{
  color:#666;
  font-size:15px;
}
.leccion-badge{
  display:inline-block;
  background:linear-gradient(135deg, #f6a89e, #f28b6b);
  color:#fff;
  padding:8px 16px;
  border-radius:20px;
  font-size:14px;
  font-weight:700;
  margin-top:12px;
}
.form-group{
  margin-bottom:20px;
}
label{
  display:block;
  font-weight:700;
  color:#b42b6f;
  margin-bottom:8px;
  font-size:15px;
}
label span{
  color:#f28b6b;
  font-size:12px;
}
input[type="text"],
textarea{
  width:100%;
  padding:14px;
  border:2px solid #f5cfd0;
  border-radius:12px;
  font-size:15px;
  font-family:inherit;
  transition:all 0.3s ease;
  background:#fafafa;
}
input[type="text"]:focus,
textarea:focus{
  outline:none;
  border-color:#f6a89e;
  background:#fff;
  box-shadow:0 0 0 4px rgba(246, 168, 158, 0.1);
}
textarea{
  resize:vertical;
  min-height:140px;
}
input[type="file"]{
  width:100%;
  padding:14px;
  border:2px dashed #f5cfd0;
  border-radius:12px;
  background:#fafafa;
  cursor:pointer;
  font-size:14px;
  transition:all 0.3s ease;
}
input[type="file"]:hover{
  border-color:#f6a89e;
  background:#fff;
}
.file-hint{
  font-size:12px;
  color:#666;
  margin-top:6px;
  font-style:italic;
}
.divider{
  height:1px;
  background:linear-gradient(to right, transparent, #f5cfd0, transparent);
  margin:24px 0;
}
.btn-group{
  display:flex;
  gap:12px;
}
button,
.btn-cancel{
  flex:1;
  padding:16px;
  border:none;
  border-radius:14px;
  font-weight:800;
  font-size:16px;
  cursor:pointer;
  transition:all 0.3s ease;
  text-decoration:none;
  text-align:center;
  display:inline-block;
}
button{
  background:linear-gradient(135deg, #f6a89e 0%, #f28b6b 100%);
  color:#fff;
  box-shadow:0 8px 20px rgba(242, 139, 107, 0.3);
  text-transform:uppercase;
  letter-spacing:0.5px;
}
button:hover{
  transform:translateY(-2px);
  box-shadow:0 12px 28px rgba(242, 139, 107, 0.4);
}
.btn-cancel{
  background:#e0e0e0;
  color:#666;
}
.btn-cancel:hover{
  background:#d0d0d0;
  transform:translateY(-2px);
}
</style>
</head>
<body>
<div class="container">
  <form class="form" id="crearForm" method="post" enctype="multipart/form-data">
    <div class="header">
      <h2> Crear Nuevo Subtema</h2>
      <p>Agrega contenido adicional a tu lección</p>
      <div class="leccion-badge"> <?= $tituloLeccion ?></div>
    </div>
    
    <input type="hidden" name="accion" value="crear">
    <input type="hidden" name="id_leccion" value="<?= $id_leccion ?>">
    
    <div class="form-group">
      <label> Título del subtema <span>*</span></label>
      <input type="text" name="titulo" placeholder="Ej: Características principales" required>
    </div>
    
    <div class="form-group">
      <label> Contenido del subtema <span>*</span></label>
      <textarea name="cuerpo" placeholder="Escribe el contenido detallado del subtema..." required></textarea>
    </div>
    
    <div class="divider"></div>
    
    <div class="form-group">
      <label> Galería de imágenes <span style="background:#fff3e0;color:#f57c00;padding:4px 10px;border-radius:20px;font-size:11px;font-weight:700;margin-left:8px">OPCIONAL</span></label>
      <input type="file" name="galeria[]" accept=".png,.jpg,.jpeg" multiple>
      <p class="file-hint"> Puedes seleccionar múltiples imágenes para crear una galería</p>
    </div>
    
    <div class="divider"></div>
    
    <div class="btn-group">
      <a class="btn-cancel" href="lecciones_html/leccion-<?= $id_leccion ?>.html">x Cancelar</a>
      <button type="button" onclick="confirmarCreacion()"> Crear Subtema</button>
    </div>
  </form>
</div>

<script>
function confirmarCreacion() {
  const form = document.getElementById('crearForm');
  
  if (!form.checkValidity()) {
    form.reportValidity();
    return;
  }
  
  Swal.fire({
    title: 'Crear Subtema',
    text: '¿Deseas guardar este nuevo subtema?',
    icon: 'question',
    showCancelButton: true,
    confirmButtonColor: '#f6a89e',
    cancelButtonColor: '#ddd',
    confirmButtonText: 'Sí, crear',
    cancelButtonText: 'Cancelar',
    background: '#fff',
    color: '#333'
  }).then((result) => {
    if (result.isConfirmed) {
      Swal.fire({
        title: 'Guardando...',
        text: 'Creando tu subtema',
        icon: 'info',
        allowOutsideClick: false,
        showConfirmButton: false,
        didOpen: () => {
          Swal.showLoading();
        }
      });
      form.submit();
    }
  });
}
</script>

</body>
</html>