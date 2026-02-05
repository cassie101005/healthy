<!DOCTYPE html>
<html lang="es">

<head>
    </style>
    <link rel="stylesheet" href="css/tablas.css">

    <!-- DataTables con Bootstrap 4 -->

<body id="page-top">
    <!-- Page Wrapper -->
    <div id="wrapper">
        <?php
        include "sideBar.php";
        //session_start();
        require_once "../modelo/utilidades/conexion.php";
        require_once "../modelo/consultar/consultar_centros.php";
        $tipo = 0;
        if ($_SESSION['tipoUsuario'] === 'admin') {
            $tipo = 1;
        } else if ($_SESSION['tipoUsuario'] === 'medico') {
            $tipo = 2;
        } else {
            $tipo = 3;
        }
        // Obtenemos los médicos
        $idUser = $_SESSION['idUsuario'];
        $resulUser = getCitasParaMedicos($tipo, $idUser);
        ?>

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
                                    <?php
                                    //  echo $tipo;
                                    if (!empty($resulUser)) {
                                        $sesion = "";
                                        if ($tipo == 1) {
                                            $sesion = "Centro médico ";
                                        } else if ($tipo == 2) {
                                            $sesion = "Médico";
                                        } else {
                                            $sesion = "Paciente";
                                        }
                                        foreach ($resulUser as $medico) {
                                            ?>
                                            <h4 class="m-0 font-weight-bold text-primary">
                                                <?php echo $sesion . " en sesión: " . $medico["RESULTADO"]; ?></h4>
                                        <?php
                                        }
                                    }
                                    ?>
                                    <div class="dropdown no-arrow">
                                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">

                                        </a>
                                        <!--img src="img/logo_citas2.png" width="100"-->
                                    </div>
                                </div>
                                <!-- Card Body -->
                                <div class="card-body">
                                    <!--CONTENIDO-->
                                    <div class="row">


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
    if (bandera == 1) {
        ordenar();
    }
    function ordenar() {
        $(document).ready(function () {
            $('#tablaCitas').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json"
                }
            });
        });
    }


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
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>



</html>