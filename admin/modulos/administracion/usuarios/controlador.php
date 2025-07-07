<!-- controlador Usuarios -->
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

/* function editar($con)
{
  // Recibe el ID del usuario desde un formulario POST y lo convierte a entero para seguridad
  $id = (int)$_POST['id'];
  
  // Escapa caracteres especiales del nombre de usuario para prevenir inyección SQL
  $usuario = mysqli_real_escape_string($con, $_POST['usuario']);
  
  // Convierte el ID de persona a entero para seguridad
  $persona_id = (int)$_POST['persona_id'];
  
  // Convierte el ID de grupo a entero para seguridad
  $grupo_id = (int)$_POST['grupo_id'];
  
  // Genera un hash seguro de la contraseña utilizando el nombre de usuario como base
  // PASSWORD_DEFAULT usa el algoritmo de hash más fuerte disponible (actualmente bcrypt)
  $clave = password_hash($usuario, PASSWORD_DEFAULT);

  // Verifica si el ID es mayor que 0, lo que significa que estamos editando un usuario existente
  if ($id > 0) {
    // Prepara la consulta SQL para actualizar un registro existente
    // No actualiza la contraseña en actualizaciones, solo usuario, persona_id y grupo_id
    // 'usuario_abm' registra qué usuario realizó la modificación (hardcodeado como 'admin')
    $sql = "UPDATE usuarios SET 
            usuario = '$usuario',
            persona_id = $persona_id, 
            grupo_id = $grupo_id, 
            usuario_abm = 'admin' 
            WHERE id = $id";
    
    // Mensaje de éxito para mostrar si la actualización se realiza correctamente
    $mensaje = "El registro se modificó con éxito";
  } else {
    // Si el ID no es mayor que 0, se trata de un nuevo registro
    // Prepara la consulta SQL para insertar un nuevo usuario
    $sql = "INSERT INTO usuarios (
              usuario, 
              persona_id, 
              grupo_id, 
              clave, 
              estado,
              activo,
              fecha_alta,
              usuario_abm
            ) VALUES (
              '$usuario',
              $persona_id,
              $grupo_id,
              '$clave',
              1,                  // estado = 1 (activo/habilitado)
              1,                  // activo = 1 (usuario activo)
              NOW(),              // fecha_alta = fecha y hora actual del servidor
              'admin'             // usuario que realizó la alta (hardcodeado)
            )";
    
    // Mensaje de éxito para mostrar si la inserción se realiza correctamente
    $mensaje = "El registro se creó con éxito";
  }

  // Ejecuta la consulta SQL (ya sea UPDATE o INSERT)
  if (mysqli_query($con, $sql)) {
    // Si la consulta se ejecuta correctamente, muestra un mensaje de éxito
    // Utiliza Bootstrap para el estilo de alerta y animación
    echo '
    <div class="alert alert-primary animated--grow-in" role="alert">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <i class="far fa-check-circle"></i> ' . $mensaje . '
    </div>';
    
    // Ejecuta la función JavaScript 'listado()' para actualizar la lista de usuarios
    echo "<script>listado();</script>";
    
    // Ejecuta la función JavaScript 'cerrar_formulario()' para cerrar el formulario de edición
    echo "<script>cerrar_formulario();</script>";
  } else {
    // Si la consulta falla, muestra un mensaje de error con estilo Bootstrap
    echo '
    <div class="alert alert-danger" role="alert">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <i class="fas fa-exclamation-triangle"></i> No se pudo crear el registro
    </div>';
  }
} */
function editar($con)
{
  $id = (int)$_POST['id'];
  $usuario = mysqli_real_escape_string($con, $_POST['usuario']);
  $persona_id = (int)$_POST['persona_id'];
  $grupo_id = (int)$_POST['grupo_id'];
  $clave = password_hash($usuario, PASSWORD_DEFAULT);

  if ($id > 0) {
    //update
    $sql = "UPDATE usuarios SET 
            usuario = '$usuario',
            persona_id = $persona_id, 
            grupo_id = $grupo_id, 
            usuario_abm = 'admin' 
            WHERE id = $id";
    $mensaje = "El registro se modificó con éxito";
  } else {
    // insert
    $sql = "INSERT INTO usuarios (
              usuario, 
              persona_id, 
              grupo_id, 
              clave, 
              estado,
              activo,
              fecha_alta,
              usuario_abm
            ) VALUES (
              '$usuario',
              $persona_id,
              $grupo_id,
              '$clave',
              1,
              1,
              NOW(),
              'admin'
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
  $sql = "DELETE FROM usuarios WHERE id = $id";

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



function resetear_clave($con)
{
  $id = (int)$_POST['id'];

  //obtengo el nombre de usuario
  $sql = "SELECT usuario FROM usuarios WHERE id = $id";
  $resultado = mysqli_query($con, $sql);
  $row = mysqli_fetch_array($resultado);
  $usuario = $row['usuario'];

  // creo la clave por defecto (nombre de usuario)
  $clave = password_hash($usuario, PASSWORD_DEFAULT);
  $sql = "UPDATE usuarios SET clave='$clave' WHERE id=$id";

  if (mysqli_query($con, $sql)) {
    echo '<div class="alert alert-primary" role="alert">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <i class="far fa-check-circle"></i> Clave del Usuario:<strong> ' . $usuario . '</strong> fue Reseteada con éxito
    </div>';
  }
}


function bloquear_usuario($con)
{
  $id = (int)$_POST['id'];

  //obtengo el nombre de usuario
  $sql = "SELECT usuario FROM usuarios WHERE id = $id";
  $resultado = mysqli_query($con, $sql);
  $row = mysqli_fetch_array($resultado);
  $usuario = $row['usuario'];

  $sql = "UPDATE usuarios SET estado=0 WHERE id=$id";

  if (mysqli_query($con, $sql)) {
    echo '<div class="alert alert-primary" role="alert">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <i class="far fa-check-circle"></i> El Usuario:<strong> ' . $usuario . '</strong> fue bloqueado con éxito </div>';
  }
}


function activar_usuario($con)
{
  $id = (int)$_POST['id'];

  //obtengo el nombre de usuario
  $sql = "SELECT usuario FROM usuarios WHERE id = $id";
  $resultado = mysqli_query($con, $sql);
  $row = mysqli_fetch_array($resultado);
  $usuario = $row['usuario'];

  $sql = "UPDATE usuarios SET estado=1 WHERE id=$id";

  if (mysqli_query($con, $sql)) {
    echo '<div class="alert alert-primary" role="alert">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <i class="far fa-check-circle"></i> El Usuario:<strong> ' . $usuario . '</strong> fue activado con éxito </div>';
  }
}
