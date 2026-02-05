<?php
require_once "../controlador/redireccion.php";
require_once "../modelo/utilidades/conexion.php";

$idCita = $_GET['id'];            // id de la cita que viene por la URL
$idUsuario = $_SESSION['idUsuario'];
$tipoUsuario = $_SESSION['tipoUsuario'] ?? 'paciente'; // 'medico' o 'paciente'

// Obtener quién es el paciente y quién es el médico en esa cita
$sql = $pdo->prepare(
    "SELECT C.idUsuario, C.idMedico
     FROM tbl_citas C
     WHERE C.id = ?"
);
$sql->execute([$idCita]);
$data = $sql->fetch(PDO::FETCH_ASSOC);

$idPaciente = $data['idUsuario'];
$idMedico = $data['idMedico'];

// Si el que está logueado es el paciente, el destinatario es el médico, y viceversa
$idDestinatario = ($idUsuario == $idPaciente) ? $idMedico : $idPaciente;

// MARCAR MENSAJES COMO LEÍDOS
// Actualizamos los mensajes donde el destinatario es el usuario actual y pertenecen a esta cita
$sqlUpdate = $pdo->prepare("
    UPDATE tbl_chat 
    SET leido = 1 
    WHERE idCita = ? 
      AND idDestinatario = ? 
      AND leido = 0
");
$sqlUpdate->execute([$idCita, $idUsuario]);

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <?php include "cabecera.php"; ?>
    <style>
        #chat-box {
            height: 380px;
            overflow-y: auto;
            padding: 15px;
            background: #f8f9fc;
            border-radius: 10px;
            border: 1px solid #d1d3e2;
        }
        .msg-me {
            text-align: right;
            margin-bottom: 12px;
        }
        .msg-other {
            text-align: left;
            margin-bottom: 12px;
        }
        .msg-bubble {
            display: inline-block;
            padding: 8px 12px;
            border-radius: 15px;
            max-width: 65%;
        }
        .msg-me .msg-bubble {
            background: #4e73df;
            color: white;
        }
        .msg-other .msg-bubble {
            background: #e2e6ea;
            color: black;
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
                    <h3 class="text-primary mb-4">Chat de la cita #<?php echo $idCita; ?></h3>

                    <div class="card shadow mb-4">
                        <div class="card-body">
                            <div id="chat-box"></div>

                            <textarea id="mensaje" class="form-control mt-3" placeholder="Escribe un mensaje..."></textarea>
                            <div class="d-flex justify-content-between mt-3">
                                                <button onclick="enviarMensaje()" class="btn btn-primary mt-2">Enviar</button>
                                                <?php 
                                                    $linkRegreso = ($tipoUsuario == 'medico') ? 'citas_medicas' : 'historial_citas';
                                                ?>
                                                <a href="<?php echo $linkRegreso; ?>" class="btn btn-success mt-2">Regresar</a>

                                            </div>
                        </div>
                        
                    </div>
                </div>

            </div>
        </div>
    </div>

    <?php include "cabeceraInferior.php"; ?>

    <script>
    const ID_CITA = <?php echo $idCita; ?>;
    const ID_DESTINATARIO = <?php echo $idDestinatario; ?>;
    const ID_USUARIO = <?php echo $idUsuario; ?>;

    function cargarMensajes() {
        $.post("../modelo/chat/cargar_mensajes.php",
        { idCita: ID_CITA },
        function(data){
            let mensajes = JSON.parse(data);
            let html = "";

            mensajes.forEach(m => {
                if (m.idRemitente == ID_USUARIO) {
                    html += `<div class="msg-me"><div class="msg-bubble">${m.mensaje}</div><br><small>${m.fecha}</small></div>`;
                } else {
                    html += `<div class="msg-other"><div class="msg-bubble">${m.mensaje}</div><br><small>${m.fecha}</small></div>`;
                }
            });

            $("#chat-box").html(html);
            $("#chat-box").scrollTop($("#chat-box")[0].scrollHeight);
        });
    }

    function enviarMensaje() {
        let mensaje = $("#mensaje").val();
        if (mensaje.trim() === "") return; // Evitar enviar mensajes vacíos

        // Optimistic UI: Agregar mensaje inmediatamente
        let fechaActual = new Date().toLocaleString();
        let htmlTemp = `<div class="msg-me"><div class="msg-bubble">${mensaje}</div><br><small>${fechaActual}</small></div>`;
        $("#chat-box").append(htmlTemp);
        $("#chat-box").scrollTop($("#chat-box")[0].scrollHeight);
        
        // Limpiar input inmediatamente
        $("#mensaje").val("");

        $.post("../modelo/chat/enviar_mensaje.php", {
            idCita: ID_CITA,
            idDestinatario: ID_DESTINATARIO,
            mensaje: mensaje
        }, function(r){
            if (r.trim() === "ok") {
                // Opcional: recargar para asegurar consistencia, pero ya se ve el mensaje
                // cargarMensajes(); 
            } else {
                // Si falla, podrías mostrar un error o revertir (opcional)
                console.error("Error al enviar mensaje");
            }
        });
    }

    // Enviar mensaje con Enter (sin Shift)
    $("#mensaje").on("keypress", function(e) {
        if (e.which === 13 && !e.shiftKey) {
            e.preventDefault();
            enviarMensaje();
        }
    });

    // Cargar mensajes cada 2 segundos
    setInterval(cargarMensajes, 2000);
    cargarMensajes();
    </script>
</body>
</html>
