<?php
// controlador entidades
if (isset($_GET['f'])) {
    $function = $_GET['f'];
} else {
    $function = "";
}

session_start();
include("../../../inc/conexion.php");
$con = conectar();

if (function_exists($function)) {
    $function($con);
} else {
    echo "La funcion " . $function . " no existe...";
}

function editar($con)
{
    $id = (int) ($_POST['id'] ?? 0);
    $tipo_entidad = mysqli_real_escape_string($con, $_POST['tipo_entidad'] ?? '');

    mysqli_begin_transaction($con);

    try {
        if ($id > 0) {
            // Update
            $stmt = mysqli_prepare($con, "UPDATE entidades SET tipo_entidad = ? WHERE id = ?");
            if (!$stmt) {
                throw new Exception("Error al preparar la consulta de actualización: " . mysqli_error($con));
            }
            mysqli_stmt_bind_param($stmt, "si", $tipo_entidad, $id);
            $mensaje = "El registro se modificó con éxito";
        } else {
            // Insert
            $stmt = mysqli_prepare($con, "INSERT INTO entidades (tipo_entidad) VALUES (?)");
            if (!$stmt) {
                throw new Exception("Error al preparar la consulta de inserción: " . mysqli_error($con));
            }
            mysqli_stmt_bind_param($stmt, "s", $tipo_entidad);
            $mensaje = "El registro se creó con éxito";
        }

        if (mysqli_stmt_execute($stmt)) {
            mysqli_commit($con);
            echo '<div class="alert alert-primary animated--grow-in" role="alert">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <i class="far fa-check-circle"></i> ' . $mensaje . '
            </div>';
            echo "<script>listado();</script>";
            echo "<script>cerrar_formulario();</script>";
        } else {
            throw new Exception("Error al ejecutar la consulta: " . mysqli_stmt_error($stmt));
        }

        mysqli_stmt_close($stmt);

    } catch (Exception $e) {
        mysqli_rollback($con);
        echo '
        <div class="alert alert-danger" role="alert">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <i class="fas fa-exclamation-triangle"></i> ' . $e->getMessage() . '
        </div>';
    }
}

function eliminar($con)
{
    $id = (int) ($_POST['id'] ?? 0);

    mysqli_begin_transaction($con);

    try {
        // IMPORTANT: Check for foreign key dependencies before deleting an entity
        // If 'personas_entidades' table exists and links to 'entidades',
        // you MUST check if any persona is linked to this entity.
        $sql_check_dependencies = "SELECT COUNT(*) FROM personas_entidades WHERE entidad_id = ?";
        $stmt_check_dependencies = mysqli_prepare($con, $sql_check_dependencies);
        if (!$stmt_check_dependencies) {
            throw new Exception("Error al preparar la verificación de dependencias: " . mysqli_error($con));
        }
        mysqli_stmt_bind_param($stmt_check_dependencies, "i", $id);
        mysqli_stmt_execute($stmt_check_dependencies);
        $res_check_dependencies = mysqli_stmt_get_result($stmt_check_dependencies);
        $row_check_dependencies = mysqli_fetch_array($res_check_dependencies);
        mysqli_stmt_close($stmt_check_dependencies);

        if ($row_check_dependencies[0] > 0) {
            throw new Exception("No se puede eliminar la entidad porque está siendo utilizada por personas o registros relacionados.");
        }


        $stmt = mysqli_prepare($con, "DELETE FROM entidades WHERE id = ?");
        if (!$stmt) {
            throw new Exception("Error al preparar la consulta de eliminación: " . mysqli_error($con));
        }
        mysqli_stmt_bind_param($stmt, "i", $id);

        if (mysqli_stmt_execute($stmt)) {
            mysqli_commit($con);
            echo '<div class="alert alert-primary" role="alert">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <i class="far fa-check-circle"></i> El registro se eliminó con éxito
            </div>';
        } else {
            throw new Exception("Error al ejecutar la consulta de eliminación: " . mysqli_stmt_error($stmt));
        }

        mysqli_stmt_close($stmt);

    } catch (Exception $e) {
        mysqli_rollback($con);
        echo '
        <div class="alert alert-danger" role="alert">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <i class="fas fa-exclamation-triangle"></i> ' . $e->getMessage() . '
        </div>';
    }
}
?>