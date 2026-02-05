<!DOCTYPE html>
<html lang="es">

<head>
    <script>
        let bandera = 0;
    </script>
    <?php include "cabecera.php"; ?>
    <style>
         .boton-igual {
            width: 120px; /* ajusta según necesites */
            text-align: center;
        }
    </style>
    <link rel="stylesheet" href="css/tablas.css">
     
    <!-- DataTables con Bootstrap 4 -->      
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
                                    <h4 class="m-0 font-weight-bold text-primary">CITAS MEDICAS POR ATENDER</h4>
                                    <div class="dropdown no-arrow">
                                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">

                                        </a>
                                        <img src="img/logo_citas2.png" width="100">
                                    </div>
                                </div>
                                <!-- Card Body -->
                                <div class="card-body">
                                    <!--CONTENIDO-->
                                    <div class="table-responsive">
                                        <?php

                                        require_once "../modelo/utilidades/conexion.php";
                                        require_once "../modelo/consultar/consultar_citas.php";
                                        // Obtenemos los médicos
                                        $idUser = $_SESSION['idUsuario'];
                                        $resulUser = getCitasParaMedicos($idUser);
                                        ?>
                                        <!-- Tabla para mostrar los médicos -->
                                        <!--able class="table table-striped table-bordered mt-4-->
                                        <table id="tablaCitas" class="table table-striped table-bordered mt-4">

                                            <thead>
                                                <tr>
                                                    <th>Código</th>
                                                    <th>Médico</th>
                                                    <th>Paciente</th>
                                                    <th>Especialidad</th>
                                                    <th>Fecha</th>
                                                    <th>Hora</th>
                                                    <th>Modalidad</th>
                                                    <th>Estatus</th>
                                                    <th>Opciones</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                // Verificamos si hay médicos en la base de datos
                                                if (!empty($resulUser)) {
                                                    foreach ($resulUser as $medico) {
                                                        echo "<tr>";
                                                        echo "<td>" . htmlspecialchars($medico['CODIGO']) . "</td>";
                                                        echo "<td>" . htmlspecialchars($medico['MEDICO']) . "</td>";
                                                        echo "<td>" . htmlspecialchars($medico['PACIENTE']) . "</td>";
                                                        echo "<td>" . htmlspecialchars($medico['ESPECIALIDAD']) . "</td>";
                                                        echo "<td>" . htmlspecialchars($medico['FECHA']) . "</td>";
                                                        echo "<td>" . htmlspecialchars($medico['HORA']) . "</td>";
                                                         echo "<td>" . htmlspecialchars($medico['MODALIDAD']) . "</td>";
                                                        //$modalidad = $medico['MODALIDAD']; 
                                                       // echo "<td>" . $modalidad . "</td>";
                                                        // Mostrar el estatus con estilo
                                                        $estado = htmlspecialchars($medico['ESTADO']);
                                                        if ($estado === 'Agendada') {?>
                                                            <td>                                                                
                                                            <span class='badge bg-info' style='font-size: 14px; color: #fff'>
                                                                <?php echo $estado; ?>
                                                                <i class="fas fa-clock fa-lg ms-1 text-white"></i>
                                                            </span>

                                                            </td>
                                                        <?php
                                                        } elseif ($estado === 'Atendida') { ?>
                                                            <td>
                                                                <span class='badge bg-success' style='font-size: 14px; color: #fff'>
                                                                    <?php echo $estado; ?>
                                                                    <i class="fas fa-check fa-lg ms-1 text-white"></i>
                                                                </span>
                                                            </td>
                                                        <?php
                                                        } elseif ($estado === 'Cancelada') {
                                                            echo "<td><span class='badge bg-danger' style='font-size: 14px; color: #fff'>$estado</span></td>";
                                                        } else {
                                                            echo "<td><span class='badge bg-secondary' style='font-size: 14px; color: #fff'>$estado</span></td>";
                                                        }
                                                        ?>
                                                      <td>
                                                            <?php
                                                            if ($estado === 'Atendida') { ?>
                                                                <a href="detalles?id=<?php echo $medico['CODIGO']; ?>" class="btn btn-success boton-igual mb-1">
                                                                    Detalles <i class="fas fa-eye fa-lg ms-2"></i>
                                                                </a>
                                                            <?php
                                                            } else { ?>
                                                                <a href="atender?id=<?php echo $medico['CODIGO']; ?>" class="btn btn-info boton-igual mb-1">
                                                                    Atender <i class="fas fa-stethoscope fa-lg ms-2"></i>
                                                                </a>
                                                            <?php
                                                            }
                                                            ?>

                                                            <!-- Botón de chat (SIEMPRE disponible para la cita) -->
                                                            <a href="chat?id=<?php echo $medico['CODIGO']; ?>" class="btn btn-warning boton-igual mt-1">
                                                                Chat <i class="fas fa-comments fa-lg ms-2"></i>
                                                            </a>

                                                            <!-- Botón de videollamada (SOLO para citas en línea Y NO atendidas) -->
                                                            <?php if ($medico['MODALIDAD'] === 'Linea' && $estado !== 'Atendida'): ?>
                                                                <a href="videollamada?id=<?php echo $medico['CODIGO']; ?>" class="btn boton-igual mt-1" style="background-color: #6f42c1; color: white; font-size: 13px;">
                                                                    Videollamada <i class="fas fa-video fa-lg ms-2"></i>
                                                                </a>
                                                            <?php endif; ?>
                                                        </td>



                                                        
                                                        <?php
                                                        echo "</tr>";
                                                        ?>
                                                            <script>
                                                                bandera = 1;
                                                            </script>
                                                        <?php
                                                    }
                                                } else {
                                                    echo "<tr><td colspan='9' class='text-center'><div class='alert alert-info m-0'>No hay citas registradas.</div></td></tr>";

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

<script>
    if(bandera == 1){
        ordenar();
    }
function ordenar(){
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