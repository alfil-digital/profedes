<?php
session_start();
include("../../../inc/conexion.php");
conectar();

if ($_GET['id'] != 0) {
  $id = (int)$_GET['id'];
  $sql = "SELECT u.*, CONCAT(p.nombre, ' ', p.apellido) as nombre_completo, p.id as persona_id 
          FROM usuarios u 
          INNER JOIN personas p ON p.id = u.persona_id 
          WHERE u.id=$id";
  $resultado = mysqli_query($con, $sql);
  $row = mysqli_fetch_array($resultado);
}
?>
<form method="post" id="form" class="row needs-validation">
  <input type="hidden" class="form-control" id="id" name="id" value="<?php echo $_GET['id']; ?>">

  <div class="col-md-3  position-relative">
    <label for="usuario" class="form-label">Usuario <?php if ($_GET['id'] != 0) echo "[" . $row['usuario'] . "]"; ?></label>
    <input type="text" class="form-control" id="usuario" name="usuario" placeholder="Usuario" aria-describedby="Usuario" required minlength="3" value="<?php if ($_GET['id'] != 0) echo $row['usuario']; ?>">
    <div class="invalid-feedback">
      controlar el campo
    </div>
  </div>

  <div class="col-md-3  position-relative">
    <label for="persona_id" class="form-label">Persona <?php if ($_GET['id'] != 0) echo "[" . $row['nombre_completo'] . "]"; ?></label>
    <select class="form-control" id="persona_id" name="persona_id" required>
      <option selected disabled value="">Seleccionar</option>
      <?php
      $sql_personas = "SELECT id, CONCAT(nombre, ' ', apellido) as nombre_completo 
                       FROM personas 
                       ORDER BY apellido, nombre";
      $resultado_personas = mysqli_query($con, $sql_personas);
      while ($row_persona = mysqli_fetch_array($resultado_personas)) {
        $selected = "";
        if (isset($row) && $row['persona_id'] == $row_persona['id']) {
          $selected = "selected";
        }
      ?>
        <option <?php echo $selected; ?> value="<?php echo $row_persona['id']; ?>">
          <?php echo $row_persona['nombre_completo']; ?>
        </option>
      <?php } ?>
    </select>
    <div class="invalid-feedback">
      controlar el campo
    </div>
  </div>
  

  <div class="col-md-3 position-relative">
    <label for="grupo" class="form-label">Grupo</label>
    <select class="form-control" id="grupo_id" name="grupo_id" required>
      <option selected disabled value="">Seleccionar</option>
      <?php
      $sql_grupos = "SELECT * FROM grupos ORDER BY descripcion";
      $resultado_grupos = mysqli_query($con, $sql_grupos);
      while ($row1 = mysqli_fetch_array($resultado_grupos)) {
        $selected = "";
        if (isset($row) && $row['grupo_id'] == $row1['id']) {
          $selected = "selected";
        }
      ?>
        <option <?php echo $selected; ?> value="<?php echo $row1['id']; ?>">
          <?php echo $row1['descripcion']; ?>
        </option>
      <?php } ?>
    </select>
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