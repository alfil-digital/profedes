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
    echo "La función " . $function . " no existe...";
}

function guardar($con)
{

    // Recibe los datos del formulario
    $id = (int)$_POST['id'];
    $titulo = mysqli_real_escape_string($con, $_POST['titulo']);
    $subtitulo = mysqli_real_escape_string($con, $_POST['subtitulo']);
    $cuerpo = $_POST['cuerpo'];

    // Verifica si existe destacado
    if (!isset($_POST['destacado'])) {
        $destacado = 0;
    } else {
        $destacado = 1;
    }
    // Verifica si existe portada
    if (!isset($_POST['portada'])) {
        $portada = 0;
    } else {
        $portada = 1;
    }

    // Verifica si se subió una imagen
    if ($_FILES['imagen']['name'] != "") {
        // Verifica si la imagen es válida
        if ($_FILES['imagen']['error'] != 0) {
            echo '
                <div class="alert alert-danger" role="alert">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <i class="fas fa-exclamation-triangle"></i> Error al subir la imagen
                </div>';
            return;
        }
        // Verifica el tamaño de la imagen
        if ($_FILES['imagen']['size'] > 2000000) {
            echo '
                <div class="alert alert-danger" role="alert">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <i class="fas fa-exclamation-triangle"></i> La imagen es demasiado grande
            </div>';
            return;
        }
        // Verifica el tipo de imagen
        $tipos_permitidos = array('image/jpeg', 'image/png', 'image/gif');
        if (!in_array($_FILES['imagen']['type'], $tipos_permitidos)) {
            echo '
                <div class="alert alert-danger" role="alert">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <i class="fas fa-exclamation-triangle"></i> El tipo de imagen no es válido
                </div>';
            return;
        }
        // Verifica si la imagen se subió correctamente
        if ($_FILES['imagen']['error'] != 0) {
            echo '  <div class="alert alert-danger" role="alert">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <i class="fas fa-exclamation-triangle"></i> Error al subir la imagen
                    </div>';
            return;
        }
        $nombre_imagen = $_FILES['imagen']['name'];
    }else {
        $nombre_imagen = $_POST['image_current'];
    }

    // Verifica si se está editando o creando un nuevo registro
    if ($id > 0) {

        // actualizo tabla novedades
        $sql = "UPDATE novedades SET 
            titulo = '$titulo',
            subtitulo = '$subtitulo',
            cuerpo = '$cuerpo',
            nombre_imagen = '$nombre_imagen',
            destacado = $destacado,
            portada = $portada
            WHERE id = $id";

        $mensaje = "El registro se modificó con éxito";
    } else {
        // Insertar nuevo registro
        $sql = "INSERT INTO novedades (
            titulo, 
            subtitulo, 
            cuerpo, 
            nombre_imagen,
            destacado,
            portada
            ) VALUES (
            '$titulo', 
            '$subtitulo', 
            '$cuerpo', 
            '$nombre_imagen',
            $destacado,
            $portada
            )";

        $mensaje = "El registro se creó con éxito";
    }


    // Ejecuta la consulta SQL
    if (mysqli_query($con, $sql)) {

        // obtengo el ultimo id de la tabla
        if ($id == 0) {
            $id = mysqli_insert_id($con);
        }
        $carpeta_destino = '../../../../fotos/'.$id;
        // Verifica si la imagen es válida
        if (is_uploaded_file($_FILES['imagen']['tmp_name'])) {

            // Verifica si la carpeta existe, si no, la crea
            if (!file_exists($carpeta_destino)) {
                mkdir($carpeta_destino, 0777, true);
                // Mueve la imagen a la carpeta de destino
            }
            move_uploaded_file($_FILES['imagen']['tmp_name'], $carpeta_destino . '/' . $nombre_imagen);
        }
        echo '
            <div class="alert alert-primary" role="alert">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <i class="far fa-check-circle"></i> ' . $mensaje . '
            </div>';
    } else {
        echo '
            <div class="alert alert-danger" role="alert">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <i class="fas fa-exclamation-triangle"></i> No se pudo guardar el registro
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

function cargar_personas($con)
{
    // Si viene un id de entidad, cargo las personas
    if (isset($_GET['entidad_id'])) {
        $entidad_id = (int)$_GET['entidad_id'];

        // pregunto si entidad es = 5. entonces es un proveedor
        if ($entidad_id == 5) {
            $sql_personas = "SELECT id, razon_social as nombre_completo FROM proveedores ORDER BY razon_social";
        } else {
            $sql_personas = "SELECT id, CONCAT(nombres, ' ', apellido) AS nombre_completo FROM personas WHERE entidad_id = $entidad_id ORDER BY nombre_completo";
        }
        // Consulta para obtener personas asociadas a la entidad seleccionada
        $resultado_personas = mysqli_query($con, $sql_personas);
        var_dump($resultado_personas);
        // Generar opciones para el select de personas
        while ($row_persona = mysqli_fetch_array($resultado_personas)) {
            echo '<option value="' . $row_persona['id'] . '">' . $row_persona['nombre_completo'] . '</option>';
        }
    }
}


function cargar_proveedores($con)
{
    $sql = "SELECT id, nombres FROM proveedores";
    $resultado = mysqli_query($con, $sql);

    while ($row = mysqli_fetch_array($resultado)) {
        echo '<option value="' . $row['id'] . '">' . $row['nombre'] . '</option>';
    }
}


function editar_detalle($con)
{
    // Recibe los datos del formulario
    $egreso_id = (int)$_POST['egreso_id'];
    $detalle_id = (int)$_POST['detalle_id'];

    $monto_detalle = (float)$_POST['monto_detalle'];
    $cantidad = $_POST['cantidad'];
    $metodo_pago_id = (int)$_POST['metodo_pago_id'];
    $estado_id = (int)$_POST['estado_id'];
    $observaciones = mysqli_real_escape_string($con, $_POST['observaciones']);

    // Verifica si se está editando o creando un nuevo registro
    if ($detalle_id > 0) {

        // actualizo tabla egresos_detalle
        $sql = "UPDATE egresos_detalle SET 
            monto = '$monto_detalle',
            cantidad = '$cantidad',
            metodo_pago_id = $metodo_pago_id,
            estado_id = $estado_id,
            observaciones = '$observaciones'
            WHERE id = $detalle_id";

        $mensaje = "El registro se modificó con éxito";
    } else {

        // Insertar nuevo registro
        $sql = "INSERT INTO egresos_detalle (
            monto, 
            cantidad, 
            metodo_pago_id, 
            estado_id, 
            egreso_id,
            observaciones
            ) VALUES (
            '$monto_detalle', 
            '$cantidad', 
            $metodo_pago_id, 
            $estado_id, 
            $egreso_id,
            '$observaciones'
            )";

        $mensaje = "El registro se creó con éxito";
    }

    // Ejecuta la consulta SQL
    if (mysqli_query($con, $sql)) {
        echo '
            <div class="alert alert-primary" role="alert">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <i class="far fa-check-circle"></i> ' . $mensaje . '
            </div>';
    } else {
        echo '
            <div class="alert alert-danger" role="alert">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <i class="fas fa-exclamation-triangle"></i> No se pudo guardar el registro
            </div>';
    }
}

function publicar($con)
{
    $id = (int)$_GET['id'];
    $publicado_db = (int)$_GET['publicado_db'];

    if ($publicado_db == 1) { $mensaje = 'La Novedad se Publico con exito.- '; $class = 'success'; } else { $mensaje = 'Se quitó la Novedad de la Web.-';$class = 'warning';}

    // actualizo tabla novedades
    $sql = "UPDATE novedades SET publicado = $publicado_db WHERE id = $id";

    if (mysqli_query($con, $sql)) {
        echo '
        <div class="alert alert-'.$class.'" role="alert">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <i class="far fa-check-circle"></i> ' . $mensaje . '
        </div>';
    } else {
        echo '
        <div class="alert alert-danger" role="alert">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <i class="fas fa-exclamation-triangle"></i> ' . $mensaje . '
        </div>';
    }
}


