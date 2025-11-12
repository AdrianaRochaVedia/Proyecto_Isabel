<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Registro</title>
  <link rel="stylesheet" href="reg.css">
  <!-- ✅ Carga la librería antes del include -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

<!-- ✅ Ahora sí incluye tu lógica PHP -->
<?php include("conreg.php"); ?>

<main>
  <section>
    <img src="98ab391dfc221be3b95ccfe6ee9bd65a.jpg" alt="">
  </section>

  <section>
    <header>
      <h3><a href="../login/login.php">¿Ya tienes cuenta? Inicia sesión</a></h3>
    </header>

    <h1>Crear una cuenta</h1>

    <form method="post" action="">
      <div>
        <label>Nombre
          <input type="text" name="nombren" placeholder="Ingrese Nombre" required>
        </label>
        <label>Apellido
          <input type="text" name="apellidon" placeholder="Ingrese Apellido" required>
        </label>
      </div>

      <label>Usuario
        <input type="text" name="usuarion" placeholder="Ingrese Usuario" required>
      </label>

      <label>Correo Electrónico
        <input type="email" name="correon" placeholder="Ingrese Correo Electrónico" required>
      </label>

      <div>
        <label>Contraseña
          <input type="password" name="contran" placeholder="Ingrese Contraseña" required>
        </label>
        <label>Confirmar contraseña
          <input type="password" name="contran2" placeholder="Repita Contraseña" required>
        </label>
      </div>

      <p>
        <input type="checkbox" required>
        <span>Al crear tu cuenta aceptas nuestros <b>Términos y Condiciones</b>.</span>
      </p>

      <div>
        <input type="submit" name="Registrar" value="Crear cuenta">
        <a href="../login/login.php">Volver al login</a>
      </div>
    </form>
  </section>
</main>

</body>
</html>
