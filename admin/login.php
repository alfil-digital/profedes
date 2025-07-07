<?php
session_start();
require_once("core/env.php");
include("inc/conexion.php");
$con = conectar();
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Plantillas</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">

    <!-- Custom styles for this template-->
    <link href="inc/css/sb-admin-2.min.css" rel="stylesheet">

    <link rel="stylesheet" href="inc/css/jquery-confirm.min.css">

    <script src="inc/js/jquery.js"></script>
    <script src="inc/js/jquery-confirm.js"></script>


    <script type="text/javascript">
        // Esta función se activa cuando un usuario necesita cambiar su contraseña.
        function cambiar_clave_pass() {
            // Llamada AJAX de jQuery: Envía una solicitud GET a 'modulos/administracion/cambiar_clave/formulario.php'.
            // La respuesta (contenido HTML del formulario) se coloca dentro del elemento con ID 'popup'.
            // Luego, el elemento 'popup' se desvanece lentamente para aparecer.
            $.get("modulos/administracion/cambiar_clave/formulario.php", function (dato) {
                $("#popup").html(dato);
                $('#popup').fadeIn('slow');
                return false;
            });
        }

        // Esta función se utiliza para cerrar la ventana emergente de cambio de contraseña.
        function cerrar_pass() {
            // jQuery: Desvanece lentamente el elemento 'popup' y el 'popup-overlay' (asumiendo que hay un elemento de superposición semitransparente).
            $('#popup').fadeOut('slow');
            $('.popup-overlay').fadeOut('slow');
        }

        // Esta función valida los campos del formulario de cambio de contraseña.
        function validar_formulario() {
            // Comprueba si el campo de la contraseña actual está vacío o es demasiado corto.
            if ($("#clave_actual").val().length < 3) {
                $("#clave_actual").focus(); // Vuelve a poner el foco en el campo.
                return 0; // Devuelve 0 para indicar que la validación falló.
            }
            // Comprueba si el campo de la nueva contraseña está vacío o es demasiado corto.
            if ($("#clave_nueva").val().length < 3) {
                $("#clave_nueva").focus();
                return 0;
            }
            // Comprueba si el campo de confirmación de la nueva contraseña está vacío o es demasiado corto.
            if ($("#clave_nueva_1").val().length < 3) {
                $("#clave_nueva_1").focus();
                return 0;
            }

            // Comprueba si la nueva contraseña y su confirmación coinciden.
            if ($("#clave_nueva_1").val() != $("#clave_nueva").val()) {
                alertas("Las claves nuevas no coinciden"); // Llama a la función de alerta personalizada.
                $("#clave_nueva").focus();
                // Limpia los campos de la nueva contraseña.
                clave_nueva.value = "";
                clave_nueva_1.value = "";
                return 0;
            }
        }

        // Esta función maneja el envío del formulario de cambio de contraseña.
        function controlar_pass() {
            // Primero llama a 'validar_formulario()'. Si la validación falla (devuelve 0)...
            if (validar_formulario() == 0) {
                $("#formulario").addClass('was-validated'); // Agrega una clase de Bootstrap para la retroalimentación de validación.
                return; // Detiene la ejecución de la función.
            }
            // Llamada AJAX de jQuery: Envía una solicitud POST a 'modulos/administracion/cambiar_clave/controlador.php'.
            // Serializa los datos del formulario del elemento con ID 'formulario'.
            // La respuesta se coloca dentro del elemento 'mensaje' y se desvanece para aparecer.
            $.post("modulos/administracion/cambiar_clave/controlador.php", $("#formulario").serialize(), function (dato) {
                $("#mensaje").html(dato);
                $('#mensaje').fadeIn('slow');
            });
        }

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
    </script>


</head>

