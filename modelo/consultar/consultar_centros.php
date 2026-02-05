<?php 


function getCitasParaMedicos($tipo, $idUser){
        global $pdo;
        if($tipo == 1){
            $sql = "SELECT
                    C.vNombre AS RESULTADO             
                FROM tbl_centros AS C               
                WHERE C.id = '$idUser'";
        }else if($tipo == 2){
            $sql = "SELECT
                    C.vNombre AS RESULTADO    
                FROM tbl_medicos AS C               
                WHERE C.id = '$idUser'";
        }else{
                $sql = "SELECT
                    C.vNombre AS RESULTADO   
                FROM tbl_pacientes AS C               
                WHERE C.idPaciente = '$idUser'";
        }

        

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