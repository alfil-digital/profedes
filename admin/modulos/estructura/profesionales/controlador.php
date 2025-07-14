<?php
if (isset($_GET['f'])) {
  $function = $_GET['f'];
} else {
  $function = "";
}

session_start();
include("../../../inc/conexion.php");
$con = conectar();

if (function_exists($function)) {
  $function($con);
} else {
  echo "La funcion " . $function . " no existe...";
}

function editar($con) {

  $id = (int)$_POST['id'];
  $nombre = mysqli_real_escape_string($con, $_POST['nombre']);
  $apellido = mysqli_real_escape_string($con, $_POST['apellido']);
  $dni = mysqli_real_escape_string($con, $_POST['dni']);
  $telefono = mysqli_real_escape_string($con, $_POST['telefono']);
  $localidad_id = (int)$_POST['localidad_id'];
  $mail = mysqli_real_escape_string($con, $_POST['mail']);
  $cuil = mysqli_real_escape_string($con, $_POST['cuil']);
  $observaciones = mysqli_real_escape_string($con, $_POST['observaciones']);
  $domicilio = mysqli_real_escape_string($con, $_POST['domicilio']);
  
  if ($id > 0) {
    // update
    $sql = "UPDATE personas SET 
            nombre = '$nombre',
            apellido = '$apellido',
            dni = '$dni',
            telefono = '$telefono',
            localidad_id = $localidad_id,
            mail = '$mail',
            cuil = '$cuil',
            observaciones = '$observaciones',
            domicilio = '$domicilio'
            WHERE id = $id";
    $mensaje = "El registro se modificó con éxito";
  } else {
    // insert
    $sql = "INSERT INTO personas (
              nombre,
              apellido,
              dni,
              telefono,
              localidad_id,
              mail,
              cuil,
              observaciones,
              domicilio,
            ) VALUES (
              '$nombre',
              '$apellido',
              '$dni',
              '$telefono',
              $localidad_id,
              '$mail',
              '$cuil',
              '$observaciones',
              '$domicilio'
            )";
    $mensaje = "El registro se creó con éxito";
  }
  
  if (mysqli_query($con, $sql)) {
    echo '
    <div class="alert alert-primary animated--grow-in" role="alert">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <i class="far fa-check-circle"></i> ' . $mensaje . '
    </div>';
    echo "<script>listado();</script>";
    echo "<script>cerrar_formulario();</script>";
  } else {
    echo '
    <div class="alert alert-danger" role="alert">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <i class="fas fa-exclamation-triangle"></i> No se pudo crear el registro
    </div>';
  }
}

function eliminar($con) {
  $id = (int)$_POST['id'];
  
  // Primero verificamos si la persona está siendo usada en la tabla usuarios
  $sql_check = "SELECT COUNT(*) as usado FROM usuarios WHERE persona_id = $id";
  $resultado = mysqli_query($con, $sql_check);
  $row = mysqli_fetch_array($resultado);
  
  if ($row['usado'] > 0) {
    echo '
    <div class="alert alert-danger" role="alert">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <i class="fas fa-exclamation-triangle"></i> No se puede eliminar la persona porque está asociada a un usuario
    </div>';
    return;
  }
  
  $sql = "DELETE FROM personas WHERE id = $id";
  
  if (mysqli_query($con, $sql)) {
    echo '
    <div class="alert alert-primary" role="alert">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <i class="far fa-check-circle"></i> El registro se eliminó con éxito
    </div>';
  } else {
    echo '
    <div class="alert alert-danger" role="alert">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <i class="fas fa-exclamation-triangle"></i> No se pudo eliminar el registro
    </div>';
  }
}