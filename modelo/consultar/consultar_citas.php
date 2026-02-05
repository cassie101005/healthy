<?php

    //require_once '../utilidades/conexion.php';

    function getCitas($idUser){
        global $pdo;  // Usamos la variable global $pdo, que es la conexión PDO
        //SELECT C.*, M.* FROM tbl_citas AS C INNER JOIN tbl_medicos AS M ON C.idMedico = M.id WHERE idUsuario = 1
        //$sql = "SELECT C.*, M.* FROM tbl_citas AS C INNER JOIN tbl_medicos AS M ON C.idMedico = M.id WHERE idUsuario = '$idUser'";
        $sql = "SELECT
        C.id AS CODIGO,
        CONCAT(M.vNombre) AS MEDICO,
        P.vNombre AS PACIENTE,
        C.vEspecialidad AS ESPECIALIDAD,
        C.dFecha AS FECHA,
        C.vHora AS HORA,
        C.vModalidad AS MODALIDAD,
        C.vEstado AS ESTADO   
        FROM tbl_citas AS C
        INNER JOIN tbl_pacientes AS P ON C.idUsuario = P.idPaciente
        INNER JOIN tbl_medicos AS M ON C.idMedico = M.id
        WHERE C.idUsuario = '$idUser'";

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

    function getCitasParaMedicos($idUser){
        global $pdo;  // Usamos la variable global $pdo, que es la conexión PDO

        $sql = "SELECT
                    C.id AS CODIGO,
                    CONCAT(M.vNombre) AS MEDICO,
                    P.vNombre AS PACIENTE,
                    C.vEspecialidad AS ESPECIALIDAD,
                    C.dFecha AS FECHA,
                    C.vHora AS HORA,
                    C.vModalidad AS MODALIDAD,
                    C.vEstado AS ESTADO     
                FROM tbl_citas AS C
                INNER JOIN tbl_pacientes AS P
                ON C.idUsuario = P.idPaciente
                INNER JOIN tbl_medicos AS M
                ON C.idMedico = M.id
                WHERE C.idMedico = '$idUser'";

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

    function getInfoCita($idCita){
        global $pdo;  // Usamos la variable global $pdo, que es la conexión PDO

        $sql = "SELECT
                    C.id AS CODIGO,
                    CONCAT(M.vNombre, ' ', M.vApellido) AS MEDICO,
                    P.vNombre AS PACIENTE,
                    C.vEspecialidad AS ESPECIALIDAD,
                    C.dFecha AS FECHA,
                    C.vHora AS HORA,
                    C.vModalidad,
                    C.vEstado AS ESTADO,
                    ATE.vSintomas AS SINTOMAS,
                    ATE.vDiagnostico AS DIAGNOSTICO,
                    ATE.vTratamiento AS TRATAMIENTO
                FROM tbl_citas AS C
                INNER JOIN tbl_pacientes AS P
                ON C.idUsuario = P.idPaciente
                INNER JOIN tbl_medicos AS M
                ON C.idMedico = M.id
                INNER JOIN tbl_atenciones AS ATE
                ON C.id = ATE.idCita
                WHERE C.id = '$idCita'";

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


    function getInfoReceta($idCita){
        global $pdo;  // Usamos la variable global $pdo, que es la conexión PDO

        $sql = "SELECT 
                    ce.vNombre AS CENTRO,
                    ce.vTelefono AS TELEFONO,
                    ce.vDireccion AS DIRECCION,
                    ci.dFecha AS FECHA,
                    pa.vNombre AS PACIENTE,
                    CURRENT_DATE() - pa.dFechaNacimiento AS EDAD,
                    ate.vDiagnostico AS DIAGNOSTICO,
                    ate.vTratamiento AS TRATAMIENTO,
                    me.vNombre AS MEDICO,
                    me.vCedula AS CEDULA,
                    me.vEspecialidad AS ESPECIALIDAD,
                    pa.vSexo AS SEXO,                    
                    YEAR(CURRENT_DATE()) -  YEAR(pa.dFechaNacimiento) AS EDAD
                FROM tbl_citas AS ci
                INNER JOIN tbl_pacientes AS pa
                ON ci.idUsuario = pa.idPaciente
                INNER JOIN tbl_medicos AS me
                ON ci.idMedico = me.id
                INNER JOIN tbl_centros AS ce
                ON pa.idCentro = ce.id
                INNER JOIN tbl_atenciones AS ate
                ON ci.id = ate.idCita
                WHERE ci.id = '$idCita'";

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
