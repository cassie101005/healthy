<?php
session_start();
require_once '../utilidades/conexion.php'; // Conexión a la base de datos

$idUsuarioLogeado = $_SESSION['usuario']['idUsuario'];

$s_pais = isset($_POST['sPais']) ? trim($_POST['sPais']) : false;
$i_celular = isset($_POST['txtCelular']) ? trim($_POST['txtCelular']) : false;
$i_dominio = isset($_POST['txtDominio']) ? trim($_POST['txtDominio']) : false;

// Lista de códigos de país (ladas)
$ladas = [
    "Mexico" => "+52",
    "USA" => "+1",
    "Colombia" => "+57",
    "Argentina" => "+54",
    "España" => "+34",
    "Chile" => "+56",
    "Peru" => "+51",
    "Venezuela" => "+58"
];
$lada = isset($ladas[$s_pais]) ? $ladas[$s_pais] : "";

// Verificar celular
$sqlComprobarTel = "SELECT * FROM tbl_usuarios WHERE vCelular = ? AND vLada = ? AND idUsuario <> ?";
$stm = $pdo->prepare($sqlComprobarTel);
$stm->execute([$i_celular, $lada, $idUsuarioLogeado]);
$existeCelular = $stm->fetch(PDO::FETCH_ASSOC);

// Verificar dominio
$sqlComprobarDominio = "SELECT * FROM tbl_usuarios WHERE vDominio = ? AND idUsuario <> ?";
$stmD = $pdo->prepare($sqlComprobarDominio);
$stmD->execute([$i_dominio, $idUsuarioLogeado]);
$existeDominio = $stmD->fetch(PDO::FETCH_ASSOC);

// Respuesta inicial
$response = [
    'pais' => $s_pais,
    'celular' => $i_celular,
    'dominio' => $i_dominio,
    'existe_celular' => $existeCelular ? true : false,
    'existe_dominio' => $existeDominio ? true : false
];

// Si no hay conflictos, actualizar
if (!$existeCelular && !$existeDominio) {
    try {
        $sqlUpdate = "UPDATE tbl_usuarios 
                      SET idPais = ?, vLada = ?, vCelular = ?, vDominio = ?, dFechaModificacion = NOW()
                      WHERE idUsuario = ?";
        $stmtUpdate = $pdo->prepare($sqlUpdate);
        $stmtUpdate->execute([$s_pais, $lada, $i_celular, $i_dominio, $idUsuarioLogeado]);

        $response['actualizado'] = true;
        $response['mensaje'] = 'Datos actualizados correctamente.';
    } catch (PDOException $e) {
        $response['actualizado'] = false;
        $response['mensaje'] = 'Error al actualizar: ' . $e->getMessage();
    }
} else {
    $response['actualizado'] = false;
    $response['mensaje'] = 'No se pudo actualizar por conflicto de datos.';
}

// Finalmente, respondemos
header('Content-Type: application/json; charset=utf-8');
echo json_encode($response);
