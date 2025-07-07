
function editar(id) {
    $('html').animate({
        scrollTop: $("html").offset().top
    }, 0);
    $.get("modulos/web/novedades/formulario.php?id=" + id, function (dato) {
        $("#formulario").css('display', 'block');
        $("#formulario").html(dato);
        $('#formulario').fadeIn('slow');
    });
}

// Ejecuto el script para validar el formulario
function guardar(id) {

    // Validar el formulario
    var form = document.getElementById('form'); 
    var editor = document.getElementById('cuerpo'); 

    editor.value = window.editor.getData(); // Obtener el contenido del editor CKEditor
    
    if (form.checkValidity()) {
        // Si el formulario es válido, enviar los datos
        var formData = new FormData(form);
        formData.append('cuerpo', editor.value); // Agregar el contenido del editor al FormData
        $.ajax({
            url: 'modulos/web/novedades/controlador.php?f=guardar',
            type: 'POST',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: function (response) {
                console.log(response);
                
                $('#mensaje').html(response);
                $('#mensaje').show().fadeOut(3000);
                listado();
            }
        });
    } else {
        // Si el formulario no es válido, mostrar mensaje de error
        alert('Por favor complete todos los campos obligatorios.');
    }
}
// Cerrar el formulario
function cerrar_formulario() {
    $('#formulario').hide();
    $('#listado').show();
    $('#mensaje').hide();
}
// Listar los datos
function listado() {
    $.ajax({
        url: 'modulos/web/novedades/listado.php',
        type: 'POST',
        success: function (response) {
            $('#listado').html(response);
            $('#listado').show();
            $('#formulario').hide();
            // $('#mensaje').hide();
        }
    });
}

function cargar_editor() {

    const {
        ClassicEditor,
        Essentials,
        Paragraph,
        Bold,
        Italic,
        Font
    } = CKEDITOR;
    // Create a free account and get <YOUR_LICENSE_KEY>
    // https://portal.ckeditor.com/checkout?plan=free
    ClassicEditor
        .create(document.querySelector('#cuerpo'), {
            licenseKey: 'GPL',
            plugins: [Essentials, Paragraph, Bold, Italic, Font],
            toolbar: {
                items: [
                    'undo', 'redo', '|',
                    'bold', 'italic', '|',
                    'fontSize', 'fontFamily', 'fontColor', 'fontBackgroundColor'
                ]
            },
            fontSize: {
                options: [9, 11, 13, 'default', 17, 19, 21]
            },
            fontFamily: {
                options: [
                    'default',
                    'Arial, Helvetica, sans-serif',
                    'Courier New, Courier, monospace',
                    'Georgia, serif',
                    'Lucida Sans Unicode, Lucida Grande, sans-serif',
                    'Tahoma, Geneva, sans-serif',
                    'Times New Roman, Times, serif',
                    'Trebuchet MS, Helvetica, sans-serif',
                    'Verdana, Geneva, sans-serif'
                ]
            },
            fontColor: {
                columns: 5,
                documentColors: 10
            },
            fontBackgroundColor: {
                columns: 5,
                documentColors: 10
            }
            
            // toolbar: [
            //     'undo', 'redo', '|', 'bold', 'italic', '|',
            //     'fontSize', 'fontFamily', 'fontColor', 'fontBackgroundColor'
            // ]
        })
        .then(editor => {
            window.editor = editor;
        })
        .catch(error => {
            console.error(error);
        });

}

function publicar(id, publicado_db) {
    $.ajax({
        url: 'modulos/web/novedades/controlador.php?f=publicar&id=' + id + '&publicado_db=' + publicado_db,
        type: 'POST',
        success: function (response) {

            console.log(response);

            $('#mensaje').html(response);
            $('#mensaje').show().fadeOut(3000);
            listado();
        }
    });
}