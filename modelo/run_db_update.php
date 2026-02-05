<?php
require_once 'utilidades/conexion.php';

try {
    $sql = file_get_contents('db_update_medicamentos.sql');
    $pdo->exec($sql);
    echo "Tablas creadas correctamente.";
} catch (PDOException $e) {
    echo "Error al crear tablas: " . $e->getMessage();
}
?>