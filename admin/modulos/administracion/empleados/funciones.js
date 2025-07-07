// docedes-plantillas/plantilla/modulos/administracion/empleados/funciones.js

function editar(id) {
  $("html").animate(
    {
      scrollTop: $("html").offset().top,
    },
    0
  );
  $.get(
    "modulos/administracion/empleados/formulario.php?id=" + id,
    function (dato) {
      $("#listado").css("display", "none"); // Ocultar el listado
      $("#formulario").html(dato); // <--- ¡Esta línea es clave!
      $("#formulario").fadeIn("slow"); // Mostrar el formulario
    }
  ).fail(function (xhr, status, error) {
    // ... manejo de errores ...
  });
}

function cerrar_formulario() {
  $("#formulario").css("display", "none");
  $("#formulario").html("");
  $("#formulario").fadeOut("slow");
}

function listado() {
  $("html").animate(
    {
      scrollTop: $("html").offset().top,
    },
    0
  );
  // Cargar el listado de empleados
  $.get("modulos/administracion/empleados/listado.php", function (dato) {
    $("#listado").css("display", "block");
    $("#listado").html(dato);

    // Usar el callback de fadeIn para asegurar que el elemento esté visible
    // y completamente en el DOM antes de intentar inicializar DataTable.
    $("#listado").fadeIn("slow", function () {
      // *** Paso CLAVE: Verificar si ya existe una instancia de DataTable y destruirla ***
      // Esto previene el error de "Cannot reinitialise DataTable"
      if ($.fn.DataTable.isDataTable("#dataTable")) {
        $("#dataTable").DataTable().destroy();
        console.log(
          "Instancia de DataTable existente para Empleados destruida."
        ); // Mensaje para depuración
      }

      // Inicializar DataTables con la configuración de idioma
      $("#dataTable").DataTable({
        language: {
          sLengthMenu: "Mostrar _MENU_ registros",
          sProcessing: "Procesando...",
          sZeroRecords: "No se encontraron resultados",
          sEmptyTable: "Ningún dato disponible en esta tabla",
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
      console.log("DataTable inicializada para Empleados."); // Mensaje para depuración
    }); // Fin del callback de fadeIn
  }).fail(function (xhr, status, error) {
    console.error(
      "AJAX Error loading listado de Empleados: " + status,
      error,
      xhr
    );
    $("#mensaje").css("display", "block");
    $("#mensaje").html(
      '<div class="alert alert-danger" role="alert"><button type="button" class="close" data-dismiss="alert">&times;</button><i class="fas fa-exclamation-triangle"></i> Error al cargar el listado de Empleados: ' +
        status +
        "</div>"
    );
    $("#mensaje").fadeIn("slow");
  });
}

function validar_formulario() {
  var form = document.getElementById("form");
  if (!form.checkValidity()) {
    form.classList.add("was-validated");
    return false;
  }
  return true;
}

function guardar(id) {
  if (!validar_formulario()) {
    return;
  }

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
          var formData = new FormData(document.getElementById("form"));
          $.ajax({
            type: "POST",
            url:
              "modulos/administracion/empleados/controlador.php?f=editar&id=" +
              id,
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: function (dato) {
              $("#mensaje").css("display", "block");
              $("#mensaje").html(dato);
              $("#mensaje").fadeIn("slow");
              listado();
              cerrar_formulario();
            },
            error: function (xhr, status, error) {
              $("#mensaje").css("display", "block");
              $("#mensaje").html(
                '<div class="alert alert-danger" role="alert"><button type="button" class="close" data-dismiss="alert">&times;</button><i class="fas fa-exclamation-triangle"></i> Error en la comunicación con el servidor: ' +
                  error +
                  "</div>"
              );
              $("#mensaje").fadeIn("slow");
            },
          });
        },
      },
      cancel: {
        text: "NO",
        btnClass: "btn-red",
        action: function () {
          /* No hacer nada */
        },
      },
    },
  });
}

function eliminar(id) {
  $.confirm({
    title: "Confirmar Acción",
    content: "Desea eliminar este empleado? Esta acción no se puede deshacer.",
    icon: "far fa-question-circle",
    animation: "scale",
    closeAnimation: "scale",
    opacity: 0.5,
    buttons: {
      confirm: {
        text: "SI",
        btnClass: "btn-green",
        action: function () {
          $.post(
            "modulos/administracion/empleados/controlador.php?f=eliminar",
            { id: id },
            function (dato) {
              $("#mensaje").css("display", "block");
              $("#mensaje").html(dato);
              $("#mensaje").fadeIn("slow");
              listado();
              cerrar_formulario();
            }
          ).fail(function (xhr, status, error) {
            $("#mensaje").css("display", "block");
            $("#mensaje").html(
              '<div class="alert alert-danger" role="alert"><button type="button" class="close" data-dismiss="alert">&times;</button><i class="fas fa-exclamation-triangle"></i> Error en la comunicación con el servidor: ' +
                error +
                "</div>"
            );
            $("#mensaje").fadeIn("slow");
          });
        },
      },
      cancel: {
        text: "NO",
        btnClass: "btn-red",
        action: function () {
          /* No hacer nada */
        },
      },
    },
  });
}

function ver_datos(id) {
  $.get(
    "modulos/administracion/empleados/ver_datos.php",
    {
      id: id,
    },
    function (data) {
      $("#modalDatosContent").html(data);
      $("#modalDatos").modal("show");
    }
  );
}
