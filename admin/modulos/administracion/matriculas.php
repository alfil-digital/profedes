<script type="text/javascript" src="modulos/administracion/matriculas/funciones.js"></script>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <h1 class="h3 mb-0 text-gray-800">Matrículas</h1>
</div>

<div id="listado">
  </div>

<div id="formulario" style="display:none;">
  </div>

<div id="mensaje" style="display:none;"></div>

<script>
// Función para cargar el listado general de matrículas
// Asumiendo que el listado.php puede funcionar sin un profesional_id si queremos ver todas las matrículas
$(document).ready(function() {
    listadoMatriculasGeneral(); // Llama a una función para cargar la lista general
});

function listadoMatriculasGeneral() {
    $("html").animate(
        {
            scrollTop: $("html").offset().top,
        },
        0
    );
    $.get("modulos/administracion/matriculas/listado.php", function (dato) {
        $("#listado").css('display', 'block');
        $("#listado").html(dato);
        $('#listado').fadeIn('slow'); // FadeIn para la lista principal
    }).fail(function(xhr, status, error) {
        console.error("AJAX Error loading general matriculas list: " + status, error, xhr);
        $("#mensaje").css('display', 'block');
        $("#mensaje").html('<div class="alert alert-danger" role="alert"><button type="button" class="close" data-dismiss="alert">&times;</button><i class="fas fa-exclamation-triangle"></i> Error al cargar el listado general de matrículas: ' + status + '</div>');
        $('#mensaje').fadeIn('slow');
    });
}

// Puedes añadir aquí funciones para abrir el formulario de nueva matrícula
// Si quieres que el botón "Nueva Matrícula" en esta vista general abra el formulario principal
// Esto sería distinto de agregarMatricula() que es específica para un profesional_id
function nuevaMatriculaGeneral() {
    // Abrir un formulario vacío, quizás para una matrícula sin asignar a un profesional al inicio
    // O tal vez con un selector de profesional
    $.get("modulos/administracion/matriculas/formulario.php", function (dato) {
        $("#formulario").html(dato);
        $("#formulario").fadeIn('slow');
    });
}
</script>