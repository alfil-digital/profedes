<?php
session_start();
include("../../../inc/conexion.php");
conectar();

if ($_GET['id'] != 0) {
  // Si es edición, obtener datos del país
  $id = (int)$_GET['id'];
  $sql = "SELECT * FROM paises WHERE id = $id";
  $resultado = mysqli_query($con, $sql);
  $row = mysqli_fetch_array($resultado);
}
?>
<form method="post" id="form" class="row needs-validation">
  <!-- Campo oculto para el ID -->
  <input type="hidden" class="form-control" id="id" name="id" value="<?php echo $_GET['id']; ?>">

  <!-- Campo descripción del país -->
  <div class="col-md-12 position-relative">
    <label for="descripcion" class="form-label">Descripción <?php if ($_GET['id'] != 0) echo "[" . $row['descripcion'] . "]"; ?></label>
    <input type="text"
      class="form-control"
      id="descripcion"
      name="descripcion"
      placeholder="Nombre del país"
      required
      minlength="3"
      value="<?php if ($_GET['id'] != 0) echo $row['descripcion']; ?>">
    <div class="invalid-feedback">
      Ingrese el nombre del país (mínimo 3 caracteres)
    </div>
  </div>
</form>

<!-- Botones de acción -->
<div class="mt-4" align="center">
  <button type="submit" class="btn btn-primary" onclick="guardar()">Guardar</button>
  <button type="button" class="btn btn-danger" onclick="cerrar_formulario()">Cancelar</button>
</div>

<hr>