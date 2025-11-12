<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['id']) || empty($_SESSION['tipo'])) {
    header("Location: /login/login.php");
    exit();
}

if (empty($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
  die("<!doctype html><html><head><meta charset='utf-8'><title>Acceso Denegado</title><style>body{font-family:system-ui;display:flex;align-items:center;justify-content:center;height:100vh;margin:0;background:linear-gradient(135deg,#ffd7cc,#fde2dc);color:#b42b6f}</style></head><body><div style='text-align:center'><h1>🚫 Acceso Denegado</h1><p>No tienes permisos para editar lecciones.</p></div></body></html>");
}

$cn = mysqli_connect("localhost","root","","SISSOCIALES");
if(!$cn){ die("Error de conexión: ".mysqli_connect_error()); }
mysqli_set_charset($cn,"utf8mb4");


$regenerarPath = __DIR__."/regenerar_html.php";

require_once $regenerarPath;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['accion'] ?? '') === 'editar'){
  $id_leccion = (int)($_POST['id_leccion'] ?? 0);
  $titulo = trim($_POST['titulo'] ?? '');
  $cuerpo = trim($_POST['cuerpo'] ?? '');
  
  if ($id_leccion <= 0) {
    die("Error: ID de lección inválido");
  }
  
  if ($titulo === '' || $cuerpo === '') {
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
      icon: 'error',
      title: 'x Campos incompletos',
      text: 'Debes completar el título y el contenido de la lección',
      confirmButtonColor: '#f6a89e'
    }).then(() => {
      window.history.back();
    });
    </script></body></html>";
    exit;
  }

  $desc = mb_substr(preg_replace('/\s+/', ' ', $cuerpo), 0, 150)."...";
  
  $st = mysqli_prepare($cn,"UPDATE LECCION SET titulo=?, descripcion=?, cuerpo_html=?, actualizado_en=NOW() WHERE id_leccion=?");
  mysqli_stmt_bind_param($st,"sssi",$titulo,$desc,$cuerpo,$id_leccion);
  mysqli_stmt_execute($st);
  mysqli_stmt_close($st);

  $titleFile = $_FILES["titulo_img"] ?? null;
  if ($titleFile && $titleFile["error"]===UPLOAD_ERR_OK){
    $mime = mime_content_type($titleFile["tmp_name"]);
    if (in_array($mime,["image/png","image/jpeg"])){
      $dir = __DIR__."/uploads/lecciones/$id_leccion";
      @mkdir($dir,0777,true);
      
      foreach(['jpg','jpeg','png','JPG','JPEG','PNG'] as $ex){
        $oldFile = "$dir/title.$ex";
        if(file_exists($oldFile)) unlink($oldFile);
      }
      
      $ext = strtolower(pathinfo($titleFile["name"], PATHINFO_EXTENSION));
      $ext = ($ext === 'jpeg') ? 'jpg' : $ext;
      move_uploaded_file($titleFile["tmp_name"], "$dir/title.$ext");
    }
  }

  $frontFile = $_FILES["portada_front"] ?? null;
  if ($frontFile && $frontFile["error"]===UPLOAD_ERR_OK){
    $mime = mime_content_type($frontFile["tmp_name"]);
    if (in_array($mime,["image/png","image/jpeg"])){
      $dir = __DIR__."/uploads/lecciones/$id_leccion";
      @mkdir($dir,0777,true);
      
      foreach(['jpg','jpeg','png','JPG','JPEG','PNG'] as $ex){
        $oldFile = "$dir/front.$ex";
        if(file_exists($oldFile)) unlink($oldFile);
      }
      
      $ext = strtolower(pathinfo($frontFile["name"], PATHINFO_EXTENSION));
      $ext = ($ext === 'jpeg') ? 'jpg' : $ext;
      move_uploaded_file($frontFile["tmp_name"], "$dir/front.$ext");
      mysqli_query($cn,"UPDATE LECCION SET portada_front='uploads/lecciones/$id_leccion/front.$ext' WHERE id_leccion=$id_leccion");
    }
  }
  $backFile = $_FILES["portada_back"] ?? null;
  if ($backFile && $backFile["error"]===UPLOAD_ERR_OK){
    $mime = mime_content_type($backFile["tmp_name"]);
    if (in_array($mime,["image/png","image/jpeg"])){
      $dir = __DIR__."/uploads/lecciones/$id_leccion";
      @mkdir($dir,0777,true);
      
      foreach(['jpg','jpeg','png','JPG','JPEG','PNG'] as $ex){
        $oldFile = "$dir/back.$ex";
        if(file_exists($oldFile)) unlink($oldFile);
      }
      
      $ext = strtolower(pathinfo($backFile["name"], PATHINFO_EXTENSION));
      $ext = ($ext === 'jpeg') ? 'jpg' : $ext;
      move_uploaded_file($backFile["tmp_name"], "$dir/back.$ext");
      mysqli_query($cn,"UPDATE LECCION SET portada_back='uploads/lecciones/$id_leccion/back.$ext' WHERE id_leccion=$id_leccion");
    }
  }

  $tituloImgRel = path_title_image_rel($id_leccion);
  regenerar_leccion_html($cn, $id_leccion, $tituloImgRel);

  echo "<!doctype html><html><head><meta charset='utf-8'><script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
  <style>
  @font-face {
    font-family: 'Quincho Script PERSONAL USE';
    src: url('fuentes/QuinchoScriptPERSONALUSE.woff2') format('woff2'),
        url('fuentes/QuinchoScriptPERSONALUSE.woff') format('woff');
    font-weight: normal;
    font-style: normal;
    font-display: swap;
  }
  body{
    font-family:'Quincho Script PERSONAL USE','Segoe UI',Arial,sans-serif;
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
    title: '✓ ¡Lección Actualizada!',
    text: 'Los cambios se han guardado correctamente',
    confirmButtonColor: '#f6a89e',
    timer: 2000,
    showConfirmButton: false
  }).then(() => {
    window.location.href = 'lecciones_html/leccion-$id_leccion.html';
  });
  </script></body></html>";
  exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_leccion'])) {
  $id_leccion = (int)$_POST['id_leccion'];
  
  if ($id_leccion <= 0) {
    die("Error: ID de lección inválido");
  }

  $leccion = mysqli_fetch_assoc(mysqli_query($cn,"SELECT titulo,cuerpo_html FROM LECCION WHERE id_leccion=$id_leccion"));
  
  if(!$leccion) {
    die("Error: Lección no encontrada.");
  }
} else {
  die("Error: Acceso inválido. Usa el botón 'Editar lección' desde la lección.");
}
?>
<!doctype html>
<html lang="es"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Editar Lección</title>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
*{box-sizing:border-box;margin:0;padding:0}
body{
  font-family:'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  background:rgba(0,0,0,0.7);
  display:flex;
  align-items:center;
  justify-content:center;
  min-height:100vh;
  padding:20px;

}

