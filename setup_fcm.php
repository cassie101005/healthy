<?php
require_once "modelo/utilidades/conexion.php";

function addColumnIfNotExists($pdo, $table, $column, $definition)
{
    try {
        // Verificar si la columna existe
        $stmt = $pdo->prepare("SHOW COLUMNS FROM $table LIKE ?");
        $stmt->execute([$column]);

        if ($stmt->rowCount() == 0) {
            // No existe, agregarla
            $sql = "ALTER TABLE $table ADD COLUMN $column $definition";
            $pdo->exec($sql);
            echo "Columna '$column' agregada a la tabla '$table'.<br>";
        } else {
            echo "La columna '$column' ya existe en la tabla '$table'.<br>";
        }
    } catch (PDOException $e) {
        echo "Error en tabla '$table': " . $e->getMessage() . "<br>";
    }
}

echo "<h2>Configurando Base de Datos para Notificaciones Push</h2>";

// Agregar columna fcm_token a tbl_pacientes
addColumnIfNotExists($pdo, 'tbl_pacientes', 'fcm_token', 'VARCHAR(255) NULL');

// Agregar columna fcm_token a tbl_medicos
addColumnIfNotExists($pdo, 'tbl_medicos', 'fcm_token', 'VARCHAR(255) NULL');

echo "<h3>Configuración completada.</h3>";
?>