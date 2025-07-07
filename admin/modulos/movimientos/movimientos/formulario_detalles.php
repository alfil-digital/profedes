<?php

session_start();
include("../../../inc/conexion.php");
$con = conectar(); // Asegúrate de llamar a la función para obtener la conexión

// El ID del movimiento (`egreso_id` en el original, ahora `movimiento_id`) debe venir siempre.
// Si no viene, no se puede agregar detalles.
$movimiento_id = (int)$_GET['id']; // ID del movimiento principal
$detalle_id = (int)$_GET['detalle_id']; // ID del detalle si se está editando (0 si es nuevo)

// Si el movimiento_id es 0, no se puede continuar.
if ($movimiento_id == 0) {
    echo '<div class="alert alert-danger" role="alert">No se puede cargar detalles sin un ID de movimiento válido.</div>';
    echo '<script>setTimeout(function(){ cerrar_formulario(); }, 3000);</script>'; // Cierra el formulario después de un tiempo
    exit; // Termina la ejecución
}

// Consulta para obtener la información del movimiento principal
$sql_movimiento = "SELECT
            m.id,
            m.numero_factura,
            m.tipo, -- Nuevo: Obtener el tipo de movimiento
            case when m.persona_id is not null then (select concat(nombres,' ',apellido) from personas where id = m.persona_id)
                when m.proveedor_id is not null then (select razon_social from proveedores where id = m.proveedor_id)
                else 'N/A' -- Si no tiene persona ni proveedor asociado
            end as nombre_relacionado
        FROM
            movimientos m
        WHERE
            m.id = $movimiento_id;";

$resultado_movimiento = mysqli_query($con, $sql_movimiento);
$row_movimiento = mysqli_fetch_array($resultado_movimiento);

// Inicialización de variables para el detalle
$monto_detalle = 0;
$cantidad = 0;
$metodo_pago_id = null;
$observaciones_detalle = "";

// Obtener los datos del detalle a editar si `detalle_id` no es 0
if ($detalle_id != 0) {
  $sql_detalle = "SELECT
                md.id,
                md.monto,
                md.cantidad,
                md.metodo_pago_id,
                md.observaciones
            FROM
                movimientos_detalle md
            WHERE
                md.id = $detalle_id;";

  $resultado_detalle = mysqli_query($con, $sql_detalle);
  $row_detalle = mysqli_fetch_array($resultado_detalle);

  // Asignar los valores del detalle a las variables
  if ($row_detalle) {
    $monto_detalle = $row_detalle['monto'];
    $cantidad = $row_detalle['cantidad'];
    $metodo_pago_id = $row_detalle['metodo_pago_id'];
    $observaciones_detalle = $row_detalle['observaciones'];
  }
}
?>

