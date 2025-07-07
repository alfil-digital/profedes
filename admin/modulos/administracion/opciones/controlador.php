<!-- controlador Opciones -->
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
  echo "La funcion" . $function . "no existe...";
}

function editar($con)
{
  $id = (int)$_POST['id'];
  $titulo = mysqli_real_escape_string($con, $_POST['titulo']);
  $descripcion = mysqli_real_escape_string($con, $_POST['descripcion']);
  $icono = mysqli_real_escape_string($con, $_POST['icono']);
  $orden = (int)$_POST['orden'];

  if ($id > 0) {
    //update
    $sql = "UPDATE opciones SET 
            titulo = '$titulo', 
            descripcion = '$descripcion',
            icono = '$icono',
            orden = $orden, 
            usuario_abm='admin' 
            WHERE id = $id";
    $mensaje = "El registro se modificó con éxito";
  } else {
    // insert
    $sql = "INSERT INTO opciones (titulo, descripcion, icono, orden, usuario_abm) 
            VALUES ('$titulo', '$descripcion', '$icono', $orden, 'admin')";
    $mensaje = "El registro se creó con éxito";
  }

  //ejecuto la consulta
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
    <div class="alert alert-danger animated--grow-in" role="alert">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <i class="fas fa-exclamation-triangle"></i> No se pudo crear el registro
    </div>';
  }
}

function eliminar($con)
{
  $id = (int)$_POST['id'];
  $sql = "DELETE FROM opciones WHERE id = $id";

  if (mysqli_query($con, $sql)) {
    echo '
    <div class="alert alert-primary animated--grow-in" role="alert">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <i class="far fa-check-circle"></i> El registro se eliminó con éxito
    </div>';
  } else {
    echo '
    <div class="alert alert-danger animated--grow-in" role="alert">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <i class="fas fa-exclamation-triangle"></i> No se pudo eliminar el registro
    </div>';
  }
}
