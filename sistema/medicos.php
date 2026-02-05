<?php
require_once "../controlador/redireccion.php";
require_once "../modelo/utilidades/conexion.php";
require_once "../modelo/consultar/consultar_medicos.php";

$idCentro = $_SESSION['idUsuario'];
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <?php include "cabecera.php"; ?>
</head>

<body id="page-top">

<div id="wrapper">

    <?php include "sideBar.php"; ?>

    <div id="content-wrapper" class="d-flex flex-column">

        <div id="content">
            <?php include "topBar.php"; ?>

            <div class="container-fluid">

                <div class="row">
                    <div class="col-xl-12 col-lg-12">
                        <div class="card shadow mb-4">

                            <div class="card-header py-3">
                                <h4 class="m-0 font-weight-bold text-primary">Listado general de médicos registrados</h4>
                            </div>

                            <div class="card-body">
                                <div class="table-responsive">

                                    <button class="btn btn-success mb-3" data-toggle="collapse" data-target="#formRegistroMedico">
                                        Registrar nuevo médico <i class="fas fa-stethoscope ml-2"></i>
                                    </button>

                                    <div class="collapse" id="formRegistroMedico">
                                        <div class="card card-body">

                                            <form action="../modelo/registrar/registro_medicos.php" method="POST" enctype="multipart/form-data">

                                                <input hidden value="<?= $idCentro ?>" type="number" name="iCentro">
                                                
                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label>Nombre</label>
                                                        <input type="text" name="vNombre" class="form-control" required>
                                                    </div>

                                                    <div class="col-md-6 mb-3">
                                                        <label>Correo</label>
                                                        <input type="email" name="vCorreo" class="form-control" required>
                                                    </div>

                                                    <div class="col-md-6 mb-3">
                                                        <label>Teléfono</label>
                                                        <input type="text" name="vTelefono" class="form-control" required>
                                                    </div>

                                                    <div class="col-md-6 mb-3">
                                                        <label>Especialidad</label>
                                                        <select name="vEspecialidad" class="form-control" required>
                                                            <option value="">Seleccione una especialidad</option>
                                                            <option value="Endocrinología">Endocrinología</option>
                                                            <option value="Cardiología">Cardiología</option>
                                                            <option value="Neumología">Neumología</option>
                                                            <option value="Nefrología">Nefrología</option>
                                                            <option value="Reumatología">Reumatología</option>
                                                            <option value="Gastroenterología">Gastroenterología</option>
                                                            <option value="Hepatología">Hepatología</option>
                                                            <option value="Neurología">Neurología</option>
                                                            <option value="Psiquiatría">Psiquiatría</option>
                                                            <option value="Oncología">Oncología</option>
                                                        </select>
                                                    </div>

                                                    <div class="col-md-6 mb-3">
                                                        <label>Cédula Profesional</label>
                                                        <input type="text" name="vCedula" class="form-control" required>
                                                    </div>

                                                    <div class="col-md-6 mb-3">
                                                        <label>Usuario</label>
                                                        <input type="text" name="vUsuario" class="form-control" required>
                                                    </div>

                                                    <div class="col-md-6 mb-3">
                                                        <label>Contraseña</label>
                                                        <input type="password" name="vContrasena" class="form-control" required>
                                                    </div>

                                                    <div class="col-md-6 mb-3">
                                                        <label>Foto (opcional)</label>
                                                        <input type="file" name="vFoto" class="form-control">
                                                    </div>
                                                </div>

                                                <button type="submit" class="btn btn-primary mt-3">Guardar médico</button>
                                            </form>

                                        </div>
                                    </div>

                                    <?php $resulUser = getMedicos($idCentro); ?>

                                    <hr>

                                    <table class="table table-striped table-bordered mt-4">
                                        <thead>
                                            <tr>
                                                <th>Nombre</th>
                                                <th>Correo</th>
                                                <th>Teléfono</th>
                                                <th>Especialidad</th>
                                                <th>Cédula</th>
                                                <th>Usuario</th>
                                                <th>Foto</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            <?php if (!empty($resulUser)): ?>
                                                <?php foreach ($resulUser as $medico): ?>
                                                    <tr>
                                                        <td><?= $medico['vNombre'] ?></td>
                                                        <td><?= $medico['vCorreo'] ?></td>
                                                        <td><?= $medico['vTelefono'] ?></td>
                                                        <td><?= $medico['vEspecialidad'] ?></td>
                                                        <td><?= $medico['vCedula'] ?></td>
                                                        <td><?= $medico['vUsuario'] ?></td>

                                                        <td>
                                                            <?php if ($medico['vFoto']): ?>
                                                                <img src="uploads/medicos/<?= $medico['vFoto'] ?>" width="100">
                                                            <?php else: ?>
                                                                No disponible
                                                            <?php endif; ?>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>

                                            <?php else: ?>
                                                <tr><td colspan="7" class="text-center">No hay médicos registrados</td></tr>
                                            <?php endif; ?>

                                        </tbody>
                                    </table>

                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>

    </div>

<?php include "cabeceraInferior.php"; ?>
</body>
</html>
