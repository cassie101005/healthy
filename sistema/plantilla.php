<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Quiniela</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat&family=Roboto&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Baloo+2&family=Bebas+Neue&family=Comfortaa&family=Fira+Sans&family=Fredoka&family=Indie+Flower&family=Lato&family=Lobster&family=Montserrat&family=Open+Sans&family=Orbitron&family=Pacifico&family=Poppins&family=Quicksand&family=Rajdhani&family=Roboto&display=swap" rel="stylesheet">
  <style>
    .quiniela-header,
    .quiniela-footer {
      padding: 1rem;
      color: white;
      text-align: center;
    }
    .quiniela-footer {
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    .quiniela-table img {
      width: 32px;
      height: 32px;
    }
    .btn-custom {
      width: 100%;
      font-weight: bold;
    }
  </style>
</head>
<body>

<div class="container my-3 plantilla">
  <!-- SELECCIÓN DE FUENTE -->
  <div class="mb-3">
    <label for="fuenteSelect" class="form-label fw-bold">Selecciona el tipo de letra:</label>
    <select id="fuenteSelect" class="form-select">
      <option value="Arial" selected>Arial</option>
      <option value="'Roboto', sans-serif">Roboto</option>
      <option value="'Open Sans', sans-serif">Open Sans</option>
      <option value="'Lato', sans-serif">Lato</option>
      <option value="'Montserrat', sans-serif">Montserrat</option>
      <option value="'Poppins', sans-serif">Poppins</option>
      <option value="'Orbitron', sans-serif">Orbitron</option>
      <option value="'Bebas Neue', sans-serif">Bebas Neue</option>
      <option value="'Rajdhani', sans-serif">Rajdhani</option>
      <option value="'Fira Sans', sans-serif">Fira Sans</option>
      <option value="'Pacifico', cursive">Pacifico</option>
      <option value="'Lobster', cursive">Lobster</option>
      <option value="'Fredoka', sans-serif">Fredoka</option>
      <option value="'Indie Flower', cursive">Indie Flower</option>
      <option value="'Quicksand', sans-serif">Quicksand</option>
      <option value="'Comfortaa', cursive">Comfortaa</option>
      <option value="'Baloo 2', cursive">Baloo 2</option>
      <option value="'Comic Sans MS', cursive">Comic Sans MS</option>
    </select>
  </div>

  <!-- CAMBIO DE COLOR Y FONDO -->
  <div class="row g-2 mb-3">
    <div class="col-md-6">
      <h6>Cabecera:</h6>
      <label class="form-label">Color de letra:</label>
      <input type="color" id="headerTextColor" class="form-control form-control-color mb-2">

      <label class="form-label">Color de fondo:</label>
      <input type="color" id="headerBgColor1" class="form-control form-control-color mb-2">

      <div class="form-check mb-2">
        <input class="form-check-input" type="checkbox" id="headerGradientCheck">
        <label class="form-check-label" for="headerGradientCheck">Usar degradado</label>
      </div>

      <input type="color" id="headerBgColor2" class="form-control form-control-color mb-2" disabled>
    </div>

    <div class="col-md-6">
      <h6>Pie de página:</h6>
      <label class="form-label">Color de letra:</label>
      <input type="color" id="footerTextColor" class="form-control form-control-color mb-2">

      <label class="form-label">Color de fondo:</label>
      <input type="color" id="footerBgColor1" class="form-control form-control-color mb-2">

      <div class="form-check mb-2">
        <input class="form-check-input" type="checkbox" id="footerGradientCheck">
        <label class="form-check-label" for="footerGradientCheck">Usar degradado</label>
      </div>

      <input type="color" id="footerBgColor2" class="form-control form-control-color mb-2" disabled>
    </div>
  </div>

  <!-- CABECERA -->
  <div class="quiniela-header" id="header">
    <img src="img/tu_logo.png" alt="Logo" style="height: 80px;">
    <h4 class="mt-2">(NOMBRE DE TU QUINIELA)</h4>
  </div>

  <!-- TABLA -->
  <table class="table table-bordered text-center align-middle quiniela-table mt-3">
    <tbody>
      <?php for($pos = 1; $pos <= 12; $pos += 2){ ?>
        <tr>        
          <td>L</td>
          <td>Equipo <?php echo $pos; ?></td>
          <td>E</td>
          <td>Equipo <?php echo $pos + 1; ?></td>
          <td>V</td>
        </tr>
      <?php } ?>
    </tbody>
  </table>

  <!-- FOOTER -->
  <div class="quiniela-footer" id="footer">
    <span>PRECIO: $20</span>
    <span>CIERRE: 2025-04-15 / 23:32:00</span>
  </div>
</div>

<script>
  const fuenteSelect = document.getElementById("fuenteSelect");
  const plantilla = document.querySelector(".plantilla");

  fuenteSelect.addEventListener("change", () => {
    plantilla.style.fontFamily = fuenteSelect.value;
  });

  // HEADER Y FOOTER
  const header = document.getElementById("header");
  const footer = document.getElementById("footer");

  // Cambios color texto
  document.getElementById("headerTextColor").addEventListener("input", e => {
    header.style.color = e.target.value;
  });
  document.getElementById("footerTextColor").addEventListener("input", e => {
    footer.style.color = e.target.value;
  });

  // Cambio color fondo header
  const headerBg1 = document.getElementById("headerBgColor1");
  const headerBg2 = document.getElementById("headerBgColor2");
  const headerGradCheck = document.getElementById("headerGradientCheck");

  function actualizarFondoHeader() {
    if (headerGradCheck.checked) {
      headerBg2.disabled = false;
      header.style.background = `linear-gradient(90deg, ${headerBg1.value}, ${headerBg2.value})`;
    } else {
      headerBg2.disabled = true;
      header.style.background = headerBg1.value;
    }
  }

  headerBg1.addEventListener("input", actualizarFondoHeader);
  headerBg2.addEventListener("input", actualizarFondoHeader);
  headerGradCheck.addEventListener("change", actualizarFondoHeader);

  // Cambio color fondo footer
  const footerBg1 = document.getElementById("footerBgColor1");
  const footerBg2 = document.getElementById("footerBgColor2");
  const footerGradCheck = document.getElementById("footerGradientCheck");

  function actualizarFondoFooter() {
    if (footerGradCheck.checked) {
      footerBg2.disabled = false;
      footer.style.background = `linear-gradient(90deg, ${footerBg1.value}, ${footerBg2.value})`;
    } else {
      footerBg2.disabled = true;
      footer.style.background = footerBg1.value;
    }
  }

  footerBg1.addEventListener("input", actualizarFondoFooter);
  footerBg2.addEventListener("input", actualizarFondoFooter);
  footerGradCheck.addEventListener("change", actualizarFondoFooter);
</script>

</body>
</html>
