<?php
session_start();
require_once "../utilidades/conexion.php";

$idUsuario = $_SESSION['idUsuario'] ?? null;

if (!$idUsuario) {
    echo json_encode([
        "mensajes" => [],
        "citas" => []
    ]);
    exit;
}

/* ==========================================================
   1) NOTIFICACIONES DE MENSAJES NO LEÍDOS
   ========================================================== */

$tipoUsuario = $_SESSION['tipoUsuario'] ?? 'paciente'; // 'medico' o 'paciente'

if ($tipoUsuario == 'medico') {
    // Si soy médico, busco mensajes donde yo soy el destinatario
    // y el remitente es el paciente (tbl_pacientes)
    $sqlMsg = $pdo->prepare("
        SELECT C.idCita, C.mensaje, C.fecha, 
               P.vNombre AS nombreRemitente,
               P.vNombre AS nombrePaciente
        FROM tbl_chat C
        INNER JOIN tbl_citas T ON C.idCita = T.id
        INNER JOIN tbl_pacientes P ON T.idUsuario = P.idPaciente
        WHERE C.idDestinatario = ?
          AND C.leido = 0
        ORDER BY C.fecha DESC
    ");
} else {
    // Si soy paciente, busco mensajes donde yo soy el destinatario
    // y el remitente es el médico (tbl_medicos)
    $sqlMsg = $pdo->prepare("
        SELECT C.idCita, C.mensaje, C.fecha, 
               M.vNombre AS nombreRemitente,
               P.vNombre AS nombrePaciente
        FROM tbl_chat C
        INNER JOIN tbl_citas T ON C.idCita = T.id
        INNER JOIN tbl_medicos M ON T.idMedico = M.id
        INNER JOIN tbl_pacientes P ON T.idUsuario = P.idPaciente
        WHERE C.idDestinatario = ?
          AND C.leido = 0
        ORDER BY C.fecha DESC
    ");
}

$sqlMsg->execute([$idUsuario]);
$mensajes = $sqlMsg->fetchAll(PDO::FETCH_ASSOC);

// Ajustar nombre según el tipo de remitente
if ($tipoUsuario == 'paciente') {
    // Si soy paciente, el remitente es un médico
    foreach ($mensajes as &$msg) {
        $msg['nombreRemitente'] = "Dr. " . $msg['nombreRemitente'];
        $msg['tipoRemitente'] = 'medico';
    }
} else {
    // Si soy médico, el remitente es un paciente
    foreach ($mensajes as &$msg) {
        $msg['tipoRemitente'] = 'paciente';
    }
}

/* ==========================================================
   2) NOTIFICACIONES DE CITAS PRÓXIMAS (hoy o en 15 minutos)
   ========================================================== */

$sqlCita = $pdo->prepare("
    SELECT C.id, C.dFecha, C.vHora, 
           M.vNombre AS medicoNombre
    FROM tbl_citas C
    INNER JOIN tbl_medicos M ON C.idMedico = M.id
    WHERE C.idUsuario = ?
      AND C.vEstado = 'Agendada'
");
$sqlCita->execute([$idUsuario]);
$citas = $sqlCita->fetchAll(PDO::FETCH_ASSOC);

$notificacionesCitas = [];

$ahora = new DateTime();

foreach ($citas as $c) {
    $fechaHoraCita = new DateTime($c['dFecha'] . ' ' . $c['vHora']);
    $diff = $ahora->diff($fechaHoraCita);

    // Si la cita es hoy
    if ($c['dFecha'] == date('Y-m-d')) {
        $notificacionesCitas[] = [
            "texto" => "Tienes una cita hoy con el Dr. " . $c['medicoNombre'],
            "idCita" => $c['id']
        ];
    }

    // Si faltan 0-15 minutos
    if ($diff->invert == 0 && $diff->h == 0 && $diff->i <= 15) {
        $notificacionesCitas[] = [
            "texto" => "Tu cita con el Dr. " . $c['medicoNombre'] . " es en menos de 15 minutos",
            "idCita" => $c['id']
        ];
    }
}

/* ==========================================================
   RESPUESTA JSON FINAL
   ========================================================== */

header('Content-Type: application/json');

echo json_encode([
    "mensajes" => $mensajes,
    "citas" => $notificacionesCitas
]);
?>