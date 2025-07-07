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
  echo "La función " . $function . " no existe...";
}

function editar($con)
{
  // Recibe los datos del formulario
  $id = (int)$_POST['id'];
  $numero_factura = mysqli_real_escape_string($con, $_POST['numero_factura']);
  $monto_total = (float)$_POST['monto_total'];
  $entidad_id = (int)$_POST['entidad_id'];

  $concepto_id = (int)$_POST['concepto_id'];
  $fecha_emision_factura = $_POST['fecha_emision_factura'];
  $tipo_moneda_id = (int)$_POST['tipo_moneda_id'];
  $observaciones = mysqli_real_escape_string($con, $_POST['observaciones']);

  // Verifica si se está editando o creando un nuevo registro
  if ($id > 0) {

    if (isset($_POST['entidad_id']) && !empty($_POST['entidad_id']) && $_POST['entidad_id'] != 5) {
      $entidad_id = 'persona_id = ' . (int)$_POST['persona_id'];
      $entidad_id1 = 'proveedor_id = null';
    } else {
      $entidad_id = 'proveedor_id = ' . (int)$_POST['persona_id'];
      $entidad_id1 = 'persona_id = null';
    }

    // Actualizar registro existente
    $sql = "UPDATE ingresos SET 
            numero_factura = '$numero_factura',
            monto_total = '$monto_total',
            $entidad_id,
            $entidad_id1,
            concepto_id = $concepto_id,
            fecha_emision_factura = '$fecha_emision_factura',
            tipo_moneda_id = $tipo_moneda_id,
            observaciones = '$observaciones'
            WHERE id = $id";
    $mensaje = "El registro se modificó con éxito";
  } else {

    if (isset($_POST['entidad_id']) && !empty($_POST['entidad_id']) && $_POST['entidad_id'] != 5) {

      $entidad_id_value = (int)$_POST['persona_id'];
      $entidad_id_attr = 'persona_id';
    } else {

      $entidad_id_value = (int)$_POST['persona_id'];
      $entidad_id_attr = 'proveedor_id';
    }
    // Insertar nuevo registro
    $sql = "INSERT INTO ingresos (
            numero_factura, 
            monto_total, 
            $entidad_id_attr,
            concepto_id, 
            fecha_emision_factura, 
            tipo_moneda_id, 
            observaciones
            ) VALUES (
            '$numero_factura', 
            '$monto_total', 
            $entidad_id_value, 
            $concepto_id, 
            '$fecha_emision_factura', 
            $tipo_moneda_id, 
            '$observaciones'
            )";
    $mensaje = "El registro se creó con éxito";
  }

  // Ejecuta la consulta SQL
  if (mysqli_query($con, $sql)) {
    echo '
    <div class="alert alert-primary" role="alert">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <i class="far fa-check-circle"></i> ' . $mensaje . '
    </div>';
  } else {
    echo '
    <div class="alert alert-danger" role="alert">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <i class="fas fa-exclamation-triangle"></i> No se pudo guardar el registro
    </div>';
  }
}

function eliminar($con)
{
  $id = (int)$_POST['id'];
  $sql = "DELETE FROM usuarios WHERE id = $id";

  if (mysqli_query($con, $sql)) {
    echo '
    <div class="alert alert-primary" role="alert">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <i class="far fa-check-circle"></i> El registro se eliminó con éxito
    </div>';
  } else {
    echo '
    <div class="alert alert-danger" role="alert">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <i class="fas fa-exclamation-triangle"></i> No se pudo eliminar el registro
    </div>';
  }
}

function cargar_personas($con)
{
  // Si viene un id de entidad, cargo las personas
  if (isset($_GET['entidad_id'])) {
    $entidad_id = (int)$_GET['entidad_id'];

    // pregunto si entidad es = 5. entonces es un proveedor
    if ($entidad_id == 5) {
      echo $sql_personas = "SELECT id, razon_social as nombre_completo FROM proveedores ORDER BY razon_social";
    } else {
      $sql_personas = "SELECT id, CONCAT(nombres, ' ', apellido) AS nombre_completo FROM personas WHERE entidad_id = $entidad_id ORDER BY nombre_completo";
    }
    // Consulta para obtener personas asociadas a la entidad seleccionada
    $resultado_personas = mysqli_query($con, $sql_personas);
    // Generar opciones para el select de personas
    while ($row_persona = mysqli_fetch_array($resultado_personas)) {
      echo '<option value="' . $row_persona['id'] . '">' . $row_persona['nombre_completo'] . '</option>';
    }
  }
}


function cargar_proveedores($con)
{
  $sql = "SELECT id, nombres FROM proveedores";
  $resultado = mysqli_query($con, $sql);

  while ($row = mysqli_fetch_array($resultado)) {
    echo '<option value="' . $row['id'] . '">' . $row['nombre'] . '</option>';
  }
}


function editar_detalle($con)
{
  // Recibe los datos del formulario
  $ingreso_id = (int)$_POST['ingreso_id'];
  $detalle_id = (int)$_POST['detalle_id'];

  $monto_detalle = (float)$_POST['monto_detalle'];
  $cantidad = $_POST['cantidad'];
  $metodo_pago_id = (int)$_POST['metodo_pago_id'];
  $estado_id = (int)$_POST['estado_id'];
  $observaciones = mysqli_real_escape_string($con, $_POST['observaciones']);

  // Verifica si se está editando o creando un nuevo registro
  if ($detalle_id > 0) {

    // actualizo tabla ingresos_detalle
    $sql = "UPDATE ingresos_detalle SET 
            monto = '$monto_detalle',
            cantidad = '$cantidad',
            metodo_pago_id = $metodo_pago_id,
            estado_id = $estado_id,
            observaciones = '$observaciones'
            WHERE id = $detalle_id";

    $mensaje = "El registro se modificó con éxito";
  } else {

    // Insertar nuevo registro
    $sql = "INSERT INTO ingresos_detalle (
            monto, 
            cantidad, 
            metodo_pago_id, 
            estado_id, 
            ingreso_id,
            observaciones
            ) VALUES (
            '$monto_detalle', 
            '$cantidad', 
            $metodo_pago_id, 
            $estado_id, 
            $ingreso_id,
            '$observaciones'
            )"; 
    
    $mensaje = "El registro se creó con éxito";
  }

  // Ejecuta la consulta SQL
  if (mysqli_query($con, $sql)) {
    echo '
    <div class="alert alert-primary" role="alert">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <i class="far fa-check-circle"></i> ' . $mensaje . '
    </div>';
  } else {
    echo '
    <div class="alert alert-danger" role="alert">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <i class="fas fa-exclamation-triangle"></i> No se pudo guardar el registro
    </div>';
  }
}
