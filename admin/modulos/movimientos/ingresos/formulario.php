<?php
session_start();
include("../../../inc/conexion.php");
conectar();

// Si viene un id del gasto, edito
if ($_GET['id'] != 0) {
  $id = (int)$_GET['id'];
  $sql = "SELECT e.id
                      ,e.numero_factura
                      ,(SELECT SUM(monto) FROM ingresos_detalle d WHERE d.ingreso_id = e.id) as monto_total
                      ,e.persona_id
                      ,(select entidad_id from personas where id = persona_id) as entidad_id
                      ,e.proveedor_id
                      ,e.concepto_id
                      ,e.fecha_emision_factura
                      ,e.tipo_moneda_id
                      ,e.observaciones
               FROM ingresos e WHERE id = $id"; // FALTA JOIN DETALLES

  $resultado = mysqli_query($con, $sql);
  $row = mysqli_fetch_array($resultado);
} else {
  // obtengo el último número de factura y le sumo 1
  $sql = "SELECT numero_factura FROM ingresos ORDER BY numero_factura DESC LIMIT 1";
  $resultado = mysqli_query($con, $sql);
  $row = mysqli_fetch_array($resultado);

  // Verifica si se obtuvo un resultado
  if ($row && isset($row['numero_factura'])) {
    $numero_factura = explode("-", $row['numero_factura']);
    $numero_factura[1] = $numero_factura[1] + 1;
    $numero_factura = $numero_factura[0] . "-" . str_pad($numero_factura[1], 4, '0', STR_PAD_LEFT);
  } else {
    // Asigna un valor predeterminado si no hay resultados
    $numero_factura = "0000-0001"; // O cualquier otro valor que tenga sentido
  }
}