<body class="bg-gradient-primary">
    <div id="popup" style="display: none;"></div>
    <div id="mensaje" style="display: none;"></div>
    <?php
    // Comprueba si el formulario de inicio de sesión fue enviado (es decir, se hizo clic en el botón 'login').
    if (isset($_POST['login'])) {

        // Obtener nombre de usuario y contraseña de la solicitud POST.
        $user = $_POST['user'];
        $password = $_POST['password'];
        // Escapa caracteres especiales en una cadena para usar en una sentencia SQL. Esto es crucial para prevenir la inyección SQL.
        $suer = mysqli_real_escape_string($con, $user);

        // Consulta SQL para recuperar información del usuario basada en el nombre de usuario proporcionado.
        $sql = "SELECT
            u.id,
            u.usuario,
            u.clave,
            u.grupo_id,
            concat(p.nombre,' ',p.apellido) as nombre_apellido
            FROM usuarios u
            inner join personas p on p.id=u.persona_id
            WHERE
            u.usuario='$user'";

        // Ejecutar la consulta SQL.
        $resultado = mysqli_query($con, $sql);

        // Comprobar si se devolvió alguna fila (es decir, si el usuario existe).
        if (mysqli_num_rows($resultado) != 0) {
            // Obtener los datos del usuario como un array asociativo.
            $row = mysqli_fetch_array($resultado);
            // Verificar la contraseña ingresada contra la contraseña hasheada almacenada en la base de datos.
            if (password_verify($_POST['password'], $row['clave'])) {
                // Si la contraseña coincide, establecer variables de sesión con la información del usuario.
                $_SESSION['userid'] = $row['id'];
                $_SESSION['nombre_apellido'] = $row['nombre_apellido'];
                $_SESSION['usuario'] = $row['usuario'];
                $_SESSION['grupo_id'] = $row['grupo_id'];

                // Comprobar si la contraseña del usuario es la misma que su nombre de usuario (indicando una contraseña predeterminada/inicial).
                if (password_verify($row['usuario'], $row['clave'])) {
                    // Si es la contraseña predeterminada, pedir al usuario que la cambie llamando a la función JavaScript.
                    echo "<script>cambiar_clave_pass();</script>";
                } else {
                    // Si no es la contraseña predeterminada, redirigir a la página principal del índice.
                    echo "<script>document.location='index.php';</script>";
                }
            } else {
                // Si la contraseña no coincide, mostrar una alerta.
                echo "<script>alertas('Usuario inexistente o datos mal ingresados');</script>";
            }
        } else {
            // Si el usuario no existe, mostrar una alerta.
            echo "<script>alertas('Usuario inexistente o datos mal ingresados');</script>";
        }
    } ?>


    <div class="container h-100">
        <div class="d-flex justify-content-center h-100">
            <div sty class="user_card">
                <div class="d-flex justify-content-center">
                    <div class="brand_logo_container">
                        <img src="./vendor/img/logo-DocEdEs.png" alt="Logo" class="brand_logo">
                    </div>
                </div>
                <div class="d-flex justify-content-center form_container">
                    <form action="" method="post" class="login">
                        <div class="input-group mb-3">
                            <div class="input-group-append">
                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                            </div>
                            <input type="text" name="user" class="form-control input_user" value=""
                                placeholder="Usuario">
                        </div>
                        <div class="input-group mb-2">
                            <div class="input-group-append">
                                <span class="input-group-text"><i class="fas fa-key"></i></span>
                            </div>
                            <input type="password" name="password" class="form-control input_pass" value=""
                                placeholder="Clave">
                        </div>

                        <div class="d-flex justify-content-center mt-3 login_container">
                            <button type="submit" name="login" class="btn login_btn">Login</button>
                        </div>
                    </form>
                </div>

                <div class="mt-4">
                    <div class="d-flex justify-content-center links text-white">
                        Complete los datos para Ingresar
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <script src="inc/js/jquery.dataTables.min.js"></script>

</body>
<style type="text/css">
    /* Todos los estilos CSS aquí definen la apariencia visual de la página de inicio de sesión,
       incluyendo colores, posicionamiento, sombras y capacidad de respuesta.
       Estos son clases CSS, IDs CSS y selectores CSS que apuntan a elementos HTML específicos. */
    body,
    html {
        margin: 0;
        padding: 0;
        height: 100%;
        background: #BCE8F0 !important;
    }

    .user_card {
        height: 400px;
        width: 350px;
        margin-top: auto;
        margin-bottom: auto;
        background: #48BFD5;
        position: relative;
        display: flex;
        justify-content: center;
        flex-direction: column;
        padding: 10px;
        box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
        -webkit-box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
        -moz-box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
        border-radius: 5px;

    }

    .brand_logo_container {
        position: absolute;
        height: 170px;
        width: 170px;
        top: -75px;
        border-radius: 50%;
        background: #60a3bc;
        padding: 10px;
        text-align: center;
    }

    .brand_logo {
        height: 100%;
        width: 100%;
        border-radius: 50%;
        border: 2px solid white;
    }

    .form_container {
        margin-top: 100px;
    }

    .login_btn {
        width: 100%;
        background: rgb(1, 126, 148) !important;
        color: white !important;
    }

    .login_btn:focus {
        box-shadow: none !important;
        outline: 0px !important;
    }

    .login_container {
        padding: 0 2rem;
    }

    .input-group-text {
        color: #000 !important;
        border: 0 !important;
        border-radius: 0.25rem 0 0 0.25rem !important;
    }

    .input_user,
    .input_pass:focus {
        box-shadow: none !important;
        outline: 0px !important;
    }

    .custom-checkbox .custom-control-input:checked~.custom-control-label::before {
        background-color: #4e73df !important;
    }

    /* Estilos para la ventana emergente de cambio de contraseña  */
    #popup {
        left: 0;
        top: 0;
        position: fixed;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        overflow: auto;
        z-index: 1;
    }

    /* Estilos para el contenido dentro de la ventana emergente */
    .content-popup {
        margin: 0px auto;
        margin-top: 50px;
        margin-bottom: 50px;
        position: relative;
        padding: 10px;
        width: 90%;
        min-height: 250px;
        border-radius: 4px;
        background-color: #e9ebf2;
    }

    /* Estilos para el encabezado dentro de la ventana emergente */
    .content-popup h2 {
        background-color: #4e73df;
        color: #fff;

        margin-right: 40px;

        margin-top: 0;
        padding-bottom: 4px;
        line-height: 35px;
        border-radius: 4px 4px 4px 4px;
        font-size: 18px;
    }

    /* Estilos para el botón de cerrar de la ventana emergente */
    a.popup-cerrar {
        position: absolute;
        right: 10px;
        background-color: #DC3545;
        padding: 10px 10px;
        font-size: 20px;
        text-decoration: none;
        line-height: 1;
        color: #fff;
        border-radius: 4px 4px 4px 4px;
        opacity: 1;
    }
</style>

</html>