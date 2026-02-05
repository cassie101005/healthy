import { initializeApp } from "https://www.gstatic.com/firebasejs/10.12.0/firebase-app.js";
import { getMessaging, getToken, onMessage, isSupported } from "https://www.gstatic.com/firebasejs/10.12.0/firebase-messaging.js";

// Configuración de Firebase
const firebaseConfig = {
    apiKey: "AIzaSyBJ6yQ1j_DWxag7MPCJGAZwCW2FZOTEXjE",
    authDomain: "healthy-c83ca.firebaseapp.com",
    projectId: "healthy-c83ca",
    storageBucket: "healthy-c83ca.firebasestorage.app",
    messagingSenderId: "809640150546",
    appId: "1:809640150546:web:3dba54c489e0c824487acb",
    measurementId: "G-7Y4YC4NSSP"
};

// Inicializar App
const app = initializeApp(firebaseConfig);

// Clave VAPID pública
const vapidKey = "BHgbifqWpyMfohYT--zqg4_ewXAkTRjUnPQHG1L9WApgW9ESYc-3rR-k7JrVncgFvyy8q1zGdCOK5uyoqSn8a2s";

async function iniciarNotificaciones() {
    console.log("Iniciando sistema de notificaciones...");

    try {
        // 1. Verificar soporte (Localhost o HTTPS)
        const soporta = await isSupported();
        if (!soporta) {
            console.warn("Firebase Messaging no es soportado en este navegador/entorno.");
            return;
        }

        const messaging = getMessaging(app);

        // 2. Registrar Service Worker
        // La ruta '../firebase-messaging-sw.js' asume que estamos en /sistema/ y el archivo está en la raíz /
        const registration = await navigator.serviceWorker.register('../firebase-messaging-sw.js');
        console.log("Service Worker registrado:", registration);

        // 3. Pedir permisos
        const permission = await Notification.requestPermission();
        if (permission === "granted") {
            console.log("Permiso concedido.");

            // 4. Obtener Token
            const token = await getToken(messaging, {
                vapidKey: vapidKey,
                serviceWorkerRegistration: registration
            });

            if (token) {
                console.log("Token generado:", token);
                guardarToken(token);
            } else {
                console.log("No se pudo generar el token.");
            }
        } else {
            console.log("Permiso denegado.");
        }

        // 5. Escuchar mensajes en primer plano
        onMessage(messaging, (payload) => {
            console.log("Mensaje en primer plano:", payload);
            const { title, body, icon } = payload.notification;

            // Verificar si es una notificación de videollamada
            if (payload.data && payload.data.type === 'videollamada') {
                console.log("📞 Notificación de videollamada recibida");

                // Usar el sistema de toast existente
                if (typeof window.mostrarToast === 'function') {
                    const idCita = payload.data.idCita;
                    const doctor = payload.data.doctor;
                    const fecha = new Date().toISOString(); // Fecha actual para ID único

                    window.mostrarToast(
                        `Dr. ${doctor}`,
                        "Ha iniciado la videollamada. Haz clic para unirte.",
                        idCita,
                        fecha,
                        "Paciente" // Para que muestre el formato correcto
                    );

                    // Reproducir sonido de llamada si es posible
                    try {
                        const audio = new Audio('sounds/ringtone.mp3'); // Asegúrate de tener este archivo o usa uno genérico
                        audio.play().catch(e => console.log("No se pudo reproducir audio:", e));
                    } catch (e) {
                        console.log("Error audio:", e);
                    }
                }
            }

            // Mostrar también la notificación nativa del navegador
            new Notification(title, { body, icon });
        });

    } catch (error) {
        console.error("Error en notificaciones:", error);
        // Solo mostramos alerta si NO es un error de "falta de soporte" (para no molestar en localhost si algo falla levemente)
        if (!error.message.includes("unsupported-browser")) {
            Swal.fire({
                icon: 'error',
                title: 'Error de Notificaciones',
                text: error.message
            });
        }
    }
}

function guardarToken(token) {
    fetch('../modelo/modificar/guardar_token.php', {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: "token=" + encodeURIComponent(token)
    })
        .then(res => res.json())
        .then(data => console.log("Token guardado en BD:", data))
        .catch(err => console.error("Error guardando token en BD:", err));
}

// Iniciar
iniciarNotificaciones();
