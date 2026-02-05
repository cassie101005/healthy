<?php
session_start(); // Asegúrate de que la sesión esté iniciada
require_once '../utilidades/conexion.php';

// Enable error logging (optional - comment out in production)
error_log("=== obtener_medicos_por_especialidad.php ===");

if (isset($_POST['especialidad']) && isset($_SESSION['usuario'])) {
    $especialidad = trim($_POST['especialidad']); // Trim para eliminar espacios

    // Log para debugging
    error_log("Especialidad: " . $especialidad);

    $stmt = $pdo->prepare("SELECT * FROM tbl_medicos WHERE vEspecialidad = ?");
    $stmt->execute([$especialidad]);
    $medicos = $stmt->fetchAll();

    error_log("Médicos encontrados: " . count($medicos));

    if ($medicos) {
        echo '<option value="">Seleccione un médico</option>';
        foreach ($medicos as $medico) {
            $nombreCompleto = htmlspecialchars($medico['vNombre']);
            if (!empty($medico['vApellido'])) {
                $nombreCompleto .= " " . htmlspecialchars($medico['vApellido']);
            }
            echo '<option value="' . $medico['id'] . '">' . $nombreCompleto . '</option>';
        }
    } else {
        echo '<option value="">No hay médicos disponibles para esta especialidad</option>';
    }
} else {
    if (!isset($_POST['especialidad'])) {
        error_log("ERROR: Especialidad no recibida");
        echo '<option value="">Error: Especialidad no recibida</option>';
    } else if (!isset($_SESSION['usuario'])) {
        error_log("ERROR: Sesión no iniciada");
        echo '<option value="">Error: Sesión no iniciada</option>';
    }
}
