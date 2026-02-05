<?php
session_start();
require_once "../utilidades/conexion.php";
require_once "../notificaciones/enviar_notificacion.php";

$idRemitente = $_SESSION['idUsuario'];       // quien manda el mensaje (paciente o médico)
$idCita = $_POST['idCita'];                 // cita a la que pertenece el chat
$idDestinatario = $_POST['idDestinatario']; // a quién se lo mandas
$mensaje = trim($_POST['mensaje']);         // texto

if ($mensaje === "") {
    exit("vacio");
}

$sql = $pdo->prepare("
    INSERT INTO tbl_chat (idCita, idRemitente, idDestinatario, mensaje)
    VALUES (?, ?, ?, ?)
");
$sql->execute([$idCita, $idRemitente, $idDestinatario, $mensaje]);

// ========== NOTIFICACIÓN PUSH ==========

// Obtener información de la cita para saber quién es paciente y quién es médico
$sqlCita = $pdo->prepare("SELECT idUsuario, idMedico FROM tbl_citas WHERE id = ?");
$sqlCita->execute([$idCita]);
$cita = $sqlCita->fetch(PDO::FETCH_ASSOC);

// Determinar el tipo de destinatario
$tipoDestinatario = ($idDestinatario == $cita['idMedico']) ? 'medico' : 'usuario';

// Obtener el nombre del remitente
if ($idRemitente == $cita['idMedico']) {
    // El remitente es el médico
    $sqlRemitente = $pdo->prepare("SELECT vNombre FROM tbl_medicos WHERE id = ?");
    $sqlRemitente->execute([$idRemitente]);
    $remitente = $sqlRemitente->fetch(PDO::FETCH_ASSOC);
    $nombreRemitente = "Dr. " . $remitente['vNombre'];
} else {
    // El remitente es el paciente
    $sqlRemitente = $pdo->prepare("SELECT vNombre FROM tbl_pacientes WHERE idPaciente = ?");
    $sqlRemitente->execute([$idRemitente]);
    $remitente = $sqlRemitente->fetch(PDO::FETCH_ASSOC);
    $nombreRemitente = $remitente['vNombre'];
}

// Enviar notificación push al destinatario
notificarNuevoMensaje($pdo, $idDestinatario, $tipoDestinatario, $nombreRemitente, $mensaje, $idCita);

echo "ok";

