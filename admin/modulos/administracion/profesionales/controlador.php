<?php
// controlador.php (del módulo profesionales)

// Este bloque de código inicial es el punto de entrada para el controlador.
// Su propósito es determinar qué función debe ejecutarse basándose en un parámetro 'f'
// recibido a través de la URL (método GET).

// Comprueba si la variable 'f' está presente en la URL (parámetros GET).
if (isset($_GET['f'])) {
  // Si 'f' está presente, asigna su valor a la variable $function.
  // Esta variable probablemente contendrá el nombre de una función que se desea ejecutar.
  $function = $_GET['f'];
} else {
  // Si 'f' no está presente en la URL, asigna una cadena vacía a $function.
  $function = "";
}

// Inicia o reanuda una sesión PHP.
// Las sesiones se utilizan para almacenar información del usuario a través de múltiples páginas.
session_start();

// Incluye el archivo 'conexion.php' desde el directorio "../../../inc/".
// Este archivo probablemente contiene el código para establecer la conexión a la base de datos.
include("../../../inc/conexion.php");

// Llama a la función 'conectar()' (definida en 'conexion.php') para establecer
// la conexión a la base de datos y asigna el objeto de conexión a la variable $con.
$con = conectar();

// Comprueba si la función cuyo nombre está almacenado en $function existe.
// function_exists() es una función de PHP que verifica si una función ha sido definida.
if (function_exists($function)) {
  // Si la función existe, la llama, pasando el objeto de conexión ($con) como argumento.
  // Esto permite que la función interactúe con la base de datos.
  $function($con);
} else {
  // Si la función no existe, imprime un mensaje de error indicando que la función no fue encontrada.
  echo "La funcion " . $function . " no existe...";
}

