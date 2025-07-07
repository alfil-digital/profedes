// funciones.js para el módulo de matrículas (ejecutadas generalmente dentro del contexto del profesional)

function cargarMatriculasProfesional(profesional_id) {
    // Carga el listado de matrículas para un profesional específico
    $.get("modulos/administracion/matriculas/listado.php?profesional_id=" + profesional_id, function (dato) {
        $("#matriculas-list-container").html(dato); // Asumiendo que este contenedor existe en profesionales/formulario.php
        // La inicialización de DataTable para #dataTableMatriculas se maneja dentro de listado.php
    }).fail(function(xhr, status, error) {
        console.error("Error al cargar matrículas para el profesional " + profesional_id + ": " + status, error, xhr);
        $("#mensaje").css('display', 'block');
        $("#mensaje").html('<div class="alert alert-danger" role="alert"><button type="button" class="close" data-dismiss="alert">&times;</button><i class="fas fa-exclamation-triangle"></i> Error al cargar matrículas: ' + status + '</div>');
        $('#mensaje').fadeIn('slow');
    });
}

function agregarMatricula(profesional_id) {
    // Abre el formulario para añadir una nueva matrícula a un profesional
    // Muestra el formulario en un contenedor específico (por ejemplo, dentro del listado de matrículas)
    $.get("modulos/administracion/matriculas/formulario.php?profesional_id=" + profesional_id, function (dato) {
        $("#formulario-matricula-container").html(dato);
        $("#formulario-matricula-container").fadeIn('slow');
    }).fail(function(xhr, status, error) {
        console.error("Error al cargar formulario de nueva matrícula: " + status, error, xhr);
    });
}

function editarMatricula(id_matricula, profesional_id) {
    // Abre el formulario para editar una matrícula existente
    $.get("modulos/administracion/matriculas/formulario.php?id=" + id_matricula + "&profesional_id=" + profesional_id, function (dato) {
        $("#formulario-matricula-container").html(dato);
        $("#formulario-matricula-container").fadeIn('slow');
    }).fail(function(xhr, status, error) {
        console.error("Error al cargar formulario de edición de matrícula: " + status, error, xhr);
    });
}

function guardarMatricula() {
    // Valida y guarda el formulario de matrícula
    var form = document.getElementById('form_matricula'); // Usa el ID específico del formulario de matrícula
    if (!form.checkValidity()) {
        form.classList.add('was-validated');
        return;
    }

    $.confirm({
        title: 'Guardar Matrícula!',
        content: 'Desea <b>Guardar</b> los datos de la Matrícula?',
        icon: 'far fa-question-circle',
        animation: 'scale',
        closeAnimation: 'scale',
        opacity: 0.5,
        buttons: {
            'confirm': {
                text: 'SI',
                btnClass: 'btn-green',
                action: function () {
                    var formData = new FormData(form); // Usa el formulario específico
                    $.ajax({
                        type: "POST",
                        url: "modulos/administracion/matriculas/controlador.php?f=editar",
                        data: formData,
                        cache: false,
                        contentType: false,
                        processData: false,
                        success: function (dato) {
                            $("#mensaje").css('display', 'block');
                            $("#mensaje").html(dato);
                            $('#mensaje').fadeIn('slow');
                            // El controlador PHP ya llama a cargarMatriculasProfesional()
                            // y cerrar_formulario_matricula() si el guardado es exitoso.
                        },
                        error: function(xhr, status, error) {
                            $("#mensaje").css('display', 'block');
                            $("#mensaje").html('<div class="alert alert-danger" role="alert"><button type="button" class="close" data-dismiss="alert">&times;</button><i class="fas fa-exclamation-triangle"></i> Error en la comunicación con el servidor: ' + error + '</div>');
                            $('#mensaje').fadeIn('slow');
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

function eliminarMatricula(id_matricula, profesional_id) {
    $.confirm({
        title: 'Confirmar Eliminación',
        content: 'Desea eliminar esta matrícula? Esta acción no se puede deshacer.',
        icon: 'far fa-question-circle',
        animation: 'scale',
        closeAnimation: 'scale',
        opacity: 0.5,
        buttons: {
            confirm: {
                text: 'SI',
                btnClass: 'btn-green',
                action: function () {
                    $.post("modulos/administracion/matriculas/controlador.php?f=eliminar",
                        { id: id_matricula, profesional_id: profesional_id }, // Pasa profesional_id para refrescar
                        function (dato) {
                            $("#mensaje").css('display', 'block');
                            $("#mensaje").html(dato);
                            $('#mensaje').fadeIn('slow');
                            // El controlador PHP ya llama a cargarMatriculasProfesional()
                        }
                    ).fail(function(xhr, status, error) {
                        $("#mensaje").css('display', 'block');
                        $("#mensaje").html('<div class="alert alert-danger" role="alert"><button type="button" class="close" data-dismiss="alert">&times;</button><i class="fas fa-exclamation-triangle"></i> Error en la comunicación con el servidor: ' + error + '</div>');
                        $('#mensaje').fadeIn('slow');
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

function cerrar_formulario_matricula() {
  $("#formulario-matricula-container").html(''); // Limpia el contenido del formulario
  $("#formulario-matricula-container").fadeOut('slow'); // Oculta el contenedor del formulario
}

// Puedes añadir aquí funciones para gestionar matricula_estado si lo necesitas (sería un submódulo similar):
// function verEstadosMatricula(matricula_id) { ... }
// function agregarEstadoMatricula(matricula_id) { ... }
// etc.