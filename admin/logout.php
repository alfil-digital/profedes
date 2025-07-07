<!-- Flujo de Trabajo: Cuando un usuario hace clic en el botón "Salir" (Cerrar sesión) y confirma en el modal (desde header.php e index.php), el navegador navega a logout.php. Este script:

Inicia la sesión para acceder a sus datos.
Destruye todos los datos de la sesión usando session_destroy(), terminando efectivamente el estado de inicio de sesión del usuario.
Redirige el navegador a index.php usando una redirección HTTP header(). Dado que index.php (y header.php incluido dentro de él) verifica $_SESSION['userid'], el usuario será redirigido de nuevo a login.php porque ya no ha iniciado sesión. -->

<?php
    // Iniciar la sesión. Esto es necesario para acceder y destruir las variables de sesión.
    session_start();
    // Destruir todos los datos registrados en la sesión. Esto cierra la sesión del usuario de manera efectiva.
    session_destroy();
    // Redirigir al usuario de vuelta a la página de inicio (que luego redirigirá a login si no ha iniciado sesión).
    header('location:index.php');
?>