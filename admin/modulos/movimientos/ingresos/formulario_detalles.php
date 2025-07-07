<?php

use function PHPSTORM_META\map;

session_start();
include("../../../inc/conexion.php");
conectar();

// id del gasto viene si o si, si no existe el gasto no se puede agregar detalles

// imprimo $_GET

// print_r($_GET); die();

$ingreso_id = (int)$_GET['id'];
$detalle_id = (int)$_GET['detalle_id'];
$sql = "SELECT
            e.id,
            e.numero_factura,
            case when e.persona_id is not null then (select concat(nombres,' ',apellido) from personas where id = e.persona_id)
                when e.persona_id is not null then(select razon_social from proveedores where id = e.proveedor_id) 
            end as nombre
        FROM
            ingresos e
        WHERE
            e.id = $ingreso_id;";

$resultado = mysqli_query($con, $sql);
$row = mysqli_fetch_array($resultado);

// obtegno el detalle a editar $GET['detalle_id']
if ($detalle_id != 0) {
  $sql_detalle = "SELECT
                ed.id,
                ed.monto,
                ed.cantidad,
                ed.estado_id,
                ed.metodo_pago_id,
                ed.observaciones
            FROM
                ingresos_detalle ed
            WHERE
                ed.id = $detalle_id;";

  $resultado_detalle = mysqli_query($con, $sql_detalle);
  $row_detalle = mysqli_fetch_array($resultado_detalle);
}
?>

