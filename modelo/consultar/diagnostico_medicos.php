<?php
session_start();
require_once '../utilidades/conexion.php';

echo "<h2>Diagnóstico de Médicos</h2>";

// Verificar sesión
if (!isset($_SESSION['usuario'])) {
    echo "<p style='color:red;'>❌ No hay sesión iniciada</p>";
    exit;
}

$paciente = $_SESSION['usuario'];
$idCentro = $paciente['idCentro'] ?? null;

echo "<h3>Información del Usuario Logueado:</h3>";
echo "<pre>";
print_r($paciente);
echo "</pre>";

echo "<p><strong>ID Centro del paciente:</strong> " . ($idCentro ?? 'NULL') . "</p>";

// Obtener TODOS los médicos sin filtro
echo "<h3>Todos los Médicos en la Base de Datos:</h3>";
$stmt = $pdo->query("SELECT id, vNombre, vApellido, vEspecialidad, idCentro FROM tbl_medicos ORDER BY id DESC");
$todosMedicos = $stmt->fetchAll();

echo "<table border='1' cellpadding='5' style='border-collapse: collapse;'>";
echo "<tr><th>ID</th><th>Nombre</th><th>Apellido</th><th>Especialidad</th><th>ID Centro</th><th>¿Coincide Centro?</th></tr>";

foreach ($todosMedicos as $medico) {
    $coincide = ($medico['idCentro'] == $idCentro) ? '✅ SÍ' : '❌ NO';
    echo "<tr>";
    echo "<td>" . $medico['id'] . "</td>";
    echo "<td>" . htmlspecialchars($medico['vNombre']) . "</td>";
    echo "<td>" . htmlspecialchars($medico['vApellido']) . "</td>";
    echo "<td>" . htmlspecialchars($medico['vEspecialidad']) . "</td>";
    echo "<td>" . ($medico['idCentro'] ?? 'NULL') . "</td>";
    echo "<td>" . $coincide . "</td>";
    echo "</tr>";
}

echo "</table>";

// Probar consulta con filtro
if (isset($_GET['especialidad'])) {
    $especialidad = $_GET['especialidad'];
    echo "<h3>Médicos Filtrados por Especialidad: " . htmlspecialchars($especialidad) . "</h3>";
    
    $stmt = $pdo->prepare("SELECT * FROM tbl_medicos WHERE vEspecialidad = ? AND idCentro = ?");
    $stmt->execute([$especialidad, $idCentro]);
    $medicos = $stmt->fetchAll();
    
    if ($medicos) {
        echo "<ul>";
        foreach ($medicos as $medico) {
            echo "<li>" . htmlspecialchars($medico['vNombre']) . " " . htmlspecialchars($medico['vApellido']) . "</li>";
        }
        echo "</ul>";
    } else {
        echo "<p style='color:red;'>❌ No se encontraron médicos con esa especialidad y centro</p>";
    }
}

echo "<hr>";
echo "<h3>Prueba de Especialidades:</h3>";
echo "<p>Haz clic en una especialidad para ver qué médicos aparecen:</p>";
$especialidades = ['Endocrinología', 'Cardiología', 'Neumología', 'Nefrología', 'Reumatología', 'Gastroenterología', 'Hepatología', 'Neurología', 'Psiquiatría', 'Oncología'];
foreach ($especialidades as $esp) {
    echo "<a href='?especialidad=" . urlencode($esp) . "' style='margin-right: 10px;'>" . $esp . "</a>";
}
?>
