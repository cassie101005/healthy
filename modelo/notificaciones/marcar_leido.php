<?php
session_start();
require_once "../utilidades/conexion.php";

if (!isset($_SESSION['idUsuario'])) {
    exit("no_session");
}

$idUsuario = $_SESSION['idUsuario'];

// Marcar todos los mensajes recibidos por este usuario como leídos
$sql = $pdo->prepare("UPDATE tbl_chat SET leido = 1 WHERE idDestinatario = ? AND leido = 0");
$sql->execute([$idUsuario]);

echo "ok";
?>
