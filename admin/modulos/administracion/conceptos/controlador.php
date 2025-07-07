<!-- controlador localidades -->
<?php

if (isset($_GET['f'])) {          // Verifica si existe el parámetro 'f' en la URL
    $function = $_GET['f'];       // Si existe, asigna su valor a $function
} else {
    $function = "";               // Si no existe, asigna una cadena vacía a $function
}

session_start();
include("../../../inc/conexion.php");
$con = conectar();

if (function_exists($function)) {
    $function($con);
} else {
    echo "La funcion" . $function . "no existe...";
}

function editar($con)
{
    $id = (int)$_POST['id'];
    $nombre = mysqli_real_escape_string($con, $_POST['nombre']);
   

    if ($id > 0) {
        // Update usando prepared statement
        $stmt = mysqli_query($con, "UPDATE conceptos SET nombre = '$nombre' WHERE id = $id");
        
        $mensaje = "El registro se modificó con éxito";
    } else {
        // Insert usando prepared statement
        $stmt = mysqli_query($con, "INSERT INTO conceptos (nombre) VALUES ('$nombre')");
        $mensaje = "El registro se creó con éxito";
    }

    if ($stmt) {
        echo '<div class="alert alert-primary" role="alert">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <i class="far fa-check-circle"></i> ' . $mensaje . '
        </div>';
        echo "<script>listado();</script>";
        echo "<script>cerrar_formulario();</script>";
    } else {
        echo '<div class="alert alert-danger" role="alert">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <i class="fas fa-exclamation-triangle"></i> No se pudo actualizar el registro
        </div>';
    }

    mysqli_close($con);
}


function eliminar($con)
{
    $id = (int)$_POST['id'];

    // Usar prepared statement para mayor seguridad
    $stmt = mysqli_query($con, "DELETE FROM conceptos WHERE id = $id");

    if ($stmt) {
        echo '<div class="alert alert-primary" role="alert">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <i class="far fa-check-circle"></i> El registro se eliminó con éxito
        </div>';
    } else {
        echo '<div class="alert alert-danger" role="alert">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <i class="fas fa-exclamation-triangle"></i> No se pudo eliminar el registro
        </div>';
    }

    mysqli_close($con);
}
?>