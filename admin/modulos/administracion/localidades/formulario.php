<?php
session_start();
include("../../../inc/conexion.php");
$con = conectar(); // Asegúrate de que 'conectar()' devuelva la conexión

$row = null; // Inicializar $row a null

if (isset($_GET['id']) && $_GET['id'] != 0) {
  $id = (int) $_GET['id'];
  // La consulta debe seleccionar de 'localidades' y unirse a 'provincias' para el nombre de provincia
  $sql = "SELECT l.*, p.id AS provincia_id_fk FROM localidades l LEFT JOIN provincias p ON l.provincia_id = p.id WHERE l.id = $id";
  $resultado = mysqli_query($con, $sql);

  if ($resultado && mysqli_num_rows($resultado) > 0) {
    $row = mysqli_fetch_array($resultado);
  } else {
    // Si no se encuentra el ID, asumir que es un nuevo registro
    $id = 0;
  }
} else {
  $id = 0; // Si no hay ID o es 0, es un nuevo registro
}
?>
<form method="post" id="form" class="row needs-validation">
  <input type="hidden" class="form-control" id="id" name="id" value="<?php echo isset($id) ? $id : 0; ?>">

  <div class="col-md-3 position-relative">
    <label for="nombre" class="form-label">Nombre de la Localidad
      <?php if (isset($row['nombre']))
        echo "[" . $row['nombre'] . "]"; ?></label>
    <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Nombre de la Localidad" required
      minlength="3" value="<?php echo isset($row['nombre']) ? $row['nombre'] : ''; ?>">
    <div class="invalid-feedback">
      Ingrese el nombre
    </div>
  </div>

  <div class="col-md-3 position-relative">
    <label for="provincia_id" class="form-label">Provincia</label>
    <select class="form-control" id="provincia_id" name="provincia_id" required>
      <option selected disabled value="">Seleccionar</option>
      <?php
      $sql_provincias = "SELECT id, nombre FROM provincias ORDER BY nombre"; // Consulta corregida para solo ID y nombre
      $resultado_provincias = mysqli_query($con, $sql_provincias);
      if ($resultado_provincias) {
        while ($row_provincia = mysqli_fetch_array($resultado_provincias)) {
          $selected = "";
          // Usamos provincia_id_fk del SELECT principal para preseleccionar
          if (isset($row) && isset($row['provincia_id_fk']) && $row['provincia_id_fk'] == $row_provincia['id']) {
            $selected = "selected";
          }
          ?>
          <option <?php echo $selected; ?> value="<?php echo $row_provincia['id']; ?>">
            <?php echo $row_provincia['nombre']; ?>
          </option>
          <?php
        }
      } else {
        echo "<option value=''>Error al cargar provincias</option>";
      }
      ?>
    </select>
    <div class="invalid-feedback">
      Seleccione una provincia
    </div>
  </div>

  <div class="col-md-3 position-relative">
    <label for="codigo_postal" class="form-label">Código Postal</label>
    <input type="text" class="form-control" id="codigo_postal" name="codigo_postal" placeholder="Código Postal"
      value="<?php echo isset($row['codigo_postal']) ? $row['codigo_postal'] : ''; ?>">
    <div class="invalid-feedback">
      Ingrese el código postal
    </div>
  </div>

  <div class="col-md-12 position-relative">
    <label for="observaciones" class="form-label">Observaciones</label>
    <textarea class="form-control" id="observaciones" name="observaciones"
      rows="3"><?php echo isset($row['observaciones']) ? $row['observaciones'] : ''; ?></textarea>
  </div>

</form>
<div class="mt-4" align="center">
  <button type="submit" class="btn btn-primary" onclick="guardar()">Guardar</button>
  <button type="button" class="btn btn-danger" onclick="cerrar_formulario()">Cancelar</button>
</div>

<hr>