// docedes-plantillas/plantilla/modulos/administracion/profesionales/funciones.js
// MODIFICACIÓN: Cambiado el comentario de la ruta para reflejar el módulo de profesionales.

// Función para cargar el formulario de edición o creación de un profesional.
// Recibe un 'id' que será 0 para un nuevo registro o el ID del profesional a editar.
function editar(id) {
  // Desplaza la página al principio (arriba del todo) de forma instantánea.
  // Esto es útil para que el usuario vea el formulario cargado desde el inicio de la vista.
  $("html").animate(
    {
      scrollTop: $("html").offset().top,
    },
    0
  );
  // Cargar el formulario de edición/creación del profesional.
  // Realiza una petición GET al archivo 'formulario.php', pasando el 'id' como parámetro.
  // MODIFICACIÓN: Asegurado que la ruta sea para el módulo de profesionales.
  $.get(
    "modulos/administracion/profesionales/formulario.php?id=" + id,
    function (dato) {
      // Una vez que se recibe el contenido del formulario (dato), lo inserta en el elemento con ID 'formulario'.
      $("#formulario").css("display", "block"); // Establece el estilo CSS 'display' a 'block' para hacerlo visible.
      $("#formulario").html(dato); // Inserta el HTML del formulario.
      $("#formulario").fadeIn("slow"); // Muestra el formulario con un efecto de desvanecimiento lento.
    }
  );
}

// Función para cerrar el formulario de edición/creación.
function cerrar_formulario() {
  $("#formulario").css("display", "none"); // Oculta el elemento con ID 'formulario' estableciendo 'display' a 'none'.
  $("#formulario").html(""); // Limpia el contenido HTML del formulario.
  $("#formulario").fadeOut("slow"); // Oculta el formulario con un efecto de desvanecimiento lento.
}

// Función para cargar el listado de profesionales.
function listado() {
  // Desplaza la página al principio de forma instantánea.
  $("html").animate(
    {
      scrollTop: $("html").offset().top,
    },
    0
  );
  // Cargar el listado de profesionales.
  // Realiza una petición GET al archivo 'listado.php'.
  // MODIFICACIÓN: Asegurado que la ruta sea para el módulo de profesionales.
  $.get("modulos/administracion/profesionales/listado.php", function (dato) {
    $("#listado").css("display", "block"); // Muestra el elemento con ID 'listado'.
    $("#listado").html(dato); // Inserta el HTML del listado en el elemento.

    // Usar el callback de fadeIn para asegurar que el elemento esté visible
    // y completamente en el DOM antes de intentar inicializar DataTable.
    $("#listado").fadeIn("slow", function () {
      // *** Paso CLAVE: Verificar si ya existe una instancia de DataTable y destruirla ***
      // Esto previene el error de "Cannot reinitialise DataTable"
      if ($.fn.DataTable.isDataTable("#dataTable")) {
        // Comprueba si el elemento '#dataTable' ya tiene una instancia de DataTable.
        $("#dataTable").DataTable().destroy(); // Destruye la instancia existente de DataTable.
        console.log("Instancia de DataTable existente destruida."); // Mensaje para depuración en consola.
      }

      // Inicializar DataTables con la configuración de idioma.
      $("#dataTable").DataTable({
        // Inicializa la tabla con el ID 'dataTable' como una DataTable.
        language: {
          // Configuración del idioma para la tabla.
          sLengthMenu: "Mostrar _MENU_ registros", // Texto para el selector de número de registros por página.
          sProcessing: "Procesando...", // Mensaje mientras se procesan los datos.
          sZeroRecords: "No se encontraron resultados", // Mensaje cuando no hay registros que coincidan.
          sEmptyTable: "Ningún dato disponible en esta tabla", // Mensaje cuando la tabla está vacía.
          sInfo:
            "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros", // Información sobre los registros mostrados.
          sInfoEmpty:
            "Mostrando registros del 0 al 0 de un total de 0 registros", // Información cuando no hay registros.
          sInfoFiltered: "(filtrado de un total de _MAX_ registros)", // Información cuando los registros están filtrados.
          sInfoPostFix: "", // Sufijo para la información.
          sSearch: "Buscar:", // Etiqueta para el campo de búsqueda.
          sUrl: "", // URL para la carga de datos (si se usa Ajax).
          sInfoThousands: ",", // Separador de miles en la información.
          sLoadingRecords: "Cargando...", // Mensaje de carga de registros.
          oPaginate: {
            // Configuración de la paginación.
            sFirst: "Primero", // Texto para el botón de ir a la primera página.
            sLast: "Último", // Texto para el botón de ir a la última página.
            sNext: "Siguiente", // Texto para el botón de ir a la siguiente página.
            sPrevious: "Anterior", // Texto para el botón de ir a la página anterior.
          },
        },
      });
      console.log("DataTable inicializada."); // Mensaje para depuración en consola.
    }); // Fin del callback de fadeIn
  }).fail(function (xhr, status, error) {
    // Bloque que se ejecuta si la petición AJAX falla.
    console.error("AJAX Error loading listado: " + status, error, xhr); // Registra el error en la consola del navegador.
    $("#mensaje").css("display", "block"); // Muestra el elemento con ID 'mensaje'.
    $("#mensaje").html(
      // Inserta un mensaje de error en el elemento 'mensaje'.
      '<div class="alert alert-danger" role="alert"><button type="button" class="close" data-dismiss="alert">&times;</button><i class="fas fa-exclamation-triangle"></i> Error al cargar el listado: ' +
        status +
        "</div>"
    );
    $("#mensaje").fadeIn("slow"); // Muestra el mensaje de error con un efecto de desvanecimiento lento.
  });
}

