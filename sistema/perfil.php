<?php
// sistema/perfil.php
session_start();
require_once "../modelo/utilidades/conexion.php";

// Verificar sesión
if (!isset($_SESSION['idUsuario'])) {
    header("Location: ../index.php");
    exit;
}

$tipoUsuario = $_SESSION['tipoUsuario'] ?? 'paciente';
$idUsuario = $_SESSION['idUsuario'];
$usuario = [];
$mensaje = $_SESSION['mensaje'] ?? '';
$tipoMensaje = $_SESSION['tipo_mensaje'] ?? 'success';

// Limpiar mensajes de sesión
unset($_SESSION['mensaje']);
unset($_SESSION['tipo_mensaje']);

// Obtener datos frescos de la base de datos
try {
    if ($tipoUsuario === 'medico') {
        $stmt = $pdo->prepare("SELECT * FROM tbl_medicos WHERE id = ?");
        $stmt->execute([$idUsuario]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
    } else {
        $stmt = $pdo->prepare("SELECT * FROM tbl_pacientes WHERE idPaciente = ?");
        $stmt->execute([$idUsuario]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
    }
} catch (PDOException $e) {
    die("Error al obtener datos: " . $e->getMessage());
}

if (!$usuario) {
    die("Usuario no encontrado.");
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Perfil - Healthy</title>
    <?php include "cabecera.php"; ?>
</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <?php include "sideBar.php"; ?>

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <?php include "topBar.php"; ?>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Mi Perfil</h1>
                        <button id="btnRegresar" class="btn btn-success shadow-sm">
                            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Regresar
                        </button>
                    </div>

                    <?php if ($mensaje): ?>
                        <div class="alert alert-<?php echo $tipoMensaje; ?> alert-dismissible fade show" role="alert">
                            <?php echo $mensaje; ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php endif; ?>

                    <div class="row">

                        <!-- Profile Card -->
                        <div class="col-xl-4 col-lg-5">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Foto de Perfil</h6>
                                </div>
                                <div class="card-body text-center">
                                    <div class="position-relative d-inline-block" style="cursor: pointer;"
                                        onclick="document.getElementById('vFoto').click();">
                                        <?php
                                        $fotoPerfil = 'img/undraw_profile.svg';
                                        if (!empty($usuario['vFoto'])) {
                                            $rutaFoto = 'uploads/' . ($tipoUsuario === 'medico' ? 'medicos/' : 'pacientes/') . $usuario['vFoto'];
                                            if (file_exists($rutaFoto)) {
                                                $fotoPerfil = $rutaFoto;
                                            }
                                        }
                                        ?>
                                        <img id="imgPreview" class="img-profile rounded-circle mb-3"
                                            src="<?php echo $fotoPerfil; ?>"
                                            style="width: 150px; height: 150px; object-fit: cover;">
                                        <div class="position-absolute w-100 h-100 rounded-circle d-flex align-items-center justify-content-center"
                                            style="top: 0; left: 0; background: rgba(0,0,0,0.5); opacity: 0; transition: opacity 0.3s;"
                                            onmouseover="this.style.opacity=1" onmouseout="this.style.opacity=0">
                                            <i class="fas fa-camera text-white fa-2x"></i>
                                        </div>
                                    </div>

                                    <h5 class="font-weight-bold">
                                        <?php echo htmlspecialchars($usuario['vNombre'] . ' ' . ($usuario['vApellido'] ?? '')); ?>
                                    </h5>
                                    <p class="text-muted"><?php echo ucfirst($tipoUsuario); ?></p>

                                    <?php if ($tipoUsuario === 'medico'): ?>
                                        <span
                                            class="badge badge-info"><?php echo htmlspecialchars($usuario['vEspecialidad']); ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <!-- Edit Form -->
                        <div class="col-xl-8 col-lg-7">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Información Personal</h6>
                                </div>
                                <div class="card-body">
                                    <form action="../modelo/modificar/actualizar_perfil.php" method="POST"
                                        enctype="multipart/form-data">
                                        <input type="hidden" name="tipoUsuario" value="<?php echo $tipoUsuario; ?>">
                                        <input type="file" id="vFoto" name="vFoto" style="display: none;"
                                            accept="image/*">

                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="vNombre">Nombre</label>
                                                <input type="text" class="form-control" id="vNombre" name="vNombre"
                                                    value="<?php echo htmlspecialchars($usuario['vNombre']); ?>"
                                                    required>
                                            </div>

                                            <?php if ($tipoUsuario === 'medico'): ?>
                                                <div class="form-group col-md-6">
                                                    <label for="vApellido">Apellido</label>
                                                    <input type="text" class="form-control" id="vApellido" name="vApellido"
                                                        value="<?php echo htmlspecialchars($usuario['vApellido'] ?? ''); ?>">
                                                </div>
                                            <?php endif; ?>
                                        </div>

                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="vUsuario">Usuario (Login)</label>
                                                <input type="text" class="form-control" id="vUsuario" name="vUsuario"
                                                    value="<?php echo htmlspecialchars($usuario['vUsuario']); ?>"
                                                    readonly>
                                                <small class="form-text text-muted">El nombre de usuario no se puede
                                                    cambiar.</small>
                                            </div>

                                            <div class="form-group col-md-6">
                                                <label for="vTelefono">Teléfono</label>
                                                <input type="text" class="form-control" id="vTelefono" name="vTelefono"
                                                    value="<?php echo htmlspecialchars($usuario['vTelefono']); ?>">
                                            </div>
                                        </div>

                                        <?php if ($tipoUsuario === 'medico'): ?>
                                            <div class="form-group">
                                                <label for="vCorreo">Correo Electrónico</label>
                                                <input type="email" class="form-control" id="vCorreo" name="vCorreo"
                                                    value="<?php echo htmlspecialchars($usuario['vCorreo']); ?>">
                                            </div>

                                            <div class="form-row">
                                                <div class="form-group col-md-6">
                                                    <label for="vEspecialidad">Especialidad</label>
                                                    <input type="text" class="form-control" id="vEspecialidad"
                                                        name="vEspecialidad"
                                                        value="<?php echo htmlspecialchars($usuario['vEspecialidad']); ?>"
                                                        readonly>
                                                    <small class="form-text text-muted">Contacte al administrador para
                                                        cambiar su especialidad.</small>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="vCedula">Cédula Profesional</label>
                                                    <input type="text" class="form-control" id="vCedula" name="vCedula"
                                                        value="<?php echo htmlspecialchars($usuario['vCedula']); ?>"
                                                        readonly>
                                                </div>
                                            </div>
                                        <?php else: // Paciente ?>
                                            <div class="form-group">
                                                <label for="vDireccion">Dirección</label>
                                                <input type="text" class="form-control" id="vDireccion" name="vDireccion"
                                                    value="<?php echo htmlspecialchars($usuario['vDireccion']); ?>">
                                            </div>

                                            <div class="form-row">
                                                <div class="form-group col-md-6">
                                                    <label for="dFechaNacimiento">Fecha de Nacimiento</label>
                                                    <input type="date" class="form-control" id="dFechaNacimiento"
                                                        name="dFechaNacimiento"
                                                        value="<?php echo htmlspecialchars($usuario['dFechaNacimiento']); ?>"
                                                        readonly>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="vSexo">Sexo</label>
                                                    <input type="text" class="form-control" id="vSexo"
                                                        value="<?php echo htmlspecialchars($usuario['vSexo']); ?>" readonly>
                                                </div>
                                            </div>
                                        <?php endif; ?>

                                        <hr>
                                        <h6 class="font-weight-bold text-primary">Cambiar Contraseña (Opcional)</h6>
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="new_password">Nueva Contraseña</label>
                                                <input type="password" class="form-control" id="new_password"
                                                    name="new_password"
                                                    placeholder="Dejar en blanco para mantener la actual">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="confirm_password">Confirmar Contraseña</label>
                                                <input type="password" class="form-control" id="confirm_password"
                                                    name="confirm_password" placeholder="Repetir nueva contraseña">
                                            </div>
                                        </div>

                                        <button type="submit" class="btn btn-primary btn-block" id="btnGuardar"
                                            disabled>
                                            <i class="fas fa-save mr-2"></i>Guardar Cambios
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <?php include "cabeceraInferior.php"; ?>

    <script>
        $(document).ready(function () {
            const $form = $('form');
            const $btnGuardar = $('#btnGuardar');
            const $btnRegresar = $('#btnRegresar');

            // Check if we just saved (there's a message in the session)
            const justSaved = <?php echo ($mensaje) ? 'true' : 'false'; ?>;

            // Capture initial data after a small delay
            let initialData = '';
            setTimeout(function () {
                initialData = $form.serialize();
            }, 100);

            // Image Preview
            $('#vFoto').change(function () {
                const file = this.files[0];
                if (file) {
                    let reader = new FileReader();
                    reader.onload = function (event) {
                        $('#imgPreview').attr('src', event.target.result);
                        $btnGuardar.prop('disabled', false); // Enable save button
                    }
                    reader.readAsDataURL(file);
                }
            });

            // Detect changes in the form
            $form.on('change input', function () {
                const currentData = $form.serialize();
                if (currentData !== initialData) {
                    $btnGuardar.prop('disabled', false);
                } else {
                    $btnGuardar.prop('disabled', true);
                }
            });

            // Handle Back Button Click
            $btnRegresar.on('click', function (e) {
                e.preventDefault();
                const currentData = $form.serialize();

                if (currentData !== initialData) {
                    Swal.fire({
                        title: '¿Estás seguro?',
                        text: "Tienes cambios sin guardar. ¿Quieres salir sin guardar?",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Sí, salir',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // If we just saved, go back 2 steps to skip the POST redirect
                            if (justSaved) {
                                window.history.go(-2);
                            } else {
                                window.history.back();
                            }
                        }
                    });
                } else {
                    // If we just saved, go back 2 steps to skip the POST redirect
                    if (justSaved) {
                        window.history.go(-2);
                    } else {
                        window.history.back();
                    }
                }
            });
        });
    </script>

</body>

</html>