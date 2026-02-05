<?php
require_once "modelo/utilidades/conexion.php";

function checkColumns($pdo, $table)
{
    try {
        $stmt = $pdo->query("DESCRIBE $table");
        $columns = $stmt->fetchAll(PDO::FETCH_COLUMN);
        echo "Tabla '$table' columns: " . implode(", ", $columns) . "\n";
    } catch (PDOException $e) {
        echo "Error checking $table: " . $e->getMessage() . "\n";
    }
}

checkColumns($pdo, 'tbl_pacientes');
checkColumns($pdo, 'tbl_medicos');
?>