<div class="content" class="container-fluid bg-light p-3">

  <h3> Agrear Detalle a la Factura Nro. <?= $row['numero_factura'] . ' (' . $row['nombre'] . ')' ?></h3>

  <form method="post" id="form" class="row needs-validation">
    <input type="hidden" class="form-control" id="ingreso_id" name="ingreso_id" value="<?= $ingreso_id ?>">
    <input type="hidden" class="form-control" id="detalle_id" name="detalle_id" value="<?= $detalle_id ?>">


    <!-- Monto-->
    <div class="col-md-3 position-relative">
      <label for="monto" class="form-label">Monto</label>
      <input type="text" class="form-control" id="monto_detalle" name="monto_detalle" placeholder="Monto" required value="<?php if ($detalle_id != 0) echo $row_detalle['monto']; ?>">
      <div class="invalid-feedback">
        Controlar el campo
      </div>
    </div>


    <!--cantidad -->
    <div class="col-md-3 position-relative">
      <label for="cantidad" class="form-label">Cantidad</label>
      <input type="text" class="form-control" id="cantidad" name="cantidad" placeholder="Cantidad" required value="<?php if ($detalle_id != 0) echo $row_detalle['cantidad']; ?>">
      <div class="invalid-feedback">
        Controlar el campo
      </div>
    </div>

    <!-- Selección de Estado -->
    <div class="col-md-3 position-relative">
      <label for="estado_id" class="form-label">Estado</label>
      <select class="form-control" id="estado_id" name="estado_id" required>
        <option selected disabled value="">Seleccionar</option>
        <?php
        $sql_estados = "SELECT id, nombre FROM estados";
        $resultado_estados = mysqli_query($con, $sql_estados);
        while ($row_estado = mysqli_fetch_array($resultado_estados)) {
          $selected = (isset($row_detalle) && ($row_detalle['estado_id'] == $row_estado['id'])) ? "selected" : "";
        ?>
          <option <?= $selected; ?> value="<?= $row_estado['id']; ?>"> <?= $row_estado['nombre']; ?></option>
        <?php } ?>
      </select>
      <div class="invalid-feedback">
        Seleccione un estado
      </div>
    </div>


    <!-- metodo de pago -->
    <div class="col-md-3 position-relative">
      <label for="metodo_pago_id" class="form-label">Metodo de Pago</label>
      <select class="form-control" id="metodo_pago_id" name="metodo_pago_id" required>
        <option selected disabled value="">Seleccionar</option>
        <?php
        $sql_metodo_pago = "SELECT id, descripcion FROM metodo_pago order by descripcion";
        $resultado_metodo_pago = mysqli_query($con, $sql_metodo_pago);
        while ($row_metodo_pago = mysqli_fetch_array($resultado_metodo_pago)) {
          $selected = (isset($row_detalle) && $row_detalle['metodo_pago_id'] == $row_metodo_pago['id']) ? "selected" : "";
          $texto_option = $row_metodo_pago['descripcion']; // Concatenación
        ?>
          <option <?php echo $selected; ?> value="<?php echo $row_metodo_pago['id']; ?>">
            <?php echo htmlspecialchars($texto_option); ?>
          </option>
        <?php } ?>
      </select>
      <div class="invalid-feedback">
        Seleccione un metodo de pago
      </div>
    </div>

    <!-- Observaciones -->
    <div class="col-md-3 position-relative">
      <label for="observaciones" class="form-label">Observaciones </label>
      <textarea class="form-control" id="observaciones" name="observaciones" placeholder="Observaciones" required minlength="1"><?php if ($detalle_id != 0) echo $row_detalle['observaciones']; ?></textarea>
      <div class="invalid-feedback">
        Controlar el campo
      </div>
    </div>


  </form>



  <div class="mt-4" align="center">
    <button type="submit" class="btn btn-primary" onclick="agregar_detalle()">Agregar</button>
    <button type="button" class="btn btn-danger" onclick="cerrar_formulario()">Cancelar</button>
  </div>

  <br>

  <!-- INICIA TABLA DETALLES  -->

  <table class="table table-striped table-hover table-bordered" id="dataTable" width="100%" cellspacing="0">
    <thead>
      <tr>
        <th>ID</th>
        <th>Monto</th>
        <th>Cantidad</th>
        <th>Estado</th>
        <th>Metodo de Pago</th>
        <th>Acciones</th>
      </tr>
    </thead>
    <tbody>
      <?php
      echo $sql_get_detalle = "SELECT
                ed.id,
                ed.monto,
                ed.cantidad,
                e.nombre as estado,
                mp.descripcion as metodo_pago
            FROM
                ingresos_detalle ed
            LEFT JOIN
                estados e ON ed.estado_id = e.id
            LEFT JOIN
                metodo_pago mp ON ed.metodo_pago_id = mp.id
            WHERE
                ed.ingreso_id = $ingreso_id;";

      $resultado_get_detalle = mysqli_query($con, $sql_get_detalle); // Ejecuta la consulta
      if (mysqli_num_rows($resultado_get_detalle) > 0) {

        while ($row_get_detalle = mysqli_fetch_array($resultado_get_detalle)) { // Itera sobre los resultados
         
          $detalle_get_id = isset($row_get_detalle['id']) ? $row_get_detalle['id'] : ''; // Obtiene el ID de la provincia
          $monto = isset($row_get_detalle['monto']) ? $row_get_detalle['monto'] : ''; // Obtiene el nombre de la provincia
          $cantidad = isset($row_get_detalle['cantidad']) ? $row_get_detalle['cantidad'] : ''; // Obtiene el nombre de la provincia
          $estado = isset($row_get_detalle['estado']) ? $row_get_detalle['estado'] : ''; // Obtiene el nombre de la provincia
          $metodo_pago = isset($row_get_detalle['metodo_pago']) ? $row_get_detalle['metodo_pago'] : ''; // Obtiene el nombre de la provincia
          
          switch ($estado) {
            case 'Pagado':
              $detalle_label = 'success';
              break;
            case 'Cancelado':
              $detalle_label = 'danger';
              break;
            case 'Pendiente':
              $detalle_label = 'warning';
              break;
            default:
              # code...
              break;
          }
        

      ?>
        <tr>
          <td align="center"><?php echo $detalle_get_id; ?></td> <!-- Muestra el ID -->
          <td><?php echo $monto; ?></td> <!-- Muestra el nombre -->
          <td><?php echo $cantidad; ?></td> <!-- Muestra el nombre -->
          <td> <span class="badge badge-<?=$detalle_label; ?>"> <?php echo $estado; ?> </span></td> <!-- Muestra el nombre -->
          <td><?php echo $metodo_pago; ?></td> <!-- Muestra el nombre -->
          <td>
            <!-- Botón para editar la provincia -->
            <a onclick="agregarDetalles(<?php echo $ingreso_id; ?>,<?php echo $detalle_get_id; ?>)" class="btn btn-primary btn-icon-split" title="Editar">
              <span class="icon text-white-50">
                <i class="fas fa-edit"></i>
              </span>
            </a>
            <!-- Botón para eliminar la provincia -->
            <a onclick="eliminar(<?php echo $detalle_get_id; ?>)" class="btn btn-danger btn-icon-split" title="Eliminar">
              <span class="icon text-white-50">
                <i class="fas fa-trash"></i>
              </span>
            </a>
          </td>
        </tr>
      <?php } } else {
        echo  "<tr><td colspan='6'>No hay detalles cargados </td></tr>";
      } ?>
  </table>

</div>

<hr>