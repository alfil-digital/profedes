<?php
session_start();
include("../../../inc/conexion.php");
$con = conectar();

$row = null;

if (isset($_GET['id']) && $_GET['id'] != 0) {
  $id = (int)$_GET['id'];
  $sql = "SELECT id, tipo_entidad FROM entidades WHERE id = $id";
  $resultado = mysqli_query($con, $sql);

  if ($resultado && mysqli_num_rows($resultado) > 0) {
    $row = mysqli_fetch_array($resultado);
  } else {
    $id = 0;
  }
} else {
  $id = 0;
}
?>
<form method="post" id="form" class="row needs-validation">
  <input type="hidden" class="form-control" id="id" name="id" value="<?php echo isset($id) ? $id : 0; ?>">

  <div class="col-md-6 position-relative">
    <label for="tipo_entidad" class="form-label">Tipo de Entidad </label>
    <input type="text" class="form-control" id="tipo_entidad" name="tipo_entidad" placeholder="Ej: Cliente, Profesional, Empleado" required minlength="3"
      value="<?php echo isset($row['tipo_entidad']) ? htmlspecialchars($row['tipo_entidad']) : ''; ?>">
    <div class="invalid-feedback">
      Ingrese el tipo de entidad
    </div>
  </div>

</form>
<div class="mt-4" align="center">
  <button type="submit" class="btn btn-primary" onclick="guardar()">Guardar</button>
  <button type="button" class="btn btn-danger" onclick="cerrar_formulario()">Cancelar</button>
</div>

<hr>