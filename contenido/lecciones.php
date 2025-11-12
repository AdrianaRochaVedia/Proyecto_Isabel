<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link rel="stylesheet" href="lecciones.css">
    <link rel="stylesheet" href="card.css">
    <link rel="stylesheet" href="../titulos.css">
       <link rel="stylesheet" href="../stylesof.css">
   
   
    <title>LECCIONES</title>
</head>
<body>
    <?php
if (session_status() === PHP_SESSION_NONE) session_start();
?>
    <div class="header">
        <video autoplay muted loop class="background-video">
            <source src="video.mp4" type="video/mp4">
            
      
        </video>
        <img  class="logo"   src="Brown White Modern Elegant Round Coffee Shop Logo (3).png" alt="">
        
           
</div>

    <div class="navbar">
    <a href="../inicio.php">Inicio </a>
        <div class="dropdown">
            <a href="lecciones.php">Lecciones</a>
            <div class="dropdown-content">
              <a href="lecciones_html/leccion-1.html">Guerra Federal</a>
                <a href="lecciones_html/leccion-2.html">Mapa de pérdidas territoriales de Bolivia</a>
                <a href="lecciones_html/leccion-3.html">Usurpación del Litoral </a>
                <a href="lecciones_html/leccion-4.html">La Guerra del Chaco</a>
                <a href="lecciones_html/leccion-5.html">Culturas antes de la colonia en el territorio nacional </a>
            </div>
        </div>
            <a href="../actividad.php">Actividades</a>
            <a href="../videos.php">Videos</a>
<a href="../registro/registro.php">Registrarse</a>


    </div>
    
<div class="contenedor">
    <div class="titulo"> <center><h1 >LECCIONES</h1></center></div>
  


    <div class="card">
        <div class="face front">
        
        <h3>AGREGA MÁS TEMAS</h3>
    </div>
        <div class="face back">
            <a href="lecciones_crear.php"><img src="../f.jpg" alt=""></a>
           
        </div>
       

      
       
    </div>
    
    <?php
$cn = mysqli_connect("localhost","root","","SISSOCIALES");
mysqli_set_charset($cn,"utf8mb4");
$rs = mysqli_query($cn,"SELECT id_leccion,titulo,portada_front,portada_back FROM LECCION WHERE estado=1 AND portada_front<>'' AND portada_back<>'' ORDER BY id_leccion DESC");
while($r = mysqli_fetch_row($rs)){
  $id = (int)$r[0];
  $t  = htmlspecialchars($r[1]);
  $f  = htmlspecialchars($r[2]);
  $b  = htmlspecialchars($r[3]);
  $link = "lecciones_html/leccion-$id.html";
  echo "
  <div class='card'>
    <div class='face front'>
      <img src='$f' alt=''>
      <h3>$t</h3>
    </div>
    <div class='face back'>
      <a href='$link' target='_blank'><img src='$b' alt=''></a>
    </div>
  </div>";
}
mysqli_close($cn);
?>


 
</div>
<section>
     <img 
    src="../6d41c541-7834-4b84-9471-1894b7caaad6.jpg" >
    <img 
    src="../chaco2.jpg" 
 >
   
    <img src="../pac2.jpg">

    <img src="../cul3.jpg">

  </section>

</body>
</html>

<?php if (!empty($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1): ?>
  <a href="lecciones_crear.php" class="btn-primary">+ Crear lección</a>
<?php endif; ?>

<style>
:root {
  --salmon3: #f6a89e;
  --salmon4: #f28b6b;
}

.btn-primary {
  position: fixed;       
  top: 400px;                
  right: 30px;             
  background: var(--salmon3);
  color: #fff;
  text-decoration: none;
  padding: 10px 16px;
  border-radius: 10px;
  font-weight: 800;
  z-index: 9999;           
  transition: 0.2s ease;
}

.btn-primary:hover {
  background: var(--salmon4);
  transform: translateY(-2px);
}
</style>
