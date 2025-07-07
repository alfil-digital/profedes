<!-- controlador Cambiar Clave -->
<?php
session_start();
include("../../../inc/conexion.php");
conectar();
if (isset($_POST['clave_actual'])) {
  $sql = "select u.clave from usuarios u where u.id='" . $_SESSION['userid'] . "' ";
  $resultado = mysqli_query($con, $sql);
  if (mysqli_num_rows($resultado) != 0) {
    $row = mysqli_fetch_array($resultado);
    if (password_verify($_POST['clave_actual'], $row['clave'])) {
      $clave_nueva = password_hash($_POST['clave_nueva'], PASSWORD_DEFAULT);
      $sql = "UPDATE usuarios set clave='" . $clave_nueva . "' where id=" . $_SESSION['userid'];
      if (mysqli_query($con, $sql)) {
        echo "<script>alert('Clave actualizada con exito');cerrar_pass();</script>";
      } else {
        echo "<script>alert('Error: No se pudo Actualizar el registro.');</script>";
      }
    } else {
      echo "<script>
      alert('Error: La clave actual no coincide.'); 
      clave_actual.value = '';
      $('#clave_actual').focus();</script>";
    }
  }
}
