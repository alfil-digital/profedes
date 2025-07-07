<?php
session_start();
include("../../../inc/conexion.php");
conectar();

if ($_GET['id'] != 0) {
  $id = (int)$_GET['id'];
  $sql = "SELECT * FROM conceptos WHERE id = $id";
  $resultado = mysqli_query($con, $sql);
  $row = mysqli_fetch_array($resultado);
}
?>
<form method="post" id="form" class="row needs-validation">
  <input type="hidden" class="form-control" id="id" name="id" value="<?php echo $_GET['id']; ?>">

  <div class="col-md-3 position-relative">
    <label for="nombre" class="form-label">Nombre <?php if ($_GET['id'] != 0) echo "[" . $row['nombre'] . "]"; ?></label>
    <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Nombre" required minlength="3"
      value="<?php if ($_GET['id'] != 0) echo $row['nombre']; ?>">
    <div class="invalid-feedback">
      Ingrese el nombre
    </div>
  </div>


</form>
<div class="mt-4" align="center">
  <button type="submit" class="btn btn-primary" onclick="guardar()">Guardar</button>
  <button type="button" class="btn btn-danger" onclick="cerrar_formulario()">Cancelar</button>
</div>

<hr>