<?php
// Controlador para el módulo de Matrículas

if (isset($_GET['f'])) {
    $function = $_GET['f'];
} else {
    $function = "";
}

session_start();
include("../../../inc/conexion.php"); // Asegúrate que la ruta a tu archivo de conexión sea correcta
$con = conectar();

if (function_exists($function)) {
    $function($con);
} else {
    echo "La función " . $function . " no existe.";
}

function editar($con)
{
    $id = (int)($_POST['id'] ?? 0);
    $profesional_id = (int)($_POST['profesional_id'] ?? 0); // Crucial para el enlace con el profesional
    $numero_matricula = mysqli_real_escape_string($con, $_POST['numero_matricula'] ?? '');
    $fecha_alta = mysqli_real_escape_string($con, $_POST['fecha_alta'] ?? '');
    $tipo = mysqli_real_escape_string($con, $_POST['tipo'] ?? '');
    $usuario_abm = mysqli_real_escape_string($con, $_SESSION['usuario'] ?? 'SYSTEM'); // Asumiendo que el usuario está en sesión

    mysqli_begin_transaction($con); // Iniciar transacción

    try {
        // Validación básica: profesional_id debe ser válido y existir
        if ($profesional_id <= 0) {
            throw new Exception("Error: El ID de Profesional no es válido.");
        }
        $sql_check_prof = "SELECT COUNT(*) FROM profesionales WHERE id = $profesional_id";
        $res_check_prof = mysqli_query($con, $sql_check_prof);
        $row_check_prof = mysqli_fetch_array($res_check_prof);
        if ($row_check_prof[0] == 0) {
            throw new Exception("Error: El Profesional seleccionado no existe.");
        }

        if ($id > 0) {
            // Actualizar una matrícula existente
            $stmt = mysqli_prepare($con, "UPDATE matriculas SET profesional_id = ?, numero_matricula = ?, fecha_alta = ?, tipo = ?, usuario_abm = ?, fecha_modificacion = CURRENT_TIMESTAMP WHERE id = ?");
            if (!$stmt) {
                throw new Exception("Error al preparar la consulta de actualización de matrícula: " . mysqli_error($con));
            }
            mysqli_stmt_bind_param($stmt, "issssi", $profesional_id, $numero_matricula, $fecha_alta, $tipo, $usuario_abm, $id);
            $mensaje = "La matrícula se modificó con éxito.";
        } else {
            // Insertar una nueva matrícula
            $stmt = mysqli_prepare($con, "INSERT INTO matriculas (profesional_id, numero_matricula, fecha_alta, tipo, usuario_abm, fecha_creacion, fecha_modificacion) VALUES (?, ?, ?, ?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)");
            if (!$stmt) {
                throw new Exception("Error al preparar la consulta de inserción de matrícula: " . mysqli_error($con));
            }
            mysqli_stmt_bind_param($stmt, "issss", $profesional_id, $numero_matricula, $fecha_alta, $tipo, $usuario_abm);
            $mensaje = "La matrícula se creó con éxito.";
        }

        if (mysqli_stmt_execute($stmt)) {
            mysqli_commit($con); // Confirmar transacción
            echo '<div class="alert alert-primary animated--grow-in" role="alert">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <i class="far fa-check-circle"></i> ' . $mensaje . '
            </div>';
            // Después de guardar, refrescar la lista de matrículas para el profesional y cerrar el formulario
            echo "<script>cargarMatriculasProfesional(" . $profesional_id . ");</script>"; // Función JS para recargar lista
            echo "<script>cerrar_formulario_matricula();</script>"; // Función JS para cerrar formulario
        } else {
            throw new Exception("Error al ejecutar la consulta de matrícula: " . mysqli_stmt_error($stmt));
        }

        mysqli_stmt_close($stmt);

    } catch (Exception $e) {
        mysqli_rollback($con); // Revertir transacción en caso de error
        echo '
        <div class="alert alert-danger" role="alert">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <i class="fas fa-exclamation-triangle"></i> ' . $e->getMessage() . '
        </div>';
    }
}

function eliminar($con)
{
    $id = (int)($_POST['id'] ?? 0);
    $profesional_id = (int)($_POST['profesional_id'] ?? 0); // Necesario para refrescar la lista después

    mysqli_begin_transaction($con); // Iniciar transacción

    try {
        // IMPORTANTE: Verificar dependencias de clave foránea antes de eliminar
        // Comprobar si esta matrícula es referenciada por alguna entrada en matricula_estado
        $sql_check_estados = "SELECT COUNT(*) FROM matricula_estado WHERE matricula_id = ?";
        $stmt_check_estados = mysqli_prepare($con, $sql_check_estados);
        if (!$stmt_check_estados) {
            throw new Exception("Error al preparar la verificación de estados de matrícula: " . mysqli_error($con));
        }
        mysqli_stmt_bind_param($stmt_check_estados, "i", $id);
        mysqli_stmt_execute($stmt_check_estados);
        $res_check_estados = mysqli_stmt_get_result($stmt_check_estados);
        $row_check_estados = mysqli_fetch_array($res_check_estados);
        mysqli_stmt_close($stmt_check_estados);

        if ($row_check_estados[0] > 0) {
            throw new Exception("No se puede eliminar la matrícula porque tiene estados asociados. Elimine los estados primero.");
        }

        $stmt = mysqli_prepare($con, "DELETE FROM matriculas WHERE id = ?");
        if (!$stmt) {
            throw new Exception("Error al preparar la consulta de eliminación de matrícula: " . mysqli_error($con));
        }
        mysqli_stmt_bind_param($stmt, "i", $id);

        if (mysqli_stmt_execute($stmt)) {
            mysqli_commit($con); // Confirmar transacción
            echo '<div class="alert alert-primary" role="alert">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <i class="far fa-check-circle"></i> La matrícula se eliminó con éxito.
            </div>';
            echo "<script>cargarMatriculasProfesional(" . $profesional_id . ");</script>"; // Refrescar la lista
        } else {
            throw new Exception("Error al ejecutar la consulta de eliminación de matrícula: " . mysqli_stmt_error($stmt));
        }

        mysqli_stmt_close($stmt);

    } catch (Exception $e) {
        mysqli_rollback($con); // Revertir transacción en caso de error
        echo '
        <div class="alert alert-danger" role="alert">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <i class="fas fa-exclamation-triangle"></i> ' . $e->getMessage() . '
        </div>';
    }
}
?>