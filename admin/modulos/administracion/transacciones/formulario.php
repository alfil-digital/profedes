<?php
// Este es el archivo formulario.php que genera el formulario de edición

session_start();
include("../../../inc/conexion.php");
conectar();

// Captura el ID del ítem desde la URL (enviado por la función editar() en JS)
if ($_GET['id'] != 0) {
  $id = (int)$_GET['id']; //convierte el id a entero mediante casting (int) para evitar inyecciones sql
  // Consulta para obtener los datos del ítem a editar
  $sql = "select * from items where id=$id";
  $resultado = mysqli_query($con, $sql);
  $row = mysqli_fetch_array($resultado);
  $orden = $row['orden'];
}

?>
<!-- Formulario HTML con campos precargados si es edición -->
<form method="post" id="form" class="row needs-validation">
  <!-- Este campo hidden contiene el ID del ítem (0 para nuevo, >0 para editar) -->
  <input type="hidden" class="form-control" id="id" name="id" value="<?php echo $_GET['id']; ?>">

  <div class="col-md-3  position-relative">
    <label for="descripcion" class="form-label">Descripción <?php if ($_GET['id'] != 0) echo "[" . $row['descripcion'] . "]"; ?></label>
    <!-- Campo descripción precargado si es edición -->
    <input type="text" class="form-control" id="descripcion" name="descripcion" placeholder="Descripción" aria-describedby="Descripción" required minlength="3" value="<?php if ($_GET['id'] != 0) echo $row['descripcion']; ?>">
    <div class="invalid-feedback">
      controlar el campo
    </div>
  </div>

  <div class="col-md-3 position-relative">
    <label for="enlace" class="form-label">Enlace</label>
    <!-- Campo enlace precargado si es edición -->
    <input type="text" class="form-control" id="enlace" name="enlace" placeholder="Enlace" aria-describedby="Enlace" required minlength="3" value="<?php if ($_GET['id'] != 0) echo $row['enlace']; ?>">
    <div class="invalid-feedback">
      controlar el campo
    </div>
  </div>

  <div class="col-md-3 position-relative">
    <label for="titulo" class="form-label">Opción</label>
    <!-- Select de opciones con la opción del ítem seleccionada si es edición -->
    <select class="form-control" id="opcion_id" name="opcion_id" required>
      <option selected disabled value="">Seleccionar</option>
      <?php
      $selected = "";
      // Consulta todas las opciones disponibles
      $sql = "select * from opciones order by descripcion";
      $resultado_opciones = mysqli_query($con, $sql);
      while ($row1 = mysqli_fetch_array($resultado_opciones)) {
        // Marca como selected la opción correspondiente al ítem
        if (isset($row)) {
          if ($row['opcion_id'] == $row1['id']) {
            $selected = "selected";
          } else {
            $selected = "";
          }
        } ?>
        <option <?php echo $selected; ?> value="<?php echo $row1['id']; ?>"><?php echo $row1['descripcion']; ?></option>
      <?php }
      ?>
    </select>
    <div class="invalid-feedback">
      controlar el campo
    </div>
  </div>

  <div class="col-md-2">
    <label for="orden" class="form-label">Orden</label>
    <!-- Campo orden precargado si es edición -->
    <input type="number" class="form-control" id="orden" name="orden" value="<?php echo $orden; ?>" required>
    <div class="invalid-feedback">
      controlar el campo
    </div>
  </div>

</form>
<!-- Botones de acción -->
<div class="mt-4" align="center">
  <!-- Al hacer clic llama a guardar() en JS que enviará los datos al controlador -->
  <button type="submit" class="btn btn-primary" onclick="guardar()">Guardar</button>
  <!-- Al hacer clic llama a cerrar_formulario() en JS -->
  <button type="button" class="btn btn-danger" onclick="cerrar_formulario()">Cancelar</button>
</div>

<hr>