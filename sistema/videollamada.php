<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Videollamada - Cita Médica</title>
    <?php include "cabecera.php"; ?>
    <style>
        #meet-container {
            width: 100%;
            height: calc(100vh - 100px);
            min-height: 600px;
        }

        .video-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            border-radius: 10px 10px 0 0;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .video-info {
            background: #f8f9fa;
            padding: 15px;
            border-left: 4px solid #667eea;
            margin-bottom: 20px;
            border-radius: 5px;
        }

        .btn-salir {
            position: fixed;
            bottom: 30px;
            right: 30px;
            z-index: 9999;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
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

                <!-- Topbar -->
                <?php include "topBar.php"; ?>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <?php
                    // Verificar si se recibió el ID de la cita
                    if (!isset($_GET['id']) || empty($_GET['id'])) {
                        echo '<div class="alert alert-danger">No se especificó la cita para la videollamada.</div>';
                        echo '<a href="historial_citas.php" class="btn btn-primary">Volver a Citas</a>';
                        exit;
                    }

                    require_once "../modelo/utilidades/conexion.php";

                    // Obtener información de la cita
                    $idCita = intval($_GET['id']);
                    $idUser = $_SESSION['idUsuario'];

                    // Determinar la página de retorno según el rol del usuario
                    $tipoUsuario = $_SESSION['tipoUsuario'] ?? '';
                    $paginaRetorno = ($tipoUsuario === 'medico') ? 'citas_medicas.php' : 'historial_citas.php';

                    // Consultar los detalles de la cita usando los nombres reales de las tablas
                    $sql = "SELECT 
                                c.id as idCita, 
                                c.dFecha as fecha, 
                                c.vHora as hora, 
                                c.vModalidad as modalidad, 
                                c.vEstado as estado,
                                m.vNombre as nombreMedico, 
                                m.vApellido as apellidoMedico,
                                p.vNombre as nombrePaciente, 
                                '' as apellidoPaciente,
                                c.vEspecialidad as especialidad
                            FROM tbl_citas c
                            INNER JOIN tbl_medicos m ON c.idMedico = m.id
                            INNER JOIN tbl_pacientes p ON c.idUsuario = p.idPaciente
                            WHERE c.id = ?";

                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([$idCita]);
                    $cita = $stmt->fetch();

                    if (!$cita) {
                        echo '<div class="alert alert-danger">No se encontró la cita especificada.</div>';
                        echo '<a href="' . $paginaRetorno . '" class="btn btn-primary">Volver a Citas</a>';
                        exit;
                    }

                    // Verificar que la cita sea en línea
                    if ($cita['modalidad'] !== 'Linea') {
                        echo '<div class="alert alert-warning">Esta cita no es en modalidad en línea.</div>';
                        echo '<a href="' . $paginaRetorno . '" class="btn btn-primary">Volver a Citas</a>';
                        exit;
                    }

                    // Crear nombre único para la sala de videollamada
                    $nombreSala = "Cita-" . $idCita . "-" . date('Ymd');

                    // Obtener nombre del usuario actual
                    $nombreUsuario = $_SESSION['nombre'] ?? 'Usuario';
                    ?>

                    <!-- Content Row -->
                    <div class="row">
                        <div class="col-xl-12 col-lg-12">
                            <div class="card shadow mb-4">
                                <!-- Card Header -->
                                <div class="video-header">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h3 class="m-0">
                                                <i class="fas fa-video mr-2"></i>
                                                Videollamada - Cita #<?php echo $idCita; ?>
                                            </h3>
                                        </div>
                                        <div>
                                            <a href="<?php echo $paginaRetorno; ?>" class="btn btn-light">
                                                <i class="fas fa-arrow-left mr-2"></i>Volver
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                <!-- Card Body -->
                                <div class="card-body">

                                    <!-- Información de la cita -->
                                    <div class="video-info">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <strong><i class="fas fa-user-md text-primary"></i> Médico:</strong><br>
                                                <?php echo htmlspecialchars($cita['nombreMedico'] . ' ' . $cita['apellidoMedico']); ?>
                                            </div>
                                            <div class="col-md-3">
                                                <strong><i class="fas fa-user text-info"></i> Paciente:</strong><br>
                                                <?php echo htmlspecialchars($cita['nombrePaciente']); ?>
                                            </div>
                                            <div class="col-md-3">
                                                <strong><i class="fas fa-stethoscope text-success"></i>
                                                    Especialidad:</strong><br>
                                                <?php echo htmlspecialchars($cita['especialidad']); ?>
                                            </div>
                                            <div class="col-md-3">
                                                <strong><i class="fas fa-calendar text-warning"></i> Fecha y
                                                    Hora:</strong><br>
                                                <?php echo date('d/m/Y', strtotime($cita['fecha'])) . ' - ' . $cita['hora']; ?>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Contenedor de Jitsi Meet -->
                                    <div id="meet-container"></div>

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

    <!-- Botón flotante para salir -->
    <a href="<?php echo $paginaRetorno; ?>" class="btn btn-danger btn-lg btn-salir">
        <i class="fas fa-sign-out-alt mr-2"></i>Salir de la Videollamada
    </a>

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <?php include "cabeceraInferior.php"; ?>

    <!-- Jitsi Meet API -->
    <script src='https://meet.jit.si/external_api.js'></script>

    <script>
        // Configuración de Jitsi Meet
        const domain = 'meet.jit.si';
        const options = {
            roomName: '<?php echo $nombreSala; ?>',
            width: '100%',
            height: '100%',
            parentNode: document.querySelector('#meet-container'),
            configOverwrite: {
                startWithAudioMuted: false,
                startWithVideoMuted: false,
                prejoinPageEnabled: false, // Saltar página de pre-unión
                disableInviteFunctions: true, // Deshabilitar invitaciones
            },
            interfaceConfigOverwrite: {
                TOOLBAR_BUTTONS: [
                    'microphone', 'camera', 'closedcaptions', 'desktop', 'fullscreen',
                    'fodeviceselection', 'hangup', 'profile', 'chat', 'recording',
                    'livestreaming', 'etherpad', 'sharedvideo', 'settings', 'raisehand',
                    'videoquality', 'filmstrip', 'feedback', 'stats', 'shortcuts',
                    'tileview', 'download', 'help', 'mute-everyone'
                ],
                SHOW_JITSI_WATERMARK: false,
                SHOW_WATERMARK_FOR_GUESTS: false,
                DEFAULT_BACKGROUND: '#667eea',
                DISABLE_JOIN_LEAVE_NOTIFICATIONS: true,
            },
            userInfo: {
                displayName: '<?php echo htmlspecialchars($nombreUsuario); ?>'
            }
        };

        // Inicializar Jitsi Meet
        const api = new JitsiMeetExternalAPI(domain, options);

        // Eventos de Jitsi
        api.addEventListener('videoConferenceJoined', (event) => {
            console.log('Usuario unido a la videollamada:', event);

            // Si el usuario es médico, notificar al paciente
            <?php if ($tipoUsuario === 'medico'): ?>
                console.log('Notificando al paciente...');
                fetch('../modelo/notificaciones/notificar_videollamada.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'idCita=<?php echo $idCita; ?>'
                })
                    .then(response => response.json())
                    .then(data => console.log('Resultado notificación:', data))
                    .catch(error => console.error('Error notificando:', error));
            <?php endif; ?>
        });

        api.addEventListener('videoConferenceLeft', (event) => {
            console.log('Usuario salió de la videollamada');
            // Opcional: redireccionar automáticamente
            window.location.href = '<?php echo $paginaRetorno; ?>';
        });

        api.addEventListener('participantJoined', (event) => {
            console.log('Participante unido:', event);
        });

        api.addEventListener('participantLeft', (event) => {
            console.log('Participante salió:', event);
        });

        // Manejo de errores
        api.addEventListener('errorOccurred', (event) => {
            console.error('Error en la videollamada:', event);
        });
    </script>

</body>

</html>