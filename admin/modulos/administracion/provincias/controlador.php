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
    $pais_id = (int)$_POST['pais_id'];

    if($id == 0){
        // inserto
        $sql = "INSERT INTO provincias (nombre, pais_id) VALUES ('$nombre', $pais_id)";
        $con->query($sql);
        if ($con->error) {
            echo '<div class="alert alert-danger" role="alert">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <i class="fas fa-exclamation-triangle"></i> Error al crear el registro: ' . $con->error . '
            </div>';
            return;
        }else{
            // Si la inserción fue exitosa, mostrar mensaje de éxito
            echo '<div class="alert alert-primary" role="alert">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <i class="far fa-check-circle"></i> El registro se creó con éxito
            </div>';
        }

    }else{
        // update la localidad
        $sql = "UPDATE provincias SET nombre = '$nombre', pais_id = $pais_id WHERE id = $id";
        $con->query($sql);

        if ($con->error) {
            echo '<div class="alert alert-danger" role="alert">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <i class="fas fa-exclamation-triangle"></i> Error al actualizar el registro: ' . $con->error . '
            </div>';
            return;
        } else {
            // Si la actualización fue exitosa, mostrar mensaje de éxito
            echo '<div class="alert alert-primary" role="alert">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <i class="far fa-check-circle"></i> El registro se modificó con éxito
            </div>';
        }
    }



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