// Service Worker para Firebase Cloud Messaging
// Este archivo maneja las notificaciones push en segundo plano

importScripts('https://www.gstatic.com/firebasejs/12.6.0/firebase-app-compat.js');
importScripts('https://www.gstatic.com/firebasejs/12.6.0/firebase-messaging-compat.js');

// Configuración de Firebase (misma que en firebase.js)
const firebaseConfig = {
  apiKey: "AIzaSyBJ6yQ1j_DWxag7MPCJGAZwCW2FZOTEXjE",
  authDomain: "healthy-c83ca.firebaseapp.com",
  projectId: "healthy-c83ca",
  storageBucket: "healthy-c83ca.firebasestorage.app",
  messagingSenderId: "809640150546",
  appId: "1:809640150546:web:3dba54c489e0c824487acb",
  measurementId: "G-7Y4YC4NSSP"
};

// Inicializar Firebase en el Service Worker
firebase.initializeApp(firebaseConfig);

// Obtener instancia de messaging
const messaging = firebase.messaging();

// Manejar mensajes en segundo plano (cuando la app no está en primer plano)
messaging.onBackgroundMessage((payload) => {
  console.log('[firebase-messaging-sw.js] Mensaje en segundo plano recibido:', payload);

  // Personalizar la notificación
  const notificationTitle = payload.notification?.title || 'Healthy - Nueva notificación';
  const notificationOptions = {
    body: payload.notification?.body || 'Tienes una nueva notificación',
    icon: './logo.png', // Usar el logo de la aplicación
    badge: './logo.png',
    tag: payload.data?.tag || 'healthy-notification',
    requireInteraction: true, // La notificación permanece hasta que el usuario interactúe
    data: {
      url: payload.data?.url || './',
      ...payload.data
    },
    actions: [
      {
        action: 'open',
        title: 'Abrir'
      },
      {
        action: 'close',
        title: 'Cerrar'
      }
    ]
  };

  // Mostrar la notificación
  return self.registration.showNotification(notificationTitle, notificationOptions);
});

// Manejar el clic en la notificación
self.addEventListener('notificationclick', (event) => {
  console.log('[firebase-messaging-sw.js] Notificación clickeada:', event);

  event.notification.close();

  if (event.action === 'close') {
    return;
  }

  // Obtener la URL de destino
  const urlToOpen = event.notification.data?.url || './';

  // Abrir o enfocar la ventana de la aplicación
  event.waitUntil(
    clients.matchAll({ type: 'window', includeUncontrolled: true })
      .then((clientList) => {
        // Buscar si ya hay una ventana abierta
        for (let i = 0; i < clientList.length; i++) {
          const client = clientList[i];
          if (client.url.includes(urlToOpen) && 'focus' in client) {
            return client.focus();
          }
        }
        // Si no hay ventana abierta, abrir una nueva
        if (clients.openWindow) {
          return clients.openWindow(urlToOpen);
        }
      })
  );
});
