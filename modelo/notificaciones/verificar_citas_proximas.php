<?php
/**
 * Script CRON para verificar citas próximas y enviar recordatorios
 * 
 * Este script debe ejecutarse cada minuto mediante un CRON job.
 * Verifica si hay citas que inicien en exactamente 15 minutos y envía
 * notificaciones push tanto al paciente como al médico.
 * 
 * Configuración CRON (ejecutar cada minuto):
 * * * * * * php /ruta/completa/a/verificar_citas_proximas.php
 */

require_once __DIR__ . "/../utilidades/conexion.php";
require_once __DIR__ . "/enviar_notificacion.php";

// Calcular la hora exacta dentro de 15 minutos
$ahora = new DateTime();
$en15Minutos = clone $ahora;
$en15Minutos->add(new DateInterval('PT15M'));

// Formatear para comparar con la base de datos
$fechaHoy = $ahora->format('Y-m-d');
$horaObjetivo = $en15Minutos->format('H:i:00'); // Redondear a minutos exactos

// Log para debugging
error_log("Verificando citas para: $fechaHoy a las $horaObjetivo");

// Buscar citas que inicien en exactamente 15 minutos y que estén "Agendadas"
// Agregamos una columna para verificar si ya se envió el recordatorio
$sql = $pdo->prepare("
    SELECT 
        C.id,
        C.idUsuario,
        C.idMedico,
        C.vHora,
        C.recordatorio_enviado,
        U.vNombre as nombrePaciente,
        M.vNombre as nombreMedico
    FROM tbl_citas C
    INNER JOIN tbl_usuarios U ON C.idUsuario = U.id
    INNER JOIN tbl_medicos M ON C.idMedico = M.id
    WHERE C.dFecha = ?
    AND C.vHora = ?
    AND C.vEstado = 'Agendada'
    AND (C.recordatorio_enviado IS NULL OR C.recordatorio_enviado = 0)
");

$sql->execute([$fechaHoy, $horaObjetivo]);
$citas = $sql->fetchAll(PDO::FETCH_ASSOC);

if (count($citas) > 0) {
    error_log("Se encontraron " . count($citas) . " citas próximas");
    
    foreach ($citas as $cita) {
        // Enviar recordatorio al paciente
        $envioPaciente = recordatorioCitaProxima(
            $pdo,
            $cita['idUsuario'],
            'usuario',
            $cita['nombreMedico'],
            $cita['vHora']
        );
        
        // Enviar recordatorio al médico
        $envioMedico = recordatorioCitaProxima(
            $pdo,
            $cita['idMedico'],
            'medico',
            $cita['nombrePaciente'],
            $cita['vHora']
        );
        
        // Marcar que ya se envió el recordatorio para esta cita
        if ($envioPaciente || $envioMedico) {
            $updateSql = $pdo->prepare("
                UPDATE tbl_citas 
                SET recordatorio_enviado = 1 
                WHERE id = ?
            ");
            $updateSql->execute([$cita['id']]);
            
            error_log("Recordatorios enviados para cita ID: {$cita['id']}");
        }
    }
} else {
    error_log("No se encontraron citas próximas");
}

echo "Verificación completada: " . count($citas) . " recordatorios enviados\n";
?>
