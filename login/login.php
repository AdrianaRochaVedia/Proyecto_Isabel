<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Login</title>
  <link rel="stylesheet" href="log.css">
  <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>

  <div>
    <div class="encabezado"></div>
    <h1>BIENVENIDO A LA PÁGINA</h1>

    <form action="controlador.php" method="post">
      <label for="usuario">Usuario</label>
      <input type="text" name="usuario" id="usuario" placeholder="Ingrese Usuario o Correo Electrónico" required>

      <label for="contra">Contraseña</label>
      <input type="password" name="contra" id="contra" placeholder="Ingrese Contraseña" required>

      <input type="submit" name="Ingresar" value="Ingresar">
    </form>

    <h3>¿No tienes cuenta? <a href="../registro/registro.php">Regístrate</a></h3>
  </div>

</body>
</html>
