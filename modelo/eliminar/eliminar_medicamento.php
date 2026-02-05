<?php
// modelo/eliminar/eliminar_medicamento.php
session_start();
require_once '../utilidades/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['idMedicamento'])) {

    // Verificar sesión válida
    if (!isset($_SESSION['tipoUsuario']) || $_SESSION['tipoUsuario'] !== 'paciente') {
        echo "No autorizado";
        exit;
    }

    $idMedicamento = $_POST['idMedicamento'];
    $idPaciente = $_SESSION['idUsuario'];

    try {
        // Verificar que el medicamento pertenezca al paciente
        $checkSql = "SELECT idMedicamento FROM tbl_medicamentos WHERE idMedicamento = ? AND idPaciente = ?";
        $checkStmt = $pdo->prepare($checkSql);
        $checkStmt->execute([$idMedicamento, $idPaciente]);

        if ($checkStmt->rowCount() > 0) {
            $sql = "DELETE FROM tbl_medicamentos WHERE idMedicamento = ?";
            $stmt = $pdo->prepare($sql);
            if ($stmt->execute([$idMedicamento])) {
                echo "success";
            } else {
                echo "error";
            }
        } else {
            echo "No se encontró el medicamento o no tienes permiso para eliminarlo.";
        }

    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>