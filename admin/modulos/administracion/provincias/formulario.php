<?php
session_start();
include("../../../inc/conexion.php");
conectar();

if ($_GET['id'] != 0) {
  $id = (int)$_GET['id'];
  $sql = "SELECT * FROM provincias WHERE id = $id";
  $resultado = mysqli_query($con, $sql);
  $row = mysqli_fetch_array($resultado);
}
?>
<form method="post" id="form" class="row needs-validation">
  <input type="hidden" class="form-control" id="id" name="id" value="<?php echo $_GET['id']; ?>">

  <div class="col-md-3 position-relative">
    <label for="nombre" class="form-label">Nombre de la Provincia <?php if ($_GET['id'] != 0) echo "[" . $row['nombre'] . "]"; ?></label>
    <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Nombre de la Provincia" required minlength="3"
      value="<?php if ($_GET['id'] != 0) echo $row['nombre']; ?>">
    <div class="invalid-feedback">
      Ingrese el nombre
    </div>
  </div>

  <div class="col-md-3 position-relative">
    <label for="pais_id" class="form-label">País</label>
    <select class="form-control" id="pais_id" name="pais_id" required>
      <option selected disabled value="">Seleccionar</option>
      <?php
      $sql_paises = "SELECT * FROM paises ORDER BY nombre";
      $resultado_paises = mysqli_query($con, $sql_paises);
      while ($row_pais = mysqli_fetch_array($resultado_paises)) {
        $selected = "";
        if (isset($row) && $row['pais_id'] == $row_pais['id']) {
          $selected = "selected";
        }
      ?>
        <option <?php echo $selected; ?> value="<?php echo $row_pais['id']; ?>">
          <?php echo $row_pais['nombre']; ?>
        </option>
      <?php } ?>
    </select>
    <div class="invalid-feedback">
      Seleccione un país
    </div>
  </div>

  <div class="col-md-3 position-relative">
    <label for="created_at" class="form-label">Fecha de Creación</label>
    <input type="text" class="form-control" id="created_at" name="created_at" placeholder="Fecha de Creación"
      value="<?php if ($_GET['id'] != 0) echo $row['created_at']; ?>" readonly>
  </div>

</form>
<div class="mt-4" align="center">
  <button type="submit" class="btn btn-primary" onclick="guardar()">Guardar</button>
  <button type="button" class="btn btn-danger" onclick="cerrar_formulario()">Cancelar</button>
</div>

<hr>