// Función para validar el formulario.
function validar_formulario() {
  var form = document.getElementById("form"); // Obtiene el elemento del formulario por su ID.
  // Comprueba la validez del formulario usando la API de validación de HTML5.
  if (!form.checkValidity()) {
    form.classList.add("was-validated"); // Agrega la clase 'was-validated' para mostrar los mensajes de validación de Bootstrap.
    return false; // Retorna false si el formulario no es válido.
  }
  return true; // Retorna true si el formulario es válido.
}

// Función para guardar los datos del formulario (crear o actualizar un profesional).
function guardar(id) {
  // Primero, valida el formulario. Si no es válido, detiene la ejecución.
  if (!validar_formulario()) {
    return;
  }

  // Muestra un cuadro de diálogo de confirmación (usando una librería como jConfirm o similar).
  $.confirm({
    title: "Guardar!", // Título del cuadro de diálogo.
    content: "Desea <b>Guardar</b> el <b> Registro</b>?", // Contenido del mensaje.
    icon: "far fa-question-circle", // Icono a mostrar.
    animation: "scale", // Tipo de animación de entrada.
    closeAnimation: "scale", // Tipo de animación de salida.
    opacity: 0.5, // Opacidad del fondo.
    buttons: {
      confirm: {
        // Botón de confirmación.
        text: "SI", // Texto del botón.
        btnClass: "btn-green", // Clase CSS del botón.
        action: function () {
          // Función que se ejecuta al hacer clic en "SI".
          // Crea un objeto FormData a partir del formulario para enviar los datos, incluyendo archivos si los hubiera.
          var formData = new FormData(document.getElementById("form"));
          // Realiza una petición AJAX (asíncrona) al servidor.
          $.ajax({
            type: "POST", // Método de la petición HTTP.
            // URL del controlador PHP, pasando la función 'editar' y el 'id'.
            // MODIFICACIÓN: Asegurado que la ruta sea para el módulo de profesionales.
            url:
              "modulos/administracion/profesionales/controlador.php?f=editar&id=" +
              id,
            data: formData, // Los datos a enviar (objeto FormData).
            cache: false, // No almacenar en caché la respuesta.
            contentType: false, // No establecer el tipo de contenido (FormData lo hace automáticamente).
            processData: false, // No procesar los datos (necesario para FormData).
            success: function (dato) {
              // Función que se ejecuta si la petición es exitosa.
              $("#mensaje").css("display", "block"); // Muestra el elemento de mensajes.
              $("#mensaje").html(dato); // Inserta la respuesta del servidor en el elemento de mensajes.
              $("#mensaje").fadeIn("slow"); // Muestra el mensaje con desvanecimiento.
              //listado(); // Llama a la función 'listado()' para refrescar la tabla de profesionales (se llama desde el controlador).
              //cerrar_formulario(); // Llama a la función 'cerrar_formulario()' para ocultar el formulario (se llama desde el controlador).
              // NOTA: Estas llamadas se han comentado aquí porque el controlador PHP ya las emite
              // como scripts JavaScript si la operación es exitosa, evitando duplicidad.
            },
            error: function (xhr, status, error) {
              // Función que se ejecuta si hay un error en la petición AJAX.
              $("#mensaje").css("display", "block"); // Muestra el elemento de mensajes.
              $("#mensaje").html(
                // Inserta un mensaje de error detallado.
                '<div class="alert alert-danger" role="alert"><button type="button" class="close" data-dismiss="alert">&times;</button><i class="fas fa-exclamation-triangle"></i> Error en la comunicación con el servidor: ' +
                  error +
                  "</div>"
              );
              $("#mensaje").fadeIn("slow"); // Muestra el mensaje de error con desvanecimiento.
            },
          });
        },
      },
      cancel: {
        // Botón de cancelación.
        text: "NO", // Texto del botón.
        btnClass: "btn-red", // Clase CSS del botón.
        action: function () {
          /* No hacer nada */
          // No realiza ninguna acción si se hace clic en "NO".
        },
      },
    },
  });
}

