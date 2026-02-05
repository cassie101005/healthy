<?php
session_start();
require_once '../utilidades/conexion.php';
require_once '../notificaciones/enviar_notificacion.php';

$idUsuario = $_SESSION['idUsuario'];
$idMedico = $_POST['idMedico'];
$vEspecialidad = $_POST['vEspecialidad'];
$dFecha = $_POST['dFecha'];
$vHora = $_POST['vHora'];
$vModalidad = $_POST['vModalidad'];
$dFechaRegistro = date('Y-m-d H:i:s');

// Validar que el usuario no tenga una cita "Agendada"
$consultaCitaUsuario = $pdo->prepare("SELECT * FROM tbl_citas WHERE idUsuario = ? AND vEstado = 'Agendada'");
$consultaCitaUsuario->execute([$idUsuario]);

if ($consultaCitaUsuario->rowCount() > 0) {
    mostrarAlerta('warning', 'Ya tienes una cita pendiente', 'Debes atender tu cita actual antes de agendar una nueva.', true);
    exit();
}

// Validar que no haya una cita con ese médico, fecha y hora
$consulta = $pdo->prepare("SELECT * FROM tbl_citas WHERE idMedico = ? AND dFecha = ? AND vHora = ?");
$consulta->execute([$idMedico, $dFecha, $vHora]);

if ($consulta->rowCount() > 0) {
    mostrarAlerta('error', 'Conflicto de horario', 'Ya hay una cita con ese médico en esa fecha y hora.', true);
    exit();
}

// Insertar la nueva cita
$insertar = $pdo->prepare("INSERT INTO tbl_citas 
    (idUsuario, idMedico, vEspecialidad, dFecha, vHora, vModalidad, dFechaRegistro, vEstado) 
    VALUES (?, ?, ?, ?, ?, ?, ?, 'Agendada')");
$exito = $insertar->execute([
    $idUsuario,
    $idMedico,
    $vEspecialidad,
    $dFecha,
    $vHora,
    $vModalidad,
    $dFechaRegistro
]);

if ($exito) {
    // ========== NOTIFICACIONES PUSH ==========
    
    // Obtener nombre del médico
    $sqlMedico = $pdo->prepare("SELECT vNombre FROM tbl_medicos WHERE id = ?");
    $sqlMedico->execute([$idMedico]);
    $medico = $sqlMedico->fetch(PDO::FETCH_ASSOC);
    
    // Obtener nombre del paciente
    $sqlUsuario = $pdo->prepare("SELECT vNombre FROM tbl_pacientes WHERE idPaciente = ?");
    $sqlUsuario->execute([$idUsuario]);
    $usuario = $sqlUsuario->fetch(PDO::FETCH_ASSOC);
    
    // Formatear fecha para mostrar
    $fechaFormateada = date('d/m/Y', strtotime($dFecha));
    
    // Notificar al paciente
    notificarNuevaCitaPaciente($pdo, $idUsuario, $medico['vNombre'], $fechaFormateada, $vHora);
    
    // Notificar al médico
    notificarNuevaCitaMedico($pdo, $idMedico, $usuario['vNombre'], $fechaFormateada, $vHora);
    
    // ==========================================
    
    mostrarAlerta('success', '¡Cita registrada!', 'Tu cita fue registrada con éxito.', false, '../../sistema/historial_citas');
} else {
    mostrarAlerta('error', 'Error', 'Ocurrió un error al registrar la cita.', true);
}

// Función para mostrar SweetAlert2
function mostrarAlerta($icono, $titulo, $mensaje, $volver = false, $redireccion = '')
{
    echo "
    <!DOCTYPE html>
    <html lang='es'>
    <head>
        <meta charset='UTF-8'>
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    </head>
    <body>
        <script>
            Swal.fire({
                icon: '$icono',
                title: '$titulo',
                text: '$mensaje',
                confirmButtonText: '" . ($volver ? 'Volver' : 'Aceptar') . "'
            }).then(() => {
                " . ($volver ? "window.history.back();" : "window.location.href = '$redireccion';") . "
            });
        </script>
    </body>
    </html>";
}
?>
