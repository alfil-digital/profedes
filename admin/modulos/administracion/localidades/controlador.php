<?php

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
    // Sanitización y verificación de los datos recibidos por POST
    $id = (int) ($_POST['id'] ?? 0);
    $nombre = mysqli_real_escape_string($con, $_POST['nombre'] ?? '');
    $provincia_id = (int) ($_POST['provincia_id'] ?? 0); // Asegurar que sea int y no null
    $codigo_postal = mysqli_real_escape_string($con, $_POST['codigo_postal'] ?? '');
    $observaciones = mysqli_real_escape_string($con, $_POST['observaciones'] ?? '');


    // Validar que los IDs de claves foráneas sean válidos antes de la transacción
    // Verificar si provincia_id existe en la tabla provincias
    $sql_check_provincia = "SELECT COUNT(*) FROM provincias WHERE id = $provincia_id";
    $res_check_provincia = mysqli_query($con, $sql_check_provincia);
    $row_check_provincia = mysqli_fetch_array($res_check_provincia);
    if ($row_check_provincia[0] == 0 && $provincia_id != 0) { // Permitir 0 si no es NOT NULL en DB
        echo '
        <div class="alert alert-danger" role="alert">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <i class="fas fa-exclamation-triangle"></i> Error: La Provincia seleccionada no es válida.
        </div>';
        return;
    }


    // --- INICIO DE TRANSACCIÓN ---
    mysqli_begin_transaction($con);

    try {
        if ($id > 0) {
            // Update usando prepared statement para localidades
            $stmt = mysqli_prepare($con, "UPDATE localidades SET nombre = ?, provincia_id = ?, codigo_postal = ?, observaciones = ? WHERE id = ?");
            if (!$stmt) {
                throw new Exception("Error al preparar la consulta de actualización: " . mysqli_error($con));
            }
            mysqli_stmt_bind_param($stmt, "sisss", $nombre, $provincia_id, $codigo_postal, $observaciones, $id);
            $mensaje = "El registro se modificó con éxito";
        } else {
            // Insert usando prepared statement para localidades
            $stmt = mysqli_prepare($con, "INSERT INTO localidades (nombre, provincia_id, codigo_postal, observaciones) VALUES (?, ?, ?, ?)");
            if (!$stmt) {
                throw new Exception("Error al preparar la consulta de inserción: " . mysqli_error($con));
            }
            mysqli_stmt_bind_param($stmt, "siss", $nombre, $provincia_id, $codigo_postal, $observaciones);
            $mensaje = "El registro se creó con éxito";
        }

        if (mysqli_stmt_execute($stmt)) {
            mysqli_commit($con); // Confirmar transacción
            echo '<div class="alert alert-primary animated--grow-in" role="alert">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <i class="far fa-check-circle"></i> ' . $mensaje . '
            </div>';
            echo "<script>listado();</script>";
            echo "<script>cerrar_formulario();</script>";
        } else {
            // Si la ejecución falla, lanzar excepción para que el catch la maneje
            throw new Exception("Error al ejecutar la consulta: " . mysqli_stmt_error($stmt));
        }

        mysqli_stmt_close($stmt);

    } catch (Exception $e) {
        mysqli_rollback($con); // Revertir transacción si hay error
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

    // --- INICIO DE TRANSACCIÓN ---
    mysqli_begin_transaction($con);

    try {
        // Antes de eliminar una localidad, verificar si está siendo referenciada por personas
        // (Esto es crucial si tienes una foreign key de personas.localidad_id a localidades.id)
        $sql_check_personas = "SELECT COUNT(*) FROM personas WHERE localidad_id = ?";
        $stmt_check_personas = mysqli_prepare($con, $sql_check_personas);
        if (!$stmt_check_personas) {
            throw new Exception("Error al preparar la verificación de personas: " . mysqli_error($con));
        }
        mysqli_stmt_bind_param($stmt_check_personas, "i", $id);
        mysqli_stmt_execute($stmt_check_personas);
        $res_check_personas = mysqli_stmt_get_result($stmt_check_personas);
        $row_check_personas = mysqli_fetch_array($res_check_personas);
        mysqli_stmt_close($stmt_check_personas);

        if ($row_check_personas[0] > 0) {
            throw new Exception("No se puede eliminar la localidad porque está siendo utilizada por personas.");
        }


        // Usar prepared statement para mayor seguridad para localidades
        $stmt = mysqli_prepare($con, "DELETE FROM localidades WHERE id = ?");
        if (!$stmt) {
            throw new Exception("Error al preparar la consulta de eliminación: " . mysqli_error($con));
        }
        mysqli_stmt_bind_param($stmt, "i", $id);

        if (mysqli_stmt_execute($stmt)) {
            mysqli_commit($con); // Confirmar transacción
            echo '<div class="alert alert-primary" role="alert">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <i class="far fa-check-circle"></i> El registro se eliminó con éxito
            </div>';
        } else {
            // Si la ejecución falla, lanzar excepción
            throw new Exception("Error al ejecutar la consulta de eliminación: " . mysqli_stmt_error($stmt));
        }

        mysqli_stmt_close($stmt);

    } catch (Exception $e) {
        mysqli_rollback($con); // Revertir transacción si hay error
        echo '
        <div class="alert alert-danger" role="alert">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <i class="fas fa-exclamation-triangle"></i> ' . $e->getMessage() . '
        </div>';
    }
}
?>