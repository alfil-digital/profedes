

<?php
session_start();
include("../../../inc/conexion.php");
$con = conectar();

if ($_GET['id'] != 0) {
  $id = (int)$_GET['id'];
  $sql = "SELECT p.*, (SELECT provincia_id FROM localidades WHERE id = p.localidad_id) as provincia_id FROM personas p WHERE id = $id";
  $resultado = mysqli_query($con, $sql);
  $row = mysqli_fetch_array($resultado);
}
?>
<form method="post" id="form" class="row needs-validation">
  <input type="hidden" class="form-control" id="id" name="id" value="<?php echo $_GET['id']; ?>">

  <div class="col-md-4 position-relative">
    <label for="nombre" class="form-label">Nombre <?php if ($_GET['id'] != 0) echo "[" . $row['nombre'] . "]"; ?></label>
    <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Nombre" required minlength="3"
      value="<?php if ($_GET['id'] != 0) echo $row['nombre']; ?>">
    <div class="invalid-feedback">
      Ingrese el nombre
    </div>
  </div>

  <div class="col-md-4 position-relative">
    <label for="apellido" class="form-label">Apellido <?php if ($_GET['id'] != 0) echo "[" . $row['apellido'] . "]"; ?></label>
    <input type="text" class="form-control " required id="apellido" name="apellido" placeholder="Apellido" minlength="3"
      value="<?php if ($_GET['id'] != 0) echo $row['apellido']; ?>">
    <div class="invalid-feedback">
      Ingrese el apellido
    </div>
  </div>

  <div class="col-md-4 position-relative">
    <label for="dni" class="form-label">DNI <?php if ($_GET['id'] != 0) echo "[" . $row['dni'] . "]"; ?></label>
    <input type="text" class="form-control" id="dni" name="dni" placeholder="DNI" required
      value="<?php if ($_GET['id'] != 0) echo $row['dni']; ?>">
    <div class="invalid-feedback">
      Ingrese el DNI
    </div>
  </div>

  <div class="col-md-4 position-relative">
    <label for="telefono" class="form-label">Teléfono <?php if ($_GET['id'] != 0) echo "[" . $row['telefono'] . "]"; ?></label>
    <input type="text" class="form-control" id="telefono" name="telefono" placeholder="Teléfono"
      value="<?php if ($_GET['id'] != 0) echo $row['telefono']; ?>">
    <div class="invalid-feedback">
      Ingrese el teléfono
    </div>
  </div>

  <div class="col-md-4 position-relative">
    <label for="mail" class="form-label">Email <?php if ($_GET['id'] != 0) echo "[" . $row['mail'] . "]"; ?></label>
    <input type="email" class="form-control" id="mail" name="mail" placeholder="Email"
      value="<?php if ($_GET['id'] != 0) echo $row['mail']; ?>">
    <div class="invalid-feedback">
      Ingrese un email válido
    </div>
  </div>


  <div class="col-md-4 position-relative">
    <label for="cuil" class="form-label">CUIL <?php if ($_GET['id'] != 0) echo "[" . $row['cuil'] . "]"; ?></label>
    <input type="text" class="form-control" id="cuil" name="cuil" placeholder="CUIL"
      value="<?php if ($_GET['id'] != 0) echo $row['cuil']; ?>">
    <div class="invalid-feedback">
      Ingrese el CUIL
    </div>
  </div>


 <div class="col-md-4 position-relative">
    <label for="domicilio" class="form-label">Domicilio <?php if ($_GET['id'] != 0) echo "[" . $row['domicilio'] . "]"; ?></label>
    <input type="text" class="form-control" id="domicilio" name="domicilio" placeholder="Domicilio"
      value="<?php if ($_GET['id'] != 0) echo $row['domicilio']; ?>">
    <div class="invalid-feedback">
      Ingrese el domicilio
    </div>
  </div>

  <div class="col-md-4 position-relative">
    <label for="provincia_id" class="form-label">Provincia</label>
    <select class="form-control" id="provincia_id" name="provincia_id" required>
      <option selected disabled value="">Seleccionar</option>
      <?php
      
      // Consulta para obtener todas las provincias
      $sql_provincias = "SELECT * FROM provincias ORDER BY nombre";
      $resultado_provincias = mysqli_query($con, $sql_provincias);
      while ($row_provincia = mysqli_fetch_array($resultado_provincias)) {
        $selected = "";
        // Verifica si se está editando y si la provincia coincide
        if (isset($row) && $row['provincia_id'] == $row_provincia['id']) {
          $selected = "selected";
        }
      ?>
        <!-- El value es el ID de la provincia -->
        <option <?php echo $selected; ?> value="<?php echo $row_provincia['id']; ?>">
          <?php echo $row_provincia['nombre']; ?> <!-- Muestra el nombre de la provincia -->
        </option>
      <?php } ?>
    </select>
    <div class="invalid-feedback">
      Seleccione una provincia
    </div>
  </div>

  <div class="col-md-4 position-relative">
    <label for="localidad_id" class="form-label">Localidad</label>
    <select class="form-control" id="localidad_id" name="localidad_id" required>
      <option selected disabled value="">Seleccionar</option>
      <?php
      $sql_localidades = "SELECT * FROM localidades ORDER BY nombre";
      $resultado_localidades = mysqli_query($con, $sql_localidades);
      while ($row_localidad = mysqli_fetch_array($resultado_localidades)) {
        $selected = "";
        if (isset($row) && $row['localidad_id'] == $row_localidad['id']) {
          $selected = "selected";
        }
      ?>
        <option <?php echo $selected; ?> value="<?php echo $row_localidad['id']; ?>">
          <?php echo $row_localidad['nombre']; ?>
        </option>
      <?php } ?>
    </select>
    <div class="invalid-feedback">
      Seleccione una localidad
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