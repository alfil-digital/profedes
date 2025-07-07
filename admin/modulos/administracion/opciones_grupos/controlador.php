<!-- controlador Opciones Grupos -->
<?php
session_start();
include("../../../inc/conexion.php");
conectar();

if ($_GET['tipo'] == 'add') {
  $grupo_id = (int)$_GET['grupo_id'];
  $opcion_id = (int)$_GET['opcion_id'];
  $sql = "INSERT INTO grupos_opciones (grupo_id, opcion_id) VALUES ($grupo_id, $opcion_id)";

  echo '
    <div class="alert alert-primary animated--grow-in" role="alert">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <i class="far fa-check-circle"></i> Habilitado correctamente!
    </div>';
} else {
  $grupo_id = (int)$_GET['grupo_id'];
  $opcion_id = (int)$_GET['opcion_id'];
  $sql = "DELETE FROM grupos_opciones WHERE grupo_id=$grupo_id AND opcion_id=$opcion_id";

  echo '
    <div class="alert alert-primary animated--grow-in" role="alert">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <i class="far fa-check-circle"></i> Deshabilitado correctamente!
    </div>';
}

mysqli_query($con, $sql);
