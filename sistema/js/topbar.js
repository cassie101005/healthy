function cargarNotificaciones() {
    $.post("../modelo/notificaciones/obtener_notificaciones.php", {}, function (data) {
        let json;
        try {
            json = (typeof data === 'object') ? data : JSON.parse(data);
        } catch (e) {
            console.error("Error parsing notification JSON:", e, data);
            return;
        }

        let lista = $("#listaNotificaciones");
        let badge = $("#cantNotif");

        lista.html("");
        let totalNotificaciones = 0;

        /* MENSAJES */
        if (json.mensajes && json.mensajes.length > 0) {
            console.log('📩 Mensajes detectados:', json.mensajes);

            json.mensajes.forEach(m => {
                totalNotificaciones++;
                lista.append(`
                <a class="dropdown-item d-flex align-items-center" href="chat?id=${m.idCita}">
                    <div class="mr-3">
                        <div class="icon-circle bg-info">
                            <i class="fas fa-envelope text-white"></i>
                        </div>
                    </div>
                    <div>
                        <div class="small text-gray-500">${m.fecha}</div>
                        <span class="font-weight-bold">
                            Nuevo mensaje en la cita #${m.idCita} de ${m.nombreRemitente}
                        </span>
                    </div>
                </a>
            `);

                // Mostrar toast notification
                console.log('🔔 Mostrando toast:', m);
                if (typeof window.mostrarToast === 'function') {
                    // Agregar prefijo "Paciente" si el remitente es paciente
                    let nombreMostrar = m.nombreRemitente || 'Usuario';
                    if (m.tipoRemitente === 'paciente') {
                        nombreMostrar = 'Paciente ' + nombreMostrar;
                    }

                    window.mostrarToast(
                        nombreMostrar,
                        m.mensaje || 'Nuevo mensaje',
                        m.idCita,
                        m.fecha,
                        m.nombrePaciente || null
                    );
                } else {
                    console.error('❌ mostrarToast no disponible');
                }
            });
        }

        /* CITAS */
        if (json.citas) {
            json.citas.forEach(c => {
                totalNotificaciones++;
                lista.append(`
                <a class="dropdown-item d-flex align-items-center" href="historial_citas">
                    <div class="mr-3">
                        <div class="icon-circle bg-warning">
                            <i class="fas fa-calendar text-white"></i>
                        </div>
                    </div>
                    <div>
                        <span class="font-weight-bold">${c.texto}</span>
                    </div>
                </a>
            `);
            });
        }

        if (totalNotificaciones === 0) {
            lista.html(`<div class='text-center small text-gray-500 p-3'>No tienes notificaciones nuevas</div>`);
            badge.text("");
            // Deshabilitar botón de limpiar
            $("#btnLimpiarNotif").prop("disabled", true);
        } else {
            badge.text(totalNotificaciones);
            // Habilitar botón de limpiar
            $("#btnLimpiarNotif").prop("disabled", false);
        }
    });
}

// Iniciar carga de notificaciones
$(document).ready(function () {
    cargarNotificaciones();
    setInterval(cargarNotificaciones, 3000);
});

// Función para limpiar todas las notificaciones
function limpiarNotificaciones() {
    $.post("../modelo/notificaciones/marcar_todas_leidas.php", {}, function (response) {
        let json;
        try {
            json = (typeof response === 'object') ? response : JSON.parse(response);
        } catch (e) {
            console.error("Error parsing response:", e);
            return;
        }

        if (json.success) {
            // Recargar notificaciones inmediatamente
            cargarNotificaciones();

            // Mostrar mensaje de éxito
            console.log("✅ Notificaciones limpiadas:", json.count);
        } else {
            console.error("❌ Error al limpiar notificaciones:", json.message);
        }
    });
}
