<?php
if (session_status() === PHP_SESSION_NONE) session_start();
?>
<?php
if (empty($_SESSION['id']) || empty($_SESSION['tipo'])) {
    header("Location: /login/login.php");
    exit();
}
if (empty($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
  die("<!doctype html><html><head><meta charset='utf-8'><title>Acceso Denegado</title><style>body{font-family:system-ui;display:flex;align-items:center;justify-content:center;height:100vh;margin:0;background:linear-gradient(135deg,#ffd7cc,#fde2dc);color:#b42b6f}</style></head><body><div style='text-align:center'><h1>🚫 Acceso Denegado</h1><p>No tienes permisos para crear lecciones.</p><a href='../contenido/lecciones.php' style='color:#f28b6b;font-weight:bold'>← Volver a lecciones</a></div></body></html>");
}

$cn = mysqli_connect("localhost","root","","SISSOCIALES");
if(!$cn){ die("Error de conexión: ".mysqli_connect_error()); }
mysqli_set_charset($cn,"utf8mb4");

function path_title_image_rel(int $id){
  $base = __DIR__."/uploads/lecciones/$id/";
  foreach (['jpg','jpeg','png','JPG','JPEG','PNG'] as $ex){
    if (file_exists($base."title.$ex")) return "uploads/lecciones/$id/title.$ex";
  }
  return '';
}

function generar_html_subtema(mysqli $cn, int $id_leccion, int $orden, string $tituloImgRel=''){
  $L = mysqli_fetch_assoc(mysqli_query($cn,"SELECT titulo FROM LECCION WHERE id_leccion=$id_leccion"));
  $S = mysqli_fetch_assoc(mysqli_query($cn,"SELECT titulo,cuerpo_html FROM SUBTEMA WHERE LECCION_id_leccion=$id_leccion AND orden=$orden ORDER BY id_subtema ASC LIMIT 1"));
  if(!$L || !$S) return;

  $maxOrden = mysqli_fetch_row(mysqli_query($cn,"SELECT MAX(orden) FROM SUBTEMA WHERE LECCION_id_leccion=$id_leccion"))[0];
  $esUltimoSubtema = ($orden == $maxOrden);

  $tL = htmlspecialchars($L['titulo'],ENT_QUOTES);
  $tS = htmlspecialchars($S['titulo'],ENT_QUOTES);
  $cS = nl2br(htmlspecialchars($S['cuerpo_html'],ENT_QUOTES));
  $titleBgUrl = $tituloImgRel ? "../".$tituloImgRel : "";
  $galDir = __DIR__."/uploads/lecciones/$id_leccion/subtemas/subtema-$orden";
  $galeria = [];
  if (is_dir($galDir)){
    $files = glob($galDir."/*.{jpg,jpeg,png,JPG,JPEG,PNG}", GLOB_BRACE);
    $galeria = $files;
  }

  $isAdmin = !empty($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1;

  ob_start(); ?>
  
<!doctype html>
<html lang="es">
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title><?= $tL ?> – <?= $tS ?></title>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
:root{--salmon1:#fde2dc;--salmon3:#f6a89e;--salmon4:#f28b6b;--borde:#f5cfd0;--text:#333}
*{box-sizing:border-box} 
body{margin:0;font-family:Segoe UI,Arial,sans-serif;background:linear-gradient(180deg,var(--salmon1),#fff);color:var(--text)}
.wrap{max-width:980px;margin:0 auto;padding:0 14px}
.header{padding:36px 0;background:linear-gradient(135deg,#ffd7cc,#ffe7e2);border-bottom:5px solid #fdb5a5ff;box-shadow:0 8px 24px rgba(0,0,0,.08)}
.title-mask{margin:0;text-align:center;line-height:1.1;font-weight:900;font-size:clamp(30px,6vw,56px);color:transparent;-webkit-text-fill-color:transparent;background-repeat:no-repeat;background-position:center;background-size:cover;-webkit-background-clip:text;background-clip:text}
.no-img{background-image:linear-gradient(90deg,#f6a89e,#f8b4a6)}
.card{background:#fff;border:1px solid var(--borde);border-radius:16px;padding:18px;margin:20px 0;box-shadow:0 8px 22px rgba(0,0,0,.06)}
.btn{display:inline-block;background:var(--salmon3);color:#fff;text-decoration:none;padding:10px 14px;border-radius:10px;font-weight:800;margin:8px 4px;border:none;cursor:pointer;transition:all .3s,   right: 30px; }
.btn:hover{background:var(--salmon4);transform:translateY(-2px)}
.btn-edit{background:var(--salmon3)}
.btn-edit:hover{background:var(--salmon4)}
.btn-delete{background:var(--salmon3)}
.btn-delete:hover{background:var(--salmon4)}
.btn-evaluar{background:#faa997ff;color:#fff;font-size:16px;padding:12px 20px}
.btn-evaluar:hover{background:#ff937aff;transform:translateY(-2px) scale(1.05)}
.actions-bar{display:flex;gap:8px;flex-wrap:wrap;margin-bottom:16px;padding-bottom:16px;border-bottom:2px solid #f5cfd0}
.eval-section{background:#fdd2c8ff;border:2px solid #f8e1e3ff;border-radius:16px;padding:20px;margin:20px 0;text-align:center}
.eval-section h3{color:#5a3d9a;margin:0 0 10px}
.eval-section p{color:#666;margin:0 0 16px}
.carousel-container{position:relative;margin-top:24px;border-radius:16px;overflow:hidden;background:#f9f9f9;box-shadow:0 8px 24px rgba(0,0,0,.1)}
.carousel-wrapper{position:relative;width:100%;padding-top:56.25%;overflow:hidden}
.carousel-track{position:absolute;top:0;left:0;width:100%;height:100%;display:flex;transition:transform .5s cubic-bezier(.4,0,.2,1)}
.carousel-slide{min-width:100%;height:100%;display:flex;align-items:center;justify-content:center;background:#000}
.carousel-slide img{width:100%;height:100%;object-fit:contain}
.carousel-btn{position:absolute;top:50%;transform:translateY(-50%);background:rgba(246,168,158,.95);color:#fff;border:none;width:50px;height:50px;border-radius:50%;cursor:pointer;font-size:20px;font-weight:bold;display:flex;align-items:center;justify-content:center;transition:all .3s;z-index:10;box-shadow:0 4px 12px rgba(0,0,0,.2)}
.carousel-btn:hover{background:var(--salmon4);transform:translateY(-50%) scale(1.1)}
.carousel-btn.prev{left:16px}
.carousel-btn.next{right:16px}
.carousel-indicators{position:absolute;bottom:16px;left:50%;transform:translateX(-50%);display:flex;gap:10px;z-index:10}
.indicator{width:12px;height:12px;border-radius:50%;background:rgba(255,255,255,.5);border:2px solid rgba(246,168,158,.8);cursor:pointer;transition:all .3s}
.indicator.active{background:var(--salmon3);transform:scale(1.3)}
.carousel-counter{position:absolute;top:16px;right:16px;background:rgba(0,0,0,.7);color:#fff;padding:8px 16px;border-radius:20px;font-size:14px;font-weight:700;z-index:10}
</style>
</head>
<body>
  
<?php if (!empty($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1): ?>
  <a href="../evaluaciones" class="btn-primary">+ Crear Evaluación</a>
<?php endif; ?>
<header class="header"><div class="wrap">
  <h1 class="title-mask <?= $titleBgUrl ? '' : 'no-img' ?>" style="<?= $titleBgUrl ? "background-image:url('$titleBgUrl')" : "" ?>">
    <?= $tL ?>
  </h1>
</div></header>

<main class="wrap">
  <section class="card">
<?php if($isAdmin): ?>
    <div class="actions-bar">
      <?php if (!empty($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1): ?>
  <button type="button" class="btn btn-edit" onclick="confirmarEditar()"> Editar</button>
<?php endif; ?>
            <?php if (!empty($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1): ?>
      <button type="button" class="btn btn-delete" onclick="confirmarEliminar()"> Eliminar</button>
      <?php endif; ?>
    </div>
    <?php endif; ?>

    <h2 style="margin:0 0 10px;color:#b42b6f"> Subtema <?= $orden ?> – <?= $tS ?></h2>
    <div><?= $cS ?></div>

    <?php if(count($galeria) > 0): ?>
      <div class="carousel-container">
        <div class="carousel-wrapper">
          <div class="carousel-track" id="carouselTrack">
            <?php foreach($galeria as $img): 
              $imgRel = "uploads/lecciones/$id_leccion/subtemas/subtema-$orden/".basename($img);
            ?>
              <div class="carousel-slide">
                <img src="../<?= htmlspecialchars($imgRel) ?>" alt="Imagen de galería" loading="lazy">
              </div>
            <?php endforeach; ?>
          </div>
        </div>
        
        <?php if(count($galeria) > 1): ?>
          <button class="carousel-btn prev" onclick="moveSlide(-1)">‹</button>
          <button class="carousel-btn next" onclick="moveSlide(1)">›</button>
          
          <div class="carousel-counter">
            <span id="currentSlide">1</span> / <?= count($galeria) ?>
          </div>
          
          <div class="carousel-indicators">
            <?php for($i = 0; $i < count($galeria); $i++): ?>
              <span class="indicator <?= $i === 0 ? 'active' : '' ?>" onclick="goToSlide(<?= $i ?>)"></span>
            <?php endfor; ?>
          </div>
        <?php endif; ?>
      </div>

      <script>
        let currentIndex = 0;
        const totalSlides = <?= count($galeria) ?>;
        const track = document.getElementById('carouselTrack');
        const indicators = document.querySelectorAll('.indicator');
        const counter = document.getElementById('currentSlide');

        function updateCarousel() {
          track.style.transform = `translateX(-${currentIndex * 100}%)`;
          counter.textContent = currentIndex + 1;
          indicators.forEach((ind, idx) => {
            ind.classList.toggle('active', idx === currentIndex);
          });
        }

        function moveSlide(direction) {
          currentIndex += direction;
          if (currentIndex < 0) currentIndex = totalSlides - 1;
          if (currentIndex >= totalSlides) currentIndex = 0;
          updateCarousel();
        }

        function goToSlide(index) {
          currentIndex = index;
          updateCarousel();
        }
      </script>
    <?php endif; ?>
  </section>

  <?php if($esUltimoSubtema): ?>
  <section class="eval-section">
    <h3> ¡Has completado todos los subtemas!</h3>
    <p>Realiza la evaluación para medir tu progreso y obtener tu calificación</p>
    <form method="POST" action="../realizar_evaluacion.php" style="display:inline">
      <input type="hidden" name="id_leccion" value="<?= $id_leccion ?>">
      <button type="submit" class="btn btn-evaluar"> Realizar Evaluación</button>
    </form>
  </section>
  <?php endif; ?>

  <section class="card">
    <p style="margin:0">
      <?php
        $prev = $orden-1; $next = $orden+1;
        $hasPrev = mysqli_fetch_row(mysqli_query($cn,"SELECT 1 FROM SUBTEMA WHERE LECCION_id_leccion=$id_leccion AND orden=$prev"));
        $hasNext = mysqli_fetch_row(mysqli_query($cn,"SELECT 1 FROM SUBTEMA WHERE LECCION_id_leccion=$id_leccion AND orden=$next"));
      ?>
      <?php if($hasPrev): ?><a class="btn" href="leccion-<?= $id_leccion ?>-s<?= $prev ?>.html">◀ Anterior</a><?php endif; ?>
      <a class="btn" href="leccion-<?= $id_leccion ?>.html">Volver a la lección</a>
      <?php if($hasNext): ?><a class="btn" href="leccion-<?= $id_leccion ?>-s<?= $next ?>.html">Siguiente ▶</a><?php endif; ?>
    </p>
  </section>
</main>


<?php if($isAdmin): ?>
<script>
function confirmarEditar() {
  Swal.fire({
    title: ' Editar Subtema',
    text: '¿Deseas modificar este subtema?',
    icon: 'question',
    showCancelButton: true,
    confirmButtonColor: '#f6a89e',
    cancelButtonColor: '#ddd',
    confirmButtonText: 'Sí, editar',
    cancelButtonText: 'Cancelar',
    background: '#fff',
    color: '#333'
  }).then((result) => {
    if (result.isConfirmed) {
      const form = document.createElement('form');
      form.method = 'POST';
      form.action = '../editar_subtema.php';
      form.innerHTML = `
        <input type="hidden" name="id_leccion" value="<?= $id_leccion ?>">
        <input type="hidden" name="orden" value="<?= $orden ?>">
      `;
      document.body.appendChild(form);
      form.submit();
    }
  });
}

function confirmarEliminar() {
  Swal.fire({
    title: ' ¿Eliminar Subtema?',
    text: 'Esta acción no se puede deshacer',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#f6a89e',
    cancelButtonColor: '#ddd',
    confirmButtonText: 'Sí, eliminar',
    cancelButtonText: 'Cancelar',
    background: '#fff',
    color: '#333'
  }).then((result) => {
    if (result.isConfirmed) {
      const form = document.createElement('form');
      form.method = 'POST';
      form.action = '../borrar_subtema.php';
      form.innerHTML = `
        <input type="hidden" name="id_leccion" value="<?= $id_leccion ?>">
        <input type="hidden" name="orden" value="<?= $orden ?>">
      `;
      document.body.appendChild(form);
      form.submit();
    }
  });
}
</script>
<?php endif; ?>

</body>
</html>
<?php
  $html = ob_get_clean();
  $dir = __DIR__."/lecciones_html";
  @mkdir($dir,0777,true);
  file_put_contents("$dir/leccion-$id_leccion-s$orden.html",$html);
}

function generar_html_leccion(mysqli $cn, int $id_leccion, string $tituloImgRel=''){
  $L = mysqli_fetch_assoc(mysqli_query($cn,"SELECT titulo,cuerpo_html FROM LECCION WHERE id_leccion=$id_leccion"));
  if(!$L) return;
  $t   = htmlspecialchars($L['titulo'],ENT_QUOTES);
  $c   = nl2br(htmlspecialchars($L['cuerpo_html'],ENT_QUOTES));
  $titleBgUrl = $tituloImgRel ? "../".$tituloImgRel : "";

  $subs = mysqli_query($cn,"SELECT orden,titulo,cuerpo_html FROM SUBTEMA WHERE LECCION_id_leccion=$id_leccion ORDER BY orden ASC, id_subtema ASC");

  $isAdmin = false;
  if (isset($_SESSION['tipo']) && $_SESSION['tipo'] === 'ADMINISTRADOR') {
    $isAdmin = true;
  }

  ob_start(); ?>
<!doctype html>
<html lang="es">
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title><?= $t ?></title>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
:root{
  --salmon1:#fde2dc; --salmon2:#f8b4a6; --salmon3:#f6a89e; --salmon4:#f28b6b;
  --card:#fff; --text:#333; --borde:#f5cfd0;
}
*{box-sizing:border-box}
body{margin:0;font-family:Segoe UI,Arial,sans-serif;color:var(--text);background:linear-gradient(180deg,var(--salmon1) 0%,#fff 100%)}
.wrap{max-width:980px;margin:0 auto;padding:0 14px}

.header{padding:36px 0;background:linear-gradient(135deg,#ffd7cc,#ffe7e2);border-bottom:6px solid #ffc9bd;box-shadow:0 8px 24px rgba(0,0,0,.08)}
.title-mask{margin:0;line-height:1.1;font-weight:900;font-size:clamp(30px,6vw,64px);text-align:center;color:transparent;-webkit-text-fill-color:transparent;background-repeat:no-repeat;background-position:center;background-size:cover;-webkit-background-clip:text;background-clip:text}
.no-img{background-image:linear-gradient(90deg,#f6a89e 0%, #f8b4a6 100%)}

.banner{background:#fff;border:1px solid var(--borde);border-left:4px solid var(--salmon3);padding:10px 12px;border-radius:10px;margin:16px auto;display:flex;gap:10px;align-items:center;justify-content:space-between;max-width:980px;flex-wrap:wrap}
.banner button{background:var(--salmon3);color:#fff;text-decoration:none;padding:8px 12px;border-radius:8px;font-weight:800;white-space:nowrap;border:none;cursor:pointer;transition:all .3s}
.banner button:hover{background:var(--salmon4);transform:translateY(-2px)}
.banner .btn-eval{background: #f8b4a6}
.banner .btn-eval:hover{background:#ffc9bd}

.card{background:var(--card);border:1px solid var(--borde);border-radius:16px;padding:18px;margin:16px 0;box-shadow:0 8px 22px rgba(0,0,0,.06)}
.grid{display:grid;gap:14px;grid-template-columns:repeat(auto-fit,minmax(280px,1fr))}
.sub{background:#fff;border:1px solid var(--borde);border-radius:12px;padding:16px;display:flex;flex-direction:column;transition:transform .2s,box-shadow .2s}
.sub:hover{transform:translateY(-4px);box-shadow:0 12px 28px rgba(0,0,0,.12)}
.sub h3{margin:0 0 8px;font-size:18px;color:#b42b6f}
.sub p{margin:0 0 12px;line-height:1.6;color:#555;flex-grow:1}
.actions{margin-top:auto}
.btn{display:inline-block;background:var(--salmon3);color:#fff;text-decoration:none;padding:10px 14px;border-radius:10px;font-weight:800;transition:all .3s}
.btn:hover{background:var(--salmon4);transform:translateY(-2px)}
.leccion-actions{display:flex;gap:8px;margin-bottom:16px;padding-bottom:12px;border-bottom:2px solid #f5cfd0}
.btn-edit{background:var(--salmon3);border:none;cursor:pointer;padding:10px 14px;border-radius:10px;color:#fff;font-weight:800;transition:all .3s}
.btn-edit:hover{background:var(--salmon4);transform:translateY(-2px)}
.btn-delete{background:var(--salmon3);border:none;cursor:pointer;padding:10px 14px;border-radius:10px;color:#fff;font-weight:800;transition:all .3s}
.btn-delete:hover{background:var(--salmon4);transform:translateY(-2px)}
</style>
</head>
<body>

<header class="header">
  <div class="wrap">
    <h1 class="title-mask <?= $titleBgUrl ? '' : 'no-img' ?>" style="<?= $titleBgUrl ? "background-image:url('$titleBgUrl')" : "" ?>">
      <?= $t ?>
    </h1>
  </div>
</header>

<?php if($isAdmin): ?>
<div class="banner">

<div><strong> Gestión</strong> Administra tus contenidos</div>

  <div style="display:flex;gap:8px">
    <form method="POST" action="../crear_subtema.php" style="display:inline;margin:0">
      <input type="hidden" name="id_leccion" value="<?= $id_leccion ?>">
      <button type="submit">+ Crear subtema</button>
    </form>
    <form method="POST" action="../crear_evaluacion.php" style="display:inline;margin:0">
      <input type="hidden" name="id_leccion" value="<?= $id_leccion ?>">
      <button type="submit" class="btn-eval">+ Crear evaluación</button>
    </form>
  </div>
</div>
<?php endif; ?>

<main class="wrap">
  <section class="card">
    <?php if($isAdmin): ?>
    <div class="leccion-actions">
      <button type="button" class="btn-edit" onclick="editarLeccion()"> Editar lección</button>
      <button type="button" class="btn-delete" onclick="eliminarLeccion()"> Eliminar lección</button>
    </div>
    <?php endif; ?>
    
    <h2 style="margin:0 0 10px;color:#b42b6f"> Contenido general</h2>
    <div><?= $c ?></div>
  </section>

  <section class="card">
    <h2 style="margin:0 0 16px;color:#b42b6f"> Subtemas de la lección</h2>
    <div class="grid">
      <?php if(mysqli_num_rows($subs)>0): while($s=mysqli_fetch_assoc($subs)): 
        $ord = (int)$s['orden'];
        $ts  = htmlspecialchars($s['titulo']);
        $cs  = trim($s['cuerpo_html']);
        $prev = htmlspecialchars(mb_substr(preg_replace('/\s+/', ' ', strip_tags($cs)), 0, 120));
        if(strlen($cs) > 120) $prev .= '…';
      ?>
        <article class="sub">
          <h3> Subtema <?= $ord ?>: <?= $ts ?></h3>
          <p><?= $prev ?></p>
          <div class="actions">
            <a class="btn" href="leccion-<?= $id_leccion ?>-s<?= $ord ?>.html">Ver más ▶</a>
          </div>
        </article>
      <?php endwhile; else: ?>
        <div class="sub">
          
        </div>
      <?php endif; ?>
    </div>
  </section>
</main>

<?php if($isAdmin): ?>
<script>
function editarLeccion() {
  Swal.fire({
    title: ' Editar Lección',
    text: '¿Deseas modificar esta lección?',
    icon: 'question',
    showCancelButton: true,
    confirmButtonColor: '#f6a89e',
    cancelButtonColor: '#ddd',
    confirmButtonText: 'Sí, editar',
    cancelButtonText: 'Cancelar'
  }).then((result) => {
    if (result.isConfirmed) {
      const form = document.createElement('form');
      form.method = 'POST';
      form.action = '../editar_leccion.php';
      form.innerHTML = '<input type="hidden" name="id_leccion" value="<?= $id_leccion ?>">';
      document.body.appendChild(form);
      form.submit();
    }
  });
}

function eliminarLeccion() {
  Swal.fire({
    title: ' ¿Eliminar Lección?',
    html: '<p>Se eliminará toda la lección y sus subtemas</p><p><strong>Esta acción no se puede deshacer</strong></p>',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#f6a89e',
    cancelButtonColor: '#ddd',
    confirmButtonText: 'Sí, eliminar',
    cancelButtonText: 'Cancelar'
  }).then((result) => {
    if (result.isConfirmed) {
      const form = document.createElement('form');
      form.method = 'POST';
      form.action = '../borrar_leccion.php';
      form.innerHTML = '<input type="hidden" name="id_leccion" value="<?= $id_leccion ?>"><input type="hidden" name="confirmar" value="si">';
      document.body.appendChild(form);
      form.submit();
    }
  });
}
</script>
<?php endif; ?>

</body>
</html>
<?php
  $html = ob_get_clean();
  $dir = __DIR__."/lecciones_html";
  @mkdir($dir,0777,true);
  file_put_contents("$dir/leccion-$id_leccion.html",$html);

  $rs = mysqli_query($cn,"SELECT orden FROM SUBTEMA WHERE LECCION_id_leccion=$id_leccion ORDER BY orden ASC");
  $tituloImgRel = $tituloImgRel ?: path_title_image_rel($id_leccion);
  while($x = mysqli_fetch_row($rs)){
    generar_html_subtema($cn, $id_leccion, (int)$x[0], $tituloImgRel);
  }
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && ($_POST["accion"] ?? "") === "crear") {
  $titulo     = trim($_POST["titulo"] ?? "");
  $desarrollo = trim($_POST["desarrollo"] ?? "");
  $frontFile  = $_FILES["portada_front"] ?? null;
  $backFile   = $_FILES["portada_back"]  ?? null;
  $titleFile  = $_FILES["titulo_img"]    ?? null;

  $valida = function($f){
    if(!$f || $f["error"]!==UPLOAD_ERR_OK) return "Imagen no subida";
    $mime = mime_content_type($f["tmp_name"]);
    return in_array($mime,["image/png","image/jpeg"]) ? true : "Solo PNG o JPG";
  };
  if ($titulo==="" || $desarrollo==="") die("Completa título y contenido.");
  $v1 = $valida($frontFile); if($v1!==true) die($v1);
  $v2 = $valida($backFile);  if($v2!==true) die($v2);
  if ($titleFile && $titleFile["error"]===UPLOAD_ERR_OK){ $v3=$valida($titleFile); if($v3!==true) die($v3); }

  $desc = mb_substr(preg_replace('/\s+/', ' ', $desarrollo), 0, 150)."...";
  $st = mysqli_prepare($cn,"INSERT INTO LECCION (titulo, descripcion, cuerpo_html, video_url, portada_front, portada_back, estado, creado_por, publicado_por, creado_en, actualizado_en, USUARIO_id_usuario) VALUES (?, ?, ?, '', '', '', 1, 'admin', 'admin', NOW(), NOW(), 1)");
  mysqli_stmt_bind_param($st,"sss",$titulo,$desc,$desarrollo);
  mysqli_stmt_execute($st);
  $id = mysqli_insert_id($cn);
  mysqli_stmt_close($st);

  $dir = __DIR__."/uploads/lecciones/$id";
  @mkdir($dir,0777,true);
  $ext = function($n){ $e=strtolower(pathinfo($n,PATHINFO_EXTENSION)); return $e==='jpeg'?'jpg':$e; };

  $eF = $ext($frontFile["name"]); move_uploaded_file($frontFile["tmp_name"], "$dir/front.$eF");
  $eB = $ext($backFile["name"]);  move_uploaded_file($backFile["tmp_name"],  "$dir/back.$eB");
  mysqli_query($cn,"UPDATE LECCION SET portada_front='uploads/lecciones/$id/front.$eF', portada_back='uploads/lecciones/$id/back.$eB' WHERE id_leccion=$id");

  $tituloImgRel = '';
  if ($titleFile && $titleFile["error"]===UPLOAD_ERR_OK){
    $eT = $ext($titleFile["name"]);
    if (move_uploaded_file($titleFile["tmp_name"], "$dir/title.$eT")){
      $tituloImgRel = "uploads/lecciones/$id/title.$eT";
    }
  }

  generar_html_leccion($cn, $id, $tituloImgRel);
  
  echo "<!doctype html><html><head><meta charset='utf-8'><script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script></head><body><script>
  Swal.fire({
  
    icon: 'success',
    title: ' ¡Lección Creada!',
    text: 'La lección se ha guardado correctamente',
    confirmButtonColor: '#f6a89e',
    timer: 2000,
    showConfirmButton: false
  }).then(() => {
    window.location.href = 'lecciones_html/leccion-$id.html';
  });
  </script></body></html>";
  exit;
}
?>
<!doctype html>
<html lang="es"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title> Crear Lección</title>
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
  min-height:120px;
}
.file-input{
  position:relative;
  overflow:hidden;
  display:block;
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
button{
  width:100%;
  background:linear-gradient(135deg, #f6a89e 0%, #f28b6b 100%);
  color:#fff;
  border:none;
  border-radius:14px;
  padding:16px;
  font-weight:800;
  font-size:17px;
  cursor:pointer;
  transition:all 0.3s ease;
  box-shadow:0 8px 20px rgba(242, 139, 107, 0.3);
  text-transform:uppercase;
  letter-spacing:0.5px;
}
button:hover{
  transform:translateY(-2px);
  box-shadow:0 12px 28px rgba(242, 139, 107, 0.4);
}
button:active{
  transform:translateY(0);
}
.divider{
  height:1px;
  background:linear-gradient(to right, transparent, #f5cfd0, transparent);
  margin:24px 0;
}
.optional-badge{
  display:inline-block;
  background:#fff3e0;
  color:#f57c00;
  padding:4px 10px;
  border-radius:20px;
  font-size:11px;
  font-weight:700;
  margin-left:8px;
}
</style>
</head>
<body>
<div class="container">
  <form class="form" id="crearForm" method="post" enctype="multipart/form-data">
    <div class="header">
      <h2> Crear Nueva Lección</h2>
    </div>
    
    <input type="hidden" name="accion" value="crear">
    
    <div class="form-group">
      <label> Título de la lección <span>*</span></label>
      <input type="text" name="titulo" placeholder="Ej: Historia de Bolivia" required>
    </div>
    
    <div class="form-group">
      <label> Contenido de la lección <span>*</span></label>
      <textarea name="desarrollo" placeholder="Escribe el contenido principal de la lección..." required></textarea>
    </div>
    
    <div class="divider"></div>
    
    <div class="form-group">
      <label> Imagen para el título <span class="optional-badge">OPCIONAL</span></label>
      <input type="file" name="titulo_img" accept=".png,.jpg,.jpeg">
    </div>
    
    <div class="form-group">
      <label> Portada frontal <span>*</span></label>
      <input type="file" name="portada_front" accept=".png,.jpg,.jpeg" required>
    </div>
    
    <div class="form-group">
      <label> Portada trasera <span>*</span></label>
      <input type="file" name="portada_back" accept=".png,.jpg,.jpeg" required>
    </div>
    
    <div class="divider"></div>
    
    <button type="button" onclick="confirmarCreacion()"> Crear Lección</button>
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
    title: ' Crear Lección',
    text: '¿Deseas guardar esta nueva lección?',
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
        text: 'Creando tu lección',
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