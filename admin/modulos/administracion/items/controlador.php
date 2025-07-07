<!-- controlador Items -->
<?php
// Este es el archivo controlador.php que maneja las acciones de CRUD

// Captura el parámetro 'f' de la URL que indica qué función ejecutar
// Este valor viene de las llamadas AJAX $.post o $.get en el JavaScript
if (isset($_GET['f'])) {
  $function = $_GET['f'];
} else {
  $function = "";
}

session_start();
include("../../../inc/conexion.php");
$con = conectar();

// Comprueba si la función solicitada existe y la ejecuta
// Este es un patrón básico de enrutamiento para llamar a 'editar' o 'eliminar'
if (function_exists($function)) {
  $function($con); // Ejecuta la función pasando la conexión a BD
} else {
  echo "La funcion" . $function . "no existe...";
}

function editar($con)
{
  // Esta función es llamada cuando guardar() en JS envía datos al controlador con f=editar
  // Recibe datos del formulario vía POST desde $.post en la función guardar()
  $id = (int)$_POST['id'];
  $descripcion = mysqli_real_escape_string($con, $_POST['descripcion']);
  $enlace = mysqli_real_escape_string($con, $_POST['enlace']);
  $opcion_id = (int)$_POST['opcion_id'];
  $orden = (int)$_POST['orden'];

  if ($id > 0) {
    //update - si el ID existe, actualiza el registro
    $sql = "UPDATE items SET descripcion = '$descripcion', enlace = '$enlace', 
            opcion_id = $opcion_id, orden = $orden, usuario_abm='admin' 
            WHERE id = $id";
    $mensaje = "El registro se modificó con éxito";
  } else {
    // insert - si el ID es 0, crea un nuevo registro
    $sql = "INSERT INTO items (descripcion, enlace, opcion_id, orden, usuario_abm) 
            VALUES ('$descripcion', '$enlace', $opcion_id, $orden, 'admin')";
    $mensaje = "El registro se creó con éxito";
  }

  //ejecuto la consulta
  if (mysqli_query($con, $sql)) {
    // Si la operación SQL fue exitosa
    echo '
    <div class="alert alert-primary animated--grow-in" role="alert">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <i class="far fa-check-circle"></i> ' . $mensaje . '
    </div>';
    // Ejecuta funciones JavaScript en el cliente para actualizar la UI
    echo "<script>listado();</script>"; // Recarga la lista
    echo "<script>cerrar_formulario();</script>"; // Cierra el formulario
  } else {
    // Si la operación SQL falló
    echo '
    <div class="alert alert-danger animated--grow-in" role="alert">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <i class="fas fa-exclamation-triangle"></i> No se pudo crear el registro
    </div>';
  }
}

function eliminar($con)
{
  // Esta función es llamada cuando eliminar() en JS envía datos al controlador con f=eliminar
  // Recibe el ID vía POST desde $.post en la función eliminar()
  $id = (int)$_POST['id'];
  $sql = "DELETE FROM items WHERE id = $id";

  if (mysqli_query($con, $sql)) {
    // Si la eliminación fue exitosa
    echo '
    <div class="alert alert-primary animated--grow-in" role="alert">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <i class="far fa-check-circle"></i> El registro se eliminó con éxito
    </div>';
  } else {
    // Si la eliminación falló
    echo '
    <div class="alert alert-danger animated--grow-in" role="alert">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <i class="fas fa-exclamation-triangle"></i> No se pudo eliminar el registro
    </div>';
  }
}
