<!DOCTYPE html>
<html lang="es">

<head>
    <?php include "cabecera.php"; ?>
</head>

<body id="page-top">
    <?php
    $id = $_GET["id"];
    ?>
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
                                    <h4 class="m-0 font-weight-bold text-primary">CITA MEDICA ATENDIDA <i
                                            class="fas fa-check fa-lg ms-1 text-success"></i></h4>
                                    <div class="dropdown no-arrow">
                                        <img src="img/atencion.png" width="100">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 mb-2">
                                        <!--a href="citas_medicas" class="btn btn-danger mt-3 ml-3">Regresar</a-->
                                        <!--a href="citas_medicas" class="btn btn-danger mt-3 ml-3">
                                        Regresar <i class="fas fa-arrow-left ml-2"></i>
                                    </a-->
                                        <a href="receta?id=<?php echo $id; ?>" target="_blank"
                                            class="btn btn-success mt-3 ml-3">
                                            Ver receta <i class="fas fa-notes-medical ml-2"></i>
                                        </a>
                                        <a href="#" class="btn btn-info mt-3 ml-3" data-toggle="modal"
                                            data-target="#modalGlosario">
                                            Glosario Técnico <i class="fas fa-book-medical ml-2"></i>
                                        </a>
                                    </div>
                                </div>
                                <!-- Card Body -->
                                <div class="card-body">
                                    <!--CONTENIDO-->
                                    <?php
                                    require_once "../modelo/utilidades/conexion.php";
                                    require_once "../modelo/consultar/consultar_citas.php";

                                    $resulUser = getInfoCita($id);
                                    ?>
                                    <div class="table-responsive">
                                        <?php
                                        foreach ($resulUser as $medico) { ?>
                                            <h4>Paciente: <?php echo $medico["PACIENTE"]; ?></h4>
                                            <?php
                                            ?>
                                            <hr>
                                            <input hidden type="text" value="<?php echo $id; ?>" name="idCita">
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label>Sintomas presentados</label>
                                                    <textarea readonly class="form-control" name="vSintomas" id=""
                                                        rows="6"><?php echo $medico["SINTOMAS"]; ?></textarea>
                                                </div>

                                                <div class="col-md-6 mb-3">
                                                    <label>Diagnostico</label>
                                                    <textarea readonly class="form-control" name="vDiagnostico" id=""
                                                        rows="6"><?php echo $medico["DIAGNOSTICO"]; ?></textarea>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12 mb-6">
                                                    <label>Tratamiento y/o sugerencias para el paciente</label>
                                                    <textarea readonly class="form-control" name="vTratamiento" id=""
                                                        rows="6"><?php echo $medico["TRATAMIENTO"]; ?></textarea>
                                                </div>
                                            </div>
                                            <?php
                                        }
                                        ?>

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

    <!-- Modal Glosario Técnico -->
    <div class="modal fade" id="modalGlosario" tabindex="-1" role="dialog" aria-labelledby="modalGlosarioLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title" id="modalGlosarioLabel">Glosario Técnico de Términos Médicos</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <!-- Categoría: Términos Generales -->
                    <h5 class="text-primary">🩺 Términos Generales</h5>
                    <ul class="list-group mb-3">
                        <li class="list-group-item"><strong>Cefalea:</strong> Dolor de cabeza.</li>
                        <li class="list-group-item"><strong>Fiebre:</strong> Aumento de la temperatura corporal.</li>
                        <li class="list-group-item"><strong>Disnea:</strong> Dificultad para respirar.</li>
                        <li class="list-group-item"><strong>Edema:</strong> Hinchazón por acumulación de líquidos.</li>
                        <li class="list-group-item"><strong>Náuseas:</strong> Sensación de querer vomitar.</li>
                        <li class="list-group-item"><strong>Taquicardia:</strong> Frecuencia cardíaca elevada.</li>
                        <li class="list-group-item"><strong>Bradicardia:</strong> Frecuencia cardíaca baja.</li>
                        <li class="list-group-item"><strong>Hipotensión:</strong> Presión arterial baja.</li>
                        <li class="list-group-item"><strong>Hipertensión:</strong> Presión arterial alta.</li>
                    </ul>

                    <!-- Categoría: Enfermedades Crónicas Comunes -->
                    <h5 class="text-success">📋 Enfermedades Crónicas Comunes</h5>
                    <ul class="list-group mb-3">
                        <li class="list-group-item"><strong>Diabetes Mellitus:</strong> Alteración metabólica con
                            niveles altos de azúcar en sangre.</li>
                        <li class="list-group-item"><strong>Hipertensión Arterial:</strong> Elevación constante de la
                            presión sanguínea.</li>
                        <li class="list-group-item"><strong>Asma:</strong> Inflamación crónica de las vías
                            respiratorias.</li>
                        <li class="list-group-item"><strong>Artritis Reumatoide:</strong> Enfermedad autoinmune que
                            afecta articulaciones.</li>
                        <li class="list-group-item"><strong>Enfermedad Renal Crónica:</strong> Deterioro progresivo de
                            la función renal.</li>
                        <li class="list-group-item"><strong>EPOC:</strong> Enfermedad pulmonar obstructiva crónica.</li>
                        <li class="list-group-item"><strong>Osteoporosis:</strong> Disminución de la densidad ósea.</li>
                    </ul>

                    <!-- Categoría: Síntomas Frecuentes -->
                    <h5 class="text-warning">🧠 Síntomas Frecuentes</h5>
                    <ul class="list-group mb-3">
                        <li class="list-group-item"><strong>Mialgia:</strong> Dolor muscular.</li>
                        <li class="list-group-item"><strong>Artralgia:</strong> Dolor en las articulaciones.</li>
                        <li class="list-group-item"><strong>Fatiga:</strong> Cansancio físico o mental excesivo.</li>
                        <li class="list-group-item"><strong>Mareos:</strong> Sensación de inestabilidad.</li>
                        <li class="list-group-item"><strong>Tos:</strong> Expulsión brusca de aire para limpiar vías
                            respiratorias.</li>
                        <li class="list-group-item"><strong>Vómito:</strong> Expulsión violenta del contenido estomacal.
                        </li>
                        <li class="list-group-item"><strong>Palpitaciones:</strong> Percepción de latidos del corazón
                            acelerados o irregulares.</li>
                    </ul>

                    <!-- Categoría: Tratamientos y Medicamentos -->
                    <h5 class="text-danger">💊 Tratamientos y Medicamentos</h5>
                    <ul class="list-group mb-3">
                        <li class="list-group-item"><strong>Antibióticos:</strong> Sustancias que eliminan o inhiben
                            bacterias.</li>
                        <li class="list-group-item"><strong>Antiinflamatorios:</strong> Medicamentos que reducen la
                            inflamación.</li>
                        <li class="list-group-item"><strong>Antipiréticos:</strong> Medicamentos que bajan la fiebre.
                        </li>
                        <li class="list-group-item"><strong>Insulina:</strong> Hormona que regula los niveles de
                            glucosa.</li>
                        <li class="list-group-item"><strong>Broncodilatadores:</strong> Medicamentos que expanden las
                            vías respiratorias.</li>
                        <li class="list-group-item"><strong>Diuréticos:</strong> Medicamentos que eliminan líquidos del
                            cuerpo.</li>
                        <li class="list-group-item"><strong>Analgésicos:</strong> Medicamentos que alivian el dolor.
                        </li>
                    </ul>

                    <!-- Categoría: Procedimientos Médicos -->
                    <h5 class="text-secondary">🔬 Procedimientos Médicos</h5>
                    <ul class="list-group mb-3">
                        <li class="list-group-item"><strong>Electrocardiograma (ECG):</strong> Registro de la actividad
                            eléctrica del corazón.</li>
                        <li class="list-group-item"><strong>Biopsia:</strong> Extracción de tejido para análisis.</li>
                        <li class="list-group-item"><strong>Radiografía:</strong> Imagen interna del cuerpo usando rayos
                            X.</li>
                        <li class="list-group-item"><strong>Hemograma:</strong> Análisis de los componentes de la
                            sangre.</li>
                        <li class="list-group-item"><strong>TAC:</strong> Tomografía axial computarizada, estudio
                            detallado por imágenes.</li>
                    </ul>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>


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