<div class="content" class="container-fluid bg-light p-3">

  <h3> Agrear Detalle a la Factura Nro. <?= htmlspecialchars($row_movimiento['numero_factura']) . ' (' . htmlspecialchars($row_movimiento['nombre_relacionado']) . ')' ?></h3>
  <small>Tipo de Movimiento: <?= htmlspecialchars(ucfirst($row_movimiento['tipo'])) ?></small>

  <form method="post" id="form" class="row needs-validation">
    <input type="hidden" class="form-control" id="egreso_id" name="egreso_id" value="<?= htmlspecialchars($movimiento_id) ?>"> <input type="hidden" class="form-control" id="detalle_id" name="detalle_id" value="<?= htmlspecialchars($detalle_id) ?>">


    <div class="col-md-3 position-relative">
      <label for="monto_detalle" class="form-label">Monto</label>
      <input type="number" step="0.01" class="form-control" id="monto_detalle" name="monto_detalle" placeholder="Monto" required value="<?= htmlspecialchars($monto_detalle); ?>">
      <div class="invalid-feedback">
        Controlar el campo
      </div>
    </div>

    <div class="col-md-3 position-relative">
      <label for="cantidad" class="form-label">Cantidad</label>
      <input type="number" step="0.01" class="form-control" id="cantidad" name="cantidad" placeholder="Cantidad" required value="<?= htmlspecialchars($cantidad); ?>">
      <div class="invalid-feedback">
        Controlar el campo
      </div>
    </div>

    <div class="col-md-3 position-relative">
      <label for="metodo_pago_id" class="form-label">Método de Pago</label>
      <select class="form-control" id="metodo_pago_id" name="metodo_pago_id" required>
        <option selected disabled value="">Seleccionar</option>
        <?php
        $sql_metodo_pago = "SELECT id, descripcion FROM metodo_pago ORDER BY descripcion";
        $resultado_metodo_pago = mysqli_query($con, $sql_metodo_pago);
        while ($row_metodo_pago = mysqli_fetch_array($resultado_metodo_pago)) {
          $selected = (isset($metodo_pago_id) && $metodo_pago_id == $row_metodo_pago['id']) ? "selected" : "";
        ?>
          <option <?= $selected; ?> value="<?= htmlspecialchars($row_metodo_pago['id']); ?>">
            <?= htmlspecialchars($row_metodo_pago['descripcion']); ?>
          </option>
        <?php } ?>
      </select>
      <div class="invalid-feedback">
        Seleccione un método de pago
      </div>
    </div>

    <div class="col-md-3 position-relative">
      <label for="observaciones" class="form-label">Observaciones </label>
      <textarea class="form-control" id="observaciones" name="observaciones" placeholder="Observaciones"><?= htmlspecialchars($observaciones_detalle); ?></textarea>
      <div class="invalid-feedback">
        Controlar el campo
      </div>
    </div>

  </form>

  <div class="mt-4" align="center">
    <button type="submit" class="btn btn-primary" onclick="agregar_detalle()">Guardar Detalle</button>
    <button type="button" class="btn btn-danger" onclick="cerrar_formulario()">Cancelar</button>
  </div>

  <br>

  <table class="table table-striped table-hover table-bordered" id="dataTableDetalles" width="100%" cellspacing="0">
    <thead>
      <tr>
        <th>ID</th>
        <th>Monto</th>
        <th>Cantidad</th>
        <th>Método de Pago</th>
        <th>Observaciones</th>
        <th>Acciones</th>
      </tr>
    </thead>
    <tbody>
      <?php
      // Consulta para obtener todos los detalles asociados a este movimiento
      $sql_get_detalle = "SELECT
                md.id,
                md.monto,
                md.cantidad,
                mp.descripcion as metodo_pago,
                md.observaciones
            FROM
                movimientos_detalle md
            LEFT JOIN
                metodo_pago mp ON md.metodo_pago_id = mp.id
            WHERE
                md.movimiento_id = $movimiento_id;";

      $resultado_get_detalle = mysqli_query($con, $sql_get_detalle); // Ejecuta la consulta

      // Verifica si hay detalles para mostrar
      if (mysqli_num_rows($resultado_get_detalle) > 0) {
        while ($row_get_detalle = mysqli_fetch_array($resultado_get_detalle)) { // Itera sobre los resultados
          $detalle_get_id = htmlspecialchars($row_get_detalle['id']);
          $monto_mostrar = htmlspecialchars($row_get_detalle['monto']);
          $cantidad_mostrar = htmlspecialchars($row_get_detalle['cantidad']);
          $metodo_pago_mostrar = htmlspecialchars($row_get_detalle['metodo_pago']);
          $observaciones_mostrar = htmlspecialchars($row_get_detalle['observaciones']);

          // Aquí se quitó la lógica de 'estado' del detalle, ya que no existe en `movimientos_detalle`.
          // Si necesitas un estado por detalle, deberías agregarlo a la tabla `movimientos_detalle`.
      ?>
          <tr>
            <td align="center"><?= $detalle_get_id; ?></td>
            <td><?= $monto_mostrar; ?></td>
            <td><?= $cantidad_mostrar; ?></td>
            <td><?= $metodo_pago_mostrar; ?></td>
            <td><?= $observaciones_mostrar; ?></td>
            <td>
              <a onclick="agregarDetalles(<?= $movimiento_id; ?>, <?= $detalle_get_id; ?>)" class="btn btn-primary btn-icon-split" title="Editar Detalle">
                <span class="icon text-white-50">
                  <i class="fas fa-edit"></i>
                </span>
              </a>
              <a onclick="eliminarDetalle(<?= $detalle_get_id; ?>, <?= $movimiento_id; ?>)" class="btn btn-danger btn-icon-split" title="Eliminar Detalle">
                <span class="icon text-white-50">
                  <i class="fas fa-trash"></i>
                </span>
              </a>
            </td>
          </tr>
        <?php }
      } else {
        echo "<tr><td colspan='6'>No hay detalles cargados para este movimiento.</td></tr>";
      } ?>
    </tbody>
  </table>

</div>

<hr>