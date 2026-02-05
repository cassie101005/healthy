importScripts("https://www.gstatic.com/firebasejs/12.6.0/firebase-app-compat.js")
importScripts("https://www.gstatic.com/firebasejs/12.6.0/firebase-messaging-compat.js")



const firebaseConfig = {
  apiKey: "AIzaSyBJ6yQ1j_DWxag7MPCJGAZwCW2FZOTEXjE",
  authDomain: "healthy-c83ca.firebaseapp.com",
  projectId: "healthy-c83ca",
  storageBucket: "healthy-c83ca.firebasestorage.app",
  messagingSenderId: "809640150546",
  appId: "1:809640150546:web:3dba54c489e0c824487acb",
  measurementId: "G-7Y4YC4NSSP"
};

// Initialize Firebase
const app = firebaseConfig.initializeApp(firebaseConfig);
const messaging = firebase.messaging(app);

messaging.onBackgroundMessage(playload => {
    console.log("recibiste mensaje mientras estabas ausente");
    //previo a mostrar las notificaciones
    const notificationTitle= playload.notification.title;
    const notificationOptios = {
          body: playload.notification.body,
          icon: "/sistema/img/atencion.png"
    }
  return self.registration.showNotifivation(
    notificationTitle,
    notificationOptios
  )

})