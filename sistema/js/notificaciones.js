import { initializeApp } from "https://www.gstatic.com/firebasejs/10.8.0/firebase-app.js";
import { getMessaging, getToken, onMessage } from "https://www.gstatic.com/firebasejs/10.8.0/firebase-messaging.js";

// Configuración de Firebase (debe coincidir con firebase.js)
const firebaseConfig = {
    apiKey: "AIzaSyBJ6yQ1j_DWxag7MPCJGAZwCW2FZOTEXjE",
    authDomain: "healthy-c83ca.firebaseapp.com",
    projectId: "healthy-c83ca",
    storageBucket: "healthy-c83ca.firebasestorage.app",
    messagingSenderId: "809640150546",
    appId: "1:809640150546:web:3dba54c489e0c824487acb",
    measurementId: "G-7Y4YC4NSSP"
};

// Inicializar Firebase
const app = initializeApp(firebaseConfig);
const messaging = getMessaging(app);

// Solicitar permiso y obtener token
async function solicitarPermisoNotificaciones() {
    try {
        const permission = await Notification.requestPermission();
        if (permission === 'granted') {
            console.log('Permiso de notificaciones concedido.');

            // Obtener el token
            const token = await getToken(messaging, {
                vapidKey: 'BM_YOUR_VAPID_KEY_HERE_IF_NEEDED_OR_REMOVE_THIS_OPTION'
            });

            if (token) {
                console.log('Token FCM:', token);
                guardarTokenEnServidor(token);
            } else {
                console.log('No se pudo obtener el token de registro.');
            }
        } else {
            console.log('Permiso de notificaciones denegado.');
        }
    } catch (error) {
        console.error('Error al solicitar permiso:', error);
    }
}

// Guardar token en el backend
function guardarTokenEnServidor(token) {
    const formData = new FormData();
    formData.append('token', token);

    fetch('../modelo/notificaciones/guardar_token.php', {
        method: 'POST',
        body: formData
    })
        .then(response => response.json())
        .then(data => console.log('Token guardado:', data))
        .catch(error => console.error('Error guardando token:', error));
}

// Escuchar mensajes en primer plano
onMessage(messaging, (payload) => {
    console.log('Mensaje recibido en primer plano:', payload);

    const { title, body, icon } = payload.notification;

    // Mostrar una notificación toast o alerta personalizada
    // Aquí usamos la API nativa de notificaciones si el usuario está en otra pestaña pero el navegador está abierto
    // O simplemente actualizamos la UI

    // Ejemplo simple:
    if (Notification.permission === 'granted') {
        new Notification(title, {
            body: body,
            icon: icon || '/logo.png'
        });
    }

    // También recargar la lista de notificaciones en la topBar
    if (typeof cargarNotificaciones === 'function') {
        cargarNotificaciones();
    }
});

// Iniciar
solicitarPermisoNotificaciones();
