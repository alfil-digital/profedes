<!-- controlador Proveedores -->
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

function editar($con)
{
  $id = (int)$_POST['id'];
  $cuit = mysqli_real_escape_string($con, $_POST['cuit']);
  $descripcion = mysqli_real_escape_string($con, $_POST['descripcion']);
  $telefono = mysqli_real_escape_string($con, $_POST['telefono']);
  $domicilio = mysqli_real_escape_string($con, $_POST['domicilio']);
  $razon_social = mysqli_real_escape_string($con, $_POST['razon_social']);
  $nombre_fantasia = mysqli_real_escape_string($con, $_POST['nombre_fantasia']);

  if ($id > 0) {
    // update
    $sql = "UPDATE proveedores SET 
                      cuit = '$cuit',
                      descripcion = '$descripcion',
                      telefono = '$telefono',
                      domicilio = '$domicilio',
                      razon_social = '$razon_social',
                      nombre_fantasia = '$nombre_fantasia'
                  WHERE id = $id";
    $mensaje = "El registro se modificó con éxito";
  } else {
    // insert
    $sql = "INSERT INTO proveedores ( 
              cuit, 
              descripcion,
              telefono,
              domicilio,
              razon_social,
              nombre_fantasia
            ) VALUES (
              '$cuit',
              '$descripcion',
              '$telefono',
              '$domicilio',
              '$razon_social',
              '$nombre_fantasia'
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

function eliminar($con)
{
  $id = (int)$_POST['id'];
  $sql = "DELETE FROM proveedores WHERE id = $id";

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
