// docedes-plantillas/plantilla/modulos/administracion/profesionales/funciones.js
// MODIFICACIÓN: Cambiado el comentario de la ruta para reflejar el módulo de profesionales.

// Función para cargar el formulario de edición o creación de un profesional.
// Recibe un 'id' que será 0 para un nuevo registro o el ID del profesional a editar.
function editar(id) {
  // Desplaza la página al principio (arriba del todo) de forma instantánea.
  // Esto es útil para que el usuario vea el formulario cargado desde el inicio de la vista.
  window.scrollTo({
    top: 0,
    behavior: "auto", // 'auto' para desplazamiento instantáneo
  });

  // Cargar el formulario de edición/creación del profesional.
  // Realiza una petición GET al archivo 'formulario.php', pasando el 'id' como parámetro.
  // MODIFICACIÓN: Asegurado que la ruta sea para el módulo de profesionales.
  fetch("modulos/administracion/profesionales/formulario.php?id=" + id)
    .then((response) => {
      if (!response.ok) {
        throw new Error("Network response was not ok " + response.statusText);
      }
      return response.text();
    })
    .then((dato) => {
      const formularioElement = document.getElementById("formulario");
      formularioElement.style.display = "block"; // Establece el estilo CSS 'display' a 'block' para hacerlo visible.
      formularioElement.innerHTML = dato; // Inserta el HTML del formulario.
      // Para un efecto de desvanecimiento lento, puedes añadir y quitar una clase CSS con transiciones
      formularioElement.style.opacity = 0;
      formularioElement.style.transition = "opacity 0.6s ease-in-out";
      setTimeout(() => {
        formularioElement.style.opacity = 1;
      }, 10); // Pequeño retraso para asegurar que la transición se aplique
    })
    .catch((error) => {
      console.error("Fetch Error loading formulario: ", error);
      const mensajeElement = document.getElementById("mensaje");
      mensajeElement.style.display = "block";
      mensajeElement.innerHTML =
        '<div class="alert alert-danger" role="alert"><button type="button" class="close" data-dismiss="alert">&times;</button><i class="fas fa-exclamation-triangle"></i> Error al cargar el formulario: ' +
        error.message +
        "</div>";
      mensajeElement.style.opacity = 0;
      mensajeElement.style.transition = "opacity 0.6s ease-in-out";
      setTimeout(() => {
        mensajeElement.style.opacity = 1;
      }, 10);
    });
}

// Función para cerrar el formulario de edición/creación.
function cerrar_formulario() {
  const formularioElement = document.getElementById("formulario");
  formularioElement.style.opacity = 1;
  formularioElement.style.transition = "opacity 0.6s ease-in-out";
  formularioElement.style.opacity = 0;
  setTimeout(() => {
    formularioElement.style.display = "none"; // Oculta el elemento con ID 'formulario' estableciendo 'display' a 'none'.
    formularioElement.innerHTML = ""; // Limpia el contenido HTML del formulario.
  }, 600); // Coincide con la duración de la transición
}

