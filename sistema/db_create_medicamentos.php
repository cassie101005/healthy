<?php
// sistema/db_create_medicamentos.php
// Este archivo crea la tabla necesaria para el módulo de medicamentos.
require_once "../modelo/utilidades/conexion.php";

try {
    $sql = "CREATE TABLE IF NOT EXISTS tbl_medicamentos (
        idMedicamento INT AUTO_INCREMENT PRIMARY KEY,
        idPaciente INT NOT NULL,
        vNombre VARCHAR(255) NOT NULL,
        vDosis VARCHAR(100),
        vFrecuencia VARCHAR(100),
        dFechaInicio DATE,
        dFechaFin DATE,
        dFechaRegistro DATETIME DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (idPaciente) REFERENCES tbl_pacientes(idPaciente) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

    $pdo->exec($sql);
    echo "<h1>¡Éxito!</h1>";
    echo "<p>La tabla 'tbl_medicamentos' ha sido creada correctamente.</p>";
    echo "<a href='medicamentos.php'>Volver a Mis Medicamentos</a>";

} catch (PDOException $e) {
    echo "<h1>Error</h1>";
    echo "<p>No se pudo crear la tabla: " . $e->getMessage() . "</p>";
}
?>