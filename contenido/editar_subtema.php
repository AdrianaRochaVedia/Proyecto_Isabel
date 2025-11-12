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
if(!file_exists($regenerarPath)){
  die("Error: Falta el archivo regenerar_html.php en la carpeta del proyecto.");
}
require_once $regenerarPath;


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'editar') {
  $id_leccion = (int)($_POST['id_leccion'] ?? 0);
  $orden = (int)($_POST['orden'] ?? 0);
  $titulo = trim($_POST['titulo'] ?? '');
  $cuerpo = trim($_POST['cuerpo'] ?? '');
  
  if ($id_leccion <= 0 || $orden <= 0) {
    die("Error: Datos inválidos");
  }
  
  if ($titulo === '') {
    die("Error: Falta título del subtema.");
  }

  $subtemaQuery = mysqli_query($cn, "SELECT id_subtema FROM SUBTEMA WHERE LECCION_id_leccion=$id_leccion AND orden=$orden LIMIT 1");
  $subtemaData = mysqli_fetch_assoc($subtemaQuery);
  
  if(!$subtemaData) {
    die("Error: Subtema no encontrado");
  }
  
  $id_subtema = (int)$subtemaData['id_subtema'];

  $st = mysqli_prepare($cn, "UPDATE SUBTEMA SET titulo=?, cuerpo_html=? WHERE id_subtema=?");
  mysqli_stmt_bind_param($st, "ssi", $titulo, $cuerpo, $id_subtema);
  mysqli_stmt_execute($st);
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
    title: '✓ ¡Subtema Actualizado!',
    text: 'Los cambios se guardaron correctamente',
    confirmButtonColor: '#f6a89e',
    timer: 2000,
    showConfirmButton: false
  }).then(() => {
    window.location.href = 'lecciones_html/leccion-$id_leccion-s$orden.html';
  });
  </script></body></html>";
  exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_leccion']) && isset($_POST['orden'])) {
  $id_leccion = (int)$_POST['id_leccion'];
  $orden = (int)$_POST['orden'];
  
  if ($id_leccion <= 0 || $orden <= 0) {
    die("Error: Datos inválidos");
  }

  $subtema = mysqli_fetch_assoc(mysqli_query($cn, "SELECT id_subtema, titulo, cuerpo_html FROM SUBTEMA WHERE LECCION_id_leccion=$id_leccion AND orden=$orden LIMIT 1"));
  
  if(!$subtema) {
    die("Error: Subtema no encontrado.");
  }

  $leccion = mysqli_fetch_assoc(mysqli_query($cn, "SELECT titulo FROM LECCION WHERE id_leccion=$id_leccion"));
  $tituloLeccion = $leccion ? htmlspecialchars($leccion['titulo']) : "Lección #$id_leccion";
} else {
  die("Error: Acceso inválido. Usa el botón 'Editar' desde el subtema.");
}
?>
<!doctype html>
<html lang="es"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title> Editar subtema</title>
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
  max-width:680px;
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
  font-size:26px;
  font-weight:900;
  margin:0 0 8px;
}

.modal-header p{
  color:#666;
  margin:0;
  font-size:14px;
}

.leccion-badge{
  display:inline-block;
  background:linear-gradient(135deg, #f6a89e, #f28b6b);
  color:#fff;
  padding:6px 14px;
  border-radius:20px;
  font-size:13px;
  font-weight:700;
  margin-top:10px;
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
  box-shadow:0 8px 20px rgba(246, 168, 158, 0.3);
}

button:hover{
  transform:translateY(-2px);
  box-shadow:0 12px 28px rgba(246, 168, 158, 0.4);
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
      <button class="close-btn" onclick="cancelar()" title="Cerrar">×</button>
      <h2> Editar Subtema</h2>
      <p>Modifica el contenido del subtema</p>
      <div class="leccion-badge"> <?= $tituloLeccion ?> - Subtema <?= $orden ?></div>
    </div>
    
    <form method="post" enctype="multipart/form-data" id="editSubForm">
      <div class="modal-body">
        <input type="hidden" name="accion" value="editar">
        <input type="hidden" name="id_leccion" value="<?= $id_leccion ?>">
        <input type="hidden" name="orden" value="<?= $orden ?>">
        
        <div class="form-group">
          <label> Título del subtema <span>*</span></label>
          <input type="text" name="titulo" value="<?= htmlspecialchars($subtema['titulo']) ?>" required>
        </div>
        
        <div class="form-group">
          <label> Contenido del subtema <span>*</span></label>
          <textarea name="cuerpo" required><?= htmlspecialchars($subtema['cuerpo_html']) ?></textarea>
        </div>
        
        <div class="divider"></div>
        
        <div class="form-group">
          <label> Agregar más imágenes (opcional)</label>
          <input type="file" name="galeria[]" accept=".png,.jpg,.jpeg" multiple>
          <p class="file-hint"> Las imágenes actuales se mantendrán, estas se agregarán</p>
        </div>
      </div>
      
      <div class="modal-footer">
        <button type="button" class="btn-cancel" onclick="cancelar()">x Cancelar</button>
        <button type="button" onclick="confirmarEdicion()"> Guardar cambios</button>
      </div>
    </form>
  </div>
</div>

<script>
function cancelar() {
  window.location.href = 'lecciones_html/leccion-<?= $id_leccion ?>-s<?= $orden ?>.html';
}

document.addEventListener('keydown', function(e) {
  if (e.key === 'Escape') {
    cancelar();
  }
});

function confirmarEdicion() {
  const form = document.getElementById('editSubForm');
  
  if (!form.checkValidity()) {
    form.reportValidity();
    return;
  }
  
  Swal.fire({
    title: '💾 Guardar Cambios',
    text: '¿Deseas actualizar este subtema?',
    icon: 'question',
    showCancelButton: true,
    confirmButtonColor: '#f6a89e',
    cancelButtonColor: '#ddd',
    confirmButtonText: 'Sí, guardar',
    cancelButtonText: 'Cancelar',
    background: '#fff',
    color: '#333'
  }).then((result) => {
    if (result.isConfirmed) {
      Swal.fire({
        title: 'Guardando...',
        text: 'Actualizando el subtema',
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