<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Healthy - Plataforma de Atención Médica</title>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&display=swap" rel="stylesheet">
  <style>
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      font-family: 'Montserrat', sans-serif;
      color: #333;
      background: #f9f9f9;
      scroll-behavior: smooth;
    }

    header {
      background: #008080;
      color: white;
      padding: 40px 20px;
      text-align: center;
    }

    header h1 {
      font-size: 3rem;
      margin-bottom: 10px;
    }

    header p {
      font-size: 1.2rem;
    }

    nav {
      background: #006666;
      padding: 15px 0;
      text-align: center;
      position: sticky;
      top: 0;
      z-index: 100;
    }

    nav a {
      color: white;
      margin: 0 15px;
      text-decoration: none;
      font-weight: bold;
      font-size: 1rem;
    }

    .parallax {
      background-image: url('https://images.unsplash.com/photo-1588776814546-cbaf53f2643d?ixlib=rb-4.0.3&auto=format&fit=crop&w=1470&q=80');
      min-height: 500px;
      background-attachment: fixed;
      background-position: center;
      background-repeat: no-repeat;
      background-size: cover;
      display: flex;
      justify-content: center;
      align-items: center;
      color: white;
      text-align: center;
    }

    .parallax h2 {
      font-size: 3rem;
      background: rgba(0, 0, 0, 0.4);
      padding: 20px;
      border-radius: 10px;
    }

    section {
      padding: 60px 20px;
      max-width: 1200px;
      margin: auto;
    }

    .section-title {
      text-align: center;
      font-size: 2.2rem;
      margin-bottom: 40px;
      color: #008080;
    }

    .services {
      display: flex;
      flex-wrap: wrap;
      gap: 30px;
      justify-content: center;
    }

    .service-box {
      background: white;
      border-radius: 10px;
      padding: 30px;
      width: 300px;
      box-shadow: 0 10px 20px rgba(0,0,0,0.1);
      transition: 0.3s ease;
      text-align: center;
    }

    .service-box:hover {
      transform: translateY(-5px);
    }

    .service-box h3 {
      color: #00b894;
      margin-bottom: 15px;
    }

    .service-box p {
      font-size: 1rem;
      color: #555;
    }

    .about {
      background: #e0f7fa;
      border-radius: 10px;
      padding: 40px;
    }

    .features {
      display: flex;
      flex-wrap: wrap;
      justify-content: space-around;
      margin-top: 30px;
    }

    .feature {
      width: 300px;
      padding: 20px;
      text-align: center;
    }

    .feature h4 {
      color: #008080;
      margin-bottom: 10px;
    }

    .contact-form {
      max-width: 500px;
      margin: auto;
    }

    .contact-form input,
    .contact-form textarea {
      width: 100%;
      padding: 15px;
      margin-bottom: 15px;
      border: 1px solid #ccc;
      border-radius: 8px;
    }

    .contact-form button {
      background: #00b894;
      color: white;
      border: none;
      padding: 15px 30px;
      font-size: 1rem;
      border-radius: 30px;
      cursor: pointer;
    }

    .contact-form button:hover {
      background: #00897b;
    }

    footer {
      background: #004d4d;
      color: white;
      text-align: center;
      padding: 20px;
      margin-top: 40px;
    }

    @media (max-width: 768px) {
      .services,
      .features {
        flex-direction: column;
        align-items: center;
      }
    }
  </style>
</head>
<body>

  <header>
    <h1>Healthy</h1>
    <p>Atención médica digital, accesible y confiable</p>
  </header>

  <nav>
    <a href="#servicios">Servicios</a>
    <a href="#nosotros">Nosotros</a>
    <a href="#beneficios">Beneficios</a>
    <a href="#contacto">Contacto</a>
  </nav>

  <div class="parallax">
    <h2>Tu salud al alcance de un clic</h2>
  </div>

  <section id="servicios">
    <h2 class="section-title">Nuestros Servicios</h2>
    <div class="services">
      <div class="service-box">
        <h3>Consultas Virtuales</h3>
        <p>Conéctate con médicos en cualquier momento mediante videollamadas seguras.</p>
      </div>
      <div class="service-box">
        <h3>Recetas Digitales</h3>
        <p>Obtén recetas electrónicas listas para presentar en farmacias.</p>
      </div>
      <div class="service-box">
        <h3>Seguimiento Médico</h3>
        <p>Monitorea tu tratamiento y evolución de forma continua y confiable.</p>
      </div>
    </div>
  </section>

  <section id="nosotros" class="about">
    <h2 class="section-title">¿Quiénes Somos?</h2>
    <p>Somos una plataforma que busca acercar la salud a todas las personas mediante tecnología, ofreciendo atención médica eficiente, rápida y segura desde cualquier dispositivo con internet.</p>
  </section>

  <div class="parallax" style="background-image: url('https://images.unsplash.com/photo-1588776814993-9aeecb993d15?ixlib=rb-4.0.3&auto=format&fit=crop&w=1470&q=80');">
    <h2>Cuidamos de ti y tu familia</h2>
  </div>

  <section id="beneficios">
    <h2 class="section-title">Beneficios</h2>
    <div class="features">
      <div class="feature">
        <h4>Accesibilidad 24/7</h4>
        <p>Consulta con médicos desde donde estés, a cualquier hora.</p>
      </div>
      <div class="feature">
        <h4>Privacidad Segura</h4>
        <p>Tus datos están protegidos bajo estrictos protocolos.</p>
      </div>
      <div class="feature">
        <h4>Plataforma Intuitiva</h4>
        <p>Diseño fácil de usar, pensado para todos los públicos.</p>
      </div>
    </div>
  </section>

  <section id="contacto">
    <h2 class="section-title">Contáctanos</h2>
    <div class="contact-form">
      <input type="text" placeholder="Tu nombre" required>
      <input type="email" placeholder="Correo electrónico" required>
      <textarea rows="5" placeholder="Tu mensaje" required></textarea>
      <button>Enviar Mensaje</button>
    </div>
  </section>

  <footer>
    <p>&copy; 2025 Healthy. Todos los derechos reservados.</p>
  </footer>

</body>
</html>
