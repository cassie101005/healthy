<?php
/**
 * Ejemplo de cómo enviar notificaciones push desde el backend PHP
 * 
 * Este archivo muestra cómo enviar notificaciones push a usuarios y médicos
 * usando Firebase Cloud Messaging (FCM) desde PHP.
 * 
 * IMPORTANTE: Este es un archivo de ejemplo. Integra este código en tus
 * archivos existentes donde se crean/modifican citas o mensajes.
 */

require_once "utilidades/conexion.php";

/**
 * Envía una notificación push a un usuario o médico
 * 
 * @param PDO $pdo Conexión a la base de datos
 * @param int $idUsuario ID del usuario/médico
 * @param string $tipoUsuario Tipo: 'usuario' o 'medico'
 * @param string $titulo Título de la notificación
 * @param string $mensaje Cuerpo de la notificación
 * @param string $url URL de destino (opcional)
 * @return bool True si se envió correctamente
 */
function enviarNotificacionPush($pdo, $idUsuario, $tipoUsuario, $titulo, $mensaje, $url = '/') {
    
    // 1. Obtener el token FCM del usuario/médico
    if ($tipoUsuario === 'medico') {
        $sql = $pdo->prepare("SELECT fcm_token FROM tbl_medicos WHERE id = ?");
    } else {
        $sql = $pdo->prepare("SELECT fcm_token FROM tbl_usuarios WHERE id = ?");
    }
    
    $sql->execute([$idUsuario]);
    $resultado = $sql->fetch(PDO::FETCH_ASSOC);
    
    if (!$resultado || empty($resultado['fcm_token'])) {
        error_log("No se encontró token FCM para $tipoUsuario ID: $idUsuario");
        return false;
    }
    
    $token = $resultado['fcm_token'];
    
    // 2. Preparar el payload de la notificación
    $payload = [
        'message' => [
            'token' => $token,
            'notification' => [
                'title' => $titulo,
                'body' => $mensaje
            ],
            'data' => [
                'url' => $url,
                'tag' => 'healthy-notification',
                'timestamp' => date('Y-m-d H:i:s')
            ],
            'webpush' => [
                'headers' => [
                    'Urgency' => 'high'
                ],
                'notification' => [
                    'icon' => '/logo.png',
                    'badge' => '/logo.png',
                    'requireInteraction' => true
                ]
            ]
        ]
    ];
    
    // 3. Obtener el token de acceso de Firebase (Server Key)
    // IMPORTANTE: Reemplaza con tu Server Key de Firebase
    $serverKey = 'TU_SERVER_KEY_AQUI'; // Obtener de Firebase Console > Project Settings > Cloud Messaging
    
    // 4. Enviar la notificación a Firebase
    $ch = curl_init('https://fcm.googleapis.com/v1/projects/healthy-c83ca/messages:send');
    
    curl_setopt_array($ch, [
        CURLOPT_POST => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => [
            'Authorization: Bearer ' . $serverKey,
            'Content-Type: application/json'
        ],
        CURLOPT_POSTFIELDS => json_encode($payload)
    ]);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($httpCode === 200) {
        error_log("Notificación enviada exitosamente a $tipoUsuario ID: $idUsuario");
        return true;
    } else {
        error_log("Error al enviar notificación: HTTP $httpCode - $response");
        return false;
    }
}

// ============================================================
// EJEMPLOS DE USO
// ============================================================

// Ejemplo 1: Notificar a un paciente sobre una nueva cita
function notificarNuevaCita($pdo, $idUsuario, $nombreMedico, $fecha, $hora) {
    $titulo = "Nueva cita agendada";
    $mensaje = "Tienes una cita con el Dr. $nombreMedico el $fecha a las $hora";
    $url = "/sistema/historial_citas.php";
    
    return enviarNotificacionPush($pdo, $idUsuario, 'usuario', $titulo, $mensaje, $url);
}

// Ejemplo 2: Notificar a un médico sobre un nuevo mensaje
function notificarMensajeMedico($pdo, $idMedico, $nombrePaciente, $idCita) {
    $titulo = "Nuevo mensaje";
    $mensaje = "Tienes un nuevo mensaje de $nombrePaciente";
    $url = "/sistema/chat.php?id=$idCita";
    
    return enviarNotificacionPush($pdo, $idMedico, 'medico', $titulo, $mensaje, $url);
}

// Ejemplo 3: Recordatorio de cita próxima (15 minutos antes)
function recordatorioCitaProxima($pdo, $idUsuario, $nombreMedico) {
    $titulo = "Recordatorio de cita";
    $mensaje = "Tu cita con el Dr. $nombreMedico es en 15 minutos";
    $url = "/sistema/citas_medicas.php";
    
    return enviarNotificacionPush($pdo, $idUsuario, 'usuario', $titulo, $mensaje, $url);
}

// Ejemplo 4: Notificar cancelación de cita
function notificarCancelacionCita($pdo, $idUsuario, $tipoUsuario, $motivo) {
    $titulo = "Cita cancelada";
    $mensaje = "Tu cita ha sido cancelada. Motivo: $motivo";
    $url = "/sistema/historial_citas.php";
    
    return enviarNotificacionPush($pdo, $idUsuario, $tipoUsuario, $titulo, $mensaje, $url);
}

// ============================================================
// INTEGRACIÓN EN TUS ARCHIVOS EXISTENTES
// ============================================================

/*
EJEMPLO: Integrar en el archivo que registra nuevas citas

// En tu archivo de registro de citas (por ejemplo: registrar/registrar_cita.php)
require_once "../modelo/notificaciones/enviar_notificacion.php";

// Después de insertar la cita en la base de datos
$sqlInsert = $pdo->prepare("INSERT INTO tbl_citas ...");
$sqlInsert->execute([...]);

// Obtener datos de la cita
$idCita = $pdo->lastInsertId();
$idUsuario = $_POST['idUsuario'];
$idMedico = $_POST['idMedico'];
$fecha = $_POST['dFecha'];
$hora = $_POST['vHora'];

// Obtener nombre del médico
$sqlMedico = $pdo->prepare("SELECT vNombre FROM tbl_medicos WHERE id = ?");
$sqlMedico->execute([$idMedico]);
$medico = $sqlMedico->fetch();

// Enviar notificación al paciente
notificarNuevaCita($pdo, $idUsuario, $medico['vNombre'], $fecha, $hora);

// También puedes notificar al médico
$sqlUsuario = $pdo->prepare("SELECT vNombre FROM tbl_usuarios WHERE id = ?");
$sqlUsuario->execute([$idUsuario]);
$usuario = $sqlUsuario->fetch();

enviarNotificacionPush(
    $pdo, 
    $idMedico, 
    'medico', 
    "Nueva cita agendada",
    "Tienes una nueva cita con {$usuario['vNombre']} el $fecha a las $hora",
    "/sistema/citas_medicas.php"
);
*/

?>
