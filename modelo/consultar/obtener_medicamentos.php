<?php
// modelo/consultar/obtener_medicamentos.php

// Si no hay sesión, iniciarla (aunque debería ser llamada desde includes)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../utilidades/conexion.php';

function obtenerMedicamentos($idPaciente)
{
    global $pdo; // Usar la conexión global

    $medicamentos = [];
    try {
        $sql = "SELECT * FROM tbl_medicamentos WHERE idPaciente = ? ORDER BY dFechaInicio DESC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$idPaciente]);

        $medicamentos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        // Manejo de errores silencioso o log
    }
    return $medicamentos;
}
?>