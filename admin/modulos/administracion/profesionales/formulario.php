<?php
// Inicia o reanuda una sesión PHP. Las sesiones se utilizan para almacenar
// información del usuario a través de múltiples páginas.
session_start();

// Incluye el archivo 'conexion.php' desde el directorio "../../../inc/".
// Este archivo probablemente contiene el código para establecer la conexión a la base de datos.
include("../../../inc/conexion.php");

// Llama a la función 'conectar()' (definida en 'conexion.php') para establecer
// la conexión a la base de datos y asigna el objeto de conexión a la variable $con.
$con = conectar();

// Inicializa la variable $row a null. Esta variable contendrá los datos de la persona/profesional
// si se está editando un registro existente.
$row = null;

// Comprueba si se ha pasado un 'id' en los parámetros GET de la URL y si su valor no es 0.
// Esto indica que se está intentando editar un registro existente.
if (isset($_GET['id']) && $_GET['id'] != 0) {
  // Convierte el 'id' a un entero para asegurar su tipo y seguridad.
  $id = (int) $_GET['id'];
  // Consulta SQL para seleccionar los datos de una persona, incluyendo información de
  // localidad, provincia, el detalle específico del profesional y los datos de la matrícula.
  // MODIFICACIÓN: Se cambió el LEFT JOIN de 'clientes' a 'profesionales' y se añadió
  // un LEFT JOIN a 'matriculas' para obtener los datos de la matrícula.
  $sql = "SELECT
                p.*,                 -- Selecciona todas las columnas de la tabla 'personas' (p)
                l.nombre AS localidad_nombre, -- El nombre de la localidad
                prov.id AS provincia_id,     -- El ID de la provincia
                prof.detalle AS detalle_profesional, -- El detalle específico del profesional de la tabla 'profesionales' (prof)
                m.numero_matricula,          -- El número de matrícula del profesional
                m.tipo AS tipo_matricula     -- El tipo de matrícula
            FROM
                personas p           -- Alias 'p' para la tabla 'personas'
            INNER JOIN
                localidades l ON l.id = p.localidad_id -- Une con 'localidades' por el ID de localidad
            INNER JOIN
                provincias prov ON l.provincia_id = prov.id -- Une con 'provincias' por el ID de provincia
            LEFT JOIN                  -- LEFT JOIN para 'profesionales' (antes 'clientes')
                profesionales prof ON p.id = prof.persona_id -- Une con 'profesionales' por el ID de persona
            LEFT JOIN                  -- NUEVO: LEFT JOIN para 'matriculas'
                matriculas m ON prof.id = m.profesional_id -- Une con 'matriculas' por el ID de profesional
            WHERE
                p.id = $id";         // Filtra por el ID de la persona recibido
  // Ejecuta la consulta SQL.
  $resultado = mysqli_query($con, $sql);
  // Comprueba si la consulta se ejecutó con éxito y si se encontró al menos un registro.
  if ($resultado && mysqli_num_rows($resultado) > 0) {
    // Si se encuentra el registro, obtiene los datos como un array asociativo y los asigna a $row.
    $row = mysqli_fetch_array($resultado);
  } else {
    // Si no se encuentra el registro (por ejemplo, ID inválido), se resetea $id a 0
    // para que el formulario se comporte como una nueva inserción.
    $id = 0;
  }
} else {
  // Si no se pasa un 'id' o es 0, se asume que es una nueva inserción.
  $id = 0;
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
    <label for="detalle" class="form-label"> Detalle Profesional</label>
    <input type="text" class="form-control" id="detalle" name="detalle" placeholder="Detalle Profesional"
      value="<?php echo isset($row['detalle_profesional']) ? $row['detalle_profesional'] : ''; ?>">
    <div class="invalid-feedback">
      Ingrese el detalle del profesional
    </div>
  </div>
  <div class="col-md-4 position-relative">
    <label for="numero_matricula" class="form-label">Número Matrícula</label>
    <input type="text" class="form-control" id="numero_matricula" name="numero_matricula"
      placeholder="Número de Matrícula"
      value="<?php echo isset($row['numero_matricula']) ? $row['numero_matricula'] : ''; ?>">
    <div class="invalid-feedback">
      Ingrese el número de matrícula
    </div>
  </div>
  <div class="col-md-4 position-relative">
    <label for="tipo_matricula" class="form-label">Tipo Matrícula</label>
    <input type="text" class="form-control" id="tipo_matricula" name="tipo_matricula" placeholder="Tipo de Matrícula"
      value="<?php echo isset($row['tipo_matricula']) ? $row['tipo_matricula'] : ''; ?>">
    <div class="invalid-feedback">
      Ingrese el tipo de matrícula
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
      // Consulta SQL para obtener todas las provincias ordenadas por nombre.
      $sql_provincias = "SELECT id,nombre FROM provincias ORDER BY nombre";
      // Ejecuta la consulta.
      $resultado_provincias = mysqli_query($con, $sql_provincias);
      // Itera sobre cada fila de resultados para crear las opciones del dropdown.
      while ($row_provincia = mysqli_fetch_array($resultado_provincias)) {
        $selected = "";
        // Si se está editando un registro y la provincia coincide, la marca como seleccionada.
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
      // MODIFICACIÓN: Aquí se incluye la lógica para cargar TODAS las localidades directamente.
      // Esta es la implementación que solicitaste.
      // NOTA: Esto cargará todas las localidades en el select, sin filtrar por provincia.
      // Para un filtrado dinámico, sería necesario el enfoque con AJAX que se eliminó.
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
  <br>
  <div class="modal-footer">
    <button type="button" class="btn btn-danger" onclick="cerrar_formulario();"><i class="fas fa-times"></i>
      Cerrar</button>
    <button type="button" class="btn btn-success" onclick="guardar(<?php echo $id; ?>);"><i class="fas fa-save"></i>
      Guardar</button>
  </div>
</form>

<script>
  // REMOCIÓN: Las funciones JavaScript `cargarLocalidades` y la llamada `$(document).ready()`
  // para cargar localidades dinámicamente han sido eliminadas, ya que la lógica
  // de carga de localidades ahora está completamente en PHP al cargar el formulario.
  // Si deseas la funcionalidad de filtrado dinámico, deberías reincorporar el JS/AJAX.

  // Example starter JavaScript for disabling form submissions if there are invalid fields
  (function () {
    'use strict'
    // Fetch all the forms we want to apply custom Bootstrap validation styles to
    var forms = document.querySelectorAll('.needs-validation')
    // Loop over them and prevent submission
    Array.prototype.slice.call(forms)
      .forEach(function (form) {
        form.addEventListener('submit', function (event) {
          if (!form.checkValidity()) {
            event.preventDefault()
            event.stopPropagation()
          }

          form.classList.add('was-validated')
        }, false)
      })
  })()
</script>