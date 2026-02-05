<?php
session_start();
require_once '../utilidades/conexion.php';
date_default_timezone_set('America/Mexico_City');

//if (isset($_POST['txtNombre'], $_POST['txtDireccion'], $_POST['txtTelefono'], $_POST['txtCorreo'], $_POST['txtDirector'], $_POST['txtUsuario'], $_POST['txtContrasena'])) {
if (isset($_POST['txtNombre'], $_POST['txtCorreo'], $_POST['txtUsuario'], $_POST['txtContrasena'])) {
    $nombre = $_POST['txtNombre'];
    $direccion = $_POST['txtDireccion'];
    //$telefono = $_POST['txtTelefono'];
    $correo = $_POST['txtCorreo'];
    //$director = $_POST['txtDirector'];
    $usuario = $_POST['txtUsuario'];
    $contrasenaPlano = $_POST['txtContrasena'];
    $fechaRegistro = date("Y-m-d H:i:s");
    $fechaModificacion = null;

    $contrasenaHasheada = password_hash($contrasenaPlano, PASSWORD_DEFAULT);

    // Validar correo único
    $verificarCorreo = $pdo->prepare("SELECT COUNT(*) FROM tbl_centros WHERE vCorreo = ?");
    $verificarCorreo->execute([$correo]);
    $existeCorreo = $verificarCorreo->fetchColumn();

    if ($existeCorreo > 0) {
        $_SESSION['registro_error'] = "El correo ya está registrado. Intenta con otro.";
        header("Location: ../../registro.php");
        exit;
    }

    try {
        /*$sql = "INSERT INTO tbl_centros (vNombre, vDireccion, vTelefono, vCorreo, vDirector, vUsuario, vContrasena, dFechaRegistro, dFechaModificacion)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";*/
        $sql = "INSERT INTO tbl_centros (vNombre, vDireccion, vCorreo, vUsuario, vContrasena, dFechaRegistro, dFechaModificacion)
        VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$nombre, $direccion, $correo, $usuario, $contrasenaHasheada, $fechaRegistro, $fechaModificacion]);

        if ($stmt->rowCount() > 0) {
            // Recuperar datos del centro recién registrado
            $stmtCentro = $pdo->prepare("SELECT * FROM tbl_centros WHERE vUsuario = ?");
            $stmtCentro->execute([$usuario]);
            $centro = $stmtCentro->fetch(PDO::FETCH_ASSOC);

            // Asignar variables de sesión como en el login
            $_SESSION['usuario'] = $centro;
            $_SESSION['tipoUsuario'] = 'admin';
            $_SESSION['idUsuario'] = $centro['id'];

            header('Location: ../../sistema/medicos');
            exit;
        } else {
            $_SESSION['registro_error'] = "No se pudo registrar el centro.";
            header("Location: ../../register.php");
            exit;
        }
    } catch (PDOException $e) {
        $_SESSION['registro_error'] = "Error al registrar centro: " . $e->getMessage();
        header("Location: ../../register.php");
        exit;
    }
} else {
    $_SESSION['registro_error'] = "Faltan datos obligatorios.";
    header("Location: ../../register.php");
    exit;
}
