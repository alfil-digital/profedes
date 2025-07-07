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

// --- FUNCIÓN EDITAR ---
function editar($con)
{ // <-- La llave de apertura de la función 'editar'
  // --- Declaración y sanitización de variables POST ---
  $id = (int) ($_POST['id'] ?? 0); // Aseguramos que $id siempre sea un entero
  $nombre = mysqli_real_escape_string($con, $_POST['nombre'] ?? '');
  $apellido = mysqli_real_escape_string($con, $_POST['apellido'] ?? '');
  $dni = mysqli_real_escape_string($con, $_POST['dni'] ?? '');
  $telefono = mysqli_real_escape_string($con, $_POST['telefono'] ?? '');
  $localidad_id = (int) ($_POST['localidad_id'] ?? 0);
  // Usar 'mail' para el POST, y 'email' para la columna de la BD.
  $email_post_value = mysqli_real_escape_string($con, $_POST['mail'] ?? '');
  $cuil = mysqli_real_escape_string($con, $_POST['cuil'] ?? '');
  $observaciones = mysqli_real_escape_string($con, $_POST['observaciones'] ?? '');
  $domicilio = mysqli_real_escape_string($con, $_POST['domicilio'] ?? '');
  $detalle_empleado = mysqli_real_escape_string($con, $_POST['detalle'] ?? '');
  $legajo = mysqli_real_escape_string($con, $_POST['legajo'] ?? '');

  // --- OBTENER ID DE LA ENTIDAD 'Empleado' ---
  $sql_entidad_empleado = "SELECT id FROM entidades WHERE tipo_entidad = 'Empleado'";
  $resultado_entidad = mysqli_query($con, $sql_entidad_empleado);
  if (!$resultado_entidad || mysqli_num_rows($resultado_entidad) == 0) {
    echo '
        <div class="alert alert-danger" role="alert">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <i class="fas fa-exclamation-triangle"></i> Error: La entidad "Empleado" no fue encontrada en la base de datos.
        </div>';
    return;
  }
  $row_entidad = mysqli_fetch_array($resultado_entidad);
  $entidad_empleado_id = $row_entidad['id'];

  // --- INICIO DE TRANSACCIÓN ---
  mysqli_begin_transaction($con);

  try {
    // --- LÓGICA DE ACTUALIZACIÓN O INSERCIÓN EN 'personas' ---
    if ($id > 0) {
      // UPDATE en la tabla 'personas'
      $sql_personas = "UPDATE personas SET
                nombre = '$nombre',
                apellido = '$apellido',
                dni = '$dni',
                telefono = '$telefono',
                localidad_id = $localidad_id,
                email = '$email_post_value', -- Usar la variable con el valor del POST
                cuil = '$cuil',
                observaciones = '$observaciones',
                domicilio = '$domicilio'
                WHERE id = $id";

      $mensaje = "El registro se modificó con éxito";
    } else {
      // INSERT en la tabla 'personas'
      $sql_personas = "INSERT INTO personas (
                nombre,
                apellido,
                dni,
                telefono,
                localidad_id,
                email, -- Usar 'email'
                cuil,
                observaciones,
                domicilio
            ) VALUES (
                '$nombre',
                '$apellido',
                '$dni',
                '$telefono',
                $localidad_id,
                '$email_post_value', -- Usar la variable con el valor del POST
                '$cuil',
                '$observaciones',
                '$domicilio'
            )";
      $mensaje = "El registro se creó con éxito";
    }

    // --- EJECUTAR CONSULTA DE 'personas' ---
    if (!mysqli_query($con, $sql_personas)) {
      throw new Exception("Error al guardar la información de la persona: " . mysqli_error($con));
    }

    // Si fue un INSERT, obtener el ID de la persona recién creada
    if ($id == 0) {
      $id = mysqli_insert_id($con);
    }

    // --- MANEJAR LA TABLA 'empleados' ---
    $sql_check_empleado = "SELECT COUNT(*) as existe FROM empleados WHERE persona_id = $id";
    $resultado_check_empleado = mysqli_query($con, $sql_check_empleado);
    $row_check_empleado = mysqli_fetch_array($resultado_check_empleado);

    if ($row_check_empleado['existe'] > 0) {
      // UPDATE en 'empleados'
      $sql_empleado = "UPDATE empleados SET
                            detalle = '$detalle_empleado',
                            legajo = '$legajo'
                            WHERE persona_id = $id";
    } else {
      // INSERT en 'empleados'
      $sql_empleado = "INSERT INTO empleados (persona_id, detalle, legajo) VALUES ($id, '$detalle_empleado', '$legajo')";
    }

    if (!mysqli_query($con, $sql_empleado)) {
      throw new Exception("Error al guardar el detalle del empleado: " . mysqli_error($con));
    }

    // --- MANEJAR LA TABLA 'personas_entidades' ---
    // Asegurar que la relación persona-entidad 'Empleado' exista
    $sql_check_pe = "SELECT COUNT(*) as existe_pe FROM personas_entidades WHERE persona_id = $id AND entidad_id = $entidad_empleado_id";
    $resultado_check_pe = mysqli_query($con, $sql_check_pe);
    $row_check_pe = mysqli_fetch_array($resultado_check_pe);

    if ($row_check_pe['existe_pe'] == 0) {
      // Si no existe, insertar la relación persona-entidad 'Empleado'
      $sql_insert_pe = "INSERT INTO personas_entidades (persona_id, entidad_id) VALUES ($id, $entidad_empleado_id)";
      if (!mysqli_query($con, $sql_insert_pe)) {
        throw new Exception("Error al guardar la relación persona-entidad: " . mysqli_error($con));
      }
    }
    // Si ya existe, no se hace nada.

    mysqli_commit($con); // Si todo fue bien, confirmar la transacción
    echo '
        <div class="alert alert-primary animated--grow-in" role="alert">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <i class="far fa-check-circle"></i> ' . $mensaje . '
        </div>';
    echo "<script>listado();</script>";
    echo "<script>cerrar_formulario();</script>";

  } catch (Exception $e) {
    mysqli_rollback($con); // Si algo falló, revertir todos los cambios
    echo '
        <div class="alert alert-danger" role="alert">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <i class="fas fa-exclamation-triangle"></i> ' . $e->getMessage() . '
        </div>';
  }
} // <--- ¡Esta es la llave de cierre de la función 'editar'! Asegúrate de que esté aquí.

