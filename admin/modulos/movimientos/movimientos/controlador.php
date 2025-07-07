<?php

// Verifica si la función a ejecutar viene en la URL a través del parámetro 'f'.
// Si no viene, se asigna una cadena vacía.
if (isset($_GET['f'])) {
  $function = $_GET['f'];
} else {
  $function = "";
}

// Inicia la sesión de PHP. Esto es necesario para manejar variables de sesión si las hubiera.
session_start();
// Incluye el archivo de conexión a la base de datos. Se asume que este archivo contiene la función conectar().
include("../../../inc/conexion.php");
// Establece la conexión a la base de datos.
$con = conectar();

// Verifica si la función especificada en '$function' existe.
// Si existe, la ejecuta pasando la conexión a la base de datos como parámetro.
// De lo contrario, muestra un mensaje de error.
if (function_exists($function)) {
  $function($con);
} else {
  echo "La función " . $function . " no existe...";
}

/**
 * Función para editar o insertar un registro en la tabla 'movimientos'.
 * @param mysqli $con Objeto de conexión a la base de datos.
 */
function editar($con)
{
  // Recibe los datos del formulario enviados por POST.
  // Se aplica (int) para asegurar que los IDs sean enteros.
  // Se usa mysqli_real_escape_string para escapar caracteres especiales y prevenir inyecciones SQL.
  // Se aplica (float) para asegurar que los montos sean números flotantes.
  $id = (int) $_POST['id']; // ID del movimiento (0 si es nuevo, >0 si se edita)
  $tipo_movimiento = mysqli_real_escape_string($con, $_POST['tipo_movimiento']); // Nuevo: 'ingreso' o 'egreso'
  $numero_factura = mysqli_real_escape_string($con, $_POST['numero_factura']);
  $monto_total = (float) $_POST['monto_total']; // Campo editable, no solo calculado por detalles.
  $entidad_id = (int) $_POST['entidad_id']; // ID de la entidad seleccionada (por ejemplo, 'Clientes', 'Proveedores')
  $concepto_id = (int) $_POST['concepto_id'];
  $fecha_emision_factura = $_POST['fecha_emision_factura'];
  $tipo_moneda_id = (int) $_POST['tipo_moneda_id'];
  $observaciones = mysqli_real_escape_string($con, $_POST['observaciones']);
  $descuento = (float) $_POST['descuento']; // Campo de descuento

  // Declara variables para almacenar el nombre de la columna (persona_id o proveedor_id)
  // y su valor, dependiendo de la entidad seleccionada.
  $relacion_id_attr = ''; // 'persona_id' o 'proveedor_id'
  $relacion_id_value = 'NULL'; // El ID de la persona o proveedor

  // La lógica para determinar si es una persona o un proveedor.
  // El 'entidad_id' 5 es para 'Proveedores' (asumiendo que es el ID para proveedores en tu tabla 'entidades').
  // Si entidad_id es 5, se guarda en proveedor_id; de lo contrario, en persona_id.
  if (isset($_POST['entidad_id']) && !empty($_POST['entidad_id'])) {
    if ($_POST['entidad_id'] == 5) { // Si la entidad seleccionada es 'Proveedores' (ID 5)
      $relacion_id_attr = 'proveedor_id';
      $relacion_id_value = (int) $_POST['persona_id']; // 'persona_id' del formulario en realidad contiene el ID del proveedor
    } else { // Si la entidad seleccionada no es 'Proveedores' (es una persona)
      $relacion_id_attr = 'persona_id';
      $relacion_id_value = (int) $_POST['persona_id'];
    }
  }

  // Verifica si se está editando un registro existente ($id > 0) o creando uno nuevo ($id == 0).
  if ($id > 0) {
    // Lógica para actualizar un registro existente en la tabla 'movimientos'.
    $sql = "UPDATE movimientos SET
            tipo = '$tipo_movimiento',
            numero_factura = '$numero_factura',
            monto_total = '$monto_total',              -- Ahora se actualiza el monto_total directamente desde el formulario
            $relacion_id_attr = $relacion_id_value,
            " . (($relacion_id_attr == 'persona_id') ? "proveedor_id = NULL" : "persona_id = NULL") . ",
            concepto_id = $concepto_id,
            descuento = $descuento,
            fecha_emision_factura = '$fecha_emision_factura',
            tipo_moneda_id = $tipo_moneda_id,
            observaciones = '$observaciones',
            fecha_modificacion = NOW()
            WHERE id = $id";
    $mensaje = "El registro se modificó con éxito";
  } else {
    // Lógica para insertar un nuevo registro en la tabla 'movimientos'.
    $sql = "INSERT INTO movimientos (
            tipo,
            numero_factura,
            monto_total,                            -- Se inserta el monto total ingresado por el usuario
            $relacion_id_attr,
            concepto_id,
            descuento,
            fecha_emision_factura,
            tipo_moneda_id,
            observaciones,
            usuario_abm,
            fecha_creacion,
            fecha_modificacion
            ) VALUES (
            '$tipo_movimiento',
            '$numero_factura',
            '$monto_total',                         -- Valor del monto total ingresado
            $relacion_id_value,
            $concepto_id,
            $descuento,
            '$fecha_emision_factura',
            $tipo_moneda_id,
            '$observaciones',
            'admin',
            NOW(),
            NOW()
            )";
    $mensaje = "El registro se creó con éxito";
  }

  // Ejecuta la consulta SQL y verifica si fue exitosa.
  if (mysqli_query($con, $sql)) {
    // Si la consulta es de inserción, obtiene el ID del último registro insertado.
    if ($id == 0) {
      $id = mysqli_insert_id($con);
    }
    echo '
    <div class="alert alert-primary" role="alert">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <i class="far fa-check-circle"></i> ' . $mensaje . '
    </div>';
    // Redirige a agregar detalles después de 1 segundo si es un nuevo movimiento.
    // Para una edición, no es necesario redirigir automáticamente a detalles.
    if ($_POST['redireccionar_detalles'] == 'true') { // Nuevo campo oculto para controlar la redirección
      echo "<script>setTimeout(function(){ agregarDetalles(" . $id . "); }, 1000);</script>";
    }
  } else {
    // Si la consulta falla, muestra un mensaje de error.
    echo '
    <div class="alert alert-danger" role="alert">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <i class="fas fa-exclamation-triangle"></i> No se pudo guardar el registro: ' . mysqli_error($con) . '
    </div>';
  }
}

