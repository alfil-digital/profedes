<!-- Flujo de Trabajo:

Verificación de Sesión: Al principio, header.php comprueba si $_SESSION['userid'] está establecido. Si no lo está, significa que el usuario no ha iniciado sesión y el script los redirige inmediatamente a login.php utilizando JavaScript document.location='login.php'. Esta es una medida de seguridad crítica para proteger el acceso no autorizado a las páginas que incluyen este encabezado.
Mostrar Información del Usuario: Si el usuario ha iniciado sesión, muestra el nombre_apellido del usuario desde la sesión. Tenga en cuenta que el grupo está actualmente codificado como "---".
Elementos Interactivos (jQuery/Bootstrap):
Toggle de la Barra Lateral: El elemento <button id="sidebarToggleTop"> probablemente es manejado por una función JavaScript (por ejemplo, de sb-admin-2.min.js) que utiliza jQuery para alternar la visibilidad del menú de la barra lateral, mejorando la capacidad de respuesta.
Desplegable de Usuario: El <li class="nav-item dropdown no-arrow"> y su div anidado con dropdown-menu son componentes estándar de Bootstrap que utilizan jQuery para gestionar la funcionalidad del desplegable cuando el usuario hace clic en el icono de usuario.
"Cambiar Contraseña": Cuando se hace clic en este elemento del desplegable, el atributo onclick="cambiar_clave_pass();" llama a la función JavaScript cambiar_clave_pass(). Esta función, definida en index.php (y también en login.php), utiliza AJAX con el método $.get() de jQuery para obtener el contenido del formulario de cambio de contraseña y mostrarlo en una ventana emergente.
Modal de "Salir" (Cerrar Sesión): Este elemento del desplegable (data-toggle="modal" data-target="#logoutModal") aprovecha el componente modal de Bootstrap. Al hacer clic, muestra un modal de confirmación (#logoutModal), que es manejado por el JavaScript de Bootstrap (que a su vez depende de jQuery). -->

<?php
// Comprobar si 'userid' NO está establecido en la sesión. Esto significa que el usuario no ha iniciado sesión.
if (!isset($_SESSION['userid'])) {
    // Si no ha iniciado sesión, redirigir a 'login.php' usando JavaScript.
    echo "<script>document.location='login.php';</script>";
    // Detener la ejecución adicional del script.
    exit();
}
?>
<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
        <i class="fa fa-bars"></i>
    </button>


    <form class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
        <div class="input-group">
            <input type="text" class="form-control bg-light border-0 small" placeholder="Buscar..." aria-label="Search"
                aria-describedby="basic-addon2">
            <div class="input-group-append">
                <button class="btn btn-primary" type="button">
                    <i class="fas fa-search fa-sm"></i>
                </button>
            </div>
        </div>
    </form>

    <ul class="navbar-nav ml-auto">

        <li class="nav-item dropdown no-arrow d-sm-none">
            <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button" data-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-search fa-fw"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in"
                aria-labelledby="searchDropdown">
                <form class="form-inline mr-auto w-100 navbar-search">
                    <div class="input-group">
                        <input type="text" class="form-control bg-light border-0 small" placeholder="Buscar..."
                            aria-label="Search" aria-describedby="basic-addon2">
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="button">
                                <i class="fas fa-search fa-sm"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </li>


        <div class="topbar-divider d-none d-sm-block"></div>

        <span class="mr-2 d-none d-lg-inline small text-primary"><br>Usuario: <?php echo $_SESSION['nombre_apellido']; ?>
            <br>Grupo: ---</span>

        <li class="nav-item dropdown no-arrow">
            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">

                <div class="topbar-divider d-none d-sm-block"></div>
                <i class="fas fa-user-cog text-primary"></i>


            </a>
            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                <a class="dropdown-item" onclick="cambiar_clave_pass();">
                    <i class="fas fa-exchange-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                    Cambiar Contraseña
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                    Salir
                </a>
            </div>
        </li>
    </ul>

</nav>