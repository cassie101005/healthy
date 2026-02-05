<?php
session_start();
require_once "../utilidades/conexion.php";

header('Content-Type: application/json');

if (!isset($_SESSION['idUsuario']) || !isset($_POST['token'])) {
    echo json_encode(['status' => 'error', 'message' => 'No autorizado o faltan datos']);
    exit;
}

$idUsuario = $_SESSION['idUsuario'];
$tipoUsuario = $_SESSION['tipoUsuario'] ?? 'paciente';
$token = $_POST['token'];

try {
    if ($tipoUsuario === 'medico') {
        $sql = $pdo->prepare("UPDATE tbl_medicos SET fcm_token = ? WHERE id = ?");
    } else {
        $sql = $pdo->prepare("UPDATE tbl_pacientes SET fcm_token = ? WHERE idPaciente = ?");
    }

    $sql->execute([$token, $idUsuario]);

    echo json_encode(['status' => 'success', 'message' => 'Token guardado']);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Error BD: ' . $e->getMessage()]);
}
?>