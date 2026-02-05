<?php
// modelo/registrar/registro_medicamento.php
session_start();
require_once '../utilidades/conexion.php';

header('Content-Type: application/json');

// Verificar si el usuario es paciente
if (!isset($_SESSION['tipoUsuario']) || $_SESSION['tipoUsuario'] !== 'paciente') {
    echo json_encode(['status' => 'error', 'message' => 'Acceso no autorizado.']);
    exit;
}

if (isset($_POST['vNombre'], $_POST['vDosis'], $_POST['vFrecuencia'], $_POST['dFechaInicio'])) {

    $idPaciente = $_SESSION['idUsuario']; // Asumimos que idUsuario es el idPaciente para pacientes
    $vNombre = $_POST['vNombre'];
    $vDosis = $_POST['vDosis'];
    $vFrecuencia = $_POST['vFrecuencia'];
    $dFechaInicio = $_POST['dFechaInicio'];
    $dFechaFin = !empty($_POST['dFechaFin']) ? $_POST['dFechaFin'] : null;

    try {
        $sql = "INSERT INTO tbl_medicamentos 
                (idPaciente, vNombre, vDosis, vFrecuencia, dFechaInicio, dFechaFin) 
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $idPaciente,
            $vNombre,
            $vDosis,
            $vFrecuencia,
            $dFechaInicio,
            $dFechaFin
        ]);

        if ($stmt->rowCount() > 0) {
            echo json_encode(['status' => 'success', 'message' => 'Medicamento registrado correctamente.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'No se pudo registrar el medicamento.']);
        }

    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Error en la base de datos: ' . $e->getMessage()]);
    }

} else {
    echo json_encode(['status' => 'error', 'message' => 'Faltan datos requeridos.']);
}
?>