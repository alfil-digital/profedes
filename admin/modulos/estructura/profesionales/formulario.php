

<?php
session_start();
include("../../../inc/conexion.php");
// require_once("funciones.js");
$con = conectar();

if ($_GET['id'] != 0) {
  $id = (int)$_GET['id'];
  $sql = "SELECT *,
                (SELECT descripcion FROM matriculas as mat WHERE mat.id = p.matricula_id) AS matricula
		      FROM profesionales p 
		      WHERE id = $id";
  $resultado = mysqli_query($con, $sql);
  $row = mysqli_fetch_array($resultado);
}

// obtengo las personas
$sql = "SELECT * FROM personas";
$personas = $con->query($sql);

// obtengo las titulos
$sql = "SELECT * FROM titulos";
$titulos = $con->query($sql);

?>

<form method="post" id="form" class="row needs-validation">

  <input type="hidden" class="form-control" id="id" name="id" value="<?php echo $_GET['id']; ?>">

  <!-- Persona -->
  <div class="col-md-4 position-relative">
    <label for=""> Profesional</label>
    <select class="form-control" name="persona_id" id="">
      <option selected disabled value="">Seleccionar</option>
      <?php
      while ($row_persona = $personas->fetch_assoc()) {
        $selected = "";
        if ($_GET['id'] != 0 && $row_persona['id'] == $row['persona_id']) {
          $selected = "selected";
          $disabled = "disabled";
        }
      ?>
        <option <?php echo $selected; ?> value="<?php echo $row_persona['id']; ?>">
          <?php echo $row_persona['apellido'] . ", " . $row_persona['nombre']; ?>
        </option>
      <?php } ?>
    </select>
  </div>

  <!-- titulo -->
  <div class="col-md-4 position-relative">
    <label for=""> Titulo</label>
    <select class="form-control" name="persona_id" id="">
      <option selected disabled value="">Seleccionar</option>
      <?php
      while ($row_titulo = $titulos->fetch_assoc()) {
        $selected = "";
        if ($_GET['id'] != 0 && $row_titulo['id'] == $row['titulo_id']) {
          $selected = "selected";
          $disabled = "disabled";
        }
      ?>
        <option <?php echo $selected; ?> value="<?php echo $row_titulo['id']; ?>">
          <?php echo $row_titulo['descripcion'] ; ?>
        </option>
      <?php } ?>
    </select>
  </div>



  <!-- matricula -->
  <div class="col-md-4 position-relative">
    <label for="matricula" class="form-label">matricula <?php if ($_GET['id'] != 0) echo "[" . $row['matricula'] . "]"; ?></label>
    <input type="text" class="form-control" onblur="verificarMatricula(this)" id="matricula" name="matricula" placeholder="matricula"
      value="<?php if ($_GET['id'] != 0) echo $row['matricula']; ?>">
      <div class="invalid-feedback" id="mensaje_matricula"></div>
    <div class="invalid-feedback">
      Ingrese el matricula
    </div>
  </div>

  <div class="col-md-4 position-relative">
    <label for="cuit" class="form-label">cuit <?php if ($_GET['id'] != 0) echo "[" . $row['cuit'] . "]"; ?></label>
    <input type="text" class="form-control" id="cuit" name="cuit" placeholder="cuit"
      value="<?php if ($_GET['id'] != 0) echo $row['cuit']; ?>">
    <div class="invalid-feedback">
      Ingrese el cuit
    </div>
  </div>




  <div class="col-md-4 position-relative">
    <label for="observaciones" class="form-label">Observaciones</label>
    <textarea class="form-control" id="observaciones" name="observaciones" rows="3"><?php if ($_GET['id'] != 0) echo $row['observaciones']; ?></textarea>
  </div>

</form>
<div class="mt-4" align="center">
  <button type="submit" class="btn btn-primary" onclick="guardar(<?= $_GET['id'] ?>)">Guardar</button>
  <button type="button" class="btn btn-danger" onclick="cerrar_formulario()">Cancelar</button>
</div>

<hr>


<script type="text/javascript" src="modulos/estructura/profesionales/funciones.js"></script>