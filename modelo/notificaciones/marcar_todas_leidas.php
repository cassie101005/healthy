<?php
session_start();
require_once "../utilidades/conexion.php";

$idUsuario = $_SESSION['idUsuario'] ?? null;

if (!$idUsuario) {
    echo json_encode(["success" => false, "message" => "Usuario no autenticado"]);
    exit;
}

try {
    // Marcar todos los mensajes como leídos donde el usuario es el destinatario
    $stmt = $pdo->prepare("UPDATE tbl_chat SET leido = 1 WHERE idDestinatario = ? AND leido = 0");
    $stmt->execute([$idUsuario]);
    
    $affected = $stmt->rowCount();
    
    echo json_encode([
        "success" => true, 
        "message" => "Notificaciones marcadas como leídas",
        "count" => $affected
    ]);
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}
?>
