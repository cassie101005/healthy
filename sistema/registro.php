<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QSPORTS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="sistema/funciones/funcionesRegistroUsuarios.js"></script>
</head>
<body class="d-flex justify-content-center align-items-center vh-100">
    <div class="card p-4 shadow" style="width: 350px;">
        <h2 class="text-center">Iniciar Sesión</h2>
        <form action="sistema/modelo/registro/registroUsuarios.php" method = "POST">
            <div class="mb-3">
                <label class="form-label">Nombre</label>
                <input type="text" class="form-control" placeholder="Ingresa tu nombre" name="txtNombre" autocomplete="off" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Apellido</label>
                <input type="text" class="form-control" placeholder="Ingresa tu apellido" name="txtApellido" autocomplete="off"  required>
            </div>
            <div class="mb-3">
                <label class="form-label">Correo Electrónico</label>
                <input type="text" class="form-control" placeholder="Ingresa tu correo" name="txtCorreo" autocomplete="off" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Contraseña</label>
                <input type="password" class="form-control" placeholder="Ingresa tu contraseña" name="txtContra" autocomplete="off" required>
            </div>
            <button type="submit"  class="btn btn-primary w-100">Registro</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