?>
<form method="post" id="form" class="row needs-validation">
  <input type="hidden" class="form-control" id="id" name="id" value="<?php echo $_GET['id']; ?>">

  <!-- Número de factura -->
  <div class="col-md-3 position-relative">
    <label for="numero_factura" class="form-label">Nro Factura</label>
    <input type="text" class="form-control" id="numero_factura" name="numero_factura" placeholder="000-0000" required minlength="3" value="<?= ($_GET['id'] != 0) ? $row['numero_factura'] : $numero_factura ?>">
    <div class="invalid-feedback">
      Controlar el campo
    </div>
  </div>

  <!-- Monto total -->
  <div class="col-md-3 position-relative">
    <label for="monto_total" class="form-label">Monto Total</label>
    <input type="text" class="form-control" id="monto_total" name="monto_total" readonly placeholder="" required minlength="1" value="<?php if ($_GET['id'] != 0) echo $row['monto_total']; ?>">
    <div class="invalid-feedback">
      Controlar el campo
    </div>
  </div>


  <!-- Selección de entidad -->
  <div class="col-md-3 position-relative">
    <label for="entidad_id" class="form-label">Entidad</label>
    <select class="form-control" id="entidad_id" name="entidad_id" required onchange="cargarPersonas(this.value)">
      <option selected disabled value="">Seleccionar</option>
      <?php
      $sql_entidades = "SELECT id, tipo_entidad FROM entidades";
      $resultado_entidades = mysqli_query($con, $sql_entidades);
      while ($row_entidad = mysqli_fetch_array($resultado_entidades)) {

        if (isset($row) && is_null($row['entidad_id'])) {
          $selected = (5 == $row_entidad['id']) ? "selected" : "";
        } else {
          $selected = (isset($row) && $row['entidad_id'] == $row_entidad['id']) ? "selected" : "";
        }

      ?>
        <option <?= $selected; ?> value="<?= $row_entidad['id']; ?>">
          <?= $row_entidad['tipo_entidad']; ?>
        </option>
      <?php } ?>
    </select>
    <div class="invalid-feedback">
      Seleccione una entidad
    </div>
  </div>

  <!-- Selección de persona -->
  <div class="col-md-3 position-relative">
    <label for="persona_id" class="form-label">Persona</label>
    <select class="form-control" id="persona_id" name="persona_id" required>
      <option selected disabled value="">Seleccionar</option>
      <!-- Aquí se cargarán las personas según la entidad seleccionada -->

      <!-- si se esta editando obtengo las personas segun la entidad que viene -->

      <?php


      if ($_GET['id'] != 0) {
        $entidad_id = $row['entidad_id'];
        // pregunto si entidad es = 5. entonces es un proveedor
        if (empty($entidad_id)) {
          $sql_personas = "SELECT id, razon_social as nombre_completo FROM proveedores ORDER BY razon_social";
        } else {
          $sql_personas = "SELECT id, CONCAT(nombres, ' ', apellido) AS nombre_completo FROM personas WHERE entidad_id = $entidad_id ORDER BY nombre_completo";
        }
        // Consulta para obtener personas asociadas a la entidad seleccionada
        $resultado_personas = mysqli_query($con, $sql_personas);
        // Generar opciones para el select de personas
        while ($row_persona = mysqli_fetch_array($resultado_personas)) {

          if (empty($entidad_id)) {
            $selected = (isset($row) && $row['proveedor_id'] == $row_persona['id']) ? "selected" : "";
          } else {
            $selected = (isset($row) && $row['persona_id'] == $row_persona['id']) ? "selected" : "";
          }
          echo '<option ' . $selected . ' value="' . $row_persona['id'] . '">' . $row_persona['nombre_completo'] . '</option>';
        }
      }
      ?>

    </select>
    <div class="invalid-feedback">
      Controlar el campo
    </div>
  </div>

  <!-- Selección de concepto -->
  <div class="col-md-3 position-relative">
    <label for="concepto_id" class="form-label">Concepto</label>
    <select class="form-control" id="concepto_id" name="concepto_id" required>
      <option selected disabled value="">Seleccionar</option>
      <?php
      $sql_conceptos = "SELECT id, nombre FROM conceptos";
      $resultado_conceptos = mysqli_query($con, $sql_conceptos);
      while ($row_concepto = mysqli_fetch_array($resultado_conceptos)) {
        $selected = (isset($row) && ($row['concepto_id'] == $row_concepto['id'])) ? "selected" : "";
      ?>
        <option <?= $selected; ?> value="<?= $row_concepto['id']; ?>"> <?= $row_concepto['nombre']; ?></option>
      <?php } ?>
    </select>
    <div class="invalid-feedback">
      Seleccione un concepto
    </div>
  </div>

  <!-- Fecha de emisión -->
  <div class="col-md-3 position-relative">
    <label for="fecha_emision_factura" class="form-label">Fecha <?php if ($_GET['id'] != 0) echo "[" . $row['fecha_emision_factura'] . "]"; ?></label>
    <input type="date" class="form-control" id="fecha_emision_factura" name="fecha_emision_factura" required value="<?php if ($_GET['id'] != 0) echo $row['fecha_emision_factura']; ?>">
    <div class="invalid-feedback">
      Controlar el campo
    </div>
  </div>

  <!-- Moneda -->
  <div class="col-md-3 position-relative">
    <label for="tipo_moneda_id" class="form-label">Moneda</label>
    <select class="form-control" id="tipo_moneda_id" name="tipo_moneda_id" required>
      <option selected disabled value="">Seleccionar</option>
      <?php
      $sql_moneda = "SELECT id, concat(nombre,' (',abreviatura,')')  as moneda FROM tipo_moneda order by nombre";
      $resultado_moneda = mysqli_query($con, $sql_moneda);
      while ($row_moneda = mysqli_fetch_array($resultado_moneda)) {
        $selected = (isset($row) && $row['tipo_moneda_id'] == $row_moneda['id']) ? "selected" : "";
        $texto_option = $row_moneda['moneda']; // Concatenación
      ?>
        <option <?php echo $selected; ?> value="<?php echo $row_moneda['id']; ?>">
          <?php echo htmlspecialchars($texto_option); ?>
        </option>
      <?php } ?>
    </select>
    <div class="invalid-feedback">
      Seleccione una moneda
    </div>
  </div>

  <div class="col-md-3 position-relative">
    <label for="observaciones" class="form-label">Observaciones <?php if ($_GET['id'] != 0) echo "[" . $row['observaciones'] . "]"; ?></label>
    <textarea class="form-control" id="observaciones" name="observaciones" placeholder="Observaciones" required minlength="1"><?php if ($_GET['id'] != 0) echo $row['observaciones']; ?></textarea>
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