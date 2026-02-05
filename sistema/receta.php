<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Receta Médica</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <style>
        .receta-container {
            max-width: 800px;
            margin: 30px auto;
            padding: 30px;
            border: 2px solid #000;
            border-radius: 10px;
            background-color: #fff;
        }
        .firma {
            margin-top: 60px;
            text-align: right;
        }
        .linea-firma {
            border-top: 1px solid #000;
            width: 250px;
            margin-left: auto;
        }
        .info-medico {
            font-size: 14px;
        }
        .print-btn {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
        }

        @media print {
            .print-btn {
                display: none;
            }
        }
    </style>
</head>
<body>
    <button class="btn btn-primary print-btn" onclick="window.print()">🖨️ Imprimir receta</button>

    <?php
        $idCita = $_GET["id"];
        require_once "../modelo/utilidades/conexion.php";
        require_once "../modelo/consultar/consultar_citas.php";
        $resulUser = getInfoReceta($idCita);
        if (!empty($resulUser)) {
            foreach ($resulUser as $receta) {
    ?>
    <div class="receta-container">
        <div class="text-center">
            <h4><?php echo $receta['CENTRO']; ?></h4>
            <!--p><strong>RFC:</strong> CMSR123456XX1 &nbsp;|&nbsp; <strong>Tel:</strong> (55) 1234 5678</p-->
            <p><strong>Dirección:</strong> <?php echo $receta['DIRECCION']; ?></p>
            <p><strong>Tel:</strong> (55) 1234 5678</p>
            
            <hr>
            <h5><u>RECETA MÉDICA</u></h5>
        </div>

        <div class="mb-3">
            <strong>Fecha:</strong> <?php echo $receta['FECHA']; ?><br>
            <strong>Paciente:</strong> <?php echo $receta['PACIENTE']; ?><br>
            <strong>Edad:</strong> <?php echo $receta['EDAD']; ?> años &nbsp;&nbsp;&nbsp; <strong>Sexo:</strong> <?php echo $receta['SEXO']; ?>
        </div>

        <div class="mb-4">
            <strong>Diagnóstico:</strong><br>
            <?php echo $receta['DIAGNOSTICO']; ?>
        </div>

        <div class="mb-4">
            <strong>Tratamiento:</strong><br>
            <ul>
                <li><?php echo $receta['TRATAMIENTO']; ?></li>              
            </ul>
        </div>

        <div class="mb-4">
            <strong>Observaciones:</strong><br>
            Si los síntomas persisten, regresar en 5 días para reevaluación.
        </div>

        <div class="firma">
            <p class="linea-firma"></p>
            <p class="info-medico">
                Dr. <?php echo $receta['MEDICO']; ?><br>
                Cédula Profesional: <?php echo $receta['CEDULA']; ?><br>
                Especialidad: <?php echo $receta['ESPECIALIDAD']; ?>
            </p>
        </div>

        <div class="text-center mt-4">
            <small>Receta generada electrónicamente. Folio: 000123</small>
        </div>
    </div>
    <?php 
            }
        }
    ?>
</body>
</html>
