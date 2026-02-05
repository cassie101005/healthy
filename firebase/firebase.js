import { initializeApp } from "https://www.gstatic.com/firebasejs/12.6.0/firebase-app.js";
import { getMessaging } from "https://www.gstatic.com/firebasejs/12.6.0/firebase-messaging.js";

// Configuración del proyecto Healthy
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

export { app, messaging };
