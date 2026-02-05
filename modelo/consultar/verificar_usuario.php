<?php
session_start();
require_once '../utilidades/conexion.php';

$user = isset($_POST["txtUsuario"]) ? trim($_POST["txtUsuario"]) : "";
$pass = isset($_POST["txtContra"]) ? trim($_POST["txtContra"]) : "";

if (empty($user) || empty($pass)) {
    $_SESSION["login_error"] = "Usuario y contraseña son obligatorios.";
    header("Location: ../../index.php");
    exit;
}

function intentarLogin($pdo, $tabla, $campoUsuario, $campoContrasena, $campoID, $tipoUsuario) {
    global $user, $pass;
    $query = "SELECT * FROM $tabla WHERE $campoUsuario = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$user]);

    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($usuario && password_verify($pass, $usuario[$campoContrasena])) {
        $_SESSION['usuario'] = $usuario;
        $_SESSION['tipoUsuario'] = $tipoUsuario;
        $_SESSION['idUsuario'] = $usuario[$campoID];
        return true;
    }

    return false;
}


if (intentarLogin($pdo, 'tbl_centros', 'vUsuario', 'vContrasena', 'id', 'admin')) {
    header("Location: ../../sistema/medicos");
    exit;
}

if (intentarLogin($pdo, 'tbl_medicos', 'vUsuario', 'vContrasena', 'id', 'medico')) {

    header("Location: ../../sistema/citas_medicas");
    exit;
}

if (intentarLogin($pdo, 'tbl_pacientes', 'vUsuario', 'vContrasena', 'idPaciente', 'paciente')) {
    
    header("Location: ../../sistema/historial_citas");
    exit;
}

$_SESSION["login_error"] = "Usuario o contraseña incorrectos.";
header("Location: ../../login.php");
exit;

