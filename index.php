<!DOCTYPE html>
<html lang="es">

<head>
  <?php
  require_once "cabecera.php";
  ?>
  <!-- FIREBASE CLOUD MESSAGING -->

  <script type="module">
    import { messaging } from "./firebase/firebase.js";
    import { getToken, onMessage } from "https://www.gstatic.com/firebasejs/10.12.0/firebase-messaging.js";

    // 🔥 CLAVE PÚBLICA VAPID QUE YA TIENES
    const vapidKey = "BHgbifqWpyMfohYT--zqg4_ewXAkTRjUnPQHG1L9WApgW9ESYc-3rR-k7JrVncgFvyy8q1zGdCOK5uyoqSn8a2s";

    // Registramos el service worker
    navigator.serviceWorker.register('./firebase-messaging-sw.js')
      .then(async (registration) => {

        // Pedir permiso
        const permission = await Notification.requestPermission();

        if (permission === "granted") {
          console.log("Permiso de notificaciones concedido");

          const token = await getToken(messaging, {
            vapidKey: vapidKey,
            serviceWorkerRegistration: registration
          });

          console.log("TOKEN FCM:", token);

          if (token) {
            // Enviar token al backend PHP
            fetch('./modelo/modificar/guardar_token.php', {
              method: "POST",
              headers: { "Content-Type": "application/x-www-form-urlencoded" },
              body: "token=" + encodeURIComponent(token)
            });
          }
        } else {
          console.log("El usuario NO dio permiso para notificaciones");
        }
      });

    // Notificaciones cuando la página está en primer plano
    onMessage(messaging, (payload) => {
      console.log("Notificación en primer plano:", payload);

      // Mostrar notificación del navegador incluso cuando la página está activa
      if (Notification.permission === "granted") {
        const notificationTitle = payload.notification?.title || 'Healthy';
        const notificationOptions = {
          body: payload.notification?.body || 'Nueva notificación',
          icon: '/logo.png',
          badge: '/logo.png',
          tag: 'healthy-foreground',
          requireInteraction: false
        };

        new Notification(notificationTitle, notificationOptions);
      }
    });
  </script>

  <!-- Ya sigue tu Bootstrap sin problema -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <style>
    body {
      margin: 0;
      padding: 0;
      background: linear-gradient(to top right, #b3e5fc, #e1f5fe);
      font-family: 'Segoe UI', sans-serif;
      overflow-x: hidden;
    }

    .bubble,
    .icon-float {
      position: fixed;
      bottom: -100px;
      opacity: 0.4;
      animation: floatUp infinite;
      z-index: 0;
      pointer-events: none;
    }

    .icon-float {
      font-size: 2.5rem;
      opacity: 0.7;
    }

    @keyframes floatUp {
      0% {
        transform: translateY(0);
        opacity: 0;
      }

      50% {
        opacity: 0.6;
      }

      100% {
        transform: translateY(-120vh);
        opacity: 0;
      }
    }

    .section-title {
      color: #0056b3;
      margin-bottom: 30px;
      font-weight: bold;
    }

    .service-box,
    .feature,
    .contact-form {
      background: rgba(255, 255, 255, 0.85);
      backdrop-filter: blur(8px);
      padding: 20px;
      border-radius: 20px;
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    .parallax {
      background: url('') no-repeat center center/cover;
      height: 300px;
      display: flex;
      justify-content: center;
      align-items: center;
      color: white;
      font-size: 2rem;
      font-weight: bold;
      text-shadow: 1px 1px 6px rgba(0, 0, 0, 0.7);
      position: relative;
    }

    footer {
      background-color: #003f7f;
    }

    .navbar.scrolled {
      background-color: rgb(0, 0, 0) !important;
      transition: background-color 0.3s ease;
    }

    .service-box i {
      font-size: 2rem;
      margin-bottom: 10px;
    }

    .parallax h2 i {
      margin-right: 10px;
    }
  </style>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body>

  <!-- Efectos globales flotantes -->
  <div class="bubble"
    style="left: 10%; width: 80px; height: 80px; background: rgba(0,123,255,0.2); animation-duration: 14s; border-radius: 50%;">
  </div>
  <div class="bubble"
    style="left: 40%; width: 60px; height: 60px; background: rgba(0,123,255,0.2); animation-duration: 12s; border-radius: 50%;">
  </div>
  <div class="bubble"
    style="left: 75%; width: 90px; height: 90px; background: rgba(0,123,255,0.2); animation-duration: 16s; border-radius: 50%;">
  </div>
  <div class="icon-float text-danger" style="left: 20%; animation-duration: 15s;">💉</div>
  <div class="icon-float text-success" style="left: 35%; animation-duration: 17s;">🖥️</div>
  <div class="icon-float text-warning" style="left: 50%; animation-duration: 13s;">📱</div>
  <div class="icon-float text-primary" style="left: 65%; animation-duration: 14s;">💊</div>
  <div class="icon-float text-info" style="left: 80%; animation-duration: 11s;">💻</div>
  <div class="icon-float text-secondary" style="left: 90%; animation-duration: 18s;">👨‍⚕️</div>

  <?php require_once "nav.php"; ?>

  <div class="parallax">
    <h2><i class="fas fa-hand-holding-medical text-danger"></i>Tu salud al alcance de un clic</h2>
  </div>

  <section id="servicios" class="container py-5">
    <h2 class="section-title text-center">Nuestros Servicios</h2>
    <div class="row g-4">
      <div class="col-lg-4 col-md-6">
        <div class="service-box text-center">
          <i class="fas fa-video text-primary"></i>
          <h3>Consultas Virtuales</h3>
          <p>Conéctate con médicos en cualquier momento mediante videollamadas seguras.</p>
        </div>
      </div>
      <div class="col-lg-4 col-md-6">
        <div class="service-box text-center">
          <i class="fas fa-prescription-bottle-alt text-success"></i>
          <h3>Recetas Digitales</h3>
          <p>Obtén recetas electrónicas listas para presentar en farmacias.</p>
        </div>
      </div>
      <div class="col-lg-4 col-md-6">
        <div class="service-box text-center">
          <i class="fas fa-notes-medical text-danger"></i>
          <h3>Seguimiento Médico</h3>
          <p>Monitorea tu tratamiento y evolución de forma continua y confiable.</p>
        </div>
      </div>
    </div>
  </section>

  <section id="nosotros" class="about container py-5">
    <h2 class="section-title text-center">¿Quiénes Somos?</h2>
    <div class="feature">
      <p>Somos una plataforma que busca acercar la salud a todas las personas mediante tecnología, ofreciendo atención
        médica eficiente, rápida y segura desde cualquier dispositivo con internet.</p>
    </div>
  </section>

  <div class="parallax">
    <h2><i class="fas fa-heartbeat text-warning"></i>Cuidamos de ti y tu familia</h2>
  </div>

  <section id="beneficios" class="container py-5">
    <h2 class="section-title text-center">Beneficios</h2>
    <div class="row row-cols-1 row-cols-md-3 g-4">
      <div class="col">
        <div class="feature text-center">
          <i class="fas fa-clock fa-2x text-success mb-2"></i>
          <h4>Accesibilidad 24/7</h4>
          <p>Consulta con médicos desde donde estés, a cualquier hora.</p>
        </div>
      </div>
      <div class="col">
        <div class="feature text-center">
          <i class="fas fa-shield-alt fa-2x text-danger mb-2"></i>
          <h4>Privacidad Segura</h4>
          <p>Tus datos están protegidos bajo estrictos protocolos.</p>
        </div>
      </div>
      <div class="col">
        <div class="feature text-center">
          <i class="fas fa-mobile-alt fa-2x text-info mb-2"></i>
          <h4>Plataforma Intuitiva</h4>
          <p>Diseño fácil de usar, pensado para todos los públicos.</p>
        </div>
      </div>
    </div>
  </section>

  <section id="contacto" class="container py-5">
    <h2 class="section-title text-center">Contáctanos</h2>
    <div class="contact-form">
      <input type="text" class="form-control mb-3" placeholder="Tu nombre" required>
      <input type="email" class="form-control mb-3" placeholder="Correo electrónico" required>
      <textarea class="form-control mb-3" rows="5" placeholder="Tu mensaje" required></textarea>
      <button class="btn btn-primary w-100">Enviar Mensaje</button>
    </div>
  </section>

  <footer class="text-white text-center py-3">
    <p>&copy; 2025 Healthy. Todos los derechos reservados.</p>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    window.onscroll = function () {
      var navbar = document.querySelector('.navbar');
      if (window.scrollY > 50) {
        navbar.classList.add('scrolled');
      } else {
        navbar.classList.remove('scrolled');
      }
    };
  </script>
</body>

</html>