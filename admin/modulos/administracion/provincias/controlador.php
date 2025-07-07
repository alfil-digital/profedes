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
    $provincia_id = (int)$_POST['provincia_id'];
    $codigo_postal = mysqli_real_escape_string($con, $_POST['codigo_postal']);

    if ($id > 0) {
        // Update usando prepared statement
        $stmt = mysqli_prepare($con, "UPDATE localidades SET nombre = ?, provincia_id = ?, codigo_postal = ? WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "sisi", $nombre, $provincia_id, $codigo_postal, $id);
        $mensaje = "El registro se modificó con éxito";
    } else {
        // Insert usando prepared statement
        $stmt = mysqli_prepare($con, "INSERT INTO localidades (nombre, provincia_id, codigo_postal) VALUES (?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "sis", $nombre, $provincia_id, $codigo_postal);
        $mensaje = "El registro se creó con éxito";
    }

    if (mysqli_stmt_execute($stmt)) {
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

    mysqli_stmt_close($stmt);
}


function eliminar($con)
{
    $id = (int)$_POST['id'];

    // Usar prepared statement para mayor seguridad
    $stmt = mysqli_prepare($con, "DELETE FROM localidades WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);

    if (mysqli_stmt_execute($stmt)) {
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

    mysqli_stmt_close($stmt);
}
?>