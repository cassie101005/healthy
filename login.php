<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Acceso Médico</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <style>
    body {
      margin: 0;
      padding: 0;
      height: 100vh;
      background: linear-gradient(to top right, #d0f0ff, #f0fbff);
      overflow: hidden;
      font-family: 'Segoe UI', sans-serif;
    }

    .bubble, .icon-float {
      position: absolute;
      bottom: -100px;
      opacity: 0.4;
      animation: floatUp infinite;
    }

    .icon-float {
      font-size: 2.5rem;
      opacity: 0.8;
    }

    @keyframes floatUp {
      0% {
        transform: translateY(0);
        opacity: 0;
      }
      50% {
        opacity: 0.6;
      }
      100% {
        transform: translateY(-120vh);
        opacity: 0;
      }
    }

    .login-wrapper {
      position: relative;
      z-index: 1;
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .login-card {
      background: rgba(255, 255, 255, 0.85);
      backdrop-filter: blur(10px);
      padding: 30px;
      border-radius: 20px;
      box-shadow: 0 10px 25px rgba(0,0,0,0.1);
      width: 100%;
      max-width: 400px;
      text-align: center;
    }

    .login-card h2 {
      color: #007bff;
      margin-bottom: 20px;
    }

    .form-control {
      border-radius: 10px;
    }

    .btn-primary {
      background-color: #007bff;
      border: none;
      border-radius: 10px;
      font-weight: bold;
    }

    .btn-primary:hover {
      background-color: #0056b3;
    }
  </style>
</head>
<body>

  <!-- Burbujas -->
  <div class="bubble" style="left: 10%; width: 80px; height: 80px; background: rgba(0,123,255,0.2); animation-duration: 14s; border-radius: 50%;"></div>
  <div class="bubble" style="left: 40%; width: 60px; height: 60px; background: rgba(0,123,255,0.2); animation-duration: 12s; border-radius: 50%;"></div>
  <div class="bubble" style="left: 75%; width: 90px; height: 90px; background: rgba(0,123,255,0.2); animation-duration: 16s; border-radius: 50%;"></div>

  <!-- Iconos -->
  <div class="icon-float" style="left: 20%; animation-duration: 15s;">🩺</div>
  <div class="icon-float" style="left: 50%; animation-duration: 13s;">💉</div>
  <div class="icon-float" style="left: 80%; animation-duration: 11s;">➕</div>
  <div class="icon-float" style="left: 35%; animation-duration: 18s;">👩‍⚕️</div>

  <!-- Formulario -->
  <div class="login-wrapper">
  <div class="login-card">
    <img src="logo.png" width="100" alt="Logo Médico">
    <h2>Iniciar Sesión</h2>
    <form action="modelo/consultar/verificar_usuario.php" method="post">
      <div class="mb-3 text-start">
        <label for="usuario" class="form-label">Usuario</label>
        <input type="text" id="usuario" name="txtUsuario" class="form-control" placeholder="Ingrese su usuario" required>
      </div>
      <div class="mb-3 text-start">
        <label for="contra" class="form-label">Contraseña</label>
        <input type="password" id="contra" name="txtContra" class="form-control" placeholder="Ingrese su contraseña" required>
      </div>
      <button type="submit" class="btn btn-primary w-100">Acceder</button>
    </form>

    <!-- Enlaces extra -->
    <div class="mt-3 text-center">
      <a href="register" class="text-primary">¿No tienes cuenta? Regístrate</a><br>
      <a href="index" class="text-secondary">← Volver al inicio</a>
    </div>
  </div>
</div>


   <?php if (isset($_SESSION["login_error"])): ?>
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Acceso denegado',
                text: '<?php echo $_SESSION["login_error"]; ?>'
            });
        </script>
        <?php unset($_SESSION["login_error"]); ?>
    <?php endif; ?>

</body>
</html>
