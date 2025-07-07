// Este archivo contiene funciones JS para manejar operaciones CRUD en items

function editar(id) {
    $('html').animate({
        scrollTop: $("html").offset().top
    }, 0);
    // Solicitud GET a formulario.php con el ID del ítem a editar
    // Esta función es llamada cuando se hace clic en el botón editar en listado.php
    $.get("modulos/administracion/transacciones/formulario.php?id=" + id, function (dato) {
        // Recibe el HTML del formulario desde formulario.php y lo muestra
        $("#formulario").css('display', 'block');
        $("#formulario").html(dato);
        $('#formulario').fadeIn('slow');
    });
}

function cerrar_formulario() {
    // Esta función es llamada cuando se hace clic en "Cancelar" en el formulario
    // También es llamada por el controlador después de guardar exitosamente
    $("#formulario").css('display', 'none');
    $("#formulario").html('');
    $('#formulario').fadeOut('slow');
}

function listado() {
    $('html').animate({
        scrollTop: $("html").offset().top
    }, 0);
    // Solicitud GET a listado.php para cargar la tabla de items
    // Esta es la función principal que inicia el flujo de la aplicación
    $.get("modulos/administracion/transacciones/listado.php", function (dato) {
        // Recibe el HTML de la tabla de listado y lo muestra
        $("#listado").css('display', 'block');
        $("#listado").html(dato);
        $('#listado').fadeIn('slow');

        // Inicializa DataTables en la tabla recibida
        $('#dataTable').DataTable({
            order: [[3, 'asc'], [4, 'asc']],
            language: {
                // Configuración de idioma español para DataTables
                "sLengthMenu": "Mostrar _MENU_ registros",
                // ...otros textos localizados...
            }
        });
    });
}


function validar_formulario() {
    // Función para validar los campos del formulario antes de enviar
    // Es llamada por la función guardar()

    if ($("#descripcion").val().length < 3) {
        $("#descripcion").focus();
        return 0;
    }
    if ($("#enlace").val().length < 3) {
        $("#enlace").focus();
        return 0;
    }

    if ($("#opcion_id option:selected").text() === 'Seleccionar') {
        $("#opcion_id").focus();
        return 0;
    }

    var orden = parseInt($("#orden").val());
    if (isNaN(orden)) {
        $("#orden").focus();
        return 0;
    }
}

function guardar() {
    // Función ejecutada al hacer clic en el botón "Guardar" del formulario
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
                    //accion

                    // Envía datos del formulario mediante POST al controlador con f=editar
                    // El controlador recibirá este parámetro mediante $_GET['f']
                    $.post("modulos/administracion/transacciones/controlador.php?f=editar", $("#form").serialize(), function (dato) {
                        // Muestra el mensaje de respuesta del controlador
                        $("#mensaje").css('display', 'block');
                        $("#mensaje").html(dato);
                        $('#mensaje').fadeIn('slow');
                        // Recarga el listado para mostrar los cambios
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


function eliminar(id) {
    // Función ejecutada al hacer clic en el botón "Eliminar" en listado.php
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
                    // Envía el ID mediante POST al controlador con f=eliminar
                    // El controlador recibirá este parámetro mediante $_GET['f']
                    $.post("modulos/administracion/transacciones/controlador.php?f=eliminar", { id: id }, function (dato) {
                        // Muestra el mensaje de respuesta del controlador
                        $("#mensaje").css('display', 'block');
                        $("#mensaje").html(dato);
                        $('#mensaje').fadeIn('slow');
                        // Recarga el listado para reflejar los cambios
                        listado();
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