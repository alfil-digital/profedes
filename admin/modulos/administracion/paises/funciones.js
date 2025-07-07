function editar(id) {
  $('html').animate({
    scrollTop: $("html").offset().top
  }, 0);
  $.get("modulos/administracion/paises/formulario.php?id=" + id, function (dato) {
    $("#formulario").css('display', 'block');
    $("#formulario").html(dato);
    $('#formulario').fadeIn('slow');
  });
}


function cerrar_formulario() {
  $("#formulario").css('display', 'none');
  $("#formulario").html('');
  $('#formulario').fadeOut('slow');
}

function listado() {
  $('html').animate({
    scrollTop: $("html").offset().top
  }, 0);
  $.get("modulos/administracion/paises/listado.php", function (dato) {
    $("#listado").css('display', 'block');
    $("#listado").html(dato);
    $('#listado').fadeIn('slow');

    $('#dataTable').DataTable({
      language: {
        "sLengthMenu": "Mostrar _MENU_ registros",
        "sProcessing": "Procesando...",
        "sZeroRecords": "No se encontraron resultados",
        "sEmptyTable": "Ningún dato disponible en esta tabla",
        "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
        "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
        "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
        "sInfoPostFix": "",
        "sSearch": "Buscar:",
        "sUrl": "",
        "sInfoThousands": ",",
        "sLoadingRecords": "Cargando...",
        "oPaginate": {
          "sFirst": "Primero",
          "sLast": "Último",
          "sNext": "Siguiente",
          "sPrevious": "Anterior"
        }
      }
    });
  });
}

function validar_formulario() {
  if ($("#descripcion").val().length < 2) {
    $("#descripcion").focus();
    return 0;
  }

} 

function guardar() {
  if (validar_formulario() == 0) {
    $("#formulario").addClass('was-validated');
    return;
  }
  $.confirm({
    title: 'Guardar!',
    content: 'Desea <b>Guardar</b> el registro del Pais?',
    icon: 'far fa-question-circle',
    animation: 'scale',
    closeAnimation: 'scale',
    opacity: 0.5,
    buttons: {
      'confirm': {
        text: 'SI',
        btnClass: 'btn-green',
        action: function () {
          $.post("modulos/administracion/paises/controlador.php?f=editar",
            $("#form").serialize(),
            function (dato) {
              $("#mensaje").css('display', 'block');
              $("#mensaje").html(dato);
              $('#mensaje').fadeIn('slow');
              listado();
            });
        }
      },
      NO: {
        btnClass: 'btn-red',
        action: function () { }
      }
    }
  });
}

function eliminar(id) {
  $.confirm({
    title: 'Confirmar Acción',
    content: 'Desea eliminar este pais? Esta acción no se puede deshacer.',
    icon: 'far fa-question-circle',
    animation: 'scale',
    closeAnimation: 'scale',
    opacity: 0.5,
    buttons: {
      confirm: {
        text: 'SI',
        btnClass: 'btn-green',
        action: function () {
          $.post("modulos/administracion/paises/controlador.php?f=eliminar",
            { id: id },
            function (dato) {
              $("#mensaje").css('display', 'block');
              $("#mensaje").html(dato);
              $('#mensaje').fadeIn('slow');
              listado();
              cerrar_formulario();
            });
        }
      },
      cancel: {
        text: 'NO',
        btnClass: 'btn-red',
        action: function () { }
      }
    }
  });
}

function ver_datos(id) {
  $.get("modulos/administracion/paises/ver_datos.php",
    { id: id },
    function (data) {
      $("#modalDatosContent").html(data);
      $("#modalDatos").modal('show');
    }
  );
}