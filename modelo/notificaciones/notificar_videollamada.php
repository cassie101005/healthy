<?php
/**
 * API para notificar al paciente sobre videollamada
 * Se llama vía AJAX desde videollamada.php cuando el médico entra
 */

session_start();
require_once "../utilidades/conexion.php";
require_once "enviar_notificacion.php";

// Validar sesión
if (!isset($_SESSION['idUsuario'])) {
    http_response_code(401);
    echo json_encode(['error' => 'No autorizado']);
    exit;
}

// Obtener ID de la cita
$idCita = $_POST['idCita'] ?? '';

if (empty($idCita)) {
    http_response_code(400);
    echo json_encode(['error' => 'ID de cita requerido']);
    exit;
}

try {
    // Obtener datos de la cita para saber quién es el paciente y el médico
    $sql = $pdo->prepare("
        SELECT 
            c.idUsuario as idPaciente,
            m.vNombre as nombreMedico,
            m.vApellido as apellidoMedico
        FROM tbl_citas c
        INNER JOIN tbl_medicos m ON c.idMedico = m.id
        WHERE c.id = ?
    ");
    $sql->execute([$idCita]);
    $cita = $sql->fetch(PDO::FETCH_ASSOC);

    if (!$cita) {
        http_response_code(404);
        echo json_encode(['error' => 'Cita no encontrada']);
        exit;
    }

    $nombreCompletoMedico = $cita['nombreMedico'] . ' ' . $cita['apellidoMedico'];

    // Enviar notificación
    $enviado = notificarVideollamada($pdo, $cita['idPaciente'], $nombreCompletoMedico, $idCita);

    if ($enviado) {
        echo json_encode(['success' => true, 'message' => 'Notificación enviada']);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'No se pudo enviar la notificación (posiblemente falta token)']);
    }

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Error del servidor: ' . $e->getMessage()]);
}
?>