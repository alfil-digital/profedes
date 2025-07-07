// Función para abrir el formulario de edición/creación de un movimiento.
// 'id' es el ID del movimiento (0 para nuevo, >0 para editar).
function editar(id) {
  $("html").animate(
    {
      scrollTop: $("html").offset().top,
    },
    0
  ); // Desplaza la página al inicio para que el formulario sea visible.
  // Realiza una petición GET al formulario.php para cargar su contenido.
  // Se le pasa el ID del movimiento y el tipo de movimiento (ingreso/egreso) para que el formulario sepa qué cargar.
  // Asumo que el tipo de movimiento se pasa desde el botón que llama a 'editar'.
  // Por ahora, lo dejo dinámico para que el formulario_detalles lo reciba y lo maneje.
  // Ejemplo: editar(0, 'egreso') o editar(123, 'ingreso')
  // Para este ejemplo, lo dejo como antes, pero el formulario.php debe recibir el 'tipo'.
  // Para mantener compatibilidad con el listado actual, vamos a enviar el tipo en el GET.
  // Si no se pasa el tipo, se asume 'egreso'.
  let url = "modulos/movimientos/egresos/formulario.php?id=" + id;
  // Si necesitas pasar el tipo de movimiento desde el UI (ej: un botón para 'nuevo ingreso' vs 'nuevo egreso'),
  // el parámetro 'tipo' debería ser enviado aquí. Por ahora, 'formulario.php' lo gestiona desde la URL.
  // Si id es 0, no se está editando, entonces el tipo_movimiento es el que se desea crear.
  // Si id > 0, el tipo de movimiento se carga desde la DB en formulario.php.
  if (id === 0) {
    // Si se crea un nuevo movimiento, y esta función es genérica para ingresos/egresos,
    // se necesita saber si es un ingreso o egreso. Esto se debería pasar como un segundo argumento.
    // Ejemplo: editar(0, 'ingreso')
    // Por ahora, mantenemos la URL original y dejamos que formulario.php maneje el tipo inicial.
    // Si la llamada es desde 'Nuevo Egreso', la URL sería '.../formulario.php?id=0&tipo=egreso'
    // Si la llamada es desde 'Nuevo Ingreso', la URL sería '.../formulario.php?id=0&tipo=ingreso'
    // Por ahora, asumimos que este 'editar' se llama para egresos, y el formulario cargará el tipo desde la URL.
    // Para que el formulario.php sepa si es un ingreso o egreso al crearlo,
    // este valor de 'tipo' debe pasarse aquí.
    // Ejemplo: let tipo = (current_page_is_ingresos) ? 'ingreso' : 'egreso';
    // url += "&tipo=" + tipo; // Esto requeriría cambiar cómo se llama 'editar()'
  }

  $.get(url, function (dato) {
    $("#formulario").css("display", "block"); // Muestra el div del formulario.
    $("#formulario").html(dato); // Carga el contenido del formulario en el div.
    $("#formulario").fadeIn("slow"); // Aplica un efecto de desvanecimiento.
  });
}

// Función para cargar personas o proveedores en el select 'persona_id'
// dependiendo de la 'entidadId' seleccionada.
function cargarPersonas(entidadId) {
  // Realiza una solicitud AJAX para obtener las personas/proveedores de la entidad seleccionada.
  if (entidadId) {
    var xhr = new XMLHttpRequest();
    // La URL llama al controlador.php con la función 'cargar_personas' y el ID de la entidad.
    xhr.open(
      "GET",
      "modulos/movimientos/egresos/controlador.php?f=cargar_personas&entidad_id=" +
        entidadId,
      true
    );
    xhr.onreadystatechange = function () {
      if (xhr.readyState == 4 && xhr.status == 200) {
        // Actualiza el select de personas con los datos recibidos (opciones HTML).
        document.getElementById("persona_id").innerHTML = xhr.responseText;
      }
    };
    xhr.send();
  } else {
    // Si no hay entidad seleccionada, limpia el select de personas.
    document.getElementById("persona_id").innerHTML =
      '<option selected disabled value="">Seleccionar</option>';
  }
}

