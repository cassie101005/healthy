
<?php
phpinfo();
?>

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
    </style>
</head>
<body>
    <div class="receta-container">
        <div class="text-center">
            <h4>Centro Médico San Rafael</h4>
            <p><strong>RFC:</strong> CMSR123456XX1 &nbsp;|&nbsp; <strong>Tel:</strong> (55) 1234 5678</p>
            <p><strong>Dirección:</strong> Av. Reforma #123, CDMX</p>
            <hr>
            <h5><u>RECETA MÉDICA</u></h5>
        </div>

        <div class="mb-3">
            <strong>Fecha:</strong> 03 de mayo de 2025<br>
            <strong>Paciente:</strong> Juan Pérez Ramírez<br>
            <strong>Edad:</strong> 35 años &nbsp;&nbsp;&nbsp; <strong>Sexo:</strong> Masculino
        </div>

        <div class="mb-4">
            <strong>Diagnóstico:</strong><br>
            Infección respiratoria aguda leve.
        </div>

        <div class="mb-4">
            <strong>Tratamiento:</strong><br>
            <ul>
                <li>Amoxicilina 500mg – Tomar una cápsula cada 8 horas durante 7 días.</li>
                <li>Paracetamol 500mg – Tomar una tableta cada 6 horas si hay fiebre o dolor.</li>
                <li>Líquidos abundantes y reposo.</li>
            </ul>
        </div>

        <div class="mb-4">
            <strong>Observaciones:</strong><br>
            Si los síntomas persisten, regresar en 5 días para reevaluación.
        </div>

        <div class="firma">
            <p class="linea-firma"></p>
            <p class="info-medico">
                Dr. Rodrigo Martínez López<br>
                Cédula Profesional: 1234567<br>
                Especialidad: Medicina General
            </p>
        </div>

        <div class="text-center mt-4">
            <small>Receta generada electrónicamente. Folio: RX-000123</small>
        </div>
    </div>
</body>
</html>