// Función para eliminar un profesional.
function eliminar(id) {
  // Muestra un cuadro de diálogo de confirmación para la eliminación.
  $.confirm({
    title: "Confirmar Acción", // Título del cuadro de diálogo.
    content: "Desea <b>Eliminar</b> el <b> Profesional</b>?", // MODIFICACIÓN: Contenido del mensaje.
    icon: "fas fa-trash", // Icono a mostrar.
    animation: "scale", // Tipo de animación de entrada.
    closeAnimation: "scale", // Tipo de animación de salida.
    opacity: 0.5, // Opacidad del fondo.
    buttons: {
      confirm: {
        // Botón de confirmación.
        text: "SI", // Texto del botón.
        btnClass: "btn-green", // Clase CSS del botón.
        action: function () {
          // Función que se ejecuta al hacer clic en "SI".
          $.ajax({
            type: "GET", // Método de la petición HTTP.
            // URL del controlador PHP, pasando la función 'eliminar' y el 'id'.
            // MODIFICACIÓN: Asegurado que la ruta sea para el módulo de profesionales.
            url:
              "modulos/administracion/profesionales/controlador.php?f=eliminar&id=" +
              id,
            success: function (dato) {
              // Función que se ejecuta si la petición es exitosa.
              $("#mensaje").css("display", "block"); // Muestra el elemento de mensajes.
              $("#mensaje").html(dato); // Inserta la respuesta del servidor en el elemento de mensajes.
              $("#mensaje").fadeIn("slow"); // Muestra el mensaje con desvanecimiento.
              // listado(); // La función listado ya es llamada desde el controlador en caso de éxito.
            },
            error: function (xhr, status, error) {
              // Función que se ejecuta si hay un error en la petición AJAX.
              $("#mensaje").css("display", "block"); // Muestra el elemento de mensajes.
              $("#mensaje").html(
                // Inserta un mensaje de error detallado.
                '<div class="alert alert-danger" role="alert"><button type="button" class="close" data-dismiss="alert">&times;</button><i class="fas fa-exclamation-triangle"></i> Error en la comunicación con el servidor: ' +
                  error +
                  "</div>"
              );
              $("#mensaje").fadeIn("slow"); // Muestra el mensaje de error con desvanecimiento.
            },
          });
        },
      },
      cancel: {
        // Botón de cancelación.
        text: "NO", // Texto del botón.
        btnClass: "btn-red", // Clase CSS del botón.
        action: function () {
          /* No hacer nada */
        },
      },
    },
  });
}

// NUEVA FUNCIÓN: Para ver los datos de un profesional en un modal.
function ver_datos(id) {
  $.get(
    "modulos/administracion/profesionales/ver_datos.php?id=" + id,
    function (dato) {
      $("#verDatosModal .modal-body").html(dato); // Inserta el contenido en el cuerpo del modal.
      $("#verDatosModal").modal("show"); // Muestra el modal.
    }
  ).fail(function (xhr, status, error) {
    console.error("AJAX Error loading ver_datos: " + status, error, xhr);
    $("#mensaje").css("display", "block");
    $("#mensaje").html(
      '<div class="alert alert-danger" role="alert"><button type="button" class="close" data-dismiss="alert">&times;</button><i class="fas fa-exclamation-triangle"></i> Error al cargar los datos: ' +
        status +
        "</div>"
    );
    $("#mensaje").fadeIn("slow");
  });
}