// Función para cerrar el formulario actual.
function cerrar_formulario() {
  $("#formulario").css("display", "none"); // Oculta el div del formulario.
  $("#formulario").html(""); // Limpia el contenido del div.
  $("#formulario").fadeOut("slow"); // Aplica un efecto de desvanecimiento.
  listado(); // Vuelve a cargar el listado para reflejar los cambios.
}

// Función para cargar el listado de movimientos.
function listado() {
  $("html").animate(
    {
      scrollTop: $("html").offset().top,
    },
    0
  ); // Desplaza la página al inicio.
  // Realiza una petición GET al listado.php para cargar su contenido.
  $.get("modulos/movimientos/egresos/listado.php", function (dato) {
    $("#listado").css("display", "block"); // Muestra el div del listado.
    $("#listado").html(dato); // Carga el contenido del listado.
    $("#listado").fadeIn("slow"); // Aplica un efecto de desvanecimiento.

    // Inicializa la tabla de datos (DataTable) con opciones de idioma.
    $("#dataTable").DataTable({
      language: {
        sLengthMenu: "Mostrar _MENU_ registros",
        sProcessing: "Procesando...",
        sZeroRecords: "No se encontraron resultados",
        sEmptyTable: "Ningún dato disponible en esta tabla =(",
        sInfo:
          "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
        sInfoEmpty: "Mostrando registros del 0 al 0 de un total de 0 registros",
        sInfoFiltered: "(filtrado de un total de _MAX_ registros)",
        sInfoPostFix: "",
        sSearch: "Buscar:",
        sUrl: "",
        sInfoThousands: ",",
        sLoadingRecords: "Cargando...",
        oPaginate: {
          sFirst: "Primero",
          sLast: "Último",
          sNext: "Siguiente",
          sPrevious: "Anterior",
        },
      },
    });
  });
}

// Función para validar el formulario principal (movimientos).
// (Este es un ejemplo de validación simple en JS, Bootstrap ya maneja 'needs-validation').
function validar_formulario() {
  // Aquí puedes agregar validaciones personalizadas más allá de las de HTML5/Bootstrap.
  // Por ejemplo, verificar formatos de fecha, números, etc.
  // Por ahora, se mantiene la estructura básica.
  const form = document.getElementById("form");
  if (!form.checkValidity()) {
    form.classList.add("was-validated"); // Muestra los mensajes de validación de Bootstrap.
    return false; // El formulario no es válido.
  }
  return true; // El formulario es válido.
}

// Función para validar el formulario de detalles de movimientos.
function validar_formulario_detalle() {
  // Validaciones básicas para monto y cantidad.
  if (parseFloat($("#monto_detalle").val()) <= 0) {
    // Verifica que el monto sea mayor que 0.
    $("#monto_detalle").focus();
    return false;
  }
  if (parseFloat($("#cantidad").val()) <= 0) {
    // Verifica que la cantidad sea mayor que 0.
    $("#cantidad").focus();
    return false;
  }
  // No hay un campo 'estado_id' en el detalle ahora, así que se elimina esta validación.
  /*
  if ($("#estado_id option:selected").text() === 'Seleccionar') {
    $("#estado_id").focus();
    return false;
  }
  */
  // Otras validaciones HTML5/Bootstrap se manejan con la clase 'was-validated'.
  const form = document.getElementById("form");
  if (!form.checkValidity()) {
    form.classList.add("was-validated");
    return false;
  }
  return true;
}

