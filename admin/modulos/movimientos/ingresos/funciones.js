function editar(id) {
  $('html').animate({
    scrollTop: $("html").offset().top
  }, 0);
  $.get("modulos/movimientos/ingresos/formulario.php?id=" + id, function (dato) {
    $("#formulario").css('display', 'block');
    $("#formulario").html(dato);
    $('#formulario').fadeIn('slow');
  });
}

/* function cargar_persona(entidad) {

  $.get("modulos/movimientos/ingresos/controlador.php?f=cargar_persona&entidad=" + entidad, function (dato) {
    console.log(dato);
    var persona = JSON.parse(dato);
    var select = document.getElementById('persona_id');


    if (persona) {

      Object.keys(persona).forEach(function (elm) {
        var option = document.createElement("option");
        option.text = 'asdfasdfasd';
        select.add(option);
      })
    } else {
      select.innerHTML = '<option selected disabled value="">Seleccionar</option>';

    }


    $("#formulario").html(dato);
    $('#formulario').fadeIn('slow');
  });
} */

function cargarPersonas(entidadId) {

  // Realiza una solicitud AJAX para obtener las personas de la entidad seleccionada
  if (entidadId) {
    var xhr = new XMLHttpRequest();
    xhr.open("GET", "modulos/movimientos/ingresos/controlador.php?f=cargar_personas&entidad_id=" + entidadId, true);
    xhr.onreadystatechange = function () {
      if (xhr.readyState == 4 && xhr.status == 200) {
        // Actualiza el select de personas con los datos recibidos
        document.getElementById("persona_id").innerHTML = xhr.responseText;
      }
    };
    xhr.send();
  } else {
    // Si no hay entidad seleccionada, limpiar el select de personas
    document.getElementById("persona_id").innerHTML = '<option selected disabled value="">Seleccionar</option>';
  }
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
  $.get("modulos/movimientos/ingresos/listado.php", function (dato) {
    $("#listado").css('display', 'block');
    $("#listado").html(dato);
    $('#listado').fadeIn('slow');

    $('#dataTable').DataTable({

      language: {
        "sLengthMenu": "Mostrar _MENU_ registros",
        "sProcessing": "Procesando...",
        "sZeroRecords": "No se encontraron resultados",
        "sEmptyTable": "Ningún dato disponible en esta tabla =(",
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

  if ($("#usuario").val().length < 3) {
    $("#usuario").focus();
    return 0;
  }
  if ($("#nombre_apellido").val().length < 3) {
    $("#nombre_apellido").focus();
    return 0;
  }

  if ($("#grupo_id option:selected").text() === 'Seleccionar') {
    $("#grupo_id").focus();
    return 0;
  }
}


function validar_formulario_detalle() {

  if ($("#monto_detalle").val().length < 3) {
    $("#monto_detalle").focus();
    return 0;
  }
  if ($("#cantidad").val().length < 0) {
    $("#cantidad").focus();
    return 0;
  }

  if ($("#estado_id option:selected").text() === 'Seleccionar') {
    $("#estado_id").focus();
    return 0;
  }
}


function guardar() {

  // alert();
  //   if(validar_formulario()==0){
  //       $("#formulario").addClass('was-validated');
  //       return;
  //   }
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
          //accion
          $.post("modulos/movimientos/ingresos/controlador.php?f=editar", $("#form").serialize(), function (dato) {
            $("#mensaje").css('display', 'block');
            $("#mensaje").html(dato);
            $('#mensaje').fadeIn('slow');
            cerrar_formulario();
            listado();
          });
          //fin de accion
        }
      },
      NO: {
        btnClass: 'btn-red',
        action: function () {
          //$.alert('hiciste clic en <strong>NO</strong>');
        }
      },
    }
  });
}

/* // Función para validar el formulario antes de guardar
function guardar() {
  // Obtiene el formulario
  const form = document.getElementById('form');
  
  // Añade la clase 'was-validated' para activar los estilos de validación de Bootstrap
  form.classList.add('was-validated');
  
  // Verifica si el formulario es válido
  if (!form.checkValidity()) {
    // Si no es válido, detiene la ejecución y muestra los mensajes de error
    event.preventDefault();
    event.stopPropagation();
    return false;
  }
  
  // Si el formulario es válido, recopila todos los datos para enviar
  const formData = new FormData(form);
  
  // Realiza la petición AJAX para guardar los datos
  fetch('guardar_egreso.php', {
    method: 'POST',
    body: formData
  })
  .then(response => response.text())
  .then(data => {
    // Muestra la respuesta en el div de resultados
    document.getElementById('resultado').innerHTML = data;
  })
  .catch(error => {
    console.error('Error:', error);
    document.getElementById('resultado').innerHTML = '<div class="alert alert-danger">Error al procesar la solicitud</div>';
  });
}

// Función para cerrar el formulario y volver al listado
function cerrar_formulario() {
  // Llama a la función que carga el listado de egresos
  listado();
} */


function eliminar(id) {
  $.confirm({
    title: 'Confirmar Acción',
    content: 'Desea eliminar el registro?',
    icon: 'far fa-question-circle',
    animation: 'scale',
    closeAnimation: 'scale',
    opacity: 0.5,
    buttons: {
      confirm: {
        text: 'SI',
        btnClass: 'btn-green',
        action: function () {
          //accion de eliminar
          $.post("modulos/movimientos/ingresos/controlador.php?f=eliminar", { id: id }, function (dato) {
            $("#mensaje").css('display', 'block');
            $("#mensaje").html(dato);
            $('#mensaje').fadeIn('slow');
            listado();
            cerrar_formulario();
          });
          //fin de accion eliminar
        }
      },
      cancel: {
        text: 'NO',
        btnClass: 'btn-red',
        action: function () {

        }
      }
    }
  });
}


function agregarDetalles(id, detalle_id = 0) {

  $('html').animate({
    scrollTop: $("html").offset().top
  }, 0);
  $.get("modulos/movimientos/ingresos/formulario_detalles.php?id=" + id + '&detalle_id= ' + detalle_id, function (dato) {
    $("#formulario").css('display', 'block');
    $("#formulario").html(dato);
    $('#formulario').fadeIn('slow');
  });

}


function agregar_detalle() {

  if (validar_formulario_detalle() == 0) {
    $("#formulario").addClass('was-validated');
    return;
  }
  $.confirm({
    title: 'Agregar!',
    content: 'Desea <b>Agregar</b> el <b> Registro</b>?',
    icon: 'far fa-question-circle',
    animation: 'scale',
    closeAnimation: 'scale',
    opacity: 0.5,
    buttons: {
      'confirm': {
        text: 'SI',
        btnClass: 'btn-green',
        action: function () {
          //accion
          $.post("modulos/movimientos/ingresos/controlador.php?f=editar_detalle", $("#form").serialize(), function (dato) {
            $("#mensaje").css('display', 'block');
            $("#mensaje").html(dato);
            $('#mensaje').fadeIn('slow');
            cerrar_formulario();
            listado();
          });
          //fin de accion
        }
      },
      NO: {
        btnClass: 'btn-red',
        action: function () {
          //$.alert('hiciste clic en <strong>NO</strong>');
        }
      },
    }
  });
}