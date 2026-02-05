<?php
/**
 * Sistema de Notificaciones Push - Healthy
 * 
 * Este archivo maneja el envío de notificaciones push usando Firebase Cloud Messaging (FCM)
 * Se integra con el sistema de chat y citas médicas.
 */

require_once __DIR__ . "/../utilidades/conexion.php";
require_once __DIR__ . "/AccessToken.php"; // Importar el generador de tokens

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
function enviarNotificacionPush($pdo, $idUsuario, $tipoUsuario, $titulo, $mensaje, $url = '/')
{

    // 1. Obtener el token FCM del usuario/médico
    if ($tipoUsuario === 'medico') {
        $sql = $pdo->prepare("SELECT fcm_token FROM tbl_medicos WHERE id = ?");
    } else {
        $sql = $pdo->prepare("SELECT fcm_token FROM tbl_pacientes WHERE idPaciente = ?");
    }

    $sql->execute([$idUsuario]);
    $resultado = $sql->fetch(PDO::FETCH_ASSOC);

    if (!$resultado || empty($resultado['fcm_token'])) {
        // error_log("No se encontró token FCM para $tipoUsuario ID: $idUsuario");
        return false;
    }

    $token = $resultado['fcm_token'];

    // 2. Obtener Access Token (OAuth 2.0)
    try {
        // Busca el archivo service-account.json en el mismo directorio
        $serviceAccountPath = __DIR__ . '/service-account.json';
        if (!file_exists($serviceAccountPath)) {
            error_log("Falta el archivo service-account.json en " . __DIR__);
            return false;
        }

        $tokenGenerator = new GoogleAccessToken($serviceAccountPath);
        $accessToken = $tokenGenerator->getToken();
    } catch (Exception $e) {
        error_log("Error generando Access Token: " . $e->getMessage());
        return false;
    }

    // 3. ID del Proyecto (Hardcodeado o extraído)
    $projectId = 'healthy-c83ca';

    // 4. Preparar el payload (Formato HTTP v1)
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
                    'icon' => '/logo.png', // Ruta absoluta desde la raíz del dominio
                    'badge' => '/logo.png',
                    'requireInteraction' => true,
                    'vibrate' => [200, 100, 200],
                    'actions' => [
                        [
                            'action' => 'open',
                            'title' => 'Ver'
                        ]
                    ]
                ],
                'fcm_options' => [
                    'link' => $url
                ]
            ]
        ]
    ];

    // 5. Enviar la notificación (HTTP v1 API)
    $ch = curl_init("https://fcm.googleapis.com/v1/projects/$projectId/messages:send");

    curl_setopt_array($ch, [
        CURLOPT_POST => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => [
            'Authorization: Bearer ' . $accessToken,
            'Content-Type: application/json'
        ],
        CURLOPT_POSTFIELDS => json_encode($payload)
    ]);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    if (curl_errno($ch)) {
        error_log("Error CURL FCM: " . curl_error($ch));
    }

    curl_close($ch);

    if ($httpCode === 200) {
        // error_log("Notificación enviada exitosamente a $tipoUsuario ID: $idUsuario");
        return true;
    } else {
        error_log("Error al enviar notificación FCM v1: HTTP $httpCode - $response");
        return false;
    }
}

/**
 * Notifica a un usuario sobre un nuevo mensaje en el chat
 * 
 * @param PDO $pdo Conexión a la base de datos
 * @param int $idDestinatario ID del destinatario
 * @param string $tipoDestinatario Tipo: 'usuario' o 'medico'
 * @param string $nombreRemitente Nombre de quien envía el mensaje
 * @param string $mensaje Contenido del mensaje
 * @param int $idCita ID de la cita
 * @return bool
 */
function notificarNuevoMensaje($pdo, $idDestinatario, $tipoDestinatario, $nombreRemitente, $mensaje, $idCita)
{
    $titulo = "Nuevo mensaje de $nombreRemitente";

    // Truncar mensaje si es muy largo
    $mensajeCorto = strlen($mensaje) > 100 ? substr($mensaje, 0, 100) . '...' : $mensaje;

    // Usar ruta relativa al sistema si es posible, o absoluta si se conoce la estructura
    $url = "sistema/chat.php?id=$idCita";

    return enviarNotificacionPush($pdo, $idDestinatario, $tipoDestinatario, $titulo, $mensajeCorto, $url);
}

/**
 * Notifica a un paciente sobre una nueva cita agendada
 * 
 * @param PDO $pdo Conexión a la base de datos
 * @param int $idUsuario ID del paciente
 * @param string $nombreMedico Nombre del médico
 * @param string $fecha Fecha de la cita
 * @param string $hora Hora de la cita
 * @return bool
 */
