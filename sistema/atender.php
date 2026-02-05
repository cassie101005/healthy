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
                                    <h4 class="m-0 font-weight-bold text-primary">CITA MEDICA EN ATENCIÓN</h4>
                                    <div class="dropdown no-arrow">
                                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">

                                        </a>
                                        <img src="img/atencion.png" width="100">
                                    </div>
                                </div>
                                <!-- Card Body -->
                                <div class="card-body">
                                    <!--CONTENIDO-->
                                    <?php
                                    require_once "../modelo/utilidades/conexion.php";
                                    require_once "../modelo/consultar/consultar_citas.php";
                                    $id = $_GET["id"];

                                    $resulUser = getInfoCita($id);
                                    ?>
                                    <div class="table-responsive">
                                        <?php
                                        foreach ($resulUser as $medico) { ?>
                                            <h4>Paciente: <?php echo $medico["PACIENTE"]; ?></h4>
                                            <?php
                                        }
                                        ?>
                                        <hr>
                                        <form action="../modelo/registrar/registro_atencion.php" method="POST">
                                            <input hidden type="text" value="<?php echo $id; ?>" name="idCita">
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label>Registre los sintomas del paciente</label>
                                                    <textarea class="form-control" name="vSintomas" id="" rows="6"></textarea>
                                                </div>

                                                <div class="col-md-6 mb-3">
                                                    <label>Registre el diagnostico</label>
                                                    <textarea class="form-control" name="vDiagnostico" id="" rows="6"></textarea>
                                                </div>                                                
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12 mb-6">
                                                    <label>Registre el tratamiento y/o sugerencias para el paciente</label>
                                                    <textarea class="form-control" name="vTratamiento" id="" rows="6"></textarea>
                                                </div>                                             
                                            </div>

                                            <button type="submit" class="btn btn-success mt-3">Guardar consulta</button>
                                        </form>

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

<script>
    function mostrarMedicos() {
        var especialidad = document.querySelector('select[name="vEspecialidad"]').value;

        fetch("../modelo/consultar/obtener_medicos_por_especialidad.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: "especialidad=" + encodeURIComponent(especialidad)
        })
            .then(response => response.text())
            .then(data => {
                document.getElementById("idMedico").innerHTML = data;
            })
            .catch(error => {
                console.error("Error al cargar médicos:", error);
            });
    }

    function mostrarHoras() {
        var idMedico = document.getElementById('idMedico').value;
        var fecha = document.getElementById('dFecha').value;

        if (idMedico && fecha) {
            fetch("../modelo/consultar/obtener_horas_disponibles.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: "idMedico=" + encodeURIComponent(idMedico) + "&fecha=" + encodeURIComponent(fecha)
            })
                .then(response => response.text())
                .then(data => {
                    document.getElementById("vHora").innerHTML = data;
                })
                .catch(error => {
                    console.error("Error al cargar horas:", error);
                });
        }
    }
</script>


</html>