// Función para guardar el formulario principal (movimientos).
function guardar() {
  if (!validar_formulario()) {
    // Llama a la validación del formulario.
    // Si la validación falla, los mensajes de Bootstrap se muestran.
    return;
  }

  // Muestra una confirmación al usuario antes de guardar.
  $.confirm({
    title: "Guardar!",
    content: "Desea <b>Guardar</b> el <b> Registro</b>?",
    icon: "far fa-question-circle",
    animation: "scale",
    closeAnimation: "scale",
    opacity: 0.5,
    buttons: {
      confirm: {
        text: "SI",
        btnClass: "btn-green",
        action: function () {
          // Envía los datos del formulario al controlador.php (función 'editar').
          $.post(
            "modulos/movimientos/egresos/controlador.php?f=editar",
            $("#form").serialize(),
            function (dato) {
              $("#mensaje").css("display", "block"); // Muestra un div para mensajes.
              $("#mensaje").html(dato); // Muestra el mensaje del servidor.
              $("#mensaje").fadeIn("slow"); // Efecto de desvanecimiento para el mensaje.
              // La función 'editar' en el controlador.php ahora redirige a agregarDetalles()
              // si es un nuevo registro, por lo que no llamamos cerrar_formulario() aquí.
              // Si es edición, se debería cerrar el formulario después de guardar.
              // Para simplificar, dejo la redirección a 'agregarDetalles' en el controlador si es nuevo,
              // y si es edición, solo muestra el mensaje y se cierra.
              // Una forma más robusta sería que el controlador devuelva el ID y un flag de éxito.
            }
          );
        },
      },
      NO: {
        btnClass: "btn-red",
        action: function () {
          // No hace nada si el usuario cancela.
        },
      },
    },
  });
}

// Función para eliminar un movimiento.
function eliminar(id) {
  $.confirm({
    title: "Confirmar Acción",
    content: "Desea eliminar el registro?",
    icon: "far fa-question-circle",
    animation: "scale",
    closeAnimation: "scale",
    opacity: 0.5,
    buttons: {
      confirm: {
        text: "SI",
        btnClass: "btn-green",
        action: function () {
          // Envía la petición de eliminación al controlador.php (función 'eliminar').
          $.post(
            "modulos/movimientos/egresos/controlador.php?f=eliminar",
            { id: id },
            function (dato) {
              $("#mensaje").css("display", "block");
              $("#mensaje").html(dato);
              $("#mensaje").fadeIn("slow");
              listado(); // Recarga el listado para ver los cambios.
              cerrar_formulario(); // Cierra el formulario si estuviera abierto.
            }
          );
        },
      },
      cancel: {
        text: "NO",
        btnClass: "btn-red",
        action: function () {
          // No hace nada.
        },
      },
    },
  });
}

// Función para abrir el formulario de detalles de un movimiento.
// 'id' es el ID del movimiento principal. 'detalle_id' es opcional (0 para nuevo detalle, >0 para editar).
function agregarDetalles(id, detalle_id = 0) {
  $("html").animate(
    {
      scrollTop: $("html").offset().top,
    },
    0
  );
  // Carga el formulario_detalles.php pasando el ID del movimiento y el ID del detalle.
  $.get(
    "modulos/movimientos/egresos/formulario_detalles.php?id=" +
      id +
      "&detalle_id=" +
      detalle_id,
    function (dato) {
      $("#formulario").css("display", "block");
      $("#formulario").html(dato);
      $("#formulario").fadeIn("slow");
      // Para que la tabla de detalles tenga Datatables si aún no la tiene
      if ($.fn.DataTable.isDataTable("#dataTableDetalles")) {
        $("#dataTableDetalles").DataTable().destroy();
      }
      $("#dataTableDetalles").DataTable({
        paging: false, // No paginación para los detalles
        searching: false, // No búsqueda
        info: false, // No info
        language: {
          // Mismos idiomas que el listado principal
          sLengthMenu: "Mostrar _MENU_ registros",
          sProcessing: "Procesando...",
          sZeroRecords: "No se encontraron resultados",
          sEmptyTable: "Ningún dato disponible en esta tabla =(",
          sInfo:
            "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
          sInfoEmpty:
            "Mostrando registros del 0 al 0 de un total de 0 registros",
          sInfoFiltered: "(filtrado de un total de _MAX_ registros)",
          sInfoPostFix: "",
          sSearch: "Buscar:",
          sUrl: "",
          sInfoThousands: ",",
          sLoadingRecords: "Cargando...",
          oPaginate: {
            sFirst: "Primero",
            sLast: "Último",
            sNext: "Siguiente",
            sPrevious: "Anterior",
          },
        },
      });
    }
  );
}

