<?php
session_start();
require_once "../utilidades/conexion.php";

$idCita = $_POST['idCita'];
$idUsuario = $_SESSION['idUsuario'] ?? null;

// Marcar mensajes como leídos para el usuario actual en esta cita
if ($idUsuario) {
    $sqlUpdate = $pdo->prepare(
        "UPDATE tbl_chat 
         SET leido = 1 
         WHERE idCita = ? 
           AND idDestinatario = ? 
           AND leido = 0"
    );
    $sqlUpdate->execute([$idCita, $idUsuario]);
}

// Cargar mensajes
$sql = $pdo->prepare(
    "SELECT * FROM tbl_chat
     WHERE idCita = ?
     ORDER BY fecha ASC"
);
$sql->execute([$idCita]);

echo json_encode($sql->fetchAll(PDO::FETCH_ASSOC));
