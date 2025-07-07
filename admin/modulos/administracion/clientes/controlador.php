<?php
// controlador.php (del módulo clientes)

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
{
  
  $id = (int) ($_POST['id'] ?? 0);
  $usuario_abm = mysqli_real_escape_string($con, $_SESSION['usuario'] ?? 'SYSTEM'); //

  $persona_id = (int) ($_POST['persona_id'] ?? 0);
  $nombre = mysqli_real_escape_string($con, $_POST['nombre'] ?? '');
  $apellido = mysqli_real_escape_string($con, $_POST['apellido'] ?? '');
  $dni = mysqli_real_escape_string($con, $_POST['dni'] ?? '');
  $telefono = mysqli_real_escape_string($con, $_POST['telefono'] ?? '');
  $localidad_id = (int) ($_POST['localidad_id'] ?? 0);
  $email = mysqli_real_escape_string($con, $_POST['mail'] ?? '');
  $cuil = mysqli_real_escape_string($con, $_POST['cuil'] ?? '');
  $observaciones = mysqli_real_escape_string($con, $_POST['observaciones'] ?? '');
  $domicilio = mysqli_real_escape_string($con, $_POST['domicilio'] ?? '');
  $detalle_cliente = mysqli_real_escape_string($con, $_POST['detalle'] ?? ''); // Campo específico para clientes

  // --- OBTENER ID DE LA ENTIDAD 'Cliente' ---
  $sql_entidad_cliente = "SELECT id FROM entidades WHERE tipo_entidad = 'Cliente'";
  $resultado_entidad = mysqli_query($con, $sql_entidad_cliente);
  if (!$resultado_entidad || mysqli_num_rows($resultado_entidad) == 0) {
    echo '
        <div class="alert alert-danger" role="alert">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <i class="fas fa-exclamation-triangle"></i> Error: La entidad "Cliente" no fue encontrada en la base de datos.
        </div>';
    return;
  }
  $row_entidad = mysqli_fetch_array($resultado_entidad);
  $entidad_cliente_id = $row_entidad['id'];

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
                email = '$email',
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
                email,
                cuil,
                observaciones,
                domicilio
            ) VALUES (
                '$nombre',
                '$apellido',
                '$dni',
                '$telefono',
                $localidad_id,
                '$email',
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

    // --- MANEJAR LA TABLA 'clientes' ---
    $sql_check_cliente = "SELECT COUNT(*) as existe FROM clientes WHERE persona_id = $id";
    $resultado_check_cliente = mysqli_query($con, $sql_check_cliente);
    $row_check_cliente = mysqli_fetch_array($resultado_check_cliente);

    if ($row_check_cliente['existe'] > 0) {
      // UPDATE en 'clientes'
      $sql_cliente = "UPDATE clientes SET detalle = '$detalle_cliente' WHERE persona_id = $id";
    } else {
      // INSERT en 'clientes'
      $sql_cliente = "INSERT INTO clientes (persona_id, detalle) VALUES ($id, '$detalle_cliente')";
    }

    if (!mysqli_query($con, $sql_cliente)) {
      throw new Exception("Error al guardar el detalle del cliente: " . mysqli_error($con));
    }

    // --- MANEJAR LA TABLA 'personas_entidades' ---
    // Asegurar que la relación persona-entidad 'Cliente' exista
    $sql_check_pe = "SELECT COUNT(*) as existe_pe FROM personas_entidades WHERE persona_id = $id AND entidad_id = $entidad_cliente_id";
    $resultado_check_pe = mysqli_query($con, $sql_check_pe);
    $row_check_pe = mysqli_fetch_array($resultado_check_pe);

    if ($row_check_pe['existe_pe'] == 0) {
      // Si no existe, insertar la relación persona-entidad 'Cliente'
      $sql_insert_pe = "INSERT INTO personas_entidades (persona_id, entidad_id) VALUES ($id, $entidad_cliente_id)";
      if (!mysqli_query($con, $sql_insert_pe)) {
        throw new Exception("Error al guardar la relación persona-entidad: " . mysqli_error($con));
      }
    }

    mysqli_commit($con);
    echo '
        <div class="alert alert-primary animated--grow-in" role="alert">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <i class="far fa-check-circle"></i> ' . $mensaje . '
        </div>';
    echo "<script>listado();</script>";
    echo "<script>cerrar_formulario();</script>";

  } catch (Exception $e) {
    mysqli_rollback($con);
    echo '
        <div class="alert alert-danger" role="alert">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <i class="fas fa-exclamation-triangle"></i> ' . $e->getMessage() . '
        </div>';
  }
}

// --- FUNCIÓN ELIMINAR ---
function eliminar($con)
{
  $id = (int) ($_POST['id'] ?? 0);

  mysqli_begin_transaction($con);

  try {
    $sql_check_usuarios = "SELECT COUNT(*) as usado FROM usuarios WHERE persona_id = $id";
    $resultado_usuarios = mysqli_query($con, $sql_check_usuarios);
    $row_usuarios = mysqli_fetch_array($resultado_usuarios);

    if ($row_usuarios['usado'] > 0) {
      throw new Exception("No se puede eliminar la persona porque está asociada a un usuario.");
    }

    // Eliminar de 'clientes'
    if (!mysqli_query($con, "DELETE FROM clientes WHERE persona_id = $id")) {
      throw new Exception("Error al eliminar el registro de cliente.");
    }

    // Eliminar de 'personas_entidades'
    if (!mysqli_query($con, "DELETE FROM personas_entidades WHERE persona_id = $id")) {
      throw new Exception("Error al eliminar la relación persona-entidad.");
    }

    // Eliminar de 'personas'
    if (!mysqli_query($con, "DELETE FROM personas WHERE id = $id")) {
      throw new Exception("Error al eliminar el registro de persona.");
    }

    mysqli_commit($con);
    echo '
    <div class="alert alert-primary" role="alert">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <i class="far fa-check-circle"></i> El registro se eliminó con éxito
    </div>';

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