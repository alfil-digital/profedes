<?php
session_start();
include("../../../inc/conexion.php");
conectar();

// Si viene un id de la novedad, edito
if ($_GET['id'] != 0) {
    $id = (int)$_GET['id'];
    $sql = "SELECT id, titulo, subtitulo, cuerpo, nombre_imagen, fecha, destacado, portada FROM novedades WHERE id = $id";
    $resultado = mysqli_query($con, $sql);
    $row = mysqli_fetch_array($resultado, MYSQLI_ASSOC);
    $nombre_imagen = $row['nombre_imagen'];
} else {
    // Si no viene un id, inicializo los campos

    $row['titulo'] = '';
    $row['subtitulo'] = '';
    $row['cuerpo'] = '';
    $nombre_imagen = '';
    $row['nombre_imagen'] = '';
    $row['fecha'] = date('Y-m-d');
    $row['destacado'] = 0;
    $row['portada'] = 0;
    $id = 0;
}

?>

<!-- creo el div para el formulario  -->
<!-- <link rel="stylesheet" href="https://cdn.ckeditor.com/ckeditor5/45.1.0/ckeditor5.css"> -->
<div class="container-fluid rounded p-3 mb-4">

    <div class="row">
        <div class="col-md-12">
            <h1 class="h3 mb-0 text-gray-800">Formulario Carga Novedades</h1>
            <hr>
        </div>

        
    </div>

    <form method="post" id="form" class="row needs-validation" enctype="multipart/form-data">
        <input type="hidden" class="form-control" id="id" name="id" value="<?php echo $_GET['id']; ?>">
        <input type="hidden" class="form-control" id="nombre_imagen" name="nombre_imagen" value="<?php echo $row['nombre_imagen']; ?>">


        <!-- columna de estructura -->
        <div class="col-md-8">

            <!-- titulo -->
            <div class="col-md-12 position-relative">
                <label for="titulo" class="form-label">Título</label>
                <input type="text" class="form-control" id="titulo" name="titulo" placeholder="" required minlength="3" value="<?= $row['titulo'] ?>">
                <div class="invalid-feedback">
                    Controlar el campo
                </div>
            </div>

            <!-- sub titulo -->
            <div class="col-md-12 position-relative">
                <label for="subtitulo" class="form-label">Sub Título</label>
                <input type="text" class="form-control" id="subtitulo" name="subtitulo" placeholder="" required minlength="3" value="<?= $row['subtitulo'] ?>">
                <div class="invalid-feedback">
                    Controlar el campo
                </div>
            </div>

            <!-- <div id="cuerpo"></div> -->
            <!-- texto -->
            <div class="col-md-12 position-relative">
                <label for="texto" class="form-label">Texto</label>

                <textarea class="form-control" cols="50" rows="100" id="cuerpo" name="cuerpo"><?= $row['cuerpo']; ?></textarea>
                <div class="invalid-feedback">
                    Controlar el campo
                </div>
            </div>

            <div class="col-md-12 position-relative">
                <div class="col-md-4">
                    <label for="texto" class="form-label">Destacado</label>
                    <?php if ($row['destacado'] == 1) {
                        $destacado = 'checked';
                    } else {
                        $destacado = '';
                    } ?>
                    <input type="checkbox" class="form-control" id="destacado" name="destacado" <?= $destacado ?>>
                </div>

                <div class="col-md-4">
                    <label for="texto" class="form-label">Destacado</label>
                    <?php if ($row['portada'] == 1) {
                        $portada = 'checked';
                    } else {
                        $portada = '';
                    } ?>
                    <input type="checkbox" class="form-control" id="portada" name="portada" <?= $portada ?>>
                </div>
            </div>

        </div>

        <!-- columna de img -->
        <div class="col-md-4">

            <div class="col-md-12 position-relative">
                <label for="imagen" class="form-label">Imageneditor</label>
                <input type="file" class="form-control" id="imagen" name="imagen" placeholder="" accept=".jpg, .jpeg, .png">
                <div class="invalid-feedback">
                    Controlar el campo
                </div>
            </div>

            <div class="col-md-12 position-relative">
                <label for="imagen" class="form-label"></label>
                <?php if ($row['nombre_imagen'] != '' && $id != 0) { ?>
                    <!-- cargo un input hidden con el nombre de la imagen -->
                    <input type="hidden" class="form-control" id="image_current" name="image_current" value="<?= $row['nombre_imagen'] ?>">
                    <img src="../fotos/<?= $id ?>/<?= $nombre_imagen ?>" border="5" width="300" height="100">
                <?php } else { ?>
                    <p>No hay imagen cargada</p>
                <?php } ?>
            </div>

        </div>


        <div class="col-md-12 position-relative">
            <a class="btn btn-primary" onclick="guardar(<?= $id ?>)">Guardar</a>
            <!-- <button class="btn btn-danger" type="button" onclick="eliminar(<?= $id ?>)">Eliminar</button> -->
        </div>
    </form>
</div>

<script>
    cargar_editor();
    // Función para cargar el editor CKEditor
</script>