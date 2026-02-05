/*function registroVendedor(){
    $("#btn_vendedores").click();
}*/

function cambiarLada() {
    var pais = document.getElementById("pais").value;
    var lada = document.getElementById("lada");
  
    switch (pais) {
      case "Mexico":
        lada.textContent = "+52";
        break;
      case "USA":
        lada.textContent = "+1";
        break;
      case "Colombia":
        lada.textContent = "+57";
        break;
      case "Argentina":
        lada.textContent = "+54";
        break;
      case "España":
        lada.textContent = "+34";
        break;
      case "Chile":
        lada.textContent = "+56";
        break;
      case "Peru":
        lada.textContent = "+51";
        break;
      case "Venezuela":
        lada.textContent = "+58";
        break;
      default:
        lada.textContent = "+52"; // Valor por defecto
        break;
    }
}

  function validarDominio() {
    var dominio = document.getElementById("dominio");
    var mensajeError = document.getElementById("mensaje-error");

    // Eliminamos cualquier espacio en blanco al principio y al final
    dominio.value = dominio.value.trim();

    // Expresión regular para validar que el dominio solo contenga letras, números y guiones
    var regex = /^[a-zA-Z0-9-]+$/;

    // Verificamos si el valor cumple con la expresión regular
    if (!regex.test(dominio.value)) {
        // Si no es válido, mostramos el mensaje de error
        mensajeError.style.display = "block";
    } else {
        // Si es válido, ocultamos el mensaje de error
        mensajeError.style.display = "none";
    }
}


function validarNumeros(input) {
  // Remueve cualquier caracter que no sea número
  input.value = input.value.replace(/\D/g, '');
}


function registrarVendedores() {  
  event.preventDefault(); // Previene el envío del formulario

  let datos = $('#form_registro_vendedores').serialize();

  $.ajax({
      url: '../modelo/modificar/modificar_vendedores.php',
      type: 'POST',
      data: datos,
      dataType: "json", // Indica que esperas una respuesta en JSON
      success: function(response) {
          let errores = [];

          if (response.existe_celular) {
              errores.push("El número celular ingresado ya está registrado con otra cuenta.");
          }
          if (response.existe_dominio) {
              errores.push("El dominio ingresado ya está en uso, intenta con otro nombre.");
          }

          if (errores.length > 0) {
              mensaje("error", errores);
          } else {
              /*mensaje("success", "Datos actualizados correctamente.");
              console.log("País:", response.pais);
              console.log("Celular:", response.celular);
              console.log("Dominio:", response.dominio);*/
              mensajePersonalizarPlantilla();
          }
      },
      error: function(xhr, status, error) {
          console.error("Error en la solicitud AJAX:", error);
          alert("Hubo un problema con la solicitud.");
      }
  });
}

function mensaje(icon, mensajes) {
  let contenidoHtml = Array.isArray(mensajes) 
      ? "<ul style='text-align:left;'>" + mensajes.map(msg => `<li>${msg}</li>`).join("") + "</ul>"
      : mensajes;

  Swal.fire({
    icon: icon,
    title: "Aviso",
    html: contenidoHtml
  });
}

function mensajePersonalizarPlantilla(mensaje){
  Swal.fire({
    icon: "success",
    title: "Datos registrados",
    text: "Es hora de personalizar tu plantilla",
    allowOutsideClick: false, // evita que se cierre haciendo clic fuera
    confirmButtonText: "Ir ahora", // puedes cambiar el texto del botón
  }).then((result) => {
    if (result.isConfirmed) {
      window.location.href = "personalizar_plantilla.php"; // <-- redirige a donde quieras
    }
  });
  
}

