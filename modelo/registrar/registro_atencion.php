<?php
require_once '../utilidades/conexion.php';

$idCita = $_POST['idCita'];
$vSintomas = $_POST['vSintomas'];
$vDiagnostico = $_POST['vDiagnostico'];
$vTratamiento = $_POST['vTratamiento'];
$dFecha = date('Y-m-d H:i:s'); // Fecha actual

// Insertar la atención médica
$insertar = $pdo->prepare("INSERT INTO tbl_atenciones (idCita, vSintomas, vDiagnostico, vTratamiento, dFecha) VALUES (?, ?, ?, ?, ?)");
$exito = $insertar->execute([$idCita, $vSintomas, $vDiagnostico, $vTratamiento, $dFecha]);

if ($exito) {
    // Opcional: Actualizar estado de la cita a 'Atendida'
    $actualizarCita = $pdo->prepare("UPDATE tbl_citas SET vEstado = 'Atendida' WHERE id = ?");
    $actualizarCita->execute([$idCita]);

    echo "<script>
        alert('Atención registrada correctamente.');
        window.location.href='../../sistema/citas_medicas';
    </script>";
} else {
    echo "<script>
        alert('Error al registrar la atención.');
        window.history.back();
    </script>";
}
?>
