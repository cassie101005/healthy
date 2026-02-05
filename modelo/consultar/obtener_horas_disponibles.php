<?php
require_once '../utilidades/conexion.php';

if (isset($_POST['idMedico'], $_POST['fecha'])) {
    $idMedico = $_POST['idMedico'];
    $fecha = $_POST['fecha'];

    // Asumamos que el médico trabaja de 09:00 a 17:00
    $horasTrabajo = [
        "09:00", "10:00", "11:00", "12:00",
        "13:00", "14:00", "15:00", "16:00"
    ];

    // Traer las citas ya tomadas para ese médico y fecha
    $stmt = $pdo->prepare("SELECT vHora FROM tbl_citas WHERE idMedico = ? AND dFecha = ?");
    $stmt->execute([$idMedico, $fecha]);
    $horasOcupadas = $stmt->fetchAll(PDO::FETCH_COLUMN);

    $horasDisponibles = array_diff($horasTrabajo, $horasOcupadas);

    if ($horasDisponibles) {
        echo '<option value="">Seleccione una hora</option>';
        foreach ($horasDisponibles as $hora) {
            echo '<option value="' . $hora . '">' . $hora . '</option>';
        }
    } else {
        echo '<option value="">No hay horas disponibles</option>';
    }
} else {
    echo '<option value="">Datos incompletos</option>';
}
