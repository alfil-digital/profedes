<?php
session_start();
include("../../../inc/conexion.php");
conectar();

if ($_GET['id'] != 0) {
  $id = (int)$_GET['id'];
  $sql = "SELECT p.*
          FROM proveedores p 
          WHERE p.id=$id";
  $resultado = mysqli_query($con, $sql);
  $row = mysqli_fetch_array($resultado);
}
?>
<form method="post" id="form" class="row needs-validation">
  <input type="hidden" class="form-control" id="id" name="id" value="<?php echo $_GET['id']; ?>">

  <!-- cuit -->
  <div class="col-md-4 position-relative">
    <label for="cuit" class="form-label">CUIT <?php if ($_GET['id'] != 0) echo "[" . $row['cuit'] . "]"; ?></label>
    <input type="text" class="form-control" id="cuit" name="cuit" placeholder="CUIT" aria-describedby="CUIT" required minlength="3" value="<?php if ($_GET['id'] != 0) echo $row['cuit']; ?>">
    <div class="invalid-feedback">
      controlar el campo
    </div>
  </div>
  <!-- fin cuit -->

  <!-- razon social -->
  <div class="col-md-4 position-relative">
    <label for="razon_social" class="form-label">Razon Social</label>
    <input type="text" class="form-control" id="razon_social" name="razon_social" placeholder="Razon Social" required minlength="3" value="<?php if ($_GET['id'] != 0) echo $row['razon_social']; ?>">
    <div class="invalid-feedback">
      controlar el campo
    </div>
  </div>
  <!-- fin razon social -->

  <!-- domicilio -->
  <div class="col-md-4 position-relative">
    <label for="domicilio" class="form-label">Domicilio</label>
    <input type="text" class="form-control" id="domicilio" name="domicilio" placeholder="domicilio" required minlength="3" value="<?php if ($_GET['id'] != 0) echo $row['domicilio']; ?>">
    <div class="invalid-feedback">
      controlar el campo
    </div>
  </div>
  <!-- fin domicilio -->

  <!-- nombre fantasia -->
  <div class="col-md-4 position-relative">
    <label for="nombre_fantasia" class="form-label">Nombre Fantasia</label>
    <input type="text" class="form-control" id="nombre_fantasia" name="nombre_fantasia" placeholder="Nombre Fantasia" required minlength="3" value="<?php if ($_GET['id'] != 0) echo $row['nombre_fantasia']; ?>">
    <div class="invalid-feedback">
      controlar el campo
    </div>
  </div>
  <!-- fin nombre fantasia -->

  <!-- telefono -->
  <div class="col-md-4 position-relative">
    <label for="telefono" class="form-label">Telefono</label>
    <input type="text" class="form-control" id="telefono" name="telefono" placeholder="Telefono" minlength="3" value="<?php if ($_GET['id'] != 0) echo $row['telefono']; ?>">
    <div class="invalid-feedback">
      controlar el campo
    </div>
  </div>
  <!-- fin telefono -->

  <!-- descripcion -->
  <div class="col-md-4 position-relative">
    <label for="descripcion" class="form-label">Descripcion</label>
    <textarea class="form-control" id="descripcion" name="descripcion" placeholder="descripcion"><?php if ($_GET['id'] != 0) echo $row['descripcion']; ?></textarea>
    <div class="invalid-feedback">
      controlar el campo
    </div>
  </div>
  <!-- fin descripcion -->

</form>
<div class="mt-4" align="center">
  <button type="submit" class="btn btn-primary" onclick="guardar()">Guardar</button>
  <button type="button" class="btn btn-danger" onclick="cerrar_formulario()">Cancelar</button>
</div>

<hr>