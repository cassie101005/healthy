<?php
/**
 * Guardar Token FCM - Healthy
 * 
 * Guarda el token de Firebase Cloud Messaging del usuario/médico
 * para poder enviar notificaciones push
 */

session_start();
require_once "../utilidades/conexion.php";

// Validar que el usuario esté logueado
if (!isset($_SESSION['idUsuario'])) {
    http_response_code(401);
    echo json_encode(['error' => 'No autorizado']);
    exit;
}

// Obtener datos
$token = $_POST['token'] ?? '';
$idUsuario = $_SESSION['idUsuario'];

// Validar que el token no esté vacío
if (empty($token)) {
    http_response_code(400);
    echo json_encode(['error' => 'Token vacío']);
    exit;
}

// Determinar si es usuario o médico
// Usamos 'tipoUsuario' que es la variable correcta según login.php
$tipoUsuario = $_SESSION['tipoUsuario'] ?? 'paciente';

try {
    // Actualizar el token en la tabla correspondiente
    if ($tipoUsuario === 'medico') {
        $sql = $pdo->prepare("UPDATE tbl_medicos SET fcm_token = ? WHERE id = ?");
    } else {
        $sql = $pdo->prepare("UPDATE tbl_pacientes SET fcm_token = ? WHERE idPaciente = ?");
    }

    $sql->execute([$token, $idUsuario]);

    echo json_encode([
        'success' => true,
        'message' => 'Token guardado correctamente',
        'tipo' => $tipoUsuario
    ]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'error' => 'Error al guardar token',
        'details' => $e->getMessage()
    ]);
    error_log("Error al guardar FCM token: " . $e->getMessage());
}
?>