// Función para cargar el listado de profesionales.
function listado() {
  // Desplaza la página al principio de forma instantánea.
  window.scrollTo({
    top: 0,
    behavior: "auto",
  });

  // Cargar el listado de profesionales.
  // Realiza una petición GET al archivo 'listado.php'.
  // MODIFICACIÓN: Asegurado que la ruta sea para el módulo de profesionales.
  fetch("modulos/administracion/profesionales/listado.php")
    .then((response) => {
      if (!response.ok) {
        throw new Error("Network response was not ok " + response.statusText);
      }
      return response.text();
    })
    .then((dato) => {
      const listadoElement = document.getElementById("listado");
      listadoElement.style.display = "block"; // Muestra el elemento con ID 'listado'.
      listadoElement.innerHTML = dato; // Inserta el HTML del listado en el elemento.

      // Usar setTimeout para asegurar que el elemento esté visible
      // y completamente en el DOM antes de intentar inicializar DataTable.
      listadoElement.style.opacity = 0;
      listadoElement.style.transition = "opacity 0.6s ease-in-out";
      setTimeout(() => {
        listadoElement.style.opacity = 1;

        // *** Paso CLAVE: Verificar si ya existe una instancia de DataTable y destruirla ***
        // Esto previene el error de "Cannot reinitialise DataTable"
        const dataTableElement = document.getElementById("dataTable");
        if (
          dataTableElement &&
          typeof DataTable !== "undefined" &&
          DataTable.isDataTable(dataTableElement)
        ) {
          // Comprueba si el elemento '#dataTable' ya tiene una instancia de DataTable.
          DataTable.getInstance(dataTableElement).destroy(); // Destruye la instancia existente de DataTable.
          console.log("Instancia de DataTable existente destruida."); // Mensaje para depuración en consola.
        }

        // Inicializar DataTables con la configuración de idioma.
        if (dataTableElement && typeof DataTable !== "undefined") {
          new DataTable(dataTableElement, {
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
        } else {
          console.warn(
            "DataTable no está definido o el elemento #dataTable no existe."
          );
        }
      }, 600); // Coincide con la duración de la transición
    })
    .catch((error) => {
      // Bloque que se ejecuta si la petición AJAX falla.
      console.error("Fetch Error loading listado: ", error); // Registra el error en la consola del navegador.
      const mensajeElement = document.getElementById("mensaje");
      mensajeElement.style.display = "block"; // Muestra el elemento con ID 'mensaje'.
      mensajeElement.innerHTML =
        // Inserta un mensaje de error en el elemento 'mensaje'.
        '<div class="alert alert-danger" role="alert"><button type="button" class="close" data-dismiss="alert">&times;</button><i class="fas fa-exclamation-triangle"></i> Error al cargar el listado: ' +
        error.message +
        "</div>";
      mensajeElement.style.opacity = 0;
      mensajeElement.style.transition = "opacity 0.6s ease-in-out";
      setTimeout(() => {
        mensajeElement.style.opacity = 1;
      }, 10);
    });
}

// Función para validar el formulario.
function validar_formulario() {
  const form = document.getElementById("form"); // Obtiene el elemento del formulario por su ID.
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

  // Utilizar la API de confirmación nativa del navegador o una librería ligera.
  // Para una experiencia de usuario similar a jConfirm, se necesitaría una librería JS o implementar un modal personalizado.
  // Por simplicidad, aquí usamos confirm() nativo.
  if (confirm("¿Desea Guardar el Registro?")) {
    const formData = new FormData(document.getElementById("form"));
    // Realiza una petición AJAX (asíncrona) al servidor.
    fetch(
      "modulos/administracion/profesionales/controlador.php?f=editar&id=" + id,
      {
        method: "POST", // Método de la petición HTTP.
        body: formData, // Los datos a enviar (objeto FormData).
      }
    )
      .then((response) => {
        if (!response.ok) {
          throw new Error("Network response was not ok " + response.statusText);
        }
        return response.text();
      })
      .then((dato) => {
        const mensajeElement = document.getElementById("mensaje");
        mensajeElement.style.display = "block"; // Muestra el elemento de mensajes.
        mensajeElement.innerHTML = dato; // Inserta la respuesta del servidor en el elemento de mensajes.
        mensajeElement.style.opacity = 0;
        mensajeElement.style.transition = "opacity 0.6s ease-in-out";
        setTimeout(() => {
          mensajeElement.style.opacity = 1;
        }, 10);
        //listado(); // Llama a la función 'listado()' para refrescar la tabla de profesionales (se llama desde el controlador).
        //cerrar_formulario(); // Llama a la función 'cerrar_formulario()' para ocultar el formulario (se llama desde el controlador).
        // NOTA: Estas llamadas se han comentado aquí porque el controlador PHP ya las emite
        // como scripts JavaScript si la operación es exitosa, evitando duplicidad.
      })
      .catch((error) => {
        // Función que se ejecuta si hay un error en la petición AJAX.
        const mensajeElement = document.getElementById("mensaje");
        mensajeElement.style.display = "block"; // Muestra el elemento de mensajes.
        mensajeElement.innerHTML =
          // Inserta un mensaje de error detallado.
          '<div class="alert alert-danger" role="alert"><button type="button" class="close" data-dismiss="alert">&times;</button><i class="fas fa-exclamation-triangle"></i> Error en la comunicación con el servidor: ' +
          error.message +
          "</div>";
        mensajeElement.style.opacity = 0;
        mensajeElement.style.transition = "opacity 0.6s ease-in-out";
        setTimeout(() => {
          mensajeElement.style.opacity = 1;
        }, 10);
      });
  }
}

// Función para eliminar un profesional.
function eliminar(id) {
  // Utilizar la API de confirmación nativa del navegador.
  if (confirm("¿Desea Eliminar el Profesional?")) {
    fetch(
      "modulos/administracion/profesionales/controlador.php?f=eliminar&id=" +
        id,
      {
        method: "GET", // Método de la petición HTTP.
      }
    )
      .then((response) => {
        if (!response.ok) {
          throw new Error("Network response was not ok " + response.statusText);
        }
        return response.text();
      })
      .then((dato) => {
        const mensajeElement = document.getElementById("mensaje");
        mensajeElement.style.display = "block"; // Muestra el elemento de mensajes.
        mensajeElement.innerHTML = dato; // Inserta la respuesta del servidor en el elemento de mensajes.
        mensajeElement.style.opacity = 0;
        mensajeElement.style.transition = "opacity 0.6s ease-in-out";
        setTimeout(() => {
          mensajeElement.style.opacity = 1;
        }, 10);
        // listado(); // La función listado ya es llamada desde el controlador en caso de éxito.
      })
      .catch((error) => {
        // Función que se ejecuta si hay un error en la petición AJAX.
        const mensajeElement = document.getElementById("mensaje");
        mensajeElement.style.display = "block"; // Muestra el elemento de mensajes.
        mensajeElement.innerHTML =
          // Inserta un mensaje de error detallado.
          '<div class="alert alert-danger" role="alert"><button type="button" class="close" data-dismiss="alert">&times;</button><i class="fas fa-exclamation-triangle"></i> Error en la comunicación con el servidor: ' +
          error.message +
          "</div>";
        mensajeElement.style.opacity = 0;
        mensajeElement.style.transition = "opacity 0.6s ease-in-out";
        setTimeout(() => {
          mensajeElement.style.opacity = 1;
        }, 10);
      });
  }
}

// NUEVA FUNCIÓN: Para ver los datos de un profesional en un modal.
function ver_datos(id) {
  fetch("modulos/administracion/profesionales/ver_datos.php?id=" + id)
    .then((response) => {
      if (!response.ok) {
        throw new Error("Network response was not ok " + response.statusText);
      }
      return response.text();
    })
    .then((dato) => {
      const modalBody = document.querySelector("#verDatosModal .modal-body");
      if (modalBody) {
        modalBody.innerHTML = dato; // Inserta el contenido en el cuerpo del modal.
        // Para mostrar el modal, asumiendo que Bootstrap JS está cargado
        const verDatosModal = new bootstrap.Modal(
          document.getElementById("verDatosModal")
        );
        verDatosModal.show(); // Muestra el modal.
      } else {
        console.error("Elemento .modal-body no encontrado en #verDatosModal.");
      }
    })
    .catch((error) => {
      console.error("Fetch Error loading ver_datos: ", error);
      const mensajeElement = document.getElementById("mensaje");
      mensajeElement.style.display = "block";
      mensajeElement.innerHTML =
        '<div class="alert alert-danger" role="alert"><button type="button" class="close" data-dismiss="alert">&times;</button><i class="fas fa-exclamation-triangle"></i> Error al cargar los datos: ' +
        error.message +
        "</div>";
      mensajeElement.style.opacity = 0;
      mensajeElement.style.transition = "opacity 0.6s ease-in-out";
      setTimeout(() => {
        mensajeElement.style.opacity = 1;
      }, 10);
    });
}
