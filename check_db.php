<?php
require_once "modelo/utilidades/conexion.php";

function checkTable($pdo, $table)
{
    try {
        $stmt = $pdo->query("DESCRIBE $table");
        $columns = $stmt->fetchAll(PDO::FETCH_COLUMN);
        echo "Tabla '$table' existe. Columnas: " . implode(", ", $columns) . "\n";

        if (in_array('fcm_token', $columns)) {
            echo "  - Columna 'fcm_token' YA existe en $table.\n";
        } else {
            echo "  - Columna 'fcm_token' NO existe en $table.\n";
        }
    } catch (PDOException $e) {
        echo "Tabla '$table' NO existe o error: " . $e->getMessage() . "\n";
    }
}

checkTable($pdo, 'tbl_usuarios');
checkTable($pdo, 'tbl_pacientes');
checkTable($pdo, 'tbl_medicos');
?>