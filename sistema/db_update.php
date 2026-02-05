<?php
// sistema/db_update.php
require_once "../modelo/utilidades/conexion.php";

function addColumnIfNotExists($pdo, $table, $column, $definition)
{
    try {
        $stmt = $pdo->query("SHOW COLUMNS FROM $table LIKE '$column'");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$result) {
            $sql = "ALTER TABLE $table ADD COLUMN $column $definition";
            $pdo->exec($sql);
            echo "Columna '$column' agregada exitosamente a la tabla '$table'.<br>";
        } else {
            echo "La columna '$column' ya existe en la tabla '$table'.<br>";
        }
    } catch (PDOException $e) {
        echo "Error al modificar la tabla '$table': " . $e->getMessage() . "<br>";
    }
}

echo "<h2>Actualizando Base de Datos...</h2>";

addColumnIfNotExists($pdo, 'tbl_medicos', 'vFoto', 'VARCHAR(255) DEFAULT NULL');
addColumnIfNotExists($pdo, 'tbl_pacientes', 'vFoto', 'VARCHAR(255) DEFAULT NULL');

echo "<h3>Proceso finalizado. Puede cerrar esta ventana e intentar cambiar la foto de perfil nuevamente.</h3>";
?>