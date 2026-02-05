<?php
session_start();
require_once '../utilidades/conexion.php';

echo "<h2>DIAGNÓSTICO DEL PROBLEMA DE ESPECIALIDADES</h2>";
echo "<pre>";

// 1. Verificar sesión del paciente
echo "\n=== 1. INFORMACIÓN DE SESIÓN ===\n";
if (isset($_SESSION['usuario'])) {
    $paciente = $_SESSION['usuario'];
    echo "Paciente logueado: " . $paciente['vNombre'] . "\n";
    echo "ID Centro del paciente: " . $paciente['idCentro'] . "\n";
    $idCentroPaciente = $paciente['idCentro'];
} else {
    echo "❌ ERROR: No hay sesión de paciente activa\n";
    $idCentroPaciente = null;
}

// 2. Listar todos los médicos
echo "\n=== 2. TODOS LOS MÉDICOS EN LA BASE DE DATOS ===\n";
$stmt = $pdo->query("SELECT id, vNombre, vEspecialidad, idCentro, CHAR_LENGTH(vEspecialidad) as len FROM tbl_medicos");
$medicos = $stmt->fetchAll();
foreach ($medicos as $m) {
    echo "ID: {$m['id']} | Nombre: {$m['vNombre']} | Especialidad: [{$m['vEspecialidad']}] (Len: {$m['len']}) | idCentro: {$m['idCentro']}\n";
    // Mostrar bytes hexadecimales para detectar caracteres ocultos
    echo "    Hex: " . bin2hex($m['vEspecialidad']) . "\n";
}

// 3. Probar la consulta que usa el formulario
echo "\n=== 3. PRUEBA DE CONSULTA POR ESPECIALIDAD ===\n";
$especialidades = [
    'Endocrinología',
    'Cardiología',
    'Neumología',
    'Nefrología',
    'Reumatología',
    'Gastroenterología',
    'Hepatología',
    'Neurología',
    'Psiquiatría',
    'Oncología'
];

foreach ($especialidades as $esp) {
    if ($idCentroPaciente) {
        $stmt = $pdo->prepare("SELECT * FROM tbl_medicos WHERE vEspecialidad = ? AND idCentro = ?");
        $stmt->execute([$esp, $idCentroPaciente]);
    } else {
        $stmt = $pdo->prepare("SELECT * FROM tbl_medicos WHERE vEspecialidad = ?");
        $stmt->execute([$esp]);
    }
    $medicos = $stmt->fetchAll();

    echo "\nEspecialidad: {$esp}\n";
    if ($idCentroPaciente) {
        echo "  Consultando con idCentro = {$idCentroPaciente}\n";
    }
    echo "  Resultados: " . count($medicos) . " médicos\n";
    foreach ($medicos as $m) {
        echo "    - {$m['vNombre']} (ID: {$m['id']}, Centro: {$m['idCentro']})\n";
    }
}

// 4. Verificar centros médicos
echo "\n=== 4. CENTROS MÉDICOS ===\n";
$stmt = $pdo->query("SELECT * FROM tbl_centros");
$centros = $stmt->fetchAll();
foreach ($centros as $c) {
    echo "ID: {$c['idCentro']} | Nombre: {$c['vNombre']}\n";
}

// 5. Verificar pacientes y sus centros
echo "\n=== 5. MUESTRA DE PACIENTES ===\n";
$stmt = $pdo->query("SELECT idPaciente, vNombre, idCentro FROM tbl_pacientes LIMIT 5");
$pacientes = $stmt->fetchAll();
foreach ($pacientes as $p) {
    echo "ID: {$p['idPaciente']} | Nombre: {$p['vNombre']} | idCentro: {$p['idCentro']}\n";
}

echo "</pre>";