/**
 * Función para eliminar un registro.
 * Considerar implementar un borrado lógico (campo 'activo' o 'deleted_at') en lugar de físico.
 * @param mysqli $con Objeto de conexión a la base de datos.
 */
function eliminar($con)
{
  $id = (int) $_POST['id'];
  // Se elimina primero los detalles asociados al movimiento.
  $sql_delete_detalles = "DELETE FROM movimientos_detalle WHERE movimiento_id = $id";
  if (!mysqli_query($con, $sql_delete_detalles)) {
    echo '
      <div class="alert alert-danger" role="alert">
      <button type="button" class="close" data-dismiss="alert">&times;</button>
      <i class="fas fa-exclamation-triangle"></i> No se pudieron eliminar los detalles del movimiento: ' . mysqli_error($con) . '
      </div>';
    return; // Detiene la ejecución si falla la eliminación de detalles.
  }

  // Luego se elimina el movimiento principal.
  $sql = "DELETE FROM movimientos WHERE id = $id";

  // Ejecuta la consulta SQL y verifica si fue exitosa.
  if (mysqli_query($con, $sql)) {
    echo '
    <div class="alert alert-primary" role="alert">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <i class="far fa-check-circle"></i> El registro y sus detalles se eliminaron con éxito
    </div>';
  } else {
    echo '
    <div class="alert alert-danger" role="alert">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <i class="fas fa-exclamation-triangle"></i> No se pudo eliminar el registro: ' . mysqli_error($con) . '
    </div>';
  }
}

/**
 * Función para cargar personas o proveedores en un select HTML.
 * @param mysqli $con Objeto de conexión a la base de datos.
 */
