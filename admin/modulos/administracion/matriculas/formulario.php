<?php
// formulario matriculas
session_start();
include("../../../inc/conexion.php"); // Asegúrate de la ruta a tu conexión
$con = conectar();

$row = null;
// Obtener el ID del profesional de la URL para nuevas entradas
$profesional_id_from_get = (int)($_GET['profesional_id'] ?? 0);

if (isset($_GET['id']) && $_GET['id'] != 0) {
  $id = (int)$_GET['id'];
  $sql = "SELECT id, profesional_id, numero_matricula, fecha_alta, tipo FROM matriculas WHERE id = $id";
  $resultado = mysqli_query($con, $sql);

  if ($resultado && mysqli_num_rows($resultado) > 0) {
    $row = mysqli_fetch_array($resultado);
    // Si se está editando, asegurarse de que el profesional_id venga de la fila, no de GET
    $profesional_id_from_get = (int)($row['profesional_id'] ?? 0);
  } else {
    $id = 0; // No encontrado, tratar como nuevo registro
  }
} else {
  $id = 0; // Nuevo registro
}
?>
<form method="post" id="form_matricula" class="row needs-validation">
  <input type="hidden" id="id" name="id" value="<?php echo isset($id) ? $id : 0; ?>">
  <input type="hidden" id="profesional_id" name="profesional_id" value="<?php echo $profesional_id_from_get; ?>">

  <div class="col-md-4 position-relative">
    <label for="numero_matricula" class="form-label">Número de Matrícula</label>
    <input type="text" class="form-control" id="numero_matricula" name="numero_matricula" placeholder="Número de Matrícula" required minlength="3"
      value="<?php echo isset($row['numero_matricula']) ? htmlspecialchars($row['numero_matricula']) : ''; ?>">
    <div class="invalid-feedback">
      Ingrese el número de matrícula
    </div>
  </div>

  <div class="col-md-4 position-relative">
    <label for="fecha_alta" class="form-label">Fecha de Alta</label>
    <input type="date" class="form-control" id="fecha_alta" name="fecha_alta" required
      value="<?php echo isset($row['fecha_alta']) ? $row['fecha_alta'] : ''; ?>">
    <div class="invalid-feedback">
      Ingrese la fecha de alta
    </div>
  </div>

  <div class="col-md-4 position-relative">
    <label for="tipo" class="form-label">Tipo de Matrícula</label>
    <input type="text" class="form-control" id="tipo" name="tipo" placeholder="Ej: Nacional, Provincial, Colegiado"
      value="<?php echo isset($row['tipo']) ? htmlspecialchars($row['tipo']) : ''; ?>">
    <div class="invalid-feedback">
      Ingrese el tipo de matrícula (opcional)
    </div>
  </div>

  </form>
<div class="mt-4" align="center">
  <button type="submit" class="btn btn-primary" onclick="guardarMatricula()">Guardar Matrícula</button>
  <button type="button" class="btn btn-danger" onclick="cerrar_formulario_matricula()">Cancelar</button>
</div>

<hr>