<?php
session_start();
require_once '../utilidades/conexion.php';

header('Content-Type: application/json');

if (
    isset($_POST['vNombre'], $_POST['vCorreo'], $_POST['vTelefono'],
          $_POST['vEspecialidad'], $_POST['vCedula'], $_POST['vUsuario'], $_POST['vContrasena'], $_POST['iCentro'])
) {
    $vNombre       = $_POST['vNombre'];
    //$vApellido     = $_POST['vApellido'];
    $vCorreo       = $_POST['vCorreo'];
    $vTelefono     = $_POST['vTelefono'];
    $vEspecialidad = $_POST['vEspecialidad'];
    $vCedula       = $_POST['vCedula'];
    $vUsuario      = $_POST['vUsuario'];
    $vContrasena   = password_hash($_POST['vContrasena'], PASSWORD_BCRYPT); // Contraseña encriptada
    $iCentro       = $_POST['iCentro'];
    $dFechaRegistro = date("Y-m-d");
    $dFechaModificacion = date("Y-m-d");

    // Procesar imagen si se subió
    $vFoto = null;
    if (!empty($_FILES['vFoto']['name'])) {
        $fotoNombre = uniqid('medico_') . '_' . basename($_FILES['vFoto']['name']);
        $rutaDestino = '../../sistema/uploads/medicos/' . $fotoNombre;

        if (!is_dir('../../sistema/uploads/medicos/')) {
            mkdir('../../sistema/uploads/medicos/', 0777, true);
        }

        if (move_uploaded_file($_FILES['vFoto']['tmp_name'], $rutaDestino)) {
            $vFoto = $fotoNombre;
        }
    }

    try {
        $sql = "INSERT INTO tbl_medicos 
                (vNombre, vCorreo, vTelefono, vEspecialidad, vCedula, vUsuario, vContrasena, idCentro, vFoto, dFechaRegistro, dFechaModificacion)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $vNombre,
            $vCorreo,
            $vTelefono,
            $vEspecialidad,
            $vCedula,
            $vUsuario,
            $vContrasena,
            $iCentro,
            $vFoto,
            $dFechaRegistro,
            $dFechaModificacion
        ]);

        if ($stmt->rowCount() > 0) {
            echo json_encode(['status' => 'success', 'message' => 'Médico registrado correctamente.']);
            header("Location: ../../sistema/medicos");
        } else {
            echo json_encode(['status' => 'error', 'message' => 'No se pudo registrar el médico.']);
        }
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Error en la base de datos: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Faltan datos requeridos.']);
}
