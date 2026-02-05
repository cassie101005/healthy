<?php
session_start();
require_once "../utilidades/conexion.php";

// Verificar que el usuario esté autenticado
if (!isset($_SESSION['idUsuario'])) {
    http_response_code(401);
    echo json_encode(["error" => "No autenticado"]);
    exit;
}

$idUsuario = $_SESSION['idUsuario'];
$tipoUsuario = $_SESSION['tipoUsuario'] ?? 'usuario';

// Obtener el token FCM del POST
if (!isset($_POST['token']) || empty($_POST['token'])) {
    http_response_code(400);
    echo json_encode(["error" => "Token no proporcionado"]);
    exit;
}

$token = $_POST['token'];

try {
    // Determinar en qué tabla guardar según el tipo de usuario
    if ($tipoUsuario === 'medico') {
        // Guardar en tabla de médicos
        $sql = $pdo->prepare("
            UPDATE tbl_medicos 
            SET fcm_token = ? 
            WHERE id = ?
        ");
        $sql->execute([$token, $idUsuario]);
        
        echo json_encode([
            "success" => true,
            "message" => "Token FCM guardado para médico",
            "tipo" => "medico"
        ]);
    } elseif ($tipoUsuario === 'admin') {
        // Para admin, también guardamos en tabla de médicos si existe
        $sql = $pdo->prepare("
            UPDATE tbl_medicos 
            SET fcm_token = ? 
            WHERE id = ?
        ");
        $sql->execute([$token, $idUsuario]);
        
        echo json_encode([
            "success" => true,
            "message" => "Token FCM guardado para admin",
            "tipo" => "admin"
        ]);
    } else {
        // Para usuarios regulares, guardar en tabla de usuarios
        $sql = $pdo->prepare("
            UPDATE tbl_usuarios 
            SET fcm_token = ? 
            WHERE id = ?
        ");
        $sql->execute([$token, $idUsuario]);
        
        echo json_encode([
            "success" => true,
            "message" => "Token FCM guardado para usuario",
            "tipo" => "usuario"
        ]);
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        "error" => "Error al guardar token",
        "details" => $e->getMessage()
    ]);
}
?>
