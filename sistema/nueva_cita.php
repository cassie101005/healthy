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
                                    <h4 class="m-0 font-weight-bold text-primary">NUEVA CITA MEDICA</h4>
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
                                        <form action="../modelo/registrar/registro_citas.php" method="POST">
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label>Seleccione el área para su cita</label>
                                                    <!--input type="text" name="vEspecialidad" class="form-control" required-->
                                                    <select name="vEspecialidad" id="vEspecialidad" class="form-control"
                                                        onchange="mostrarMedicos();" required>
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
                                                    <label>Seleccione una Modalidad</label>
                                                    <select name="vModalidad" id="vModalidad" class="form-control" required>
                                                        <option value="">Seleccione una modalidad</option>
                                                        <option value="Presencial">Presencial</option>
                                                        <option value="Linea">En línea</option>
                                                    </select>
                                                </div>
                                                
                                                <div class="col-md-6 mb-3">
                                                    <label>Seleccione el médico</label>
                                                    <select name="idMedico" id="idMedico" class="form-control" required>
                                                        <option value="">Seleccione primero una especialidad</option>
                                                    </select>
                                                </div>

                                                <div class="col-md-6 mb-3">
                                                    <label>Seleccione la fecha</label>
                                                    <input type="date" name="dFecha" id="dFecha" class="form-control"
                                                        onchange="mostrarHoras();" required>
                                                </div>

                                                <div class="col-md-6 mb-3">
                                                    <label>Seleccione la hora</label>
                                                    <select name="vHora" id="vHora" class="form-control" required>
                                                        <option value="">Seleccione primero una fecha</option>
                                                    </select>
                                                </div>

                                            </div>
                                            <div class="d-flex justify-content-between mt-3">
                                                <button type="submit" class="btn btn-success">Registrar Cita</button>

                                                <a href="historial_citas" class="btn btn-success">Regresar</a>

                                            </div>

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
        var especialidad = document.getElementById('vEspecialidad').value;
        
        console.log("Especialidad seleccionada:", especialidad);

        if (!especialidad) {
            document.getElementById("idMedico").innerHTML = '<option value="">Seleccione primero una especialidad</option>';
            return;
        }

        fetch("../modelo/consultar/obtener_medicos_por_especialidad.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: "especialidad=" + encodeURIComponent(especialidad)
        })
            .then(response => response.text())
            .then(data => {
                console.log("Respuesta del servidor:", data);
                document.getElementById("idMedico").innerHTML = data;
            })
            .catch(error => {
                console.error("Error al cargar médicos:", error);
                document.getElementById("idMedico").innerHTML = '<option value="">Error al cargar médicos</option>';
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