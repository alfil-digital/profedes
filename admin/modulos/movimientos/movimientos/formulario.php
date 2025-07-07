<?php
session_start();
include("../../../inc/conexion.php");
$con = conectar(); // Asegúrate de llamar a la función para obtener la conexión

// Inicialización de variables para el formulario
$id = 0; // ID del movimiento, 0 si es nuevo
$numero_factura = " "; // Valor por defecto
$monto_total = 0;
$persona_id = null;
$proveedor_id = null;
$entidad_id = null;
$concepto_id = null;
$fecha_emision_factura = date('Y-m-d'); // Fecha actual por defecto
$tipo_moneda_id = null;
$observaciones = "";
$descuento = 0; // Campo de descuento
$tipo_movimiento = isset($_GET['tipo']) ? $_GET['tipo'] : 'egreso'; // Tipo de movimiento (ingreso/egreso)

// Si viene un ID en la URL, significa que se está editando un registro existente.
if (isset($_GET['id']) && $_GET['id'] != 0) {
  $id = (int) $_GET['id'];
  // Consulta para obtener los datos del movimiento a editar.
  $sql = "SELECT m.id
                      ,m.tipo
                      ,m.numero_factura
                      ,m.monto_total
                      ,m.persona_id
                      ,(SELECT entidad_id FROM personas WHERE id = m.persona_id) AS entidad_id -- Obtiene la entidad asociada a la persona
                      ,m.proveedor_id
                      ,m.concepto_id
                      ,m.descuento
                      ,m.fecha_emision_factura
                      ,m.tipo_moneda_id
                      ,m.observaciones
               FROM movimientos m WHERE id = $id";

  $resultado = mysqli_query($con, $sql);
  $row = mysqli_fetch_array($resultado);

  // Asignar los valores del registro a las variables del formulario
  if ($row) {
    $numero_factura = $row['numero_factura'];
    $monto_total = $row['monto_total'];
    $persona_id = $row['persona_id'];
    $proveedor_id = $row['proveedor_id'];
    $entidad_id = $row['entidad_id']; // Si es persona, viene de subconsulta; si es proveedor, será null
    $concepto_id = $row['concepto_id'];
    $fecha_emision_factura = $row['fecha_emision_factura'];
    $tipo_moneda_id = $row['tipo_moneda_id'];
    $observaciones = $row['observaciones'];
    $descuento = $row['descuento'];
    $tipo_movimiento = $row['tipo']; // Asigna el tipo de movimiento desde la base de datos

    // Si es un proveedor, la entidad_id no vendrá de la subconsulta de personas.
    // Asumimos que `entidad_id` 5 es para proveedores.
    if (!is_null($proveedor_id) && is_null($persona_id)) {
      $entidad_id = 5; // Asigna el ID de entidad de proveedor
    }
  }

} else {
  // Lógica para un nuevo registro: obtener el último número de factura y sumarle 1.
  // La consulta de número de factura debe filtrar por 'tipo' de movimiento si es necesario,
  // o si los números de factura son globales para ingresos y egresos.
  $sql = "SELECT numero_factura FROM movimientos ORDER BY id DESC LIMIT 1";
  $resultado = mysqli_query($con, $sql);
  $row_last_factura = mysqli_fetch_array($resultado);

  // Verifica si se obtuvo un resultado
  if ($row_last_factura && isset($row_last_factura['numero_factura'])) {
    $partes_factura = explode("-", $row_last_factura['numero_factura']);
    // Asegura que hay al menos dos partes y la segunda es numérica
    if (count($partes_factura) == 2 && is_numeric($partes_factura[1])) {
      $numero_secuencial = (int) $partes_factura[1] + 1;
      $numero_factura = $partes_factura[0] . "-" . str_pad($numero_secuencial, 4, '0', STR_PAD_LEFT);
    } else {
      $numero_factura = "0000-0001"; // Si el formato no es el esperado
    }
  } else {
    // Asigna un valor predeterminado si no hay resultados en la tabla.
    $numero_factura = "0000-0001";
  }
}
?>

