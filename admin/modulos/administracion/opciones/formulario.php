<?php
session_start();
include("../../../inc/conexion.php");
conectar();

if ($_GET['id'] != 0) {
  $id = (int)$_GET['id'];
  $sql = "SELECT * FROM opciones WHERE id=$id";
  $resultado = mysqli_query($con, $sql);
  $row = mysqli_fetch_array($resultado);
  $orden = $row['orden'];
  echo "<script>cargar_icono();</script>";
} else {
  $sql = "SELECT MAX(orden) as orden FROM opciones";
  $resultado = mysqli_query($con, $sql);
  $row = mysqli_fetch_array($resultado);
  $orden = $row['orden'] + 1;
}
?>
<form method="post" id="form" class="row needs-validation">
  <input type="hidden" class="form-control" id="id" name="id" value="<?php echo $_GET['id']; ?>">

  <div class="col-md-3 position-relative">
    <label for="titulo" class="form-label">Titulo <?php if ($_GET['id'] != 0) echo "[" . $row['titulo'] . "]"; ?></label>
    <input type="text" class="form-control" id="titulo" name="titulo" placeholder="Titulo de la Opción" aria-describedby="titulo" required minlength="3" value="<?php if ($_GET['id'] != 0) echo $row['titulo']; ?>">
    <div class="invalid-feedback">
      controlar el campo
    </div>
  </div>

  <div class="col-md-3  position-relative">
    <label for="descripcion" class="form-label">Descripción <?php if ($_GET['id'] != 0) echo "[" . $row['descripcion'] . "]"; ?></label>
    <input type="text" class="form-control" id="descripcion" name="descripcion" required minlength="3" value="<?php if ($_GET['id'] != 0) echo $row['descripcion']; ?>">
    <div class="invalid-feedback">
      controlar el campo
    </div>
  </div>

  <div class="col-md-2">
    <label for="icono" class="form-label">Icono</label>
    <div class="input-group">
      <input type="text" class="input-group form-control" id="icono" name="icono" placeholder="fas fa-border-none" aria-label="icono" onblur="cargar_icono();" value="<?php if ($_GET['id'] != 0) {
                                                                                                                                                                        echo $row['icono'];
                                                                                                                                                                      } ?>">
      <span class="input-group-text">
        <i id="icon" class="fas fa-border-none"></i>
      </span>
    </div>
  </div>

  <div class="col-md-2">
    <label for="orden" class="form-label">Orden</label>
    <input type="number" class="form-control" id="orden" name="orden" value="<?php echo $orden; ?>" required>
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