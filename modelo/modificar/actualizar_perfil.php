<?php
// modelo/modificar/actualizar_perfil.php
session_start();
require_once "../utilidades/conexion.php";

if (!isset($_SESSION['idUsuario'])) {
    header("Location: ../../index.php");
    exit;
}

$idUsuario = $_SESSION['idUsuario'];
$tipoUsuario = $_POST['tipoUsuario'] ?? '';
$vNombre = $_POST['vNombre'] ?? '';
$newPassword = $_POST['new_password'] ?? '';
$confirmPassword = $_POST['confirm_password'] ?? '';

// Validaciones básicas
if (empty($vNombre)) {
    $_SESSION['mensaje'] = "El nombre es obligatorio.";
    $_SESSION['tipo_mensaje'] = "danger";
    header("Location: ../../sistema/perfil.php");
    exit;
}

// Validar contraseña si se intenta cambiar
$updatePassword = false;
$hashedPassword = '';

if (!empty($newPassword)) {
    if ($newPassword !== $confirmPassword) {
        $_SESSION['mensaje'] = "Las contraseñas no coinciden.";
        $_SESSION['tipo_mensaje'] = "danger";
        header("Location: ../../sistema/perfil.php");
        exit;
    }
    $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
    $updatePassword = true;
}

try {
    // Manejo de la foto de perfil
    $vFoto = null;
    if (isset($_FILES['vFoto']) && $_FILES['vFoto']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['vFoto']['tmp_name'];
        $fileName = $_FILES['vFoto']['name'];
        $fileSize = $_FILES['vFoto']['size'];
        $fileType = $_FILES['vFoto']['type'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));

        $allowedfileExtensions = array('jpg', 'gif', 'png', 'jpeg');
        if (in_array($fileExtension, $allowedfileExtensions)) {
            // Directorio de subida
            $uploadFileDir = '../../sistema/uploads/' . ($tipoUsuario === 'medico' ? 'medicos/' : 'pacientes/');

            // Crear directorio si no existe
            if (!file_exists($uploadFileDir)) {
                mkdir($uploadFileDir, 0777, true);
            }

            // Nombre único
            $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
            $dest_path = $uploadFileDir . $newFileName;

            if (move_uploaded_file($fileTmpPath, $dest_path)) {
                $vFoto = $newFileName;
            } else {
                $_SESSION['mensaje'] = "Error al mover el archivo subido.";
                $_SESSION['tipo_mensaje'] = "danger";
                header("Location: ../../sistema/perfil.php");
                exit;
            }
        } else {
            $_SESSION['mensaje'] = "Tipo de archivo no permitido. Solo JPG, GIF, PNG.";
            $_SESSION['tipo_mensaje'] = "danger";
            header("Location: ../../sistema/perfil.php");
            exit;
        }
    }

    if ($tipoUsuario === 'medico') {
        $vApellido = $_POST['vApellido'] ?? '';
        $vTelefono = $_POST['vTelefono'] ?? '';
        $vCorreo = $_POST['vCorreo'] ?? '';

        $sql = "UPDATE tbl_medicos SET vNombre = ?, vApellido = ?, vTelefono = ?, vCorreo = ?, dFechaModificacion = NOW()";
        $params = [$vNombre, $vApellido, $vTelefono, $vCorreo];

        if ($updatePassword) {
            $sql .= ", vContrasena = ?";
            $params[] = $hashedPassword;
        }

        if ($vFoto) {
            $sql .= ", vFoto = ?";
            $params[] = $vFoto;
        }

        $sql .= " WHERE id = ?";
        $params[] = $idUsuario;

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);

    } else { // Paciente
        $vTelefono = $_POST['vTelefono'] ?? '';
        $vDireccion = $_POST['vDireccion'] ?? '';

        $sql = "UPDATE tbl_pacientes SET vNombre = ?, vTelefono = ?, vDireccion = ?";
        $params = [$vNombre, $vTelefono, $vDireccion];

        if ($updatePassword) {
            $sql .= ", vContrasena = ?";
            $params[] = $hashedPassword;
        }

        if ($vFoto) {
            $sql .= ", vFoto = ?";
            $params[] = $vFoto;
        }

        $sql .= " WHERE idPaciente = ?";
        $params[] = $idUsuario;

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
    }

    // Actualizar sesión con los nuevos datos
    if ($tipoUsuario === 'medico') {
        $stmt = $pdo->prepare("SELECT * FROM tbl_medicos WHERE id = ?");
        $stmt->execute([$idUsuario]);
        $_SESSION['usuario'] = $stmt->fetch(PDO::FETCH_ASSOC);
    } else {
        $stmt = $pdo->prepare("SELECT * FROM tbl_pacientes WHERE idPaciente = ?");
        $stmt->execute([$idUsuario]);
        $_SESSION['usuario'] = $stmt->fetch(PDO::FETCH_ASSOC);
    }

    $_SESSION['mensaje'] = "Perfil actualizado correctamente.";
    $_SESSION['tipo_mensaje'] = "success";

} catch (PDOException $e) {
    $_SESSION['mensaje'] = "Error al actualizar perfil: " . $e->getMessage();
    $_SESSION['tipo_mensaje'] = "danger";
}

header("Location: ../../sistema/perfil.php");
exit;
?>