<form method="post" id="form" class="row needs-validation">
  <input type="hidden" class="form-control" id="id" name="id" value="<?php echo htmlspecialchars($id); ?>">
  <input type="hidden" id="redireccionar_detalles" name="redireccionar_detalles"
    value="<?= ($id == 0) ? 'true' : 'false'; ?>">


  <div class="col-md-3 position-relative">
    <label for="tipo_movimiento" class="form-label">Tipo de Movimiento</label>
    <select class="form-control" id="tipo_movimiento" name="tipo_movimiento" required>
      <option value="egreso" <?= ($tipo_movimiento == 'egreso') ? 'selected' : ''; ?>>Egreso</option>
      <option value="ingreso" <?= ($tipo_movimiento == 'ingreso') ? 'selected' : ''; ?>>Ingreso</option>
    </select>
    <div class="invalid-feedback">
      Seleccione el tipo de movimiento
    </div>
  </div>

  <div class="col-md-3 position-relative">
    <label for="numero_factura" class="form-label">Nro Factura</label>
    <input type="text" class="form-control" id="numero_factura" name="numero_factura" placeholder="0000-0000" required
      minlength="3" value="<?= htmlspecialchars($numero_factura) ?>">
    <div class="invalid-feedback">
      Controlar el campo
    </div>
  </div>

  <div class="col-md-3 position-relative">
    <label for="monto_total" class="form-label">Monto Total</label>
    <input type="number" step="0.01" class="form-control" id="monto_total" name="monto_total" placeholder="0.00"
      required value="<?= htmlspecialchars($monto_total) ?>">
    <div class="invalid-feedback">
      Controlar el campo
    </div>
  </div>

  <div class="col-md-3 position-relative">
    <label for="descuento" class="form-label">Descuento</label>
    <input type="number" step="0.01" class="form-control" id="descuento" name="descuento"
      value="<?= htmlspecialchars($descuento) ?>">
    <div class="invalid-feedback">
      Controlar el campo
    </div>
  </div>

  <div class="col-md-3 position-relative">
    <label for="entidad_id" class="form-label">Entidad</label>
    <select class="form-control" id="entidad_id" name="entidad_id" required
      onchange="cargarPersonas(this.value, '<?= ($id != 0 && ($persona_id || $proveedor_id)) ? (($persona_id) ? $persona_id : $proveedor_id) : ''; ?>')">
      <option selected disabled value="">Seleccionar</option>
      <?php
      $sql_entidades = "SELECT id, tipo_entidad FROM entidades ORDER BY tipo_entidad";
      $resultado_entidades = mysqli_query($con, $sql_entidades);
      while ($row_entidad = mysqli_fetch_array($resultado_entidades)) {
        $selected = '';
        // Si estamos editando y la entidad_id del movimiento coincide con la entidad actual
        if (isset($entidad_id) && $entidad_id == $row_entidad['id']) {
          $selected = "selected";
        }
        ?>
        <option <?= $selected; ?> value="<?= htmlspecialchars($row_entidad['id']); ?>">
          <?= htmlspecialchars($row_entidad['tipo_entidad']); ?>
        </option>
      <?php } ?>
    </select>
    <div class="invalid-feedback">
      Seleccione una entidad
    </div>
  </div>

  <div class="col-md-3 position-relative">
    <label for="persona_id" class="form-label">Persona/Proveedor</label>
    <select class="form-control" id="persona_id" name="persona_id" required>
      <option selected disabled value="">Seleccionar</option>
      <?php
      // Si se está editando, precargar las personas/proveedores asociadas a la entidad ya seleccionada
      // La función cargarPersonas en JS ahora se encargará de esto si se llama desde el onchange.
      // Aquí solo se precargan si el formulario se carga por primera vez en modo edición.
      if ($id != 0 && isset($entidad_id)) {
        $sql_relacion = "";
        $selected_id = null;
        if ($entidad_id == 5) { // Si la entidad es Proveedor
          $sql_relacion = "SELECT id, razon_social AS nombre_completo FROM proveedores ORDER BY razon_social";
          $selected_id = $proveedor_id;
        } else { // Si la entidad es Persona
          $sql_relacion = "SELECT id, CONCAT(nombres, ' ', apellido) AS nombre_completo FROM personas WHERE entidad_id = $entidad_id ORDER BY nombre_completo";
          $selected_id = $persona_id;
        }

        if (!empty($sql_relacion)) {
          $resultado_relacion = mysqli_query($con, $sql_relacion);
          while ($row_relacion = mysqli_fetch_array($resultado_relacion)) {
            $selected = ($selected_id == $row_relacion['id']) ? "selected" : "";
            echo '<option ' . $selected . ' value="' . htmlspecialchars($row_relacion['id']) . '">' . htmlspecialchars($row_relacion['nombre_completo']) . '</option>';
          }
        }
      }
      ?>
    </select>
    <div class="invalid-feedback">
      Controlar el campo
    </div>
  </div>

  <div class="col-md-3 position-relative">
    <label for="concepto_id" class="form-label">Concepto</label>
    <select class="form-control" id="concepto_id" name="concepto_id" required>
      <option selected disabled value="">Seleccionar</option>
      <?php
      $sql_conceptos = "SELECT id, nombre FROM conceptos ORDER BY nombre";
      $resultado_conceptos = mysqli_query($con, $sql_conceptos);
      while ($row_concepto = mysqli_fetch_array($resultado_conceptos)) {
        $selected = (isset($concepto_id) && ($concepto_id == $row_concepto['id'])) ? "selected" : "";
        ?>
        <option <?= $selected; ?> value="<?= htmlspecialchars($row_concepto['id']); ?>">
          <?= htmlspecialchars($row_concepto['nombre']); ?></option>
      <?php } ?>
    </select>
    <div class="invalid-feedback">
      Seleccione un concepto
    </div>
  </div>

  <div class="col-md-3 position-relative">
    <label for="fecha_emision_factura" class="form-label">Fecha Emisión
      <?php if ($id != 0)
        echo "[Actual: " . htmlspecialchars($fecha_emision_factura) . "]"; ?></label>
    <input type="date" class="form-control" id="fecha_emision_factura" name="fecha_emision_factura" required
      value="<?= htmlspecialchars($fecha_emision_factura); ?>">
    <div class="invalid-feedback">
      Controlar el campo
    </div>
  </div>

  <div class="col-md-3 position-relative">
    <label for="tipo_moneda_id" class="form-label">Moneda</label>
    <select class="form-control" id="tipo_moneda_id" name="tipo_moneda_id" required>
      <option selected disabled value="">Seleccionar</option>
      <?php
      $sql_moneda = "SELECT id, concat(nombre,' (',abreviatura,')')  as moneda FROM tipo_moneda order by nombre";
      $resultado_moneda = mysqli_query($con, $sql_moneda);
      while ($row_moneda = mysqli_fetch_array($resultado_moneda)) {
        $selected = (isset($tipo_moneda_id) && $tipo_moneda_id == $row_moneda['id']) ? "selected" : "";
        $texto_option = $row_moneda['moneda'];
        ?>
        <option <?php echo $selected; ?> value="<?php echo htmlspecialchars($row_moneda['id']); ?>">
          <?php echo htmlspecialchars($texto_option); ?>
        </option>
      <?php } ?>
    </select>
    <div class="invalid-feedback">
      Seleccione una moneda
    </div>
  </div>

  <div class="col-md-3 position-relative">
    <label for="observaciones" class="form-label">Observaciones
      <?php if ($id != 0)
        echo "[Actual: " . htmlspecialchars($observaciones) . "]"; ?></label>
    <textarea class="form-control" id="observaciones" name="observaciones" placeholder="Observaciones" required
      minlength="1"><?php if ($id != 0)
        echo htmlspecialchars($observaciones); ?></textarea>
    <div class="invalid-feedback">
      Controlar el campo
    </div>
  </div>

</form>

<div class="mt-4" align="center">
  <button type="submit" class="btn btn-primary" onclick="guardar()">Guardar</button>
  <button type="button" class="btn btn-danger" onclick="cerrar_formulario()">Cancelar</button>
</div>

<hr>