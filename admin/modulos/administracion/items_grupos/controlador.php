<!-- controlador Items Grupos -->
<?php
session_start();
include("../../../inc/conexion.php");
conectar();

// Aseguramos que los IDs sean enteros para prevenir SQL injection
$grupo_id = (int)$_GET['grupo_id'];
$item_id = (int)$_GET['item_id'];

if ($_GET['tipo'] == 'add') {
  // Especificamos las columnas en el INSERT para evitar errores de conteo de columnas
  $sql = "INSERT INTO grupos_items (grupo_id, item_id) VALUES ($grupo_id, $item_id)";
  echo '
        <div class="alert alert-primary animated--grow-in" role="alert">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <i class="far fa-check-circle"></i> Habilitado correctamente!
        </div>';
} else {
  // DELETE no requiere especificación de columnas
  $sql = "DELETE FROM grupos_items WHERE grupo_id=$grupo_id AND item_id=$item_id";
  echo '
        <div class="alert alert-primary animated--grow-in" role="alert">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <i class="far fa-check-circle"></i> Deshabilitado correctamente!
        </div>';
}

mysqli_query($con, $sql);
