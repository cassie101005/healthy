<!DOCTYPE html>
<html lang="es">

<head>
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
                    <!-- Content Row -->
                    <div class="row">

                        <!-- Area Chart -->
                        <div class="col-xl-12 col-lg-12">
                            <div class="card shadow mb-4">
                                <!-- Card Header - Dropdown -->
                                <div
                                    class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h4 class="m-0 font-weight-bold text-primary">Listado general de pacientes
                                        registrados</h4>
                                    <div class="dropdown no-arrow">
                                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        </a>
                                    </div>
                                </div>
                                <!-- Card Body -->
                                <div class="card-body">
                                    <!--CONTENIDO-->
                                    <div class="table-responsive">
                                        <!-- Botón para mostrar el formulario -->
                                        <button class="btn btn-success mb-3" type="button" data-toggle="collapse"
                                                data-target="#formRegistroMedico" aria-expanded="false"
                                                aria-controls="formRegistroMedico">
                                            Registrar nuevo paciente <i class="fa fa-user-md ml-2"></i>
                                        </button>


                                        <!-- Formulario dentro del collapse -->
                                        <div class="collapse" id="formRegistroMedico">
                                            <?php $idCentro = $_SESSION['idUsuario'];  ?>
                                            <div class="card card-body">
                                                <form action="../modelo/registrar/registro_pacientes.php" method="POST"
                                                    enctype="multipart/form-data">
                                                    <input hidden type="number" name="idCentro" class="form-control"
                                                    placeholder="ID del centro médico" required value="<?php echo $idCentro; ?>">
                                                    <div class="row">
                                                        <div class="col-md-6 mb-3">
                                                            <label>Nombre completo</label>
                                                            <input type="text" name="vNombre" class="form-control"
                                                                placeholder="Nombre del paciente" required>
                                                        </div>

                                                        <div class="col-md-6 mb-3">
                                                            <label>Sexo</label>
                                                            <select name="vSexo" class="form-control" required>
                                                                <option value="" disabled selected>Seleccione una opción</option>
                                                                <option value="Masculino">Masculino</option>
                                                                <option value="Femenino">Femenino</option>
                                                            </select>
                                                        </div>


                                                        <div class="col-md-6 mb-3">
                                                            <label>Fecha de nacimiento</label>
                                                            <input type="date" name="dFechaNacimiento"
                                                                class="form-control" required>
                                                        </div>

                                                        <div class="col-md-6 mb-3">
                                                            <label>Teléfono</label>
                                                            <input type="text" name="vTelefono" class="form-control"
                                                                placeholder="Teléfono de contacto">
                                                        </div>

                                                        <!--div class="col-md-6 mb-3">
                                                            <label>Correo electrónico</label>
                                                            <input type="email" name="vCorreo" class="form-control"
                                                                placeholder="Correo del paciente" required>
                                                        </div-->

                                                        <div class="col-md-12 mb-3">
                                                            <label>Dirección</label>
                                                            <input type="text" name="vDireccion" class="form-control"
                                                                placeholder="Dirección del paciente">
                                                        </div>                                                        

                                                        <div class="col-md-6 mb-3">
                                                            <label>Usuario</label>
                                                            <input type="text" name="vUsuario" class="form-control"
                                                                placeholder="Nombre de usuario" required
                                                                autocomplete="off">
                                                        </div>

                                                        <div class="col-md-6 mb-3">
                                                            <label>Contraseña</label>
                                                            <input type="password" name="vContrasena"
                                                                class="form-control" placeholder="Contraseña de acceso"
                                                                required autocomplete="new-password">
                                                        </div>

                                                        <!--div class="col-md-6 mb-3">
                                                            <label>Foto del paciente</label>
                                                            <input type="file" name="vFoto" class="form-control"
                                                                accept="image/*">
                                                        </div-->
                                                    </div>

                                                    <button type="submit" class="btn btn-primary mt-3">Registrar
                                                        paciente</button>
                                                </form>

                                            </div>
                                        </div>

                                        <?php
                                        require_once "../modelo/utilidades/conexion.php";
                                        require_once "../modelo/consultar/consultar_pacientes.php";                                       
                                        // Obtenemos los médicos
                                        $resulUser = getPacientes($idCentro);
                                        ?>
                                        <hr style="height: 5px; background-color:rgb(0, 159, 85); border: none;">
                                        <!-- Tabla para mostrar los médicos -->
                                        <table class="table table-striped table-bordered mt-4">
                                            <thead>
                                                <tr>
                                                    <th>Nombre</th>
                                                    <th>Fecha nacimiento</th>
                                                    <th>Teléfono</th>
                                                    <th>Dirección</th>
                                                    <th>Usuario</th>                                                 
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                // Verificamos si hay médicos en la base de datos
                                                if (!empty($resulUser)) {
                                                    foreach ($resulUser as $medico) {
                                                        echo "<tr>";
                                                        echo "<td>" . htmlspecialchars($medico['vNombre']) . "</td>";
                                                        echo "<td>" . htmlspecialchars($medico['dFechaNacimiento']) . "</td>";
                                                        echo "<td>" . htmlspecialchars($medico['vTelefono']) . "</td>";
                                                        echo "<td>" . htmlspecialchars($medico['vDireccion']) . "</td>";
                                                        echo "<td>" . htmlspecialchars($medico['vUsuario']) . "</td>";
                                                        // Verificamos si tiene foto para mostrarla
                                                       /* if ($medico['vFoto']) {
                                                            echo "<td><img src='uploads/medicos/" . htmlspecialchars($medico['vFoto']) . "' alt='Foto del médico' class='img-thumbnail' width='100'></td>";
                                                        } else {
                                                            echo "<td>No disponible</td>";
                                                        }*/
                                                        echo "</tr>";
                                                    }
                                                } else {
                                                    echo "<tr><td colspan='9' class='text-center'>No hay pacientes registrados.</td></tr>";
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <!--CONTENIDO-->
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

</body>

</html>