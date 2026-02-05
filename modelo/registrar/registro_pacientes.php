<?php
session_start();
require_once '../utilidades/conexion.php';

header('Content-Type: application/json');

if (
    isset($_POST['vNombre'], $_POST['dFechaNacimiento'], $_POST['vTelefono'],
          $_POST['vDireccion'], $_POST['idCentro'], $_POST['vUsuario'], $_POST['vContrasena'])
) {
    $vNombre = $_POST['vNombre'];
    $vSexo = $_POST['vSexo'];
    $dFechaNacimiento = $_POST['dFechaNacimiento'];
    //$vCorreo       = $_POST['vCorreo'];
    $vTelefono     = $_POST['vTelefono'];
    $vDireccion    = $_POST['vDireccion'];
    $idCentro      = $_POST['idCentro'];
    $vUsuario      = $_POST['vUsuario'];
    $vContrasena   = password_hash($_POST['vContrasena'], PASSWORD_BCRYPT); // Contraseña encriptada
    $dFechaRegistro = date("Y-m-d H:i:s");

    try {
        $sql = "INSERT INTO tbl_pacientes 
                (vNombre, dFechaNacimiento, vTelefono, vDireccion, idCentro, vUsuario, vContrasena, dFechaRegistro, vSexo)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $vNombre,
            $dFechaNacimiento,
            $vTelefono,
            $vDireccion,
            $idCentro,
            $vUsuario,
            $vContrasena,
            $dFechaRegistro,
            $vSexo  
        ]);

        if ($stmt->rowCount() > 0) {
            echo json_encode(['status' => 'success', 'message' => 'Paciente registrado correctamente.']);
            header("Location: ../../sistema/pacientes");
        } else {
            echo json_encode(['status' => 'error', 'message' => 'No se pudo registrar el paciente.']);
        }
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Error en la base de datos: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Faltan datos requeridos.']);
}
