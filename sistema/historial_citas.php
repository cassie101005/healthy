<?php 
// 🚨 IMPORTANTE: DEBE SER LA PRIMERA LÍNEA
require_once "../controlador/redireccion.php"; 
require_once "../modelo/utilidades/conexion.php";
require_once "../modelo/consultar/consultar_citas.php";

$idUser = $_SESSION['idUsuario'];
$resulUser = getCitas($idUser);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <?php include "cabecera.php"; ?>
    <style>
        .boton-igual {
            width: 120px;
            text-align: center;
        }
    </style>
</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <?php include "sideBar.php"; ?>

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <?php include "topBar.php"; ?>

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <div class="row">
                        <div class="col-xl-12 col-lg-12">
                            <div class="card shadow mb-4">

                                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h4 class="m-0 font-weight-bold text-primary">MIS CITAS MEDICAS</h4>
                                    <img src="img/logo_citas2.png" width="100">
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3 ml-3 mt-3">
                                        <a class="btn btn-info mb-3" href="nueva_cita">
                                            Registrar nueva cita
                                        </a>
                                    </div>
                                </div>

                                <div class="card-body">
                                    <div class="table-responsive">

                                        <table class="table table-striped table-bordered mt-4">
                                            <thead>
                                                <tr>
                                                    <th>Código</th>
                                                    <th>Especialidad</th>
                                                    <th>Médico</th>
                                                    <th>Fecha</th>
                                                    <th>Hora</th>
                                                    <th>Modalidad</th>
                                                    <th>Estatus</th>
                                                    <th>Opciones</th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                                <?php if (!empty($resulUser)): ?>

                                                    <?php foreach ($resulUser as $medico): ?>
                                                        <tr>
                                                            <td><?= htmlspecialchars($medico['CODIGO']) ?></td>
                                                            <td><?= htmlspecialchars($medico['ESPECIALIDAD']) ?></td>
                                                            <td><?= htmlspecialchars($medico['MEDICO']) ?></td>
                                                            <td><?= htmlspecialchars($medico['FECHA']) ?></td>
                                                            <td><?= htmlspecialchars($medico['HORA']) ?></td>

                                                            <?php
                                                            $mod = htmlspecialchars($medico['MODALIDAD'] ?? 'N/A');
                                                            if ($mod === 'Presencial') {
                                                                echo "<td><span class='badge bg-primary' style='font-size: 14px; color:white;'>Presencial</span></td>";
                                                            } elseif ($mod === 'Linea') {
                                                                echo "<td><span class='badge bg-info' style='font-size: 14px; color:white;'>En línea</span></td>";
                                                            } else {
                                                                echo "<td><span class='badge bg-secondary'>N/A</span></td>";
                                                            }
                                                            ?>

                                                            <?php
                                                            $estado = htmlspecialchars($medico['ESTADO']);
                                                            if ($estado === 'Agendada') {
                                                                echo "<td><span class='badge bg-success' style='font-size: 14px; color: #fff'>$estado</span></td>";
                                                            } elseif ($estado === 'Atendida') {
                                                                echo "<td><span class='badge bg-primary' style='font-size: 14px; color: #fff'>$estado</span></td>";
                                                            } elseif ($estado === 'Cancelada') {
                                                                echo "<td><span class='badge bg-danger' style='font-size: 14px;'>$estado</span></td>";
                                                            } else {
                                                                echo "<td><span class='badge bg-secondary' style='font-size: 14px;'>$estado</span></td>";
                                                            }
                                                            ?>

                                                            <td>
                                                                <?php if ($estado === 'Atendida'): ?>
                                                                    <a href="detalles?id=<?= $medico['CODIGO'] ?>" class="btn btn-success boton-igual mb-1">
                                                                        Detalles <i class="fas fa-eye fa-lg ms-2"></i>
                                                                    </a>
                                                                <?php endif; ?>

                                                                <!-- Botón de CHAT (SIEMPRE disponible) -->
                                                                <a href="chat?id=<?= $medico['CODIGO'] ?>" class="btn btn-warning boton-igual mt-1">
                                                                    Chat <i class="fas fa-comments fa-lg ms-2"></i>
                                                                </a>

                                                                <!-- Botón de VIDEOLLAMADA (SOLO para citas en línea Y NO atendidas) -->
                                                                <?php if ($medico['MODALIDAD'] === 'Linea' && $estado !== 'Atendida'): ?>
                                                                    <a href="videollamada?id=<?= $medico['CODIGO'] ?>" class="btn boton-igual mt-1" style="background-color: #6f42c1; color: white; font-size: 13px;">
                                                                        Videollamada <i class="fas fa-video fa-lg ms-2"></i>
                                                                    </a>
                                                                <?php endif; ?>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; ?>

                                                <?php else: ?>
                                                    <tr>
                                                        <td colspan="8" class="text-center">No hay citas registradas.</td>
                                                    </tr>
                                                <?php endif; ?>

                                            </tbody>
                                        </table>

                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>

                </div>
                <!-- /.container-fluid -->

            </div>
        </div>

    </div>

    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <?php include "cabeceraInferior.php"; ?>

</body>
</html>