<?php

    //require_once '../utilidades/conexion.php';

    function getMedicos($idCentro){
        global $pdo;  // Usamos la variable global $pdo, que es la conexión PDO

        $sql = "SELECT * FROM tbl_medicos WHERE idCentro = '$idCentro'";

        try {
            $stmt = $pdo->prepare($sql);
            $stmt->execute();

            // Si hay resultados, los obtenemos
            $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            return $resultado;
        } catch (PDOException $e) {
            // Manejo de errores
            echo json_encode(["status" => "error", "message" => "Error en la consulta: " . $e->getMessage()]);
            return [];
        }
    }

?>
