<?php
require_once "../controlador/redireccion.php";
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <?php include "cabecera.php"; ?>
    <style>
        .category-section {
            margin-bottom: 2rem;
        }
        .category-title {
            font-size: 1.3rem;
            font-weight: bold;
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid;
        }
        .list-group-item {
            border-left: 3px solid transparent;
            transition: all 0.2s;
        }
        .list-group-item:hover {
            border-left-color: #4e73df;
            background-color: #f8f9fc;
            transform: translateX(5px);
        }
    </style>
</head>

<body id="page-top">
    <div id="wrapper">
        <?php include "sideBar.php"; ?>

        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <?php include "topBar.php"; ?>

                <div class="container-fluid">
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">
                            <i class="fas fa-book-medical"></i> Glosario Técnico de Términos Médicos
                        </h1>
                    </div>

                    <div class="card shadow mb-4">
                        <div class="card-body">
                            <!-- Categoría: Términos Generales -->
                            <div class="category-section">
                                <h5 class="category-title text-primary">🩺 Términos Generales</h5>
                                <ul class="list-group">
                                    <li class="list-group-item"><strong>Cefalea:</strong> Dolor de cabeza.</li>
                                    <li class="list-group-item"><strong>Fiebre:</strong> Aumento de la temperatura corporal.</li>
                                    <li class="list-group-item"><strong>Disnea:</strong> Dificultad para respirar.</li>
                                    <li class="list-group-item"><strong>Edema:</strong> Hinchazón por acumulación de líquidos.</li>
                                    <li class="list-group-item"><strong>Náuseas:</strong> Sensación de querer vomitar.</li>
                                    <li class="list-group-item"><strong>Taquicardia:</strong> Frecuencia cardíaca elevada.</li>
                                    <li class="list-group-item"><strong>Bradicardia:</strong> Frecuencia cardíaca baja.</li>
                                    <li class="list-group-item"><strong>Hipotensión:</strong> Presión arterial baja.</li>
                                    <li class="list-group-item"><strong>Hipertensión:</strong> Presión arterial alta.</li>
                                </ul>
                            </div>

                            <!-- Categoría: Enfermedades Crónicas Comunes -->
                            <div class="category-section">
                                <h5 class="category-title text-success">📋 Enfermedades Crónicas Comunes</h5>
                                <ul class="list-group">
                                    <li class="list-group-item"><strong>Diabetes Mellitus:</strong> Alteración metabólica con niveles altos de azúcar en sangre.</li>
                                    <li class="list-group-item"><strong>Hipertensión Arterial:</strong> Elevación constante de la presión sanguínea.</li>
                                    <li class="list-group-item"><strong>Asma:</strong> Inflamación crónica de las vías respiratorias.</li>
                                    <li class="list-group-item"><strong>Artritis Reumatoide:</strong> Enfermedad autoinmune que afecta articulaciones.</li>
                                    <li class="list-group-item"><strong>Enfermedad Renal Crónica:</strong> Deterioro progresivo de la función renal.</li>
                                    <li class="list-group-item"><strong>EPOC:</strong> Enfermedad pulmonar obstructiva crónica.</li>
                                    <li class="list-group-item"><strong>Osteoporosis:</strong> Disminución de la densidad ósea.</li>
                                </ul>
                            </div>

                            <!-- Categoría: Síntomas Frecuentes -->
                            <div class="category-section">
                                <h5 class="category-title text-warning">🧠 Síntomas Frecuentes</h5>
                                <ul class="list-group">
                                    <li class="list-group-item"><strong>Mialgia:</strong> Dolor muscular.</li>
                                    <li class="list-group-item"><strong>Artralgia:</strong> Dolor en las articulaciones.</li>
                                    <li class="list-group-item"><strong>Fatiga:</strong> Cansancio físico o mental excesivo.</li>
                                    <li class="list-group-item"><strong>Mareos:</strong> Sensación de inestabilidad.</li>
                                    <li class="list-group-item"><strong>Tos:</strong> Expulsión brusca de aire para limpiar vías respiratorias.</li>
                                    <li class="list-group-item"><strong>Vómito:</strong> Expulsión violenta del contenido estomacal.</li>
                                    <li class="list-group-item"><strong>Palpitaciones:</strong> Percepción de latidos del corazón acelerados o irregulares.</li>
                                </ul>
                            </div>

                            <!-- Categoría: Tratamientos y Medicamentos -->
                            <div class="category-section">
                                <h5 class="category-title text-danger">💊 Tratamientos y Medicamentos</h5>
                                <ul class="list-group">
                                    <li class="list-group-item"><strong>Antibióticos:</strong> Sustancias que eliminan o inhiben bacterias.</li>
                                    <li class="list-group-item"><strong>Antiinflamatorios:</strong> Medicamentos que reducen la inflamación.</li>
                                    <li class="list-group-item"><strong>Antipiréticos:</strong> Medicamentos que bajan la fiebre.</li>
                                    <li class="list-group-item"><strong>Insulina:</strong> Hormona que regula los niveles de glucosa.</li>
                                    <li class="list-group-item"><strong>Broncodilatadores:</strong> Medicamentos que expanden las vías respiratorias.</li>
                                    <li class="list-group-item"><strong>Diuréticos:</strong> Medicamentos que eliminan líquidos del cuerpo.</li>
                                    <li class="list-group-item"><strong>Analgésicos:</strong> Medicamentos que alivian el dolor.</li>
                                </ul>
                            </div>

                            <!-- Categoría: Procedimientos Médicos -->
                            <div class="category-section">
                                <h5 class="category-title text-secondary">🔬 Procedimientos Médicos</h5>
                                <ul class="list-group">
                                    <li class="list-group-item"><strong>Electrocardiograma (ECG):</strong> Registro de la actividad eléctrica del corazón.</li>
                                    <li class="list-group-item"><strong>Biopsia:</strong> Extracción de tejido para análisis.</li>
                                    <li class="list-group-item"><strong>Radiografía:</strong> Imagen interna del cuerpo usando rayos X.</li>
                                    <li class="list-group-item"><strong>Hemograma:</strong> Análisis de los componentes de la sangre.</li>
                                    <li class="list-group-item"><strong>TAC:</strong> Tomografía axial computarizada, estudio detallado por imágenes.</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <?php include "footer.php"; ?>
        </div>
    </div>

    <?php include "cabeceraInferior.php"; ?>
</body>
</html>