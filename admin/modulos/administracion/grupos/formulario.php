<?php
session_start();
include("../../../inc/conexion.php");
conectar();

if ($_GET['id'] != 0) {
  $id = (int)$_GET['id'];
  $sql = "select * from grupos where id=$id";
  $resultado = mysqli_query($con, $sql);
  $row = mysqli_fetch_array($resultado);
}
?>
<form method="post" id="form" class="row needs-validation">
  <input type="hidden" class="form-control" id="id" name="id" value="<?php echo $_GET['id']; ?>">

  <div class="col-md-3  position-relative">
    <label for="descripcion" class="form-label">Descripción <?php if ($_GET['id'] != 0) echo "[" . $row['descripcion'] . "]"; ?></label>
    <input type="text" class="form-control" id="descripcion" name="descripcion" placeholder="Descripción" aria-describedby="Descripción" required minlength="3" value="<?php if ($_GET['id'] != 0) echo $row['descripcion']; ?>">
    <div class="invalid-feedback">
      controlar el campo
    </div>
  </div>

</form>
<div class="mt-4" align="center">
  <button type="submit" class="btn btn-primary" onclick="guardar()">Guardar</button>
  <button type="button" class="btn btn-danger" onclick="cerrar_formulario()">Cancelar</button>
</div>

<hr>