// --- FUNCIÓN EDITAR ---
// Esta función se encarga de insertar o actualizar los datos de una persona y, específicamente, un profesional.
// Utiliza transacciones para asegurar la integridad de los datos en múltiples tablas.
function editar($con)
{
  // Recupera el 'id' de la persona del POST, convirtiéndolo a entero. Si no existe, es 0.
  $id = (int) ($_POST['id'] ?? 0);
  // Recupera el nombre de usuario de la sesión para auditoría, o 'SYSTEM' si no está definido.
  // mysqli_real_escape_string se usa para prevenir inyecciones SQL.
  $usuario_abm = mysqli_real_escape_string($con, $_SESSION['usuario'] ?? 'SYSTEM');

  // Recupera y sanitiza los datos de la persona desde los datos enviados por POST.
  // Cada asignación utiliza el operador de fusión de null (??) para proporcionar un valor predeterminado
  // si la clave no existe en $_POST, y mysqli_real_escape_string para seguridad.
  $nombre = mysqli_real_escape_string($con, $_POST['nombre'] ?? '');
  $apellido = mysqli_real_escape_string($con, $_POST['apellido'] ?? '');
  $dni = mysqli_real_escape_string($con, $_POST['dni'] ?? '');
  $telefono = mysqli_real_escape_string($con, $_POST['telefono'] ?? '');
  $localidad_id = (int) ($_POST['localidad_id'] ?? 0);
  $email = mysqli_real_escape_string($con, $_POST['mail'] ?? '');
  $cuil = mysqli_real_escape_string($con, $_POST['cuil'] ?? '');
  $observaciones = mysqli_real_escape_string($con, $_POST['observaciones'] ?? '');
  $domicilio = mysqli_real_escape_string($con, $_POST['domicilio'] ?? '');
  // Campo específico para la tabla 'profesionales'.
  $detalle_profesional = mysqli_real_escape_string($con, $_POST['detalle'] ?? '');
  // Campos específicos para la tabla 'matriculas'.
  $numero_matricula = mysqli_real_escape_string($con, $_POST['numero_matricula'] ?? '');
  $tipo_matricula = mysqli_real_escape_string($con, $_POST['tipo_matricula'] ?? '');
  // Asumimos un estado_id por defecto para el profesional si no se gestiona en el formulario
  $estado_profesional_id = 1; // Por ejemplo, 1 para 'Activo'

  // --- OBTENER ID DE LA ENTIDAD 'Profesional' ---
  // MODIFICACIÓN: Cambiado de 'Cliente' a 'Profesional' para obtener el ID de la entidad correcta.
  $sql_entidad_profesional = "SELECT id FROM entidades WHERE tipo_entidad = 'Profesional'";
  // Ejecuta la consulta.
  $resultado_entidad = mysqli_query($con, $sql_entidad_profesional);
  // Comprueba si la consulta falló o no se encontró la entidad 'Profesional'.
  if (!$resultado_entidad || mysqli_num_rows($resultado_entidad) == 0) {
    // Si hay un error, muestra un mensaje de alerta y termina la función.
    echo '
        <div class="alert alert-danger" role="alert">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <i class="fas fa-exclamation-triangle"></i> Error: La entidad "Profesional" no fue encontrada en la base de datos.
        </div>';
    return;
  }
  // Obtiene el resultado de la consulta como un array asociativo.
  $row_entidad = mysqli_fetch_array($resultado_entidad);
  // Asigna el ID de la entidad 'Profesional'.
  $entidad_profesional_id = $row_entidad['id'];

  // --- INICIO DE TRANSACCIÓN ---
  // Inicia una transacción para asegurar que todas las operaciones de la base de datos se completen
  // o se reviertan si alguna falla.
  mysqli_begin_transaction($con);

  try {
    // --- LÓGICA DE ACTUALIZACIÓN O INSERCIÓN EN 'personas' ---
    // Si $id es mayor que 0, significa que es una actualización de un registro existente.
    if ($id > 0) {
      // Consulta SQL para actualizar un registro existente en la tabla 'personas'.
      $sql_personas = "UPDATE personas SET
                nombre = '$nombre',
                apellido = '$apellido',
                dni = '$dni',
                telefono = '$telefono',
                localidad_id = $localidad_id,
                email = '$email',
                cuil = '$cuil',
                observaciones = '$observaciones',
                domicilio = '$domicilio'
                WHERE id = $id";

      // Mensaje para mostrar si la operación es exitosa.
      $mensaje = "El registro se modificó con éxito";
    } else {
      // Si $id es 0, significa que es una nueva inserción.
      // Consulta SQL para insertar un nuevo registro en la tabla 'personas'.
      $sql_personas = "INSERT INTO personas (
                nombre,
                apellido,
                dni,
                telefono,
                localidad_id,
                email,
                cuil,
                observaciones,
                domicilio
            ) VALUES (
                '$nombre',
                '$apellido',
                '$dni',
                '$telefono',
                $localidad_id,
                '$email',
                '$cuil',
                '$observaciones',
                '$domicilio'
            )";
      // Mensaje para mostrar si la operación es exitosa.
      $mensaje = "El registro se creó con éxito";
    }

    // --- EJECUTAR CONSULTA DE 'personas' ---
    // Ejecuta la consulta SQL definida para la tabla 'personas'.
    if (!mysqli_query($con, $sql_personas)) {
      // Si la consulta falla, lanza una excepción con el error de MySQL.
      throw new Exception("Error al guardar la información de la persona: " . mysqli_error($con));
    }

    // Si fue un INSERT ($id era 0), obtiene el ID de la persona recién creada.
    if ($id == 0) {
      $id = mysqli_insert_id($con);
    }

    // --- MANEJAR LA TABLA 'profesionales' (antes 'clientes') ---
    // MODIFICACIÓN: Reemplazado el manejo de la tabla 'clientes' por 'profesionales'.
    // Consulta para verificar si ya existe un registro de profesional asociado a esta persona.
    $sql_check_profesional = "SELECT id, persona_id FROM profesionales WHERE persona_id = $id";
    $resultado_check_profesional = mysqli_query($con, $sql_check_profesional);
    $row_check_profesional = mysqli_fetch_array($resultado_check_profesional);
    $profesional_existe = ($row_check_profesional && mysqli_num_rows($resultado_check_profesional) > 0);
    $profesional_id = $row_check_profesional['id'] ?? 0;

    if ($profesional_existe) {
      // Si ya existe un registro de profesional para esta persona, se actualiza.
      $sql_profesional = "UPDATE profesionales SET detalle = '$detalle_profesional', estado_id = $estado_profesional_id WHERE persona_id = $id";
    } else {
      // Si no existe, se inserta un nuevo registro de profesional.
      $sql_profesional = "INSERT INTO profesionales (persona_id, detalle, estado_id) VALUES ($id, '$detalle_profesional', $estado_profesional_id)";
    }

    // Ejecuta la consulta SQL para la tabla 'profesionales'.
    if (!mysqli_query($con, $sql_profesional)) {
      throw new Exception("Error al guardar el detalle del profesional: " . mysqli_error($con));
    }

    // Si se acaba de insertar un nuevo profesional, obtenemos su ID.
    if (!$profesional_existe) {
      $profesional_id = mysqli_insert_id($con);
    }

    // --- MANEJAR LA TABLA 'matriculas' (NUEVO) ---
    // MODIFICACIÓN: Lógica para manejar la tabla 'matriculas'.
    if (!empty($numero_matricula)) {
      // Consulta para verificar si ya existe una matrícula asociada a este profesional.
      // (Esta implementación asume una matrícula por profesional para simplificar.
      // Para múltiples matrículas, se necesitaría un manejo más complejo).
      $sql_check_matricula = "SELECT COUNT(*) as existe_matricula FROM matriculas WHERE profesional_id = $profesional_id";
      $resultado_check_matricula = mysqli_query($con, $sql_check_matricula);
      $row_check_matricula = mysqli_fetch_array($resultado_check_matricula);

      if ($row_check_matricula['existe_matricula'] > 0) {
        // Si existe, actualizar la matrícula (se asume que se actualiza la primera o la única)
        $sql_matricula = "UPDATE matriculas SET
                                    numero_matricula = '$numero_matricula',
                                    fecha_alta = CURDATE(), -- Asume fecha actual de alta o un campo en el formulario
                                    tipo = '$tipo_matricula',
                                    usuario_abm = '$usuario_abm',
                                    fecha_modificacion = NOW()
                                WHERE profesional_id = $profesional_id";
      } else {
        // Si no existe, insertar una nueva matrícula
        $sql_matricula = "INSERT INTO matriculas (
                                    profesional_id,
                                    numero_matricula,
                                    fecha_alta,
                                    tipo,
                                    usuario_abm,
                                    fecha_creacion
                                ) VALUES (
                                    $profesional_id,
                                    '$numero_matricula',
                                    CURDATE(), -- Asume fecha actual de alta
                                    '$tipo_matricula',
                                    '$usuario_abm',
                                    NOW()
                                )";
      }

      if (!mysqli_query($con, $sql_matricula)) {
        throw new Exception("Error al guardar la matrícula del profesional: " . mysqli_error($con));
      }
    }


    // --- MANEJAR LA TABLA 'personas_entidades' ---
    // Asegurar que la relación entre la persona y la entidad 'Profesional' exista.
    // MODIFICACIÓN: Cambiado a 'entidad_profesional_id'.
    $sql_check_pe = "SELECT COUNT(*) as existe_pe FROM personas_entidades WHERE persona_id = $id AND entidad_id = $entidad_profesional_id";
    $resultado_check_pe = mysqli_query($con, $sql_check_pe);
    $row_check_pe = mysqli_fetch_array($resultado_check_pe);

    // Si la relación no existe, se inserta.
    if ($row_check_pe['existe_pe'] == 0) {
      // Consulta SQL para insertar la relación persona-entidad 'Profesional'.
      $sql_insert_pe = "INSERT INTO personas_entidades (persona_id, entidad_id) VALUES ($id, $entidad_profesional_id)";
      // Ejecuta la consulta.
      if (!mysqli_query($con, $sql_insert_pe)) {
        // Si la consulta falla, lanza una excepción.
        throw new Exception("Error al guardar la relación persona-entidad para el profesional: " . mysqli_error($con));
      }
    }

    // Si todas las operaciones fueron exitosas, confirma la transacción.
    mysqli_commit($con);
    // Muestra un mensaje de éxito.
    echo '
        <div class="alert alert-primary animated--grow-in" role="alert">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <i class="far fa-check-circle"></i> ' . $mensaje . '
        </div>';
    // Llama a funciones JavaScript para refrescar el listado y cerrar el formulario.
    echo "<script>listado();</script>";
    echo "<script>cerrar_formulario();</script>";

  } catch (Exception $e) {
    // Si ocurre un error en cualquier punto de la transacción, se revierte.
    mysqli_rollback($con);
    // Muestra un mensaje de error con los detalles de la excepción.
    echo '
        <div class="alert alert-danger" role="alert">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <i class="fas fa-exclamation-triangle"></i> Error: ' . $e->getMessage() . '
        </div>';
  }
}