function notificarNuevaCitaPaciente($pdo, $idUsuario, $nombreMedico, $fecha, $hora)
{
    $titulo = "Nueva cita agendada";
    $mensaje = "Tienes una cita con el Dr. $nombreMedico el $fecha a las $hora";
    $url = "sistema/historial_citas.php";

    return enviarNotificacionPush($pdo, $idUsuario, 'usuario', $titulo, $mensaje, $url);
}

/**
 * Notifica a un médico sobre una nueva cita agendada
 * 
 * @param PDO $pdo Conexión a la base de datos
 * @param int $idMedico ID del médico
 * @param string $nombrePaciente Nombre del paciente
 * @param string $fecha Fecha de la cita
 * @param string $hora Hora de la cita
 * @return bool
 */
function notificarNuevaCitaMedico($pdo, $idMedico, $nombrePaciente, $fecha, $hora)
{
    $titulo = "Nueva cita agendada";
    $mensaje = "Tienes una nueva cita con $nombrePaciente el $fecha a las $hora";
    $url = "sistema/citas_medicas.php";

    return enviarNotificacionPush($pdo, $idMedico, 'medico', $titulo, $mensaje, $url);
}

/**
 * Envía recordatorio de cita próxima (15 minutos antes)
 * 
 * @param PDO $pdo Conexión a la base de datos
 * @param int $idUsuario ID del usuario
 * @param string $tipoUsuario Tipo: 'usuario' o 'medico'
 * @param string $nombreOtraParte Nombre del médico (si es paciente) o paciente (si es médico)
 * @param string $hora Hora de la cita
 * @return bool
 */
function recordatorioCitaProxima($pdo, $idUsuario, $tipoUsuario, $nombreOtraParte, $hora)
{
    $titulo = "⏰ Recordatorio de cita";

    if ($tipoUsuario === 'usuario') {
        $mensaje = "Tu cita con el Dr. $nombreOtraParte es en 15 minutos (a las $hora)";
    } else {
        $mensaje = "Tu cita con $nombreOtraParte es en 15 minutos (a las $hora)";
    }

    $url = "sistema/citas_medicas.php";

    return enviarNotificacionPush($pdo, $idUsuario, $tipoUsuario, $titulo, $mensaje, $url);
}


/**
 * Notifica a un paciente que el médico ha iniciado la videollamada
 * 
 * @param PDO $pdo Conexión a la base de datos
 * @param int $idPaciente ID del paciente
 * @param string $nombreMedico Nombre del médico
 * @param int $idCita ID de la cita
 * @return bool
 */
function notificarVideollamada($pdo, $idPaciente, $nombreMedico, $idCita)
{
    $titulo = "📞 Videollamada Entrante";
    $mensaje = "El Dr. $nombreMedico ha iniciado la videollamada. ¡Únete ahora!";
    $url = "sistema/videollamada.php?id=$idCita";

    // 1. Obtener el token FCM del paciente
    $sql = $pdo->prepare("SELECT fcm_token FROM tbl_pacientes WHERE idPaciente = ?");
    $sql->execute([$idPaciente]);
    $resultado = $sql->fetch(PDO::FETCH_ASSOC);

    if (!$resultado || empty($resultado['fcm_token'])) {
        return false;
    }

    $token = $resultado['fcm_token'];

    // 2. Obtener Access Token
    try {
        $serviceAccountPath = __DIR__ . '/service-account.json';
        if (!file_exists($serviceAccountPath)) {
            return false;
        }
        $tokenGenerator = new GoogleAccessToken($serviceAccountPath);
        $accessToken = $tokenGenerator->getToken();
    } catch (Exception $e) {
        return false;
    }

    // 3. Payload específico para videollamada
    $projectId = 'healthy-c83ca';
    $payload = [
        'message' => [
            'token' => $token,
            'notification' => [
                'title' => $titulo,
                'body' => $mensaje
            ],
            'data' => [
                'url' => $url,
                'type' => 'videollamada', // Identificador clave
                'idCita' => (string) $idCita,
                'doctor' => $nombreMedico,
                'timestamp' => date('Y-m-d H:i:s')
            ],
            'webpush' => [
                'headers' => [
                    'Urgency' => 'high'
                ],
                'notification' => [
                    'icon' => '/logo.png',
                    'requireInteraction' => true,
                    'vibrate' => [500, 200, 500], // Vibración más larga
                    'actions' => [
                        [
                            'action' => 'open',
                            'title' => 'Unirse'
                        ]
                    ]
                ],
                'fcm_options' => [
                    'link' => $url
                ]
            ]
        ]
    ];

    // 4. Enviar
    $ch = curl_init("https://fcm.googleapis.com/v1/projects/$projectId/messages:send");
    curl_setopt_array($ch, [
        CURLOPT_POST => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => [
            'Authorization: Bearer ' . $accessToken,
            'Content-Type: application/json'
        ],
        CURLOPT_POSTFIELDS => json_encode($payload)
    ]);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    return $httpCode === 200;
}
?>