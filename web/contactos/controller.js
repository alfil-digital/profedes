function enviar() {
  let nombre = document.getElementById("name").value.trim();
  let asunto = document.getElementById("subject").value.trim();
  let email = document.getElementById("email").value.trim();
  let telefono = document.getElementById("phone").value.trim();
  let mensaje = document.getElementById("message").value.trim();

  let errores = {};

  if (!validacionTexto(nombre)) {
    errores.nombre = "El nombre solo puede contener letras y números";
  }

  if (!validacionTexto(asunto) || !validacionNumero(asunto)) {
    errores.asunto = "El asunto solo puede contener letras y números";
  }

  if (!validacionEmail(email)) {
    errores.email = "El email no es válido";
  }

  if (!validacionNumero(telefono)) {
    errores.telefono = "Solo se permiten numeros en el teléfono";
  }

  if (!validacionTexto(mensaje) || !validacionNumero(mensaje)) {
    errores.mensaje =
      "El mensaje solo puede contener letras, números y algunos signos de puntuación";
  }

  // muestro los errores y detengo el envío si hay alguno
  if (Object.keys(errores).length > 0) {
    let errorMsg = "Por favor corrige los siguientes errores:\n";
    for (let key in errores) {
      errorMsg += `${errores[key]}\n`;
    }
    alert(errorMsg);
    return false; // Detiene el envío
  }

  // Solo se muestra este alert si no hay errores
  alert("Formulario enviado correctamente");

  $.post(
    "admin/modulos/contactos/controller.php",
    {
      nombre: nombre,
      asunto: asunto,
      email: email,
      telefono: telefono,
      mensaje: mensaje,
    },
    function (data) {
      // Puedes mostrar otro mensaje aquí si quieres
    }
  );

  return false; // Para evitar el envío tradicional del formulario
}

function validacionTexto(data) {
  // validar input

  if (!data) return false;
  else if (!/^[a-zA-Z0-9\s.,!?]+$/.test(data)) return false;
  // si no cumple con las validaciones anteriores, es válido
  else return true;
}

function validacionNumero(data) {
  if (!data) return false;
  else if (!/^[0-9\s]+$/.test(data)) return false;
  else return true;
}

function validacionEmail(data) {
  if (!data) return false;
  else if (!/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/.test(data))
    return false;
  else return true;
}