.modal-overlay{
  position:fixed;
  top:0;left:0;right:0;bottom:0;
  background:rgba(0,0,0,0.6);
  backdrop-filter:blur(8px);
  display:flex;
  align-items:center;
  justify-content:center;
  z-index:1000;
  animation:fadeIn 0.3s ease-out;
}

@keyframes fadeIn{
  from{opacity:0}
  to{opacity:1}
}

@keyframes slideUp{
  from{opacity:0;transform:translateY(30px)}
  to{opacity:1;transform:translateY(0)}
}

.modal{
  background:#fff;
  border-radius:24px;
  box-shadow:0 30px 80px rgba(0,0,0,0.3);
  width:100%;
  max-width:720px;
  max-height:90vh;
  overflow-y:auto;
  animation:slideUp 0.4s ease-out;
  position:relative;
}

.modal-header{
  background:linear-gradient(135deg, #ffd7cc, #ffe7e2);
  padding:24px 32px;
  border-bottom:3px solid #f5cfd0;
  position:sticky;
  top:0;
  z-index:10;
  border-radius:24px 24px 0 0;
}

.modal-header h2{
  color:#b42b6f;
  font-size:28px;
  font-weight:900;
  margin:0 0 8px;
}

.modal-header p{
  color:#666;
  margin:0;
  font-size:14px;
}

.close-btn{
  position:absolute;
  top:20px;
  right:20px;
  background:#fff;
  border:2px solid #f5cfd0;
  width:40px;
  height:40px;
  border-radius:50%;
  cursor:pointer;
  display:flex;
  align-items:center;
  justify-content:center;
  font-size:24px;
  color:#b42b6f;
  transition:all 0.3s ease;
  font-weight:bold;
}

.close-btn:hover{
  background:#f6a89e;
  border-color:#f6a89e;
  color:#fff;
  transform:rotate(90deg);
}

.modal-body{
  padding:32px;
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

.badge-opcional{
  background:#fff3e0;
  color:#f57c00;
  padding:4px 10px;
  border-radius:20px;
  font-size:11px;
  font-weight:700;
  margin-left:8px;
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

.modal-footer{
  padding:24px 32px;
  background:#fafafa;
  border-top:1px solid #f5cfd0;
  display:flex;
  gap:12px;
  position:sticky;
  bottom:0;
  border-radius:0 0 24px 24px;
}

button,
.btn-cancel{
  background:#f5cfd0;
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
  background:linear-gradient(135deg, #ffb6b6ff 0%, #fc9398ff 100%);
  color:#fff;
  box-shadow:0 8px 20px rgba(255, 143, 143, 0.38);
}

button:hover{
  transform:translateY(-2px);
  box-shadow:0 12px 28px rgba(175, 76, 76, 0.4);
}

.btn-cancel{
  background:#e0e0e0;
  color:#666;
}

.btn-cancel:hover{
  background:#d0d0d0;
  transform:translateY(-2px);
}

.modal::-webkit-scrollbar{
  width:8px;
}

.modal::-webkit-scrollbar-track{
  background:#f1f1f1;
}

.modal::-webkit-scrollbar-thumb{
  background:#f6a89e;
  border-radius:10px;
}

.modal::-webkit-scrollbar-thumb:hover{
  background:#f28b6b;
}
</style>
</head>
<body>

<div class="modal-overlay" onclick="if(event.target===this) cancelar()">
  <div class="modal">
    <div class="modal-header">
      <button class="close-btn" onclick="cancelar()" title="Cerrar"></button>
      <h2> Editar Lección</h2>
      <p>Modifica los datos de tu lección</p>
    </div>
    
    <form id="editarForm" method="post" enctype="multipart/form-data">
      <div class="modal-body">
        <input type="hidden" name="accion" value="editar">
        <input type="hidden" name="id_leccion" value="<?= $id_leccion ?>">
        
        <div class="form-group">
          <label> Título de la lección <span>*</span></label>
          <input type="text" name="titulo" value="<?= htmlspecialchars($leccion['titulo']) ?>" required>
        </div>
        
        <div class="form-group">
          <label> Contenido de la lección <span>*</span></label>
          <textarea name="cuerpo" required><?= htmlspecialchars($leccion['cuerpo_html']) ?></textarea>
        </div>
        
        <div class="divider"></div>
        
        <div class="form-group">
          <label> Cambiar imagen del título <span class="badge-opcional">OPCIONAL</span></label>
          <input type="file" name="titulo_img" accept=".png,.jpg,.jpeg">
          <p class="file-hint"> Solo si deseas cambiar la imagen actual del título</p>
        </div>
        
        <div class="form-group">
          <label> Cambiar portada frontal <span class="badge-opcional">OPCIONAL</span></label>
          <input type="file" name="portada_front" accept=".png,.jpg,.jpeg">
          <p class="file-hint"> Solo si deseas cambiar la portada frontal actual</p>
        </div>
        
        <div class="form-group">
          <label> Cambiar portada trasera <span class="badge-opcional">OPCIONAL</span></label>
          <input type="file" name="portada_back" accept=".png,.jpg,.jpeg">
          <p class="file-hint"> Solo si deseas cambiar la portada trasera actual</p>
        </div>
      </div>
      
      <div class="modal-footer">
        <button type="button" class="btn-cancel" onclick="cancelar()">× Cancelar</button>
        <button type="button" onclick="confirmarEdicion()"> Guardar Cambios</button>
      </div>
    </form>
  </div>
</div>

<script>
function cancelar() {
  window.location.href = 'lecciones_html/leccion-<?= $id_leccion ?>.html';
}

document.addEventListener('keydown', function(e) {
  if (e.key === 'Escape') {
    cancelar();
  }
});

function confirmarEdicion() {
  const form = document.getElementById('editarForm');
  
  if (!form.checkValidity()) {
    form.reportValidity();
    return;
  }
  
  Swal.fire({
    title: ' Guardar Cambios',
    text: '¿Deseas actualizar esta lección?',
    icon: 'question',
    showCancelButton: true,
    confirmButtonColor: '#f7a6b8ff',
    cancelButtonColor: '#ddd',
    confirmButtonText: 'Sí, guardar',
    cancelButtonText: 'Cancelar',
    background: '#fff',
    color: '#333'
  }).then((result) => {
    if (result.isConfirmed) {
      Swal.fire({
        title: 'Guardando...',
        text: 'Actualizando la lección',
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