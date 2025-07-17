//docedes-plantillas/plantilla/modulos/estructura/profesionales/funciones.js
function editar(id) {
  $('html').animate({
    scrollTop: $("html").offset().top
  }, 0);
  $.get("modulos/estructura/profesionales/formulario.php?id=" + id, function (dato) {
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
  $.get("modulos/estructura/profesionales/listado.php", function (dato) {
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
  if ($("#nombre").val().length < 2) {
    $("#nombre").focus();
    return 0;
  }
  
  if ($("#apellido").val().length < 2) {
    $("#apellido").focus();
    return 0;
  }
  if ($("#dni").val().length < 7) {
    $("#dni").focus();
    return 0;
  }
  if ($("#localidad_id option:selected").text() === 'Seleccionar') {
    $("#localidad_id").focus();
    return 0;
  }
  
  if ($("#entidad_id option:selected").text() === 'Seleccionar') {
    $("#entidad_id").focus();
    return 0;
  }


}

// function validar_email(email) {
//   var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
//   return regex.test(email);
// }

function guardar(id) {

  if (validar_formulario() == 0) {
    $("#form").addClass('was-validated');
    return;
  }

  $.confirm({
    title: 'Guardar!',
    content: 'Desea <b>Guardar</b> el <b> Registro</b>?',
    icon: 'far fa-question-circle',
    animation: 'scale',
    closeAnimation: 'scale',
    opacity: 0.5,
    buttons: {
      'confirm': {
        text: 'SI',
        btnClass: 'btn-green',
        action: function () {
          
          var formData = new FormData(document.getElementById("form"));
          $.ajax({
            type: "POST",
            url: "modulos/estructura/profesionales/controlador.php?f=editar&id=" + id,
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: function (dato) {
              $("#mensaje").css('display', 'block');
              $("#mensaje").html(dato);
              $('#mensaje').fadeIn('slow');
              listado();
              cerrar_formulario();
            }
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

function eliminar(id) {
  $.confirm({
    title: 'Confirmar Acción',
    content: 'Desea eliminar esta persona? Esta acción no se puede deshacer.',
    icon: 'far fa-question-circle',
    animation: 'scale',
    closeAnimation: 'scale',
    opacity: 0.5,
    buttons: {
      confirm: {
        text: 'SI',
        btnClass: 'btn-green',
        action: function () {
          $.post("modulos/estructura/profesionales/controlador.php?f=eliminar",
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
  // Esta línea define una función llamada "ver_datos" que acepta un parámetro "id"
  // El "id" representa el identificador único de la persona cuyos datos queremos ver

  $.get("modulos/estructura/profesionales/ver_datos.php", {
    // Aquí se realiza una petición AJAX utilizando el método GET de jQuery
    // El primer parámetro es la URL del archivo PHP que procesará la solicitud
    // Este archivo "ver_datos.php" es el encargado de recuperar la información detallada
    // de la persona desde la base de datos

    id: id
    // Este es el segundo parámetro, que son los datos enviados al servidor
    // Se envía el ID de la persona como parte de la solicitud
    // En PHP, esto se recibiría como $_GET['id']

  }, function (data) {
    // Este es el tercer parámetro: una función callback que se ejecuta 
    // cuando la solicitud AJAX se completa exitosamente
    // El parámetro "data" contiene la respuesta del servidor (el HTML generado por ver_datos.php)

    $("#modalDatosContent").html(data);
    // Esta línea toma el contenido HTML recibido del servidor y lo inserta
    // dentro del elemento con ID "modalDatosContent" (que es el cuerpo del modal)

    $("#modalDatos").modal('show');
    // Esta línea muestra el modal utilizando la función modal() de Bootstrap
    // El modal es el elemento con ID "modalDatos"
  });
}


function verificarMatricula(t) {
  let matricula = t.value;
  if (matricula.length < 6) {
    $("#mensaje_matricula").html('<div class="alert alert-danger">La matrícula debe tener al menos 5 caracteres.</div>');
    return;
  }
  console.log("Verificando matrícula:", matricula);
  
  // $.get("modulos/estructura/profesionales/controlador.php", { matricula: matricula }, function (data) {
  //   if (data === 'true') {
      $("#mensaje_matricula").html('<div class="alert alert-success">La matrícula es válida.</div>');
  //   } else {
  //     $("#mensaje_matricula").html('<div class="alert alert-danger">La matrícula ya está en uso o no es válida.</div>');
  //   }
  // });
}

function generar_matricula(){
  // genero el post para generar la matricula
  $.post("modulos/estructura/profesionales/controlador.php?f=generar_matricula",function (dato) {
    $("#mensaje").css('display', 'block');
    $("#mensaje").html(dato);
    $('#mensaje').fadeIn('slow');
    listado();
    cerrar_formulario();
  });
}