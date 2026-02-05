<?php
require_once '../utilidades/conexion.php';

echo "--- DIAGNOSTIC START ---\n";

// 1. Check Patients
echo "\n[PATIENTS]\n";
$stmt = $pdo->query("SELECT idPaciente, vNombre, idCentro FROM tbl_pacientes LIMIT 5");
$pacientes = $stmt->fetchAll();
foreach ($pacientes as $p) {
    echo "ID: " . $p['idPaciente'] . " | Name: " . $p['vNombre'] . " | idCentro: " . $p['idCentro'] . "\n";
}

// 2. Check Doctors
echo "\n[DOCTORS]\n";
$stmt = $pdo->query("SELECT id, vNombre, vEspecialidad, idCentro FROM tbl_medicos LIMIT 10");
$medicos = $stmt->fetchAll();
foreach ($medicos as $m) {
    echo "ID: " . $m['id'] . " | Name: " . $m['vNombre'] . " | Spec: [" . $m['vEspecialidad'] . "] | idCentro: " . $m['idCentro'] . "\n";
    // Check hex of specialty to detect hidden chars
    echo "    Spec Hex: " . bin2hex($m['vEspecialidad']) . "\n";
}

// 3. Check Specialties in Frontend (hardcoded in nueva_cita.php)
$frontend_specialties = [
    "Endocrinología",
    "Cardiología",
    "Neumología",
    "Nefrología",
    "Reumatología",
    "Gastroenterología",
    "Hepatología",
    "Neurología",
    "Psiquiatría",
    "Oncología"
];

echo "\n[SPECIALTY MATCHING]\n";
foreach ($medicos as $m) {
    $found = false;
    foreach ($frontend_specialties as $fs) {
        if ($m['vEspecialidad'] === $fs) {
            echo "Doctor " . $m['vNombre'] . " matches frontend specialty '" . $fs . "'\n";
            $found = true;
            break;
        }
    }
    if (!$found) {
        echo "WARNING: Doctor " . $m['vNombre'] . " specialty '" . $m['vEspecialidad'] . "' does NOT match any frontend specialty exactly.\n";
        foreach ($frontend_specialties as $fs) {
            if (trim($m['vEspecialidad']) == $fs) {
                echo "    (But matches if trimmed)\n";
            }
        }
    }
}

echo "\n--- DIAGNOSTIC END ---\n";
