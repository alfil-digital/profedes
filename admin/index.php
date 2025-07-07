<!-- Flujo de Trabajo:

Sesión y Configuración:
index.php inicia la sesión (si no se ha iniciado ya).
Incluye env.php y conexion.php para configurar el entorno y la conexión a la base de datos.
Verificación de Autenticación: Al igual que header.php, verifica si $_SESSION['userid'] está establecido. Si no, redirige a login.php usando JavaScript. Esto asegura que index.php y cualquier contenido incluido solo sean accesibles para usuarios autenticados.
Inclusión de Diseño:
Incluye menu.php para la navegación de la barra lateral.
Incluye header.php para la barra de navegación superior.
Incluye footer.php para el pie de página de la aplicación.
Carga Dinámica de Contenido (PHP):
La funcionalidad principal de index.php es cargar dinámicamente diferentes módulos basándose en el parámetro GET pagina en la URL.
Selección de Módulo: Si $_GET['pagina'] está establecido, su valor (que está codificado en base64) se decodifica para obtener la ruta real del módulo (por ejemplo, administracion/usuarios).
Control de Acceso: Se ejecuta una consulta SQL para verificar si el grupo_id del usuario actualmente logueado tiene acceso al item (módulo) solicitado de las tablas grupos_items e items.
Inclusión de Módulo: Si el usuario tiene acceso, se incluye el archivo PHP correspondiente del directorio modulos/ (por ejemplo, include("modulos/administracion/usuarios.php");). Esto inserta efectivamente el contenido de ese módulo directamente en el área de contenido principal de la página index.php.
Resaltado del Menú (jQuery): Después de incluir un módulo, se imprime código JavaScript en la página usando selectores de jQuery ($('#op_...') y $('#...')) para:
Expandir el elemento de menú colapsable correcto en la barra lateral (removeClass('collapse').addClass('collapse show')).
Agregar una clase para resaltar el elemento de menú actualmente seleccionado (addClass('item_seleccionado')).
Acceso Denegado: Si el usuario no tiene permiso para acceder al módulo solicitado, se incluye 403.php en su lugar.
Interactividad del Lado del Cliente (jQuery/JavaScript):
Cambio de Contraseña (cambiar_clave_pass(), validar_pass(), controlar_pass(), cerrar_pass()): Estas funciones están definidas aquí y son cruciales para la funcionalidad de "cambio de contraseña".
cambiar_clave_pass() realiza una solicitud AJAX GET para obtener el formulario de cambio de contraseña.
validar_pass() realiza la validación del lado del cliente de los campos de contraseña.
controlar_pass() realiza una solicitud AJAX POST para enviar el cambio de contraseña, pasando los datos del formulario.
cerrar_pass() oculta la ventana emergente.
Todas estas funciones utilizan extensivamente jQuery para la manipulación del DOM (seleccionando elementos como #popup, #mensaje, #formulario) y para realizar llamadas AJAX ($.get() y $.post()).
Alertas Personalizadas (alertas()): Esta función utiliza la librería jquery-confirm, que se basa en jQuery, para mostrar alertas modales personalizadas y con estilo.
Modal de Cierre de Sesión: El modal de Bootstrap (#logoutModal) utiliza el JavaScript de Bootstrap (que depende de jQuery) para mostrar un cuadro de diálogo de confirmación antes de redirigir a logout.php.
Desplazamiento a la Parte Superior: El botón <a class="scroll-to-top">, combinado con jquery-easing.min.js y sb-admin-2.min.js, proporciona una funcionalidad de desplazamiento suave a la parte superior de la página, que generalmente se implementa utilizando las capacidades de animación de jQuery.
 -->

<?php
// Iniciar la sesión. El '@' suprime cualquier error si la sesión ya está iniciada.
session_start();
// Incluir variables de entorno (por ejemplo, configuración de la base de datos).
include("./core/env.php");
// Incluir el archivo de conexión a la base de datos.
include("./inc/conexion.php");
// Establecer la conexión a la base de datos.
conectar();

// Comprobar si 'userid' NO está establecido en la sesión. Si el usuario no ha iniciado sesión...
if (!isset($_SESSION['userid'])) {
    // Redirigir a 'login.php' usando JavaScript.
    echo "<script>document.location='login.php';</script>";
    // Detener la ejecución adicional del script.
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Inicio</title>

    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">

    <link href="inc/css/sb-admin-2.min.css" rel="stylesheet">
    <link href="inc/css/datatables.min.css" rel="stylesheet">
    <link href="inc/css/estilo.css" rel="stylesheet">

    <script src="vendor/jquery/jquery.min.js"></script>
    <link rel="stylesheet" href="inc/css/jquery-confirm.min.css">

    <script type="text/javascript">
        // Función de alerta personalizada usando jquery-confirm.
        function alertas(msj) {
            $.alert({
                title: 'Alerta!',
                content: msj,
                icon: 'fas fa-bell',
                animation: 'scale',
                closeAnimation: 'scale',
                buttons: {
                    okay: {
                        text: 'OK!',
                        btnClass: 'btn-blue'
                    }
                }
            });
        }

        // Esta función se llama para abrir el formulario de cambio de contraseña en una ventana emergente.
        // Se invoca desde 'header.php' (desplegable de usuario) y 'login.php' (inicio de sesión inicial con contraseña predeterminada).
        function cambiar_clave_pass() {
            // Llamada AJAX de jQuery: Envía una solicitud GET a 'modulos/administracion/cambiar_clave/formulario.php'.
            // La respuesta (formulario HTML) se coloca dentro del div '#popup' y se desvanece para aparecer.
            $.get("modulos/administracion/cambiar_clave/formulario.php", function (dato) {

                $("#popup").html(dato);
                $('#popup').fadeIn('slow');
                return false;
            });
        }

        // Esta función cierra la ventana emergente de cambio de contraseña.
        function cerrar_pass() {

            $('#popup').fadeOut('slow');
            $('.popup-overlay').fadeOut('slow');
        }

        // Esta función valida los campos del formulario de cambio de contraseña.
        function validar_pass() {

            if ($("#clave_actual").val().length < 3) {
                $("#clave_actual").focus();
                return 0;
            }
            if ($("#clave_nueva").val().length < 3) {
                $("#clave_nueva").focus();
                return 0;
            }
            if ($("#clave_nueva_1").val().length < 3) {
                $("#clave_nueva_1").focus();
                return 0;
            }

            if ($("#clave_nueva_1").val() != $("#clave_nueva").val()) {
                alertas("Las claves nuevas no coinciden");
                $("#clave_nueva").focus();
                clave_nueva.value = "";
                clave_nueva_1.value = "";
                return 0;
            }
        }

        // Esta función maneja el envío del formulario de cambio de contraseña.
        function controlar_pass() {
            // Primero, llama a 'validar_pass()' para la validación del lado del cliente.
            if (validar_pass() == 0) {
                $("#formulario").addClass('was-validated'); // Agrega la clase de validación de Bootstrap.
                return;
            }
            // Llamada AJAX de jQuery: Envía una solicitud POST a 'modulos/administracion/cambiar_clave/controlador.php'.
            // Serializa los datos del formulario del elemento con ID 'formulario'.
            // La respuesta se coloca dentro del div '#mensaje' y se desvanece para aparecer.
            $.post("modulos/administracion/cambiar_clave/controlador.php", $("#formulario").serialize(), function (dato) {
                $("#mensaje").html(dato);
                $('#mensaje').fadeIn('slow');
            });
        }
    </script>

</head>

<body id="page-top">
    <div id="popup" style="display: none;"></div>
    <div id="wrapper">

        <?php include('menu.php'); ?>

        <div id="content-wrapper" class="d-flex flex-column">

            <div id="content">
                <?php include('header.php');//se incluye el header de manera dinamica ?>

                <div class="container-fluid">

                    <?php
                    // Comprobar si el parámetro 'pagina' está establecido en la URL. Esto determina qué módulo cargar.
                    if (isset($_GET['pagina'])) {

                        // Decodificar el valor de 'pagina' codificado en base64. Esta es una forma simple de ocultar la ruta del módulo.
                        $enlace = base64_decode($_GET['pagina']);

                        // Consulta SQL para comprobar si el grupo del usuario tiene acceso al módulo solicitado (item).
                        $sqlc = "SELECT i.*
                                    FROM items i
                                    JOIN grupos_items g ON g.item_id=i.id AND g.grupo_id=" . $_SESSION['grupo_id'] . "
                                    WHERE i.enlace='" . $enlace . "'
                                    ORDER BY i.orden";

                        $resultado = mysqli_query($con, $sqlc);
                        // Si el usuario tiene acceso (se devuelve una fila de la consulta).
                        if (mysqli_num_rows($resultado) != 0) {
                            // Incluir el contenido del módulo solicitado.
                            // La ruta se construye como "modulos/" . $enlace . ".php".
                            include("modulos/" . $enlace . ".php");
                            // Reemplazar '/' con '_' en el enlace para crear un ID HTML válido.
                            $it = str_replace('/', '_', $enlace);
                            // Establecer 'op' predeterminado si no se proporciona.
                            if (!isset($_GET['op'])) {
                                $_GET['op'] = 0;
                            }
                            // jQuery para mostrar el elemento de menú colapsado correcto.
                            // Esto expande dinámicamente el menú de la barra lateral correspondiente al módulo cargado.
                            echo "<script>$('#op_" . $_GET['op'] . "').removeClass('collapse').addClass('collapse show');</script>";
                            // jQuery para resaltar el elemento seleccionado en el menú.
                            echo "<script>$('#" . $it . "').addClass('item_seleccionado');</script>";
                        } else {
                            // Si el usuario no tiene acceso, incluir la página 403 (Prohibido).
                            include("403.php");
                        }
                    } else {
                        /////aca es el index sin pagina (Este bloque se ejecuta si no se establece el parámetro 'pagina', lo que significa que el usuario está en la página de inicio predeterminada)
                    }
                    ?>

                </div>
            </div>

            <?php include('footer.php'); ?>
        </div>
        <a class="scroll-to-top rounded" href="#page-top">
            <i class="fas fa-angle-up"></i>
        </a>

        <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Salir?</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">Seleccione "Cerrar sesión" a continuación si está listo para finalizar su
                        sesión actual.</div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                        <a class="btn btn-primary" href="logout.php">Cerrar sesión</a>
                    </div>
                </div>
            </div>
        </div>

        <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
        <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

        <script src="inc/js/sb-admin-2.min.js"></script>
        <script src="inc/js/jquery.js"></script>
        <script src="inc/js/jquery-confirm.js"></script>
        <script src="inc/js/jquery.dataTables.min.js"></script>
</body>

</html>