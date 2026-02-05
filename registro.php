<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Formulario de Registro</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-light">
  <div class="container py-5">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="card p-4 bg-white shadow rounded-4">
          <h2 class="text-center mb-4 text-primary">Registro de Centro Médico</h2>
          
          <?php 
            session_start();
            if (isset($_SESSION['registro_error'])):
          ?>
          <script>
            document.addEventListener("DOMContentLoaded", function () {
              Swal.fire({
                icon: 'error',
                title: 'Error de registro',
                text: '<?php echo $_SESSION['registro_error']; ?>',
                confirmButtonText: 'Aceptar'
              });
            });
          </script>
          <?php unset($_SESSION['registro_error']); ?>
          <?php endif; ?>

          <form action="modelo/registrar/registro_usuarios.php" method="POST" autocomplete="off">
            <div class="mb-3">
              <label for="nombre" class="form-label">Nombre del Centro</label>
              <input type="text" class="form-control" id="nombre" name="txtNombre" required autocomplete="off">
            </div>

            <div class="mb-3">
              <label for="direccion" class="form-label">Dirección</label>
              <input type="text" class="form-control" id="direccion" name="txtDireccion" required autocomplete="off">
            </div>

            <div class="mb-3">
              <label for="telefono" class="form-label">Teléfono</label>
              <input type="text" class="form-control" id="telefono" name="txtTelefono" required autocomplete="off">
            </div>

            <div class="mb-3">
              <label for="correo" class="form-label">Correo Electrónico</label>
              <input type="email" class="form-control" id="correo" name="txtCorreo" required autocomplete="off">
            </div>

            <div class="mb-3">
              <label for="director" class="form-label">Nombre del Director</label>
              <input type="text" class="form-control" id="director" name="txtDirector" required autocomplete="off">
            </div>

            <div class="mb-3">
              <label for="usuario" class="form-label">Usuario</label>
              <input type="text" class="form-control" id="usuario" name="txtUsuario" required autocomplete="off">
            </div>

            <div class="mb-3">
              <label for="password" class="form-label">Contraseña</label>
              <input type="password" class="form-control" id="password" name="txtContrasena" required autocomplete="new-password">
            </div>

            <button type="submit" class="btn btn-primary w-100">Registrar Centro</button>
          </form>

        </div>
      </div>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
