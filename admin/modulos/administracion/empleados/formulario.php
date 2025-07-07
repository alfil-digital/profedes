<?php
session_start();
include("../../../inc/conexion.php");
$con = conectar();

// Variable para almacenar los datos del empleado si se está editando
$row = null;

if (isset($_GET['id']) && $_GET['id'] != 0) {
  $id = (int) $_GET['id'];
  // La consulta ahora debe unirse a 'empleados' para obtener 'detalle' y 'legajo'
  // y a 'provincias' para obtener el ID de la provincia de la localidad.
  $sql = "SELECT
                p.*,
                l.nombre AS localidad_nombre, -- Renombrado para evitar conflicto con localidad_id
                prov.id AS provincia_id,
                e.detalle AS detalle_empleado, -- Alias para el detalle del empleado
                e.legajo AS legajo_empleado    -- Alias para el legajo del empleado
            FROM
                personas p
            INNER JOIN
                localidades l ON l.id = p.localidad_id
            INNER JOIN
                provincias prov ON l.provincia_id = prov.id
            LEFT JOIN -- LEFT JOIN es importante por si la persona aún no tiene un registro en 'empleados'
                empleados e ON p.id = e.persona_id
            WHERE
                p.id = $id";
  $resultado = mysqli_query($con, $sql);
  if ($resultado && mysqli_num_rows($resultado) > 0) {
    $row = mysqli_fetch_array($resultado);
  } else {
    $id = 0; // Asegurar que $id sea 0 si no se encontró, para nuevo registro
  }
} else {
  $id = 0; // Para el caso de un nuevo registro
}
?>
<form method="post" id="form" class="row needs-validation">
  <input type="hidden" class="form-control" id="id" name="id" value="<?php echo isset($id) ? $id : 0; ?>">

  <div class="col-md-4 position-relative">
    <label for="nombre" class="form-label">Nombre </label>
    <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Nombre" required minlength="3"
      value="<?php echo isset($row['nombre']) ? $row['nombre'] : ''; ?>">
    <div class="invalid-feedback">
      Ingrese el nombre
    </div>
  </div>
  <div class="col-md-4 position-relative">
    <label for="apellido" class="form-label">Apellido</label>
    <input type="text" class="form-control" required id="apellido" name="apellido" placeholder="Apellido" minlength="3"
      value="<?php echo isset($row['apellido']) ? $row['apellido'] : ''; ?>">
    <div class="invalid-feedback">
      Ingrese el apellido
    </div>
  </div>
  <div class="col-md-4 position-relative">
    <label for="dni" class="form-label">DNI</label>
    <input type="text" class="form-control" id="dni" name="dni" placeholder="DNI" required
      value="<?php echo isset($row['dni']) ? $row['dni'] : ''; ?>">
    <div class="invalid-feedback">
      Ingrese el DNI
    </div>
  </div>
  <div class="col-md-4 position-relative">
    <label for="legajo" class="form-label">Legajo </label>
    <input type="text" class="form-control" id="legajo" name="legajo" placeholder="Legajo"
      value="<?php echo isset($row['legajo_empleado']) ? $row['legajo_empleado'] : ''; ?>">
    <div class="invalid-feedback">
      Ingrese el legajo
    </div>
  </div>
  <div class="col-md-4 position-relative">
    <label for="telefono" class="form-label">Teléfono</label>
    <input type="text" class="form-control" id="telefono" name="telefono" placeholder="Teléfono"
      value="<?php echo isset($row['telefono']) ? $row['telefono'] : ''; ?>">
    <div class="invalid-feedback">
      Ingrese el teléfono
    </div>
  </div>
  <div class="col-md-4 position-relative">
    <label for="mail" class="form-label">Email</label>
    <input type="email" class="form-control" id="mail" name="mail" placeholder="Email"
      value="<?php echo isset($row['email']) ? $row['email'] : ''; ?>">
    <div class="invalid-feedback">
      Ingrese un email válido
    </div>
  </div>
  <div class="col-md-4 position-relative">
    <label for="cuil" class="form-label">CUIL</label>
    <input type="text" class="form-control" id="cuil" name="cuil" placeholder="CUIL"
      value="<?php echo isset($row['cuil']) ? $row['cuil'] : ''; ?>">
    <div class="invalid-feedback">
      Ingrese el CUIL
    </div>
  </div>
  <div class="col-md-4 position-relative">
    <label for="detalle" class="form-label"> Detalle Empleado</label>
    <input type="text" class="form-control" id="detalle" name="detalle" placeholder="Detalle Empleado"
      value="<?php echo isset($row['detalle_empleado']) ? $row['detalle_empleado'] : ''; ?>">
    <div class="invalid-feedback">
      Ingrese el detalle
    </div>
  </div>
  <div class="col-md-4 position-relative">
    <label for="domicilio" class="form-label">Domicilio</label>
    <input type="text" class="form-control" id="domicilio" name="domicilio" placeholder="Domicilio"
      value="<?php echo isset($row['domicilio']) ? $row['domicilio'] : ''; ?>">
    <div class="invalid-feedback">
      Ingrese el domicilio
    </div>
  </div>
  <div class="col-md-4 position-relative">
    <label for="provincia_id" class="form-label">Provincia</label>
    <select class="form-control" id="provincia_id" name="provincia_id" required>
      <option selected disabled value="">Seleccionar</option>
      <?php
      $sql_provincias = "SELECT id,nombre FROM provincias ORDER BY nombre";
      $resultado_provincias = mysqli_query($con, $sql_provincias);
      while ($row_provincia = mysqli_fetch_array($resultado_provincias)) {
        $selected = "";
        if (isset($row) && isset($row['provincia_id']) && $row['provincia_id'] == $row_provincia['id']) {
          $selected = "selected";
        }
        ?>
        <option <?php echo $selected; ?> value="<?php echo $row_provincia['id']; ?>">
          <?php echo $row_provincia['nombre']; ?>
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
      // Se recomienda cargar las localidades dinámicamente según la provincia seleccionada con JS
      // Por ahora, se cargan todas.
      $sql_localidades = "SELECT id,nombre FROM localidades ORDER BY nombre";
      $resultado_localidades = mysqli_query($con, $sql_localidades);
      while ($row_localidad = mysqli_fetch_array($resultado_localidades)) {
        $selected = "";
        if (isset($row) && isset($row['localidad_id']) && $row['localidad_id'] == $row_localidad['id']) {
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
    <textarea class="form-control" id="observaciones" name="observaciones"
      rows="3"><?php echo isset($row['observaciones']) ? $row['observaciones'] : ''; ?></textarea>
  </div>

</form>
<div class="mt-4" align="center">
  <button type="submit" class="btn btn-primary" onclick="guardar(<?= isset($id) ? $id : 0 ?>)">Guardar</button>
  <button type="button" class="btn btn-danger" onclick="cerrar_formulario()">Cancelar</button>
</div>

<hr>