// Función para guardar un detalle de movimiento.
function agregar_detalle() {
  if (!validar_formulario_detalle()) {
    // Llama a la validación del formulario de detalle.
    $("#form").addClass("was-validated"); // Asegura que se muestren los mensajes de validación de Bootstrap.
    return;
  }

  $.confirm({
    title: "Agregar!",
    content: "Desea <b>Agregar</b> el <b> Detalle</b>?",
    icon: "far fa-question-circle",
    animation: "scale",
    closeAnimation: "scale",
    opacity: 0.5,
    buttons: {
      confirm: {
        text: "SI",
        btnClass: "btn-green",
        action: function () {
          // Envía los datos del formulario de detalle al controlador.php (función 'editar_detalle').
          $.post(
            "modulos/movimientos/egresos/controlador.php?f=editar_detalle",
            $("#form").serialize(),
            function (dato) {
              $("#mensaje").css("display", "block");
              $("#mensaje").html(dato);
              $("#mensaje").fadeIn("slow");
              // La función 'editar_detalle' en el controlador.php se encarga de refrescar la tabla de detalles.
              // Aquí no cerramos el formulario principal, solo el de detalles se refresca.
              // cerrar_formulario(); // Esto cerraría el formulario principal, no es lo que queremos.
              // listado(); // Esto recargaría el listado principal, tampoco es lo que queremos.
            }
          );
        },
      },
      NO: {
        btnClass: "btn-red",
        action: function () {
          // No hace nada.
        },
      },
    },
  });
}

// Función para eliminar un detalle específico.
function eliminarDetalle(detalleId, movimientoId) {
  $.confirm({
    title: "Confirmar Eliminación",
    content: "¿Está seguro de que desea eliminar este detalle?",
    icon: "fas fa-trash",
    animation: "scale",
    closeAnimation: "scale",
    opacity: 0.5,
    buttons: {
      confirm: {
        text: "Sí, Eliminar",
        btnClass: "btn-danger",
        action: function () {
          // Envía la petición al controlador para eliminar el detalle.
          $.post(
            "modulos/movimientos/egresos/controlador.php?f=eliminar_detalle",
            { id: detalleId, movimiento_id: movimientoId },
            function (dato) {
              $("#mensaje").css("display", "block");
              $("#mensaje").html(dato);
              $("#mensaje").fadeIn("slow");
              // El controlador llama a agregarDetalles(movimientoId) para refrescar la tabla.
            }
          );
        },
      },
      cancel: {
        text: "No, Cancelar",
        btnClass: "btn-secondary",
        action: function () {
          // No hace nada.
        },
      },
    },
  });
}

// Función para actualizar el monto total del movimiento principal.
// Se llama después de agregar/editar/eliminar un detalle.
function actualizarMontoTotal(movimientoId, callback) {
  $.get(
    "modulos/movimientos/egresos/controlador.php?f=actualizarMontoTotal&movimiento_id=" +
      movimientoId,
    function (data) {
      // console.log("Monto total actualizado:", data); // Para depuración
      if (callback && typeof callback === "function") {
        callback(); // Ejecuta el callback (ej: recargar detalles)
      }
    }
  ).fail(function () {
    // console.error("Error al actualizar monto total.");
    if (callback && typeof callback === "function") {
      callback(); // Llama al callback incluso si hay error para no bloquear
    }
  });
}
