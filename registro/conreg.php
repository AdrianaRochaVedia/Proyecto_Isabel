<?php
$conexion = new mysqli("localhost", "root", "", "sissociales");
$conexion->set_charset("utf8");

if ($conexion->connect_errno) {
  echo "
  <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
  <script>
    Swal.fire({icon:'error', title:'Error de conexión', text:".json_encode($conexion->connect_error)."});
  </script>";
  exit;
}

if (isset($_POST["Registrar"])) {

  $requeridos = ["nombren","apellidon","usuarion","correon","contran","contran2"];
  foreach ($requeridos as $r) {
    if (empty($_POST[$r])) {
      echo "
      <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
      <script>
        Swal.fire({icon:'info', title:'Campos vacíos', text:'Completa todos los campos.'});
      </script>";
      exit;
    }
  }

  $nombren   = $_POST["nombren"];
  $apellidon = $_POST["apellidon"];
  $usuarion  = $_POST["usuarion"];
  $correon   = $_POST["correon"];
  $contran   = $_POST["contran"];
  $contran2  = $_POST["contran2"];

  if ($contran !== $contran2) {
    echo "
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    <script>
      Swal.fire({icon:'warning', title:'Contraseñas diferentes', text:'Por favor verifica ambas.'});
    </script>";
    exit;
  }
  $sql = "INSERT INTO usuario (nombre, apellido, usuario, correo, contrasenia, rol)
          VALUES (?, ?, ?, ?, ?, 'USUARIO')";
  $stmt = $conexion->prepare($sql);

  if (!$stmt) {
    echo "
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    <script>
      Swal.fire({icon:'error', title:'Error de servidor', text:".json_encode($conexion->error)."});
    </script>";
    exit;
  }

  $stmt->bind_param("sssss", $nombren, $apellidon, $usuarion, $correon, $contran);
  $ok = $stmt->execute();

  if ($ok) {
    echo "
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    <script>
      Swal.fire({
        icon:'success', title:'Usuario creado', text:'Registro exitoso',
        timer:1500, showConfirmButton:false
      }).then(()=>{ window.location.href='../login/login.php'; });
    </script>";
  } else {

    $errno = $stmt->errno;
    $msg   = ($errno == 1062)
      ? 'El usuario o correo ya existe.'
      : 'No se pudo crear el usuario. Error: '.$stmt->error;

    echo "
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    <script>
      Swal.fire({icon:'error', title:'Error', text:".json_encode($msg)."});
    </script>";
  }

  $stmt->close();
}
?>
