// Service Worker para notificaciones en segundo plano
// Este archivo debe estar en la raíz del proyecto

const CACHE_NAME = 'healthy-notifications-v1';
const mensajesNotificados = new Set(); // Rastrear mensajes ya notificados

// Instalación del Service Worker
self.addEventListener('install', (event) => {
    console.log('[Service Worker] Instalado');
    self.skipWaiting();
});

// Activación del Service Worker
self.addEventListener('activate', (event) => {
    console.log('[Service Worker] Activado');
    event.waitUntil(self.clients.claim());
});

// Escuchar mensajes desde la página principal
self.addEventListener('message', (event) => {
    if (event.data && event.data.type === 'CHECK_NOTIFICATIONS') {
        verificarNotificaciones();
    }
});

// Verificar notificaciones periódicamente
async function verificarNotificaciones() {
    try {
        const response = await fetch('./modelo/notificaciones/obtener_notificaciones.php', {
            method: 'POST',
            credentials: 'include', // Incluir cookies de sesión
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            }
        });

        const data = await response.json();

        // Mostrar notificaciones de mensajes nuevos
        if (data.mensajes && data.mensajes.length > 0) {
            const mensajesNuevos = [];

            data.mensajes.forEach((mensaje) => {
                const mensajeId = `msg-${mensaje.idCita}-${mensaje.fecha}`;

                // Solo agregar si no ha sido notificado antes
                if (!mensajesNotificados.has(mensajeId)) {
                    mensajesNuevos.push(mensaje);
                    mensajesNotificados.add(mensajeId);
                }
            });

            // Mostrar notificaciones nuevas una por una
            mensajesNuevos.forEach((mensaje, index) => {
                setTimeout(() => {
                    self.registration.showNotification(
                        `Nuevo mensaje de ${mensaje.nombreRemitente}`,
                        {
                            body: `Cita #${mensaje.idCita}: ${mensaje.mensaje.substring(0, 50)}...`,
                            icon: './logo.png',
                            badge: './logo.png',
                            tag: `msg-${mensaje.idCita}-${Date.now()}`, // Tag único para cada notificación
                            data: {
                                url: `./sistema/chat?id=${mensaje.idCita}`
                            },
                            requireInteraction: false,
                            silent: false,
                            vibrate: [200, 100, 200]
                        }
                    );
                }, index * 1000); // 1 segundo de delay entre notificaciones
            });

            // Limpiar mensajes antiguos (mantener solo los últimos 100)
            if (mensajesNotificados.size > 100) {
                const array = Array.from(mensajesNotificados);
                mensajesNotificados.clear();
                array.slice(-100).forEach(id => mensajesNotificados.add(id));
            }
        }
    } catch (error) {
        console.error('[Service Worker] Error al verificar notificaciones:', error);
    }
}

// Manejar clic en notificación
self.addEventListener('notificationclick', (event) => {
    event.notification.close();

    const urlToOpen = event.notification.data?.url || './sistema/';

    event.waitUntil(
        clients.matchAll({ type: 'window', includeUncontrolled: true })
            .then((clientList) => {
                // Buscar si ya hay una ventana abierta del sistema
                for (let i = 0; i < clientList.length; i++) {
                    const client = clientList[i];
                    if (client.url.includes('sistema/') && 'focus' in client) {
                        client.focus();
                        client.navigate(urlToOpen);
                        return;
                    }
                }
                // Si no hay ventana abierta, abrir una nueva
                if (clients.openWindow) {
                    return clients.openWindow(urlToOpen);
                }
            })
    );
});