// --- FUNCIÓN ELIMINAR ---
// (Esta función estaba bien estructurada dentro de sus llaves)
function eliminar($con)
{
  $id = (int) ($_POST['id'] ?? 0); // Aseguramos que $id siempre sea un entero

  // --- INICIO DE TRANSACCIÓN PARA LA ELIMINACIÓN ---
  mysqli_begin_transaction($con);

  try {
    // Primero verificamos si la persona está siendo usada en la tabla usuarios
    $sql_check_usuarios = "SELECT COUNT(*) as usado FROM usuarios WHERE persona_id = $id";
    $resultado_usuarios = mysqli_query($con, $sql_check_usuarios);
    $row_usuarios = mysqli_fetch_array($resultado_usuarios);

    if ($row_usuarios['usado'] > 0) {
      throw new Exception("No se puede eliminar la persona porque está asociada a un usuario.");
    }

    // --- ELIMINAR REGISTROS ASOCIADOS (empleados, personas_entidades) ---
    // Eliminar de 'empleados'
    if (!mysqli_query($con, "DELETE FROM empleados WHERE persona_id = $id")) {
      throw new Exception("Error al eliminar el registro de empleado.");
    }

    // Eliminar de 'personas_entidades'
    if (!mysqli_query($con, "DELETE FROM personas_entidades WHERE persona_id = $id")) {
      throw new Exception("Error al eliminar la relación persona-entidad.");
    }

    // --- ELIMINAR DE 'personas' ---
    if (!mysqli_query($con, "DELETE FROM personas WHERE id = $id")) {
      throw new Exception("Error al eliminar el registro de persona.");
    }

    mysqli_commit($con); // Confirmar si todo fue bien
    echo '
    <div class="alert alert-primary" role="alert">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <i class="far fa-check-circle"></i> El registro se eliminó con éxito
    </div>';

  } catch (Exception $e) {
    mysqli_rollback($con); // Revertir si algo falló
    echo '
    <div class="alert alert-danger" role="alert">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <i class="fas fa-exclamation-triangle"></i> ' . $e->getMessage() . '
    </div>';
  }
}
?>