// --- FUNCIÓN ELIMINAR ---
// Esta función se encarga de eliminar un profesional, su relación con la persona y su matrícula.
function eliminar($con)
{
  // Recupera el 'id' de la persona a eliminar del GET.
  $id = (int) ($_GET['id'] ?? 0);

  if ($id == 0) {
    echo '<div class="alert alert-danger" role="alert"><button type="button" class="close" data-dismiss="alert">&times;</button><i class="fas fa-exclamation-triangle"></i> Error: ID de profesional no válido.</div>';
    return;
  }

  // --- OBTENER ID DE LA ENTIDAD 'Profesional' ---
  // MODIFICACIÓN: Necesario para eliminar la relación correcta en personas_entidades.
  $sql_entidad_profesional = "SELECT id FROM entidades WHERE tipo_entidad = 'Profesional'";
  $resultado_entidad = mysqli_query($con, $sql_entidad_profesional);
  if (!$resultado_entidad || mysqli_num_rows($resultado_entidad) == 0) {
    echo '<div class="alert alert-danger" role="alert"><button type="button" class="close" data-dismiss="alert">&times;</button><i class="fas fa-exclamation-triangle"></i> Error: La entidad "Profesional" no fue encontrada.</div>';
    return;
  }
  $row_entidad = mysqli_fetch_array($resultado_entidad);
  $entidad_profesional_id = $row_entidad['id'];

  // Inicia una transacción para asegurar la integridad.
  mysqli_begin_transaction($con);

  try {
    // Primero, obtener el profesional_id para eliminar la matrícula
    $sql_get_profesional_id = "SELECT id FROM profesionales WHERE persona_id = $id";
    $result_prof_id = mysqli_query($con, $sql_get_profesional_id);
    $row_prof_id = mysqli_fetch_array($result_prof_id);
    $profesional_id_to_delete = $row_prof_id['id'] ?? 0;

    // Verificar si la persona está asociada a algún usuario antes de eliminar (regla de negocio).
    $sql_check_usuario = "SELECT COUNT(*) AS num_usuarios FROM usuarios WHERE persona_id = $id";
    $resultado_check_usuario = mysqli_query($con, $sql_check_usuario);
    $row_check_usuario = mysqli_fetch_array($resultado_check_usuario);
    if ($row_check_usuario['num_usuarios'] > 0) {
      throw new Exception("No se puede eliminar la persona porque está asociada a uno o más usuarios.");
    }

    // MODIFICACIÓN: Eliminar de 'matriculas' primero, luego de 'profesionales'.
    if ($profesional_id_to_delete > 0) {
      $sql_delete_matriculas = "DELETE FROM matriculas WHERE profesional_id = $profesional_id_to_delete";
      if (!mysqli_query($con, $sql_delete_matriculas)) {
        throw new Exception("Error al eliminar la(s) matrícula(s) del profesional: " . mysqli_error($con));
      }
    }

    $sql_delete_profesional = "DELETE FROM profesionales WHERE persona_id = $id";
    if (!mysqli_query($con, $sql_delete_profesional)) {
      throw new Exception("Error al eliminar el registro de profesional: " . mysqli_error($con));
    }

    // MODIFICACIÓN: Eliminar la relación en personas_entidades para 'Profesional'.
    $sql_delete_pe = "DELETE FROM personas_entidades WHERE persona_id = $id AND entidad_id = $entidad_profesional_id";
    if (!mysqli_query($con, $sql_delete_pe)) {
      throw new Exception("Error al eliminar la relación persona-entidad para el profesional: " . mysqli_error($con));
    }

    // Finalmente, eliminar de la tabla 'personas'.
    $sql_delete_persona = "DELETE FROM personas WHERE id = $id";
    if (!mysqli_query($con, $sql_delete_persona)) {
      throw new Exception("Error al eliminar la persona: " . mysqli_error($con));
    }

    mysqli_commit($con);
    echo '<div class="alert alert-primary animated--grow-in" role="alert"><button type="button" class="close" data-dismiss="alert">&times;</button><i class="far fa-check-circle"></i> El profesional y sus datos asociados se eliminaron con éxito.</div>';
    echo "<script>listado();</script>"; // Refresca el listado después de eliminar
  } catch (Exception $e) {
    mysqli_rollback($con);
    echo '<div class="alert alert-danger" role="alert"><button type="button" class="close" data-dismiss="alert">&times;</button><i class="fas fa-exclamation-triangle"></i> Error al eliminar el profesional: ' . $e->getMessage() . '</div>';
  }
}
?>