function cargar_personas($con)
{
  // Si se recibe un 'entidad_id' por GET.
  if (isset($_GET['entidad_id'])) {
    $entidad_id = (int) $_GET['entidad_id'];
    $selected_persona_id = isset($_GET['selected_persona_id']) ? (int) $_GET['selected_persona_id'] : null; // Para precargar selección

    $sql_personas = ""; // Inicializa la variable SQL.
    // Si 'entidad_id' es 5 (asumiendo que 5 es el ID para Proveedores).
    if ($entidad_id == 5) {
      $sql_personas = "SELECT id, razon_social as nombre_completo FROM proveedores ORDER BY razon_social";
    } else {
      // Para cualquier otra entidad, se buscan personas asociadas a esa entidad.
      $sql_personas = "SELECT id, CONCAT(nombres, ' ', apellido) AS nombre_completo FROM personas WHERE entidad_id = $entidad_id ORDER BY nombre_completo";
    }

    // Ejecuta la consulta para obtener las personas/proveedores.
    $resultado_personas = mysqli_query($con, $sql_personas);

    // Genera las opciones HTML para el select de personas/proveedores.
    echo '<option selected disabled value="">Seleccionar</option>'; // Opción por defecto
    while ($row_persona = mysqli_fetch_array($resultado_personas)) {
      $selected = ($selected_persona_id == $row_persona['id']) ? "selected" : "";
      echo '<option value="' . $row_persona['id'] . '" ' . $selected . '>' . htmlspecialchars($row_persona['nombre_completo']) . '</option>';
    }
  }
}


/**
 * Función para editar o insertar un registro en la tabla 'movimientos_detalle'.
 * @param mysqli $con Objeto de conexión a la base de datos.
 */
function editar_detalle($con)
{
  // Recibe los datos del formulario de detalle.
  $movimiento_id = (int) $_POST['egreso_id']; // Renombrado a movimiento_id para consistencia
  $detalle_id = (int) $_POST['detalle_id'];

  $monto_detalle = (float) $_POST['monto_detalle'];
  $cantidad = (float) $_POST['cantidad'];
  $metodo_pago_id = (int) $_POST['metodo_pago_id'];
  $observaciones = mysqli_real_escape_string($con, $_POST['observaciones']);

  // Verifica si se está editando un detalle existente ($detalle_id > 0) o creando uno nuevo.
  if ($detalle_id > 0) {
    // Actualiza un registro existente en la tabla 'movimientos_detalle'.
    $sql = "UPDATE movimientos_detalle SET
            monto = '$monto_detalle',
            cantidad = '$cantidad',
            metodo_pago_id = $metodo_pago_id,
            observaciones = '$observaciones'
            WHERE id = $detalle_id";

    $mensaje = "El detalle se modificó con éxito";
  } else {
    // Inserta un nuevo registro en la tabla 'movimientos_detalle'.
    $sql = "INSERT INTO movimientos_detalle (
            movimiento_id,
            monto,
            cantidad,
            metodo_pago_id,
            observaciones
            ) VALUES (
            $movimiento_id,
            '$monto_detalle',
            '$cantidad',
            $metodo_pago_id,
            '$observaciones'
            )";

    $mensaje = "El detalle se creó con éxito";
  }

  // Ejecuta la consulta SQL y verifica si fue exitosa.
  if (mysqli_query($con, $sql)) {
    echo '
    <div class="alert alert-primary" role="alert">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <i class="far fa-check-circle"></i> ' . $mensaje . '
    </div>';
    // Llama a la función para refrescar la lista de detalles después de guardar.
    // Esto es importante para que el usuario vea los cambios sin recargar toda la página.
    echo "<script>
            // Después de guardar el detalle, recargar los detalles para actualizar la tabla.
            agregarDetalles(" . $movimiento_id . ");
          </script>";
  } else {
    // Si la consulta falla, muestra un mensaje de error.
    echo '
    <div class="alert alert-danger" role="alert">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <i class="fas fa-exclamation-triangle"></i> No se pudo guardar el detalle: ' . mysqli_error($con) . '
    </div>';
  }
}

/**
 * Función para eliminar un detalle de un movimiento.
 * @param mysqli $con Objeto de conexión a la base de datos.
 */
function eliminar_detalle($con)
{
  $detalle_id = (int) $_POST['id'];
  $movimiento_id = (int) $_POST['movimiento_id'];

  $sql = "DELETE FROM movimientos_detalle WHERE id = $detalle_id";

  if (mysqli_query($con, $sql)) {
    echo '
        <div class="alert alert-primary" role="alert">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <i class="far fa-check-circle"></i> El detalle se eliminó con éxito
        </div>';
    echo "<script>
                // Después de eliminar el detalle, recargar los detalles para actualizar la tabla.
                agregarDetalles(" . $movimiento_id . ");
              </script>";
  } else {
    echo '
        <div class="alert alert-danger" role="alert">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <i class="fas fa-exclamation-triangle"></i> No se pudo eliminar el detalle: ' . mysqli_error($con) . '